<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
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

    public function test_admin_can_view_users_list_and_sorting_is_safe(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        User::factory()->count(3)->create(['role_id' => $this->studentRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'sort' => 'name',
            'order' => 'asc',
        ]));

        $response->assertStatus(200);

        // Test fallback on invalid sort column
        $invalidSortResponse = $this->actingAs($admin)->get(route('admin.users.index', [
            'sort' => 'non_existent_column',
            'order' => 'invalid_direction',
        ]));

        $invalidSortResponse->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_users(): void
    {
        $student = User::factory()->create(['role_id' => $this->studentRole->id]);

        $response = $this->actingAs($student)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_user_role_and_information(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $targetUser = User::factory()->create(['role_id' => $this->studentRole->id]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $targetUser), [
            'name' => 'Promoted Student',
            'email' => $targetUser->email,
            'role' => 'admin',
            'reputation_points' => 500,
        ]);

        $response->assertRedirect(route('admin.users.show', $targetUser));

        $targetUser->refresh();
        $this->assertEquals('Promoted Student', $targetUser->name);
        $this->assertEquals(500, $targetUser->reputation_points);
        $this->assertEquals($this->adminRole->id, $targetUser->role_id);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'student',
            'reputation_points' => $admin->reputation_points,
        ]);

        $admin->refresh();
        $this->assertEquals($this->adminRole->id, $admin->role_id);
    }

    public function test_admin_can_toggle_role_of_another_user_with_valid_password(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'password' => Hash::make('secret-admin-password'),
        ]);

        $student = User::factory()->create([
            'role_id' => $this->studentRole->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-role', $student), [
            'admin_password' => 'secret-admin-password',
        ]);

        $response->assertRedirect();
        $student->refresh();
        $this->assertEquals($this->adminRole->id, $student->role_id);

        // Toggle back to student
        $response = $this->actingAs($admin)->post(route('admin.users.toggle-role', $student), [
            'admin_password' => 'secret-admin-password',
        ]);

        $student->refresh();
        $this->assertEquals($this->studentRole->id, $student->role_id);
    }

    public function test_toggle_role_fails_with_invalid_password(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'password' => Hash::make('secret-admin-password'),
        ]);

        $student = User::factory()->create([
            'role_id' => $this->studentRole->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-role', $student), [
            'admin_password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['admin_password']);
        $student->refresh();
        $this->assertEquals($this->studentRole->id, $student->role_id);
    }
}
