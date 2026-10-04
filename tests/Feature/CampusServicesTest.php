<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\CodeReviewSubmission;
use App\Models\CreditTransaction;
use App\Models\GitHubActivity;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\StudySession;
use App\Models\User;
use App\Services\CodeReviewService;
use App\Services\CreditLedgerService;
use App\Services\StudentPortfolioService;
use App\Services\StudyDeskService;
use App\Services\TeamMatchmakerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CampusServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_matchmaker_calculate_match_and_find_suggested_members(): void
    {
        $user = User::factory()->create();
        $php = Skill::create(['name' => 'PHP', 'category' => 'Programming']);
        $laravel = Skill::create(['name' => 'Laravel', 'category' => 'Programming']);
        $vue = Skill::create(['name' => 'Vue', 'category' => 'Programming']);

        $user->skills()->attach([$php->id, $laravel->id]);

        $projectOwner = User::factory()->create();
        $project = Project::create([
            'title' => 'Awesome Web App',
            'description' => 'Test project',
            'owner_id' => $projectOwner->id,
        ]);
        $project->requiredSkills()->attach([$php->id, $laravel->id, $vue->id]);

        $service = new TeamMatchmakerService;
        $match = $service->calculateMatch($user, $project);

        $this->assertEquals(67, $match['score']);
        $this->assertContains('PHP', $match['matching_skills']);
        $this->assertContains('Laravel', $match['matching_skills']);
        $this->assertContains('Vue', $match['missing_skills']);

        $suggestedUser = User::factory()->create();
        $suggestedUser->skills()->attach([$vue->id]);

        $suggestions = $service->findSuggestedMembers($project);
        $this->assertTrue($suggestions->pluck('id')->contains($suggestedUser->id));
        $this->assertFalse($suggestions->pluck('id')->contains($projectOwner->id));
    }

    public function test_credit_ledger_award_spend_and_transfer_credits(): void
    {
        $service = new CreditLedgerService;
        $user1 = User::factory()->create(['credits' => 0]);
        $user2 = User::factory()->create(['credits' => 50]);

        $tx = $service->awardCredits($user1, 100, 'Welcome Bonus');
        $this->assertEquals(100, $user1->fresh()->credits);
        $this->assertInstanceOf(CreditTransaction::class, $tx);
        $this->assertEquals('award', $tx->type);

        $spendTx = $service->spendCredits($user1, 40, 'Bought template');
        $this->assertEquals(60, $user1->fresh()->credits);
        $this->assertEquals(-40, $spendTx->amount);

        $this->expectException(InvalidArgumentException::class);
        $service->spendCredits($user1, 1000, 'Invalid spend');

        $service->transferCredits($user1, $user2, 30, 'Peer help');
        $this->assertEquals(30, $user1->fresh()->credits);
        $this->assertEquals(80, $user2->fresh()->credits);
    }

    public function test_study_desk_create_and_complete_session(): void
    {
        $creditLedger = new CreditLedgerService;
        $service = new StudyDeskService($creditLedger);

        $host = User::factory()->create(['credits' => 0]);
        $participant = User::factory()->create(['credits' => 0]);

        $session = $service->createSession($host, [
            'title' => 'Laravel 12 Deep Dive',
            'description' => 'Group study for Laravel',
            'participants' => [$participant->id],
        ]);

        $this->assertInstanceOf(StudySession::class, $session);
        $this->assertStringStartsWith('https://meet.jit.si/swaphub-laravel-12-deep-dive-', $session->meeting_url);
        $this->assertEquals('scheduled', $session->status);
        $this->assertCount(1, $session->participants);

        $service->completeSession($session, 15);

        $this->assertEquals('completed', $session->fresh()->status);
        $this->assertEquals(15, $host->fresh()->credits);
        $this->assertEquals(15, $participant->fresh()->credits);
    }

    public function test_code_review_create_submit_and_accept_review(): void
    {
        $creditLedger = new CreditLedgerService;
        $service = new CodeReviewService($creditLedger);

        $author = User::factory()->create(['credits' => 100]);
        $reviewer = User::factory()->create(['credits' => 0]);

        $request = $service->createRequest($author, [
            'title' => 'Refactor Auth Controller',
            'description' => 'Please review PR #42',
            'bounty_credits' => 50,
        ]);

        $this->assertEquals(50, $author->fresh()->credits);
        $this->assertEquals('open', $request->status);

        $submission = $service->submitReview($reviewer, $request, 'Code looks neat and well documented.');
        $this->assertInstanceOf(CodeReviewSubmission::class, $submission);
        $this->assertEquals('in_review', $request->fresh()->status);

        $service->acceptReview($submission);

        $this->assertEquals('accepted', $submission->fresh()->status);
        $this->assertEquals('completed', $request->fresh()->status);
        $this->assertEquals(50, $reviewer->fresh()->credits);
    }

    public function test_student_portfolio_data_aggregation(): void
    {
        $user = User::factory()->create();
        $skill = Skill::create(['name' => 'Python', 'category' => 'Programming']);
        $user->skills()->attach($skill->id, ['proficiency_level' => 'advanced']);

        $badge = Badge::create(['name' => 'Top Contributor']);
        $user->badges()->attach($badge->id, ['awarded_at' => now()]);

        $projectOwner = User::factory()->create();
        $project = Project::create([
            'title' => 'Open Source AI',
            'description' => 'AI assistant project',
            'owner_id' => $projectOwner->id,
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'status' => 'active',
            'is_validated' => true,
            'contribution_rating' => 5,
        ]);

        SkillSwapRequest::create([
            'requester_id' => $user->id,
            'provider_id' => $projectOwner->id,
            'offered_skill_id' => $skill->id,
            'requested_skill_id' => $skill->id,
            'description' => 'Swap python knowledge',
            'status' => 'completed',
        ]);

        GitHubActivity::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'activity_type' => 'commit',
            'activity_at' => now(),
        ]);

        $service = new StudentPortfolioService;
        $portfolio = $service->getPortfolioData($user);

        $this->assertEquals($user->id, $portfolio['user']->id);
        $this->assertCount(1, $portfolio['skills']);
        $this->assertCount(1, $portfolio['verified_projects']);
        $this->assertCount(1, $portfolio['badges']);
        $this->assertEquals(1, $portfolio['completed_skill_swaps_count']);
        $this->assertEquals(1, $portfolio['github_activity_count']);
    }

    public function test_course_tagging_component_can_render(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('campus.courses'));

        $response->assertOk();
    }
}
