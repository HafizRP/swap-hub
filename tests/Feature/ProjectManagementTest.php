<?php

namespace Tests\Feature;

use App\Jobs\CreateProjectGoogleCalendar;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_authenticated_user_can_view_projects_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_project(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $projectData = [
            'title' => 'Innovative Collaboration App',
            'description' => 'A project to build collaboration platforms for students.',
            'category' => 'Development',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
        ];

        $response = $this->actingAs($user)->post(route('projects.store'), $projectData);

        $this->assertDatabaseHas('projects', [
            'title' => 'Innovative Collaboration App',
            'owner_id' => $user->id,
            'category' => 'Development',
        ]);

        Queue::assertPushed(CreateProjectGoogleCalendar::class);

        $project = Project::where('title', 'Innovative Collaboration App')->first();
        $response->assertRedirect(route('projects.show', $project));
    }

    public function test_project_owner_can_update_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('projects.update', $project), [
            'title' => 'Updated Project Title',
            'description' => 'Updated description for the project.',
            'category' => 'Design',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project Title',
            'category' => 'Design',
        ]);
    }

    public function test_non_owner_cannot_update_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->put(route('projects.update', $project), [
            'title' => 'Malicious Update',
            'description' => 'Trying to update someone else project.',
            'category' => 'Design',
            'status' => 'active',
        ]);

        $response->assertStatus(403);
    }

    public function test_project_owner_can_delete_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($owner)->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_non_owner_cannot_delete_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->delete(route('projects.destroy', $project));

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_user_with_skills_can_apply_to_project(): void
    {
        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $skills = Skill::factory()->count(3)->create();
        $applicant->skills()->attach($skills->pluck('id'));

        $response = $this->actingAs($applicant)->post(route('projects.apply', $project), [
            'message' => 'I would love to contribute to this amazing project as a backend developer.',
            'role' => 'member',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('project_members', [
            'project_id' => $project->id,
            'user_id' => $applicant->id,
            'status' => 'pending',
            'role' => 'member',
        ]);
    }

    public function test_owner_can_accept_application(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $project->members()->attach($applicant->id, [
            'role' => 'member',
            'status' => 'pending',
            'message' => 'Valid application message',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($owner)->post(route('projects.applications.accept', [$project, $applicant]));

        $response->assertRedirect();
        $this->assertDatabaseHas('project_members', [
            'project_id' => $project->id,
            'user_id' => $applicant->id,
            'status' => 'active',
        ]);
    }
}
