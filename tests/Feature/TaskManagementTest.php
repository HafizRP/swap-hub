<?php

namespace Tests\Feature;

use App\Jobs\CreateGoogleCalendarEvent;
use App\Livewire\Project\TaskBoard;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_project_member_can_create_task(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($owner)
            ->test(TaskBoard::class, ['project' => $project])
            ->set('title', 'Develop Authentication Module')
            ->set('description', 'Set up Breeze and OAuth authentication.')
            ->set('priority', 'high')
            ->call('createTask')
            ->assertHasNoErrors()
            ->assertDispatched('task-created');

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Develop Authentication Module',
            'priority' => 'high',
            'status' => 'todo',
            'created_by' => $owner->id,
        ]);

        Queue::assertPushed(CreateGoogleCalendarEvent::class);
    }

    public function test_non_member_cannot_create_task(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($outsider)
            ->test(TaskBoard::class, ['project' => $project])
            ->set('title', 'Unauthorized Task')
            ->call('createTask')
            ->assertForbidden();
    }

    public function test_member_can_update_task_status(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $task = $project->tasks()->create([
            'title' => 'Initial Task',
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test(TaskBoard::class, ['project' => $project])
            ->call('updateStatus', $task->id, 'in_progress')
            ->assertHasNoErrors();

        $this->assertEquals('in_progress', $task->fresh()->status);
    }

    public function test_task_status_update_validates_status_values(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $task = $project->tasks()->create([
            'title' => 'Task for status check',
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test(TaskBoard::class, ['project' => $project])
            ->call('updateStatus', $task->id, 'invalid_status_value')
            ->assertHasErrors(['status']);
    }

    public function test_non_member_cannot_update_task_status(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $task = $project->tasks()->create([
            'title' => 'Task to protect',
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($outsider)
            ->test(TaskBoard::class, ['project' => $project])
            ->call('updateStatus', $task->id, 'done')
            ->assertForbidden();
    }

    public function test_member_can_delete_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $task = $project->tasks()->create([
            'title' => 'Task to be deleted',
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test(TaskBoard::class, ['project' => $project])
            ->call('deleteTask', $task->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_non_member_cannot_delete_task(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $task = $project->tasks()->create([
            'title' => 'Task not yours',
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($outsider)
            ->test(TaskBoard::class, ['project' => $project])
            ->call('deleteTask', $task->id)
            ->assertForbidden();
    }
}
