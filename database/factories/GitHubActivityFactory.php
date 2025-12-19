<?php

namespace Database\Factories;

use App\Models\GitHubActivity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GitHubActivityFactory extends Factory
{
    protected $model = GitHubActivity::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'activity_type' => 'commit',
            'commit_sha' => fake()->sha1(),
            'commit_message' => fake()->sentence(),
            'branch' => 'main',
            'additions' => fake()->numberBetween(1, 100),
            'deletions' => fake()->numberBetween(1, 50),
            'metadata' => [],
            'activity_at' => now(),
        ];
    }
}
