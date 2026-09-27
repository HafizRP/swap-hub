<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Role $adminRole;

    protected Role $studentRole;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->adminRole = Role::where('slug', 'admin')->first();
        $this->studentRole = Role::where('slug', 'student')->first();
    }

    public function test_admin_can_view_projects_list_with_safe_sorting(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $owner = User::factory()->create(['role_id' => $this->studentRole->id]);
        Project::factory()->count(3)->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($admin)->get(route('admin.projects.index', [
            'sort' => 'title',
            'order' => 'asc',
        ]));

        $response->assertStatus(200);

        // Test fallback on invalid sort column
        $invalidResponse = $this->actingAs($admin)->get(route('admin.projects.index', [
            'sort' => 'invalid_col; drop table users;',
            'order' => 'random_direction',
        ]));

        $invalidResponse->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_projects(): void
    {
        $student = User::factory()->create(['role_id' => $this->studentRole->id]);

        $response = $this->actingAs($student)->get(route('admin.projects.index'));

        $response->assertStatus(403);
    }
}
