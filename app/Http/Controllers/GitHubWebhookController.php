<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\ProcessGitHubWebhookPush;
use App\Models\Project;
use App\Services\GitHubWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhooks.
     */
    public function handle(Request $request, GitHubWebhookService $webhookService): JsonResponse
    {
        $signature = $request->header('X-Hub-Signature-256');
        $rawPayload = $request->getContent();

        if (! $webhookService->verifySignature($rawPayload, $signature)) {
            Log::warning('Invalid GitHub Webhook Signature', ['signature' => $signature]);

            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        $event = $request->header('X-GitHub-Event');

        Log::info('GitHub Webhook Received', ['event' => $event]);

        if ($event === 'push') {
            return $this->handlePush($payload);
        }

        return response()->json(['message' => 'Event ignored']);
    }

    /**
     * Handle push events asynchronously via queued job.
     */
    protected function handlePush(array $payload): JsonResponse
    {
        $repoUrl = $payload['repository']['html_url'] ?? null;
        $project = Project::where('github_repo_url', $repoUrl)->first();

        if (! $project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        ProcessGitHubWebhookPush::dispatch($payload);

        return response()->json(['message' => 'Activity logged and broadcasted']);
    }
}
