<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Badge;
use App\Models\CodeReviewRequest;
use App\Models\Role;
use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $studentRole = Role::create(['name' => 'Student', 'slug' => 'student']);

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'password' => bcrypt('password123'),
        ]);

        $this->student = User::factory()->create([
            'role_id' => $studentRole->id,
            'credits' => 100,
        ]);
    }

    public function test_admin_can_suspend_and_unsuspend_user(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.toggle-suspension', $this->student), [
                'reason' => 'Spamming repository',
            ]);

        $response->assertRedirect();
        $this->student->refresh();
        $this->assertTrue($this->student->isSuspended());
        $this->assertEquals('Spamming repository', $this->student->suspension_reason);

        // Test unsuspend
        $response2 = $this->actingAs($this->admin)
            ->post(route('admin.users.toggle-suspension', $this->student));

        $response2->assertRedirect();
        $this->student->refresh();
        $this->assertFalse($this->student->isSuspended());
    }

    public function test_suspended_user_is_blocked_from_logging_in(): void
    {
        $this->student->update([
            'suspended_at' => now(),
            'suspension_reason' => 'Violation',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $this->student->email,
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_adjust_user_credits(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.credits.adjust'), [
                'user_id' => $this->student->id,
                'amount' => 50,
                'reason' => 'Bonus kontribusi acara kampus',
            ]);

        $response->assertRedirect();
        $this->student->refresh();
        $this->assertEquals(150, $this->student->credits);

        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $this->admin->id,
            'action' => 'credit.adjust',
        ]);
    }

    public function test_admin_can_cancel_code_review_and_refund_bounty(): void
    {
        $req = CodeReviewRequest::create([
            'user_id' => $this->student->id,
            'title' => 'Sample PR Review',
            'bounty_credits' => 30,
            'status' => 'open',
        ]);

        $initialCredits = $this->student->credits;

        $response = $this->actingAs($this->admin)
            ->post(route('admin.code-reviews.cancel', $req));

        $response->assertRedirect();
        $req->refresh();
        $this->student->refresh();

        $this->assertEquals('cancelled', $req->status);
        $this->assertEquals($initialCredits + 30, $this->student->credits);
    }

    public function test_admin_can_manage_skills(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.skills.store'), [
                'name' => 'Elixir',
                'category' => 'Backend',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('skills', ['name' => 'Elixir']);

        $skill = Skill::where('name', 'Elixir')->first();
        $delResponse = $this->actingAs($this->admin)
            ->delete(route('admin.skills.destroy', $skill));

        $delResponse->assertRedirect();
        $this->assertDatabaseMissing('skills', ['name' => 'Elixir']);
    }

    public function test_admin_can_manage_courses(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.courses.store'), [
                'code' => 'CS500',
                'name' => 'Advanced Algorithms',
                'department' => 'Computer Science',
                'semester' => 'Semester 7',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('courses', ['code' => 'CS500']);
    }

    public function test_admin_can_create_and_assign_badge(): void
    {
        $badge = Badge::create([
            'name' => 'Star Contributor',
            'description' => 'Top contributor badge',
            'points_required' => 500,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.badges.assign'), [
                'user_id' => $this->student->id,
                'badge_id' => $badge->id,
            ]);

        $response->assertRedirect();
        $this->assertTrue($this->student->badges()->where('badge_id', $badge->id)->exists());
    }

    public function test_admin_can_intervene_skill_swap(): void
    {
        $peer = User::factory()->create();
        $skill1 = Skill::create(['name' => 'Skill A', 'category' => 'Tech']);
        $skill2 = Skill::create(['name' => 'Skill B', 'category' => 'Tech']);
        $swap = SkillSwapRequest::create([
            'requester_id' => $this->student->id,
            'provider_id' => $peer->id,
            'offered_skill_id' => $skill1->id,
            'requested_skill_id' => $skill2->id,
            'description' => 'Collaboration on Laravel and React',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.swaps.complete', $swap));

        $response->assertRedirect();
        $swap->refresh();
        $this->assertEquals('completed', $swap->status);
    }
}
