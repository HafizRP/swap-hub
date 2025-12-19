<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming
            ['name' => 'PHP', 'category' => 'Programming', 'description' => 'Server-side scripting language'],
            ['name' => 'Laravel', 'category' => 'Programming', 'description' => 'PHP web framework'],
            ['name' => 'JavaScript', 'category' => 'Programming', 'description' => 'Client-side programming language'],
            ['name' => 'React', 'category' => 'Programming', 'description' => 'JavaScript library for building UIs'],
            ['name' => 'Vue.js', 'category' => 'Programming', 'description' => 'Progressive JavaScript framework'],
            ['name' => 'Python', 'category' => 'Programming', 'description' => 'General-purpose programming language'],
            ['name' => 'Java', 'category' => 'Programming', 'description' => 'Object-oriented programming language'],
            ['name' => 'Node.js', 'category' => 'Programming', 'description' => 'JavaScript runtime environment'],
            ['name' => 'SQL', 'category' => 'Programming', 'description' => 'Database query language'],
            ['name' => 'Git', 'category' => 'Programming', 'description' => 'Version control system'],
            
            // Design
            ['name' => 'UI/UX Design', 'category' => 'Design', 'description' => 'User interface and experience design'],
            ['name' => 'Figma', 'category' => 'Design', 'description' => 'Collaborative design tool'],
            ['name' => 'Adobe Photoshop', 'category' => 'Design', 'description' => 'Image editing software'],
            ['name' => 'Adobe Illustrator', 'category' => 'Design', 'description' => 'Vector graphics editor'],
            ['name' => 'Graphic Design', 'category' => 'Design', 'description' => 'Visual communication design'],
            ['name' => 'Prototyping', 'category' => 'Design', 'description' => 'Creating interactive mockups'],
            
            // Marketing
            ['name' => 'Digital Marketing', 'category' => 'Marketing', 'description' => 'Online marketing strategies'],
            ['name' => 'Social Media Marketing', 'category' => 'Marketing', 'description' => 'Marketing on social platforms'],
            ['name' => 'Content Writing', 'category' => 'Marketing', 'description' => 'Creating engaging content'],
            ['name' => 'SEO', 'category' => 'Marketing', 'description' => 'Search engine optimization'],
            ['name' => 'Copywriting', 'category' => 'Marketing', 'description' => 'Writing persuasive copy'],
            
            // Business
            ['name' => 'Project Management', 'category' => 'Business', 'description' => 'Managing projects effectively'],
            ['name' => 'Business Analysis', 'category' => 'Business', 'description' => 'Analyzing business processes'],
            ['name' => 'Data Analysis', 'category' => 'Business', 'description' => 'Analyzing and interpreting data'],
            ['name' => 'Financial Analysis', 'category' => 'Business', 'description' => 'Analyzing financial data'],
            
            // Other
            ['name' => 'Video Editing', 'category' => 'Multimedia', 'description' => 'Editing and producing videos'],
            ['name' => 'Photography', 'category' => 'Multimedia', 'description' => 'Capturing and editing photos'],
            ['name' => 'Animation', 'category' => 'Multimedia', 'description' => 'Creating animated content'],
        ];

        foreach ($skills as $skill) {
            \App\Models\Skill::create($skill);
        }
    }
}
