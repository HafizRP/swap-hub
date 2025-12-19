<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SkillSwapController extends Controller
{
    /**
     * Display a listing of skill swap requests.
     */
    public function index()
    {
        $swaps = \App\Models\SkillSwapRequest::with(['requester', 'offeredSkill', 'requestedSkill'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(12);
            
        return view('skill-swaps.index', compact('swaps'));
    }

    /**
     * Show the form for creating a new skill swap request.
     */
    public function create()
    {
        $skills = \App\Models\Skill::all()->groupBy('category');
        return view('skill-swaps.create', compact('skills'));
    }

    /**
     * Store a newly created skill swap request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'offered_skill_id' => ['required', 'exists:skills,id'],
            'requested_skill_id' => ['required', 'exists:skills,id', 'different:offered_skill_id'],
            'description' => ['required', 'string', 'min:20'],
            'points_offered' => ['required', 'integer', 'min:10', 'max:100'],
        ]);

        $swap = auth()->user()->skillSwapRequestsSent()->create($validated);

        return redirect()->route('skill-swaps.show', $swap)->with('status', 'swap-request-created');
    }

    /**
     * Display the specified skill swap request.
     */
    public function show(\App\Models\SkillSwapRequest $skillSwapRequest)
    {
        $skillSwapRequest->load(['requester', 'provider', 'offeredSkill', 'requestedSkill']);
        return view('skill-swaps.show', compact('skillSwapRequest'));
    }

    /**
     * Accept a skill swap request.
     */
    public function accept(\App\Models\SkillSwapRequest $skillSwapRequest)
    {
        if ($skillSwapRequest->requester_id === auth()->id()) {
            return back()->with('error', 'You cannot accept your own request.');
        }

        if ($skillSwapRequest->status !== 'pending') {
            return back()->with('error', 'This request is no longer available.');
        }

        $skillSwapRequest->update([
            'provider_id' => auth()->id(),
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Create a conversation for the swap
        $conversation = \App\Models\Conversation::create([
            'type' => 'direct',
            'name' => 'Skill Swap: ' . $skillSwapRequest->offeredSkill->name . ' for ' . $skillSwapRequest->requestedSkill->name,
        ]);

        $conversation->participants()->attach([$skillSwapRequest->requester_id, auth()->id()]);

        return redirect()->route('chat.show', $conversation)->with('status', 'swap-request-accepted');
    }

    /**
     * Mark the swap as completed.
     */
    public function complete(\App\Models\SkillSwapRequest $skillSwapRequest)
    {
        if (auth()->id() !== $skillSwapRequest->requester_id) {
            return back()->with('error', 'Only the requester can mark the swap as completed.');
        }

        if ($skillSwapRequest->status !== 'accepted') {
            return back()->with('error', 'The swap must be in progress to be marked as completed.');
        }

        $skillSwapRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Reward provider with reputation points
        $skillSwapRequest->provider->increment('reputation_points', $skillSwapRequest->points_offered);
        
        // Bonus for requester for validating
        auth()->user()->increment('reputation_points', 5);

        return back()->with('status', 'swap-completed');
    }

    /**
     * Cancel the swap request.
     */
    public function destroy(\App\Models\SkillSwapRequest $skillSwapRequest)
    {
        if (auth()->id() !== $skillSwapRequest->requester_id) {
            return back()->with('error', 'Only the requester can cancel the request.');
        }

        if ($skillSwapRequest->status !== 'pending') {
            return back()->with('error', 'Accepted requests cannot be deleted.');
        }

        $skillSwapRequest->delete();

        return redirect()->route('skill-swaps.index')->with('status', 'swap-cancelled');
    }}
