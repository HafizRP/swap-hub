<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the specified user's profile.
     */
    public function show(\App\Models\User $user): View
    {
        $user->load(['skills', 'ownedProjects', 'projects']);
        return view('profile.show', compact('user'));
    }

    /**
     * Add a skill to the user's profile.
     */
    public function addSkill(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency_level' => ['required', 'in:beginner,intermediate,advanced,expert'],
        ]);

        $request->user()->skills()->syncWithoutDetaching([
            $validated['skill_id'] => ['proficiency_level' => $validated['proficiency_level']]
        ]);

        return back()->with('status', 'skill-added');
    }

    /**
     * Remove a skill from the user's profile.
     */
    public function removeSkill(\App\Models\Skill $skill): RedirectResponse
    {
        auth()->user()->skills()->detach($skill->id);

        return back()->with('status', 'skill-removed');
    }
}
