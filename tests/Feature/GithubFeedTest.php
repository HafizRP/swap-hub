<?php

namespace Tests\Feature;

use App\Livewire\Project\GithubFeed;
use App\Models\GitHubActivity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GithubFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_github_feed_renders_empty_state_cleanly(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(GithubFeed::class, ['project' => $project])
            ->assertStatus(200)
            ->assertSee('Repository Activity')
            ->assertSee('No activity recorded yet.');
    }

    public function test_github_feed_renders_activities_without_type_errors(): void
    {
        $user = User::factory()->create(['name' => 'Test Contributor']);
        $project = Project::factory()->create(['owner_id' => $user->id]);

        GitHubActivity::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_type' => 'commit',
            'commit_message' => 'feat: implement awesome feature',
            'commit_sha' => 'abc1234567890',
            'branch' => 'main',
        ]);

        Livewire::actingAs($user)
            ->test(GithubFeed::class, ['project' => $project])
            ->assertStatus(200)
            ->assertSee('Repository Activity')
            ->assertSee('Test Contributor')
            ->assertSee('feat: implement awesome feature')
            ->assertSee('main');
    }
}
