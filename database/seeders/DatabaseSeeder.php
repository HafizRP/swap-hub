<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed skills first
        $this->call(SkillSeeder::class);
        $skills = \App\Models\Skill::all();

        // 2. Create main test user
        $testUser = User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'test@example.com',
            'major' => 'Computer Science',
            'university' => 'Stanford University',
            'bio' => 'Full-stack developer looking to collaborate on high-impact projects.',
            'reputation_points' => 500,
            'github_username' => 'teststudent',
        ]);

        // Assign some skills to test user
        $testUser->skills()->attach(
            $skills->random(3)->pluck('id'),
            ['proficiency_level' => 'advanced']
        );

        // 3. Create additional sample users
        $users = User::factory(20)->create();
        foreach ($users as $user) {
            $user->skills()->attach(
                $skills->random(rand(2, 5))->pluck('id'),
                ['proficiency_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced'])]
            );
        }

        // 4. Create Projects
        $projects = \App\Models\Project::factory(10)
            ->recycle($users->concat([$testUser]))
            ->create();

        foreach ($projects as $project) {
            // Add some members to each project
            $members = $users->random(rand(1, 3));
            foreach ($members as $member) {
                $joinedAt = fake()->dateTimeBetween($project->created_at, 'now');
                $project->members()->attach($member, [
                    'role' => fake()->randomElement(['member', 'contributor']),
                    'joined_at' => $joinedAt,
                    'is_validated' => true,
                    'created_at' => $joinedAt,
                ]);
            }

            // Create a conversation for the project
            $conversation = \App\Models\Conversation::create([
                'type' => 'project',
                'project_id' => $project->id,
                'name' => $project->title . ' Chat',
            ]);

            // Add members to conversation
            $conversation->participants()->attach($project->owner_id);
            $conversation->participants()->attach($members->pluck('id'));

            // Seed some messages with sequential dates
            $messageDate = (clone $project->created_at);
            for ($i = 0; $i < 10; $i++) {
                $messageDate = (clone $messageDate)->modify('+' . rand(1, 24) . ' hours');
                if ($messageDate > now())
                    $messageDate = now();

                \App\Models\Message::factory()->create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $members->concat([$project->owner])->random()->id,
                    'created_at' => $messageDate,
                ]);
            }

            // Seed some GitHub activity
            \App\Models\GitHubActivity::factory(5)
                ->recycle($project)
                ->recycle($members->concat([$project->owner]))
                ->create();
        }

        // 5. Create Skill Swap Requests
        foreach (range(1, 15) as $i) {
            $requester = $users->random();
            $offeredSkill = $skills->random();
            $requestedSkill = $skills->reject(fn($s) => $s->id === $offeredSkill->id)->random();
            $status = fake()->randomElement(['pending', 'accepted', 'completed', 'cancelled']);

            $createdAt = fake()->dateTimeBetween('-2 months', 'now');
            $providerId = null;
            $acceptedAt = null;
            $completedAt = null;

            if (in_array($status, ['accepted', 'completed'])) {
                $providerId = $users->reject(fn($u) => $u->id === $requester->id)->random()->id;
                $acceptedAt = (clone $createdAt)->modify('+' . rand(1, 5) . ' days');
                if ($status === 'completed') {
                    $completedAt = (clone $acceptedAt)->modify('+' . rand(1, 48) . ' hours');
                }
            }

            \App\Models\SkillSwapRequest::create([
                'requester_id' => $requester->id,
                'provider_id' => $providerId,
                'offered_skill_id' => $offeredSkill->id,
                'requested_skill_id' => $requestedSkill->id,
                'description' => fake()->paragraph(),
                'points_offered' => rand(20, 100),
                'status' => $status,
                'accepted_at' => $acceptedAt,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
            ]);
        }
    }
}
