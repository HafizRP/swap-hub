<?php

namespace Tests\Feature;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GitHubWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        config(['services.github.webhook_secret' => 'test-webhook-secret-12345']);
    }

    public function test_webhook_fails_without_signature(): void
    {
        $payload = ['ref' => 'refs/heads/main'];

        $response = $this->postJson(route('github.webhook'), $payload, [
            'X-GitHub-Event' => 'push',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid signature']);
    }

    public function test_webhook_fails_with_invalid_signature(): void
    {
        $payload = json_encode(['ref' => 'refs/heads/main']);

        $response = $this->call(
            'POST',
            route('github.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_GITHUB_EVENT' => 'push',
                'HTTP_X_HUB_SIGNATURE_256' => 'sha256=invalidhashvalue1234567890',
            ],
            $payload
        );

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid signature']);
    }

    public function test_webhook_processes_push_with_valid_hmac_and_awards_reputation(): void
    {
        Event::fake([MessageSent::class]);

        $user = User::factory()->create([
            'github_username' => 'octocat',
            'reputation_points' => 10,
        ]);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'github_repo_url' => 'https://github.com/octocat/hello-world',
        ]);

        $project->members()->attach($user->id, [
            'role' => 'owner',
            'status' => 'active',
            'is_validated' => true,
        ]);

        $conversation = Conversation::create([
            'type' => 'project',
            'project_id' => $project->id,
            'name' => 'Project Chat',
        ]);

        $payloadData = [
            'ref' => 'refs/heads/main',
            'repository' => [
                'html_url' => 'https://github.com/octocat/hello-world',
            ],
            'pusher' => [
                'name' => 'octocat',
            ],
            'commits' => [
                [
                    'id' => '6dcb09b5b57875f334f61aebed695e2e4193db5e',
                    'message' => 'Fix issue with login flow',
                    'timestamp' => now()->toIso8601String(),
                    'author' => [
                        'username' => 'octocat',
                    ],
                ],
            ],
        ];

        $rawPayload = json_encode($payloadData);
        $signature = 'sha256='.hash_hmac('sha256', $rawPayload, 'test-webhook-secret-12345');

        $response = $this->call(
            'POST',
            route('github.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_GITHUB_EVENT' => 'push',
                'HTTP_X_HUB_SIGNATURE_256' => $signature,
            ],
            $rawPayload
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'Activity logged and broadcasted']);

        $this->assertDatabaseHas('github_activities', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_type' => 'commit',
            'commit_sha' => '6dcb09b5b57875f334f61aebed695e2e4193db5e',
            'branch' => 'main',
        ]);

        $user->refresh();
        $this->assertEquals(11, $user->reputation_points);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => null,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_webhook_ignores_non_push_events_with_valid_signature(): void
    {
        $rawPayload = json_encode(['action' => 'opened']);
        $signature = 'sha256='.hash_hmac('sha256', $rawPayload, 'test-webhook-secret-12345');

        $response = $this->call(
            'POST',
            route('github.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_GITHUB_EVENT' => 'issues',
                'HTTP_X_HUB_SIGNATURE_256' => $signature,
            ],
            $rawPayload
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'Event ignored']);
    }
}
