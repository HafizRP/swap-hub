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
        // 1. Seed roles first
        $this->call(RoleSeeder::class);
        $studentRole = \App\Models\Role::where('slug', 'student')->first();
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();

        // 2. Seed skills
        $this->call(SkillSeeder::class);
        $skills = \App\Models\Skill::all();

        // 3. Create main test user (admin) if not exists
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Demo Student',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'major' => 'Computer Science',
                'university' => 'Stanford University',
                'bio' => 'Full-stack developer looking to collaborate on high-impact projects.',
                'reputation_points' => 500,
                'github_username' => 'teststudent',
                'role_id' => $adminRole->id, // Admin role
            ]
        );

        // Assign some skills to test user
        $testUser->skills()->sync(
            $skills->random(3)->mapWithKeys(function ($skill) {
                return [$skill->id => ['proficiency_level' => 'advanced']];
            })
        );

        // 4. Create additional sample users if they don't exist
        if (User::where('role_id', $studentRole->id)->count() >= 20) {
            $this->command->info('Dummy data already exists. Skipping...');

            return;
        }

        $users = User::factory(20)->create([
            'role_id' => $studentRole->id, // Student role
        ]);

        foreach ($users as $user) {
            $user->skills()->attach(
                $skills->random(rand(2, 5))->pluck('id'),
                ['proficiency_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced'])]
            );
        }

        // 5. Create Projects
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
                'name' => $project->title.' Chat',
            ]);

            // Add members to conversation
            $conversation->participants()->attach($project->owner_id);
            $conversation->participants()->attach($members->pluck('id'));

            // Seed some messages with sequential dates
            $messageDate = (clone $project->created_at);
            for ($i = 0; $i < 10; $i++) {
                $messageDate = (clone $messageDate)->modify('+'.rand(1, 24).' hours');
                if ($messageDate > now()) {
                    $messageDate = now();
                }

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
    }
}
