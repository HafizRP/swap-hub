<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectMemberAdded;
use App\Mail\MemberValidated;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Project::with(['owner', 'members'])
            ->where('status', '!=', 'archived');

        if ($request->has('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $repositories = [];
        if (auth()->user()->github_token) {
            $githubService = new \App\Services\GitHubService();
            $repositories = $githubService->getUserRepositories(auth()->user());
        }

        return view('projects.create', compact('repositories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'github_repo_url' => ['nullable', 'url'],
            'github_repo_name' => ['nullable', 'string'],
            'category' => ['required', 'string', 'in:Development,Design,Marketing'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'setup_webhook' => ['nullable', 'boolean'],
        ]);

        $project = auth()->user()->ownedProjects()->create($validated);

        // Add owner as member
        $project->members()->attach(auth()->id(), ['role' => 'owner', 'is_validated' => true]);

        // Auto-setup GitHub webhook if requested and user has GitHub token
        if ($request->boolean('setup_webhook') && $validated['github_repo_url'] && auth()->user()->github_token) {
            $this->setupGitHubWebhook($project, auth()->user()->github_token);
        }

        return redirect()->route('projects.show', $project)->with('status', 'project-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Project $project)
    {
        $project->load(['owner', 'members', 'githubActivities']);

        // Lazy create conversation if it doesn't exist
        if (!$project->conversation) {
            $conversation = \App\Models\Conversation::create([
                'type' => 'project',
                'project_id' => $project->id,
                'name' => $project->title . ' Chat',
            ]);

            // Add all current members as participants
            $conversation->participants()->attach($project->members->pluck('id'));
        }

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Project $project)
    {
        $this->authorizeOwner($project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Project $project)
    {
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:planning,active,completed,archived'],
            'github_repo_url' => ['nullable', 'url'],
            'github_repo_name' => ['nullable', 'string'],
            'category' => ['required', 'string', 'in:Development,Design,Marketing'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('status', 'project-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Project $project)
    {
        $this->authorizeOwner($project);
        $project->delete();
        return redirect()->route('projects.index')->with('status', 'project-deleted');
    }

    /**
     * Add a member to the project.
     */
    public function addMember(Request $request, \App\Models\Project $project)
    {
        $userId = $request->input('user_id', auth()->id());
        $role = $request->input('role', 'member');

        // Security: Non-owners can only add themselves
        if (auth()->id() !== $project->owner_id && $userId != auth()->id()) {
            abort(403, 'You can only join projects yourself.');
        }

        // Prevent duplicates
        $project->members()->syncWithoutDetaching([
            $userId => ['role' => $role, 'joined_at' => now()]
        ]);

        // Add to conversation if it exists
        if ($project->conversation) {
            $project->conversation->participants()->syncWithoutDetaching([$userId]);
        }

        // Send email notification to project owner
        $member = \App\Models\User::find($userId);
        if ($project->owner_id !== $userId) {
            Mail::to($project->owner->email)->send(
                new ProjectMemberAdded($project, $member, $project->owner)
            );
        }

        return back()->with('status', 'member-added');
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(\App\Models\Project $project, \App\Models\User $user)
    {
        $this->authorizeOwner($project);
        $project->members()->detach($user->id);
        return back()->with('status', 'member-removed');
    }

    /**
     * Validate a member's contribution.
     */
    public function validateMember(Request $request, \App\Models\Project $project, \App\Models\User $user)
    {
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string'],
        ]);

        $project->members()->updateExistingPivot($user->id, [
            'is_validated' => true,
            'contribution_rating' => $validated['rating'],
            'contribution_notes' => $validated['notes']
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

    protected function authorizeOwner(\App\Models\Project $project)
    {
        if (auth()->id() !== $project->owner_id) {
            abort(403);
        }
    }

    /**
     * Setup GitHub webhook for the project.
     */
    protected function setupGitHubWebhook(\App\Models\Project $project, string $githubToken): void
    {
        $webhookService = new \App\Services\GitHubWebhookService();

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
