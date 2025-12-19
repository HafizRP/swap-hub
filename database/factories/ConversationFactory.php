<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['project', 'direct']),
            'project_id' => function (array $attributes) {
                return $attributes['type'] === 'project' ? Project::factory() : null;
            },
            'name' => fake()->word(),
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
