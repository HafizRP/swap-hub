<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillSwapRequestFactory extends Factory
{
    protected $model = SkillSwapRequest::class;

    public function definition(): array
    {
        return [
            'requester_id' => User::factory(),
            'offered_skill_id' => Skill::factory(),
            'requested_skill_id' => Skill::factory(),
            'description' => fake()->paragraph(),
            'points_offered' => fake()->numberBetween(10, 100),
            'status' => fake()->randomElement(['pending', 'accepted', 'completed', 'cancelled']),
            'accepted_at' => null,
            'completed_at' => null,
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
