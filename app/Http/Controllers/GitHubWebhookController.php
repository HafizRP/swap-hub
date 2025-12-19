<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhooks.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event = $request->header('X-GitHub-Event');

        Log::info('GitHub Webhook Received', ['event' => $event]);

        if ($event === 'push') {
            return $this->handlePush($payload);
        }

        return response()->json(['message' => 'Event ignored']);
    }

    /**
     * Handle push events.
     */
    protected function handlePush(array $payload)
    {
        $repoUrl = $payload['repository']['html_url'];
        $project = Project::where('github_repo_url', $repoUrl)->first();

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        foreach ($payload['commits'] as $commit) {
            $user = User::where('github_username', $commit['author']['username'])->first();

            if ($user && $project->members()->where('user_id', $user->id)->exists()) {
                $project->githubActivities()->create([
                    'user_id' => $user->id,
                    'activity_type' => 'commit',
                    'commit_sha' => $commit['id'],
                    'commit_message' => $commit['message'],
                    'branch' => str_replace('refs/heads/', '', $payload['ref']),
                    'additions' => 0, // Would need GitHub API to get exact stats
                    'deletions' => 0,
                    'metadata' => json_encode($commit),
                    'activity_at' => \Carbon\Carbon::parse($commit['timestamp']),
                ]);

                // Reward minor reputation points for activity
                $user->increment('reputation_points', 1);
            }
        }

        // Broadcast summary to project chat
        if ($project->conversation) {
            $commitCount = count($payload['commits']);
            $branch = str_replace('refs/heads/', '', $payload['ref']);
            $pusher = $payload['pusher']['name'] ?? 'Someone';
            
            $project->conversation->messages()->create([
                'user_id' => $project->owner_id, // System messages can be sent as owner or a system user
                'content' => "🚀 **GitHub Sync**: {$pusher} pushed {$commitCount} commit(s) to `{$branch}` in `{$project->github_repo_name}`.",
            ]);
        }

        return response()->json(['message' => 'Activity logged and broadcasted']);
    }
}
