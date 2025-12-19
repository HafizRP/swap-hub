<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    /**
     * Fetch repositories for a given user.
     */
    public function getUserRepositories(User $user)
    {
        if (!$user->github_token) {
            return [];
        }

        $response = Http::withToken($user->github_token)
            ->get('https://api.github.com/user/repos', [
                'sort' => 'updated',
                'per_page' => 100,
            ]);

        return $response->json();
    }

    /**
     * Create a webhook for a repository.
     */
    public function createWebhook(User $user, string $owner, string $repo)
    {
        if (!$user->github_token) {
            return false;
        }

        $webhookUrl = route('github.webhook');

        $response = Http::withToken($user->github_token)
            ->post("https://api.github.com/repos/{$owner}/{$repo}/hooks", [
                'name' => 'web',
                'active' => true,
                'events' => ['push', 'pull_request'],
                'config' => [
                    'url' => $webhookUrl,
                    'content_type' => 'json',
                    'insecure_ssl' => '0',
                ],
            ]);

        return $response->successful();
    }
}
