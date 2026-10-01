<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\MemberValidated;
use App\Mail\NewApplicationReceived;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProjectReviewAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_applying_to_project_sends_email_to_owner(): void
    {
        Mail::fake();

        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $skills = Skill::factory()->count(3)->create();
        $applicant->skills()->attach($skills->pluck('id'));

        $response = $this->actingAs($applicant)->post(route('projects.apply', $project), [
            'message' => 'I would love to help you build this project using Laravel and Tailwind.',
            'role' => 'member',
        ]);

        $response->assertRedirect();

        Mail::assertSent(NewApplicationReceived::class, function ($mail) use ($owner, $applicant, $project) {
            return $mail->hasTo($owner->email)
                && (int) $mail->project->id === (int) $project->id
                && (int) $mail->applicant->id === (int) $applicant->id;
        });
    }

    public function test_owner_can_validate_member_contribution_and_award_points(): void
    {
        Mail::fake();

        $owner = User::factory()->create();
        $member = User::factory()->create(['reputation_points' => 10]);
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $project->members()->attach($member->id, [
            'role' => 'contributor',
            'status' => 'active',
            'is_validated' => false,
        ]);

        $response = $this->actingAs($owner)->post(route('projects.members.validate', [$project, $member]), [
            'rating' => 5,
            'notes' => 'Exceptional contributions and clean code quality.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('project_members', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'is_validated' => true,
            'contribution_rating' => 5,
            'contribution_notes' => 'Exceptional contributions and clean code quality.',
        ]);

        $this->assertEquals(60, $member->fresh()->reputation_points);

        Mail::assertSent(MemberValidated::class, function ($mail) use ($member) {
            return $mail->hasTo($member->email);
        });
    }

    public function test_non_owner_cannot_validate_member(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $project->members()->attach($member->id, [
            'role' => 'contributor',
            'status' => 'active',
        ]);

        $response = $this->actingAs($stranger)->post(route('projects.members.validate', [$project, $member]), [
            'rating' => 5,
            'notes' => 'Unauthorized validation.',
        ]);

        $response->assertStatus(403);
    }
}
