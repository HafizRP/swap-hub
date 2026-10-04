<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectMemberRequest;
use App\Http\Requests\ApplyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\ValidateMemberRequest;
use App\Mail\MemberValidated;
use App\Mail\NewApplicationReceived;
use App\Mail\ProjectMemberAdded;
use App\Models\Project;
use App\Models\User;
use App\Services\GitHubService;
use App\Services\GitHubWebhookService;
use App\Services\SkillMatchingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Project::with(['owner', 'members', 'skills'])
            ->where('status', '!=', 'archived');

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%')
                    ->orWhereHas('skills', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('owner', function ($oq) use ($search) {
                        $oq->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filter === 'my') {
            $query->whereHas('members', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        if ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $projects = $query->paginate(12)->withQueryString();

        if (Auth::check()) {
            $matcher = app(SkillMatchingService::class);
            $user = Auth::user();
            foreach ($projects as $proj) {
                $proj->skill_match = $matcher->evaluateProjectMatch($user, $proj);
            }
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $repositories = [];
        if (Auth::user()?->github_token) {
            $githubService = new GitHubService;
            $repositories = $githubService->getUserRepositories(Auth::user());
        }

        return view('projects.create', compact('repositories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = Auth::user();

        $project = DB::transaction(function () use ($user, $validated) {
            $project = $user->ownedProjects()->create($validated);

            // Add owner as member
            $project->members()->attach($user->id, [
                'role' => 'owner',
                'status' => 'active',
                'is_validated' => true,
            ]);

            // Auto-create project conversation
            $conversation = \App\Models\Conversation::create([
                'type' => 'project',
                'project_id' => $project->id,
                'name' => $project->title.' Chat',
            ]);
            $conversation->participants()->attach($user->id);

            return $project;
        });

        // Auto-create Google Calendar for this project
        \App\Jobs\CreateProjectGoogleCalendar::dispatch($project);

        // Auto-setup GitHub webhook if requested and user has GitHub token
        if ($request->boolean('setup_webhook') && ($validated['github_repo_url'] ?? null) && $user->github_token) {
            $this->setupGitHubWebhook($project, $user->github_token);
        }

        return redirect()->route('projects.show', $project)->with('status', 'project-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $project->load(['owner', 'members', 'skills', 'githubActivities']);

        // Lazy create conversation if it doesn't exist
        if (! $project->conversation) {
            $conversation = \App\Models\Conversation::create([
                'type' => 'project',
                'project_id' => $project->id,
                'name' => $project->title.' Chat',
            ]);

            // Add all current members as participants
            $conversation->participants()->attach($project->members->pluck('id'));
        }

        $skillMatch = null;
        if (Auth::check()) {
            $matcher = app(SkillMatchingService::class);
            $skillMatch = $matcher->evaluateProjectMatch(Auth::user(), $project);
        }

        return view('projects.show', compact('project', 'skillMatch'));
    }

    /**
     * Display the workspace for the project.
     */
    public function workspace(Project $project): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Check if user is a member or owner
        if ($project->owner_id !== $currentUser->id && ! $project->activeMembers->contains($currentUser->id)) {
            abort(403, 'You are not a member of this project.');
        }

        if (! $project->conversation) {
            $conversation = \App\Models\Conversation::create([
                'type' => 'project',
                'project_id' => $project->id,
                'name' => $project->title.' Chat',
            ]);
            $conversation->participants()->attach($project->members->pluck('id'));
            $project->setRelation('conversation', $conversation);
        }

        $project->load(['owner', 'members', 'githubActivities']);

        $messages = $project->conversation
            ? $project->conversation->messages()->with('user')->latest()->take(50)->get()->reverse()
            : collect();

        $userProjects = $currentUser->projects()->take(4)->get();

        return view('projects.workspace', compact('project', 'messages', 'userProjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        $repositories = [];
        if (Auth::user()?->github_token) {
            $githubService = new GitHubService;
            $repositories = $githubService->getUserRepositories(Auth::user());
        }

        return view('projects.edit', compact('project', 'repositories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $validated = $request->validated();

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('status', 'project-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);
        $project->delete();

        return redirect()->route('projects.index')->with('status', 'project-deleted');
    }

    /**
     * Add a member to the project.
     */
    public function addMember(AddProjectMemberRequest $request, Project $project): RedirectResponse
    {
        // Security: Direct member addition restricted to project owner only
        if (Auth::id() !== $project->owner_id) {
            abort(403, 'Only project owners can directly add members. Non-owners must use application workflow.');
        }

        $userId = $request->input('user_id');

        // Allow adding by email
        if ($request->has('email') && $request->filled('email')) {
            $userByEmail = User::where('email', $request->email)->first();
            if ($userByEmail) {
                $userId = $userByEmail->id;
            }
        }

        if (! $userId) {
            return back()->with('error', 'User ID or valid email is required.');
        }

        $role = $request->input('role', 'member');
        if ($role === 'owner') {
            abort(422, 'Role cannot be owner.');
        }
        $project->members()->syncWithoutDetaching([
            $userId => ['role' => $role, 'status' => 'active', 'joined_at' => now()],
        ]);

        // Add to conversation if it exists
        if ($project->conversation) {
            $project->conversation->participants()->syncWithoutDetaching([$userId]);
        }

        // Add member to project's Google Calendar
        $member = User::find($userId);
        if ($member) {
            \App\Jobs\AddMemberToProjectCalendar::dispatch($project, $member, 'reader');

            // Send email notification to project owner
            if ($project->owner_id !== $userId && $project->owner?->email) {
                Mail::to($project->owner->email)->send(
                    new ProjectMemberAdded($project, $member, $project->owner)
                );
            }
        }

        return back()->with('status', 'member-added');
    }

    /**
     * Apply to join the project.
     */
    public function apply(ApplyProjectRequest $request, Project $project): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // 0. Check User Skills (Minimal 3)
        if ($currentUser->skills()->count() < 3) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please add at least 3 skills to your profile before joining a project.');
        }

        // 1. Validate Message
        $validated = $request->validated();

        // 2. Check if already applied or member
        $existingMember = $project->members()->where('user_id', Auth::id())->first();

        if ($existingMember) {
            $status = $existingMember->pivot->status;
            if ($status === 'active') {
                return back()->with('error', 'You are already a member of this project.');
            }
            if ($status === 'pending') {
                return back()->with('error', 'Your application is already pending.');
            }
            if ($status === 'rejected') {
                return back()->with('error', 'Your previous application was rejected.');
            }
        }

        // 3. Create Application (Pending Member)
        $project->members()->attach(Auth::id(), [
            'role' => $validated['role'],
            'status' => 'pending',
            'message' => $validated['message'],
            'joined_at' => now(),
        ]);

        // 4. Notify Owner
        if ($project->owner?->email) {
            Mail::to($project->owner->email)->send(
                new NewApplicationReceived($project, $currentUser, $validated['message'])
            );
        }

        return back()->with('status', 'application-sent');
    }

    /**
     * Accept a member application.
     */
    public function acceptApplication(Request $request, Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);

        // Lock row to prevent race condition (Basic implementation)
        $memberPivot = $project->projectMembers()->where('user_id', $user->id)->firstOrFail();

        if ($memberPivot->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        // Accept
        $memberPivot->update(['status' => 'active']);

        // Add to conversation
        if ($project->conversation) {
            $project->conversation->participants()->syncWithoutDetaching([$user->id]);
        }

        // Add accepted member to project's Google Calendar
        // Map project role → Google Calendar ACL role (valid: reader, writer, owner)
        $calendarRole = ($memberPivot->role === 'contributor') ? 'reader' : 'writer';
        \App\Jobs\AddMemberToProjectCalendar::dispatch($project, $user, $calendarRole);

        return back()->with('status', 'application-accepted');
    }

    /**
     * Reject a member application.
     */
    public function rejectApplication(Request $request, Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);

        $memberPivot = $project->projectMembers()->where('user_id', $user->id)->firstOrFail();

        if ($memberPivot->status !== 'pending') {
            return back()->with('error', 'This application has already been processed.');
        }

        // Reject
        $memberPivot->update(['status' => 'rejected']);

        return back()->with('status', 'application-rejected');
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);
        $project->members()->detach($user->id);
        $project->conversation?->participants()->detach($user->id);

        return back()->with('status', 'member-removed');
    }

    /**
     * Validate a member's contribution.
     */
    public function validateMember(ValidateMemberRequest $request, Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);

        $validated = $request->validated();

        $project->members()->updateExistingPivot($user->id, [
            'is_validated' => true,
            'contribution_rating' => $validated['rating'],
            'contribution_notes' => $validated['notes'],
        ]);

        // Reward reputation points
        $reputationEarned = $validated['rating'] * 10;
        $user->increment('reputation_points', $reputationEarned);

        // Send email notification to validated member
        Mail::to($user->email)->send(
            new MemberValidated(
                $project,
                $user,
                $validated['rating'],
                $validated['notes'] ?? null,
                $reputationEarned
            )
        );

        return back()->with('status', 'member-validated');
    }

    protected function authorizeOwner(Project $project): void
    {
        Gate::authorize('manageMembers', $project);
    }

    /**
     * Setup GitHub webhook for the project.
     */
    protected function setupGitHubWebhook(Project $project, string $githubToken): void
    {
        $webhookService = new GitHubWebhookService;

        // Webhook URL that GitHub will call
        $webhookUrl = url('/webhooks/github');

        $webhookData = $webhookService->createWebhook(
            $project->github_repo_url,
            $githubToken,
            $webhookUrl
        );

        if ($webhookData) {
            $project->update([
                'github_webhook_id' => $webhookData['id'],
                'github_webhook_status' => 'active',
            ]);
        } else {
            $project->update([
                'github_webhook_status' => 'failed',
            ]);
        }
    }
}
