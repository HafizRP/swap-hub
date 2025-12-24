<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubWebhookService
{
    /**
     * Create a webhook for a GitHub repository.
     *
     * @param string $repoUrl GitHub repository URL (e.g., https://github.com/user/repo)
     * @param string $githubToken User's GitHub personal access token
     * @param string $webhookUrl The webhook URL to receive events
     * @return array|null Returns webhook data or null on failure
     */
    public function createWebhook(string $repoUrl, string $githubToken, string $webhookUrl): ?array
    {
        try {
            // Parse repository owner and name from URL
            $repoParts = $this->parseRepoUrl($repoUrl);

            if (!$repoParts) {
                Log::error('Invalid GitHub repository URL', ['url' => $repoUrl]);
                return null;
            }

            $owner = $repoParts['owner'];
            $repo = $repoParts['repo'];

            // GitHub API endpoint
            $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}/hooks";

            // Check if webhook already exists
            $existingWebhooks = $this->getWebhooks($owner, $repo, $githubToken);

            foreach ($existingWebhooks as $webhook) {
                if (isset($webhook['config']['url']) && $webhook['config']['url'] === $webhookUrl) {
                    Log::info('Webhook already exists', ['webhook_id' => $webhook['id']]);
                    return $webhook;
                }
            }

            // Create webhook
            $response = Http::withToken($githubToken)
                ->withHeaders([
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'SwapHub-App',
                ])
                ->post($apiUrl, [
                    'name' => 'web',
                    'active' => true,
                    'events' => ['push', 'pull_request', 'issues'],
                    'config' => [
                        'url' => $webhookUrl,
                        'content_type' => 'json',
                        'insecure_ssl' => '0',
                    ],
                ]);

            if ($response->successful()) {
                $webhookData = $response->json();
                Log::info('GitHub webhook created successfully', [
                    'webhook_id' => $webhookData['id'],
                    'repo' => "{$owner}/{$repo}",
                ]);

                return $webhookData;
            }

            Log::error('Failed to create GitHub webhook', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Exception creating GitHub webhook', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Get existing webhooks for a repository.
     */
    protected function getWebhooks(string $owner, string $repo, string $githubToken): array
    {
        try {
            $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}/hooks";

            $response = Http::withToken($githubToken)
                ->withHeaders([
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'SwapHub-App',
                ])
                ->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Exception getting GitHub webhooks', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Delete a webhook from a GitHub repository.
     */
    public function deleteWebhook(string $repoUrl, string $githubToken, int $webhookId): bool
    {
        try {
            $repoParts = $this->parseRepoUrl($repoUrl);

            if (!$repoParts) {
                return false;
            }

            $owner = $repoParts['owner'];
            $repo = $repoParts['repo'];

            $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}/hooks/{$webhookId}";

            $response = Http::withToken($githubToken)
                ->withHeaders([
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'SwapHub-App',
                ])
                ->delete($apiUrl);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Exception deleting GitHub webhook', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Parse GitHub repository URL to extract owner and repo name.
     *
     * @param string $url
     * @return array|null ['owner' => 'username', 'repo' => 'repository']
     */
    protected function parseRepoUrl(string $url): ?array
    {
        // Remove trailing slash
        $url = rtrim($url, '/');

        // Match GitHub URL patterns
        // https://github.com/owner/repo
        // https://github.com/owner/repo.git
        if (preg_match('#github\.com[:/]([^/]+)/([^/\.]+)(?:\.git)?$#i', $url, $matches)) {
            return [
                'owner' => $matches[1],
                'repo' => $matches[2],
            ];
        }

        return null;
    }

    /**
     * Get repository info from GitHub.
     */
    public function getRepoInfo(string $repoUrl, string $githubToken): ?array
    {
        try {
            $repoParts = $this->parseRepoUrl($repoUrl);

            if (!$repoParts) {
                return null;
            }

            $owner = $repoParts['owner'];
            $repo = $repoParts['repo'];

            $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}";

            $response = Http::withToken($githubToken)
                ->withHeaders([
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'SwapHub-App',
                ])
                ->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Exception getting repo info', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
