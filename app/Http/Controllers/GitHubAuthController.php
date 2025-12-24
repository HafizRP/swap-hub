<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GitHubAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('github')
            ->scopes(['repo', 'user:email'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            $currentUser = Auth::user();

            if ($currentUser) {
                // Scenario: Linking account while already logged in
                $currentUser->update([
                    'github_username' => $githubUser->getNickname(),
                    'github_token' => $githubUser->token,
                ]);

                return redirect()->route('profile.edit')->with('status', 'github-linked');
            }

            // Scenario: Logging in via GitHub
            $user = User::where('email', $githubUser->getEmail())->first();

            if (!$user) {
                // Auto-register new user
                $user = User::create([
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'github_username' => $githubUser->getNickname(),
                    'github_token' => $githubUser->token,
                    'password' => bcrypt(str()->random(32)), // Random password
                    'email_verified_at' => now(), // Auto-verify email from GitHub
                ]);

                Auth::login($user);

                return redirect()->route('dashboard')->with('status', 'welcome-new-user');
            }

            // User exists, login and update GitHub info
            Auth::login($user);

            $user->update([
                'github_username' => $githubUser->getNickname(),
                'github_token' => $githubUser->token,
            ]);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            if (Auth::check()) {
                return redirect()->route('profile.edit')->with('error', 'GitHub linking failed: ' . $e->getMessage());
            }
            return redirect()->route('login')->with('error', 'GitHub login failed: ' . $e->getMessage());
        }
    }
}
