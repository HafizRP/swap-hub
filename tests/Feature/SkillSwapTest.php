<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillSwapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_authenticated_user_can_view_skill_swaps_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('skills.swap.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_skill_swap_request(): void
    {
        $user = User::factory()->create();
        $offeredSkill = Skill::factory()->create(['name' => 'Laravel']);
        $requestedSkill = Skill::factory()->create(['name' => 'Vue.js']);

        $response = $this->actingAs($user)->post(route('skills.swap.store'), [
            'offered_skill_id' => $offeredSkill->id,
            'requested_skill_id' => $requestedSkill->id,
            'description' => 'I can teach Laravel architecture in exchange for learning Vue 3 composition API.',
            'points_offered' => 15,
        ]);

        $response->assertRedirect(route('skills.swap.index'));
        $this->assertDatabaseHas('skill_swap_requests', [
            'requester_id' => $user->id,
            'offered_skill_id' => $offeredSkill->id,
            'requested_skill_id' => $requestedSkill->id,
            'points_offered' => 15,
            'status' => 'pending',
        ]);
    }

    public function test_user_can_accept_another_users_skill_swap(): void
    {
        $requester = User::factory()->create();
        $provider = User::factory()->create();
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();

        $swap = SkillSwapRequest::create([
            'requester_id' => $requester->id,
            'offered_skill_id' => $skill1->id,
            'requested_skill_id' => $skill2->id,
            'description' => 'Looking to trade skills and collaborate on projects.',
            'points_offered' => 10,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($provider)->post(route('skills.swap.accept', $swap));

        $response->assertRedirect();
        $this->assertDatabaseHas('skill_swap_requests', [
            'id' => $swap->id,
            'provider_id' => $provider->id,
            'status' => 'accepted',
        ]);
    }

    public function test_user_cannot_accept_own_skill_swap(): void
    {
        $user = User::factory()->create();
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();

        $swap = SkillSwapRequest::create([
            'requester_id' => $user->id,
            'offered_skill_id' => $skill1->id,
            'requested_skill_id' => $skill2->id,
            'description' => 'Looking to trade skills.',
            'points_offered' => 10,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post(route('skills.swap.accept', $swap));

        $this->assertDatabaseHas('skill_swap_requests', [
            'id' => $swap->id,
            'provider_id' => null,
            'status' => 'pending',
        ]);
    }

    public function test_user_can_complete_skill_swap_and_points_are_awarded(): void
    {
        $requester = User::factory()->create();
        $provider = User::factory()->create(['reputation_points' => 50]);
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();

        $swap = SkillSwapRequest::create([
            'requester_id' => $requester->id,
            'provider_id' => $provider->id,
            'offered_skill_id' => $skill1->id,
            'requested_skill_id' => $skill2->id,
            'description' => 'Looking to trade skills.',
            'points_offered' => 20,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($requester)->post(route('skills.swap.complete', $swap));

        $response->assertRedirect();
        $this->assertDatabaseHas('skill_swap_requests', [
            'id' => $swap->id,
            'status' => 'completed',
        ]);

        $this->assertEquals(70, $provider->fresh()->reputation_points);
    }

    public function test_user_can_cancel_skill_swap(): void
    {
        $requester = User::factory()->create();
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();

        $swap = SkillSwapRequest::create([
            'requester_id' => $requester->id,
            'offered_skill_id' => $skill1->id,
            'requested_skill_id' => $skill2->id,
            'description' => 'Looking to trade skills.',
            'points_offered' => 10,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($requester)->post(route('skills.swap.cancel', $swap));

        $response->assertRedirect();
        $this->assertDatabaseHas('skill_swap_requests', [
            'id' => $swap->id,
            'status' => 'cancelled',
        ]);
    }
}
