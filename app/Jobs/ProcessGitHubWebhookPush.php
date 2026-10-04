<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\MessageSent;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessGitHubWebhookPush implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $payload
    ) {}

    public function handle(): void
    {
        $repoUrl = $this->payload['repository']['html_url'] ?? null;
        if (! $repoUrl) {
            return;
        }

        $project = Project::where('github_repo_url', $repoUrl)->first();
        if (! $project) {
            Log::warning('GitHub Webhook: Project not found for repository', ['url' => $repoUrl]);

            return;
        }

        $commits = $this->payload['commits'] ?? [];
        $branch = str_replace('refs/heads/', '', $this->payload['ref'] ?? 'main');
        $pusher = $this->payload['pusher']['name'] ?? 'Someone';

        foreach ($commits as $commit) {
            $user = User::where('github_username', $commit['author']['username'] ?? null)->first();

            if ($user && $project->members()->where('user_id', $user->id)->exists()) {
                // Idempotency: skip if commit already processed for this project
                $exists = $project->githubActivities()->where('commit_sha', $commit['id'])->exists();
                if ($exists) {
                    continue;
                }

                $project->githubActivities()->create([
                    'user_id' => $user->id,
                    'activity_type' => 'commit',
                    'commit_sha' => $commit['id'],
                    'commit_message' => $commit['message'],
                    'branch' => $branch,
                    'additions' => 0,
                    'deletions' => 0,
                    'metadata' => $commit,
                    'activity_at' => Carbon::parse($commit['timestamp']),
                ]);

                // Reward minor reputation points for activity
                $user->increment('reputation_points', 1);
            }
        }

        // Broadcast summary to project chat
        if ($project->conversation) {
            $commitCount = count($commits);

            // Format commits for Markdown
            $commitList = '';
            foreach (array_slice($commits, 0, 5) as $commit) {
                $subject = explode("\n", $commit['message'])[0];
                $commitList .= '- '.$subject."\n";
            }
            if ($commitCount > 5) {
                $commitList .= '- ... and '.($commitCount - 5)." more\n";
            }

            $message = $project->conversation->messages()->create([
                'user_id' => null,
                'content' => "🚀 **GitHub Sync**: {$pusher} pushed {$commitCount} commit(s) to `{$branch}`\n\n".trim($commitList),
            ]);

            broadcast(new MessageSent($message));
        }
    }
}
