<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Regular student user who can create and join projects',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full access to manage the platform',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}
