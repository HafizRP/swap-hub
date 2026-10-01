<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillSwapRequest;
use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillSwapController extends Controller
{
    /**
     * Display a listing of skill swap requests.
     */
    public function index(Request $request): View
    {
        $query = SkillSwapRequest::with(['requester', 'provider', 'offeredSkill', 'requestedSkill']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending swaps if no filter
            $query->whereIn('status', ['pending', 'accepted', 'in_progress']);
        }

        if ($request->filter === 'my') {
            $query->where(function ($q) {
                $q->where('requester_id', Auth::id())
                    ->orWhere('provider_id', Auth::id());
            });
        }

        if ($request->filled('skill_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('offered_skill_id', $request->skill_id)
                    ->orWhere('requested_skill_id', $request->skill_id);
            });
        }

        $requests = $query->latest()->paginate(12)->withQueryString();
        $skills = Skill::orderBy('name')->get();

        return view('skills.swap.index', compact('requests', 'skills'));
    }

    /**
     * Show the form for creating a new skill swap request.
     */
    public function create(): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        $userSkills = $currentUser->skills;
        $allSkills = Skill::orderBy('name')->get();

        return view('skills.swap.create', compact('userSkills', 'allSkills'));
    }

    /**
     * Store a newly created skill swap request.
     */
    public function store(StoreSkillSwapRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $currentUser */
        $currentUser = Auth::user();
        $currentUser->sentSkillSwaps()->create([
            'offered_skill_id' => $validated['offered_skill_id'],
            'requested_skill_id' => $validated['requested_skill_id'],
            'description' => $validated['description'],
            'points_offered' => $validated['points_offered'] ?? 10,
            'status' => 'pending',
        ]);

        return redirect()->route('skills.swap.index')->with('status', 'skill-swap-created');
    }

    /**
     * Accept a pending skill swap request.
     */
    public function accept(Request $request, SkillSwapRequest $skillSwap): RedirectResponse
    {
        if ((int) $skillSwap->requester_id === (int) Auth::id()) {
            return back()->with('error', 'You cannot accept your own skill swap request.');
        }

        if ($skillSwap->status !== 'pending') {
            return back()->with('error', 'This skill swap request is no longer available.');
        }

        $skillSwap->update([
            'provider_id' => Auth::id(),
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return back()->with('status', 'skill-swap-accepted');
    }

    /**
     * Mark a skill swap as completed and reward points to the provider.
     */
    public function complete(Request $request, SkillSwapRequest $skillSwap): RedirectResponse
    {
        $userId = Auth::id();
        if ((int) $skillSwap->requester_id !== (int) $userId && (int) $skillSwap->provider_id !== (int) $userId) {
            abort(403, 'Unauthorized to complete this swap.');
        }

        if (! in_array($skillSwap->status, ['accepted', 'in_progress'], true)) {
            return back()->with('error', 'Only accepted swaps can be marked completed.');
        }

        $skillSwap->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Award reputation points to provider
        if ($skillSwap->provider) {
            $skillSwap->provider->increment('reputation_points', $skillSwap->points_offered);
        }

        return back()->with('status', 'skill-swap-completed');
    }

    /**
     * Cancel a skill swap request.
     */
    public function cancel(Request $request, SkillSwapRequest $skillSwap): RedirectResponse
    {
        $userId = Auth::id();
        if ((int) $skillSwap->requester_id !== (int) $userId && (int) $skillSwap->provider_id !== (int) $userId) {
            abort(403, 'Unauthorized to cancel this swap.');
        }

        if ($skillSwap->status === 'completed') {
            return back()->with('error', 'Completed swaps cannot be cancelled.');
        }

        $skillSwap->update([
            'status' => 'cancelled',
        ]);

        return back()->with('status', 'skill-swap-cancelled');
    }
}
