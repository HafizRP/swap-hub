<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use App\Services\SkillMatchingService;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillMatchingTest extends TestCase
{
    use RefreshDatabase;

    protected SkillMatchingService $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->matcher = app(SkillMatchingService::class);
    }

    public function test_jaccard_index_mathematical_precision(): void
    {
        // 1. Identical sets: J(A, A) = 1.0
        $this->assertEquals(1.0, $this->matcher->calculateJaccardIndex([1, 2, 3], [1, 2, 3]));

        // 2. Disjoint sets: J(A, B) = 0.0
        $this->assertEquals(0.0, $this->matcher->calculateJaccardIndex([1, 2], [3, 4]));

        // 3. Partial overlap: A = {1, 2, 3}, B = {2, 3, 4, 5}
        // Intersection = {2, 3} (size 2), Union = {1, 2, 3, 4, 5} (size 5) => 2/5 = 0.40
        $this->assertEquals(0.40, $this->matcher->calculateJaccardIndex([1, 2, 3], [2, 3, 4, 5]));

        // 4. Empty sets
        $this->assertEquals(0.0, $this->matcher->calculateJaccardIndex([], []));
    }

    public function test_skill_coverage_calculation(): void
    {
        // Candidate has {1, 2, 3}, Project requires {1, 2, 4, 5}
        // Candidate fulfills 2 out of 4 requirements = 50%
        $this->assertEquals(0.50, $this->matcher->calculateCoverage([1, 2, 3], [1, 2, 4, 5]));

        // Candidate fulfills all requirements
        $this->assertEquals(1.0, $this->matcher->calculateCoverage([1, 2, 3, 4], [1, 2]));
    }

    public function test_project_match_analysis_with_eloquent_models(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $skillPhp = Skill::create(['name' => 'PHP', 'category' => 'Backend']);
        $skillLaravel = Skill::create(['name' => 'Laravel', 'category' => 'Backend']);
        $skillVue = Skill::create(['name' => 'Vue.js', 'category' => 'Frontend']);
        $skillDocker = Skill::create(['name' => 'Docker', 'category' => 'DevOps']);

        // User has PHP (advanced) and Laravel (expert)
        $user->skills()->attach($skillPhp->id, ['proficiency_level' => 'advanced']);
        $user->skills()->attach($skillLaravel->id, ['proficiency_level' => 'expert']);

        // Project requires PHP, Laravel, and Vue.js
        $project->skills()->attach([$skillPhp->id, $skillLaravel->id, $skillVue->id]);

        $analysis = $this->matcher->evaluateProjectMatch($user, $project);

        $this->assertEquals(2, $analysis['matched_count']);
        $this->assertEquals(3, $analysis['required_count']);
        $this->assertGreaterThan(0.5, $analysis['composite_score']);
        $this->assertContains('PHP', $analysis['matched_skills']->pluck('name')->all());
        $this->assertContains('Laravel', $analysis['matched_skills']->pluck('name')->all());
        $this->assertContains('Vue.js', $analysis['missing_skills']->pluck('name')->all());
    }

    public function test_skill_swap_reciprocal_compatibility(): void
    {
        $skillA = Skill::create(['name' => 'React', 'category' => 'Frontend']);
        $skillB = Skill::create(['name' => 'Python', 'category' => 'Backend']);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User 2 has React in their profile
        $user2->skills()->attach($skillA->id, ['proficiency_level' => 'expert']);

        // User 1 created swap: offers Python, requests React
        $swapRequest = SkillSwapRequest::create([
            'requester_id' => $user1->id,
            'offered_skill_id' => $skillB->id,
            'requested_skill_id' => $skillA->id,
            'description' => 'Will teach Python for React',
            'points_offered' => 50,
            'status' => 'pending',
        ]);

        $compatibility = $this->matcher->evaluateSkillSwapCompatibility($user2, $swapRequest);

        $this->assertTrue($compatibility['can_fulfill_requested']);
        $this->assertGreaterThanOrEqual(85, $compatibility['score']);
    }

    public function test_projects_page_displays_skill_matching_badges(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['status' => 'active']);

        $skill = Skill::create(['name' => 'Machine Learning', 'category' => 'AI']);
        $user->skills()->attach($skill->id, ['proficiency_level' => 'advanced']);
        $project->skills()->attach($skill->id);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee('Analisis Kecocokan');
        $response->assertSee('Jaccard Similarity');
        $response->assertSee('Machine Learning');
    }
}
