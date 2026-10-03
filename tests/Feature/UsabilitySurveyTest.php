<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\UsabilityFeedback;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsabilitySurveyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_authenticated_user_can_view_sus_survey_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('survey.sus'));

        $response->assertStatus(200);
        $response->assertSee('System Usability Scale');
        $response->assertSee('John Brooke, 1996');
    }

    public function test_user_can_submit_sus_survey_and_calculate_academic_score(): void
    {
        $user = User::factory()->create();

        // High satisfaction sample: Q_odd = 5, Q_even = 1
        // Odd sum = 5 * (5 - 1) = 20
        // Even sum = 5 * (5 - 1) = 20
        // Total = (20 + 20) * 2.5 = 100.00
        $data = [
            'respondent_name' => 'Budi Santoso',
            'respondent_role' => 'Mahasiswa Teknik Informatika',
            'q1' => 5,
            'q2' => 1,
            'q3' => 5,
            'q4' => 1,
            'q5' => 5,
            'q6' => 1,
            'q7' => 5,
            'q8' => 1,
            'q9' => 5,
            'q10' => 1,
            'qualitative_feedback' => 'Platform sangat intuitif dan mudah dipelajari.',
        ];

        $response = $this->actingAs($user)->post(route('survey.sus.store'), $data);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status', 'sus-submitted');

        $this->assertDatabaseHas('usability_feedbacks', [
            'respondent_name' => 'Budi Santoso',
            'sus_score' => 100.00,
            'grade' => 'A+',
            'adjective_rating' => 'Best Imaginable',
        ]);
    }

    public function test_admin_can_view_academic_usability_report(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        UsabilityFeedback::create([
            'respondent_name' => 'Tester',
            'respondent_role' => 'Student',
            'q1' => 4,
            'q2' => 2,
            'q3' => 4,
            'q4' => 2,
            'q5' => 4,
            'q6' => 2,
            'q7' => 4,
            'q8' => 2,
            'q9' => 4,
            'q10' => 2,
            'sus_score' => 75.00,
            'grade' => 'B',
            'adjective_rating' => 'Good',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.usability.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Evaluasi System Usability Scale');
        $response->assertSee('75');
    }

    public function test_non_admin_cannot_view_usability_report(): void
    {
        $studentRole = Role::where('slug', 'student')->first();
        $student = User::factory()->create(['role_id' => $studentRole->id]);

        $response = $this->actingAs($student)->get(route('admin.usability.index'));

        $response->assertStatus(403);
    }
}
