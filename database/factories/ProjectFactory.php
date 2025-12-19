<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $projects = [
            [
                'title' => 'E-Commerce Platform for Local Artisans',
                'description' => 'A fully functional online marketplace designed to help local craftsmen and artists sell their handmade products. Features include secure payments, inventory management, and a rating system.',
                'category' => 'Development'
            ],
            [
                'title' => 'Real-time Task Management System',
                'description' => 'A collaborative tool for teams to manage projects and tasks in real-time. Built with WebSockets for instant updates, dragging and dropping task cards, and progress tracking.',
                'category' => 'Development'
            ],
            [
                'title' => 'AI-Powered Resume Builder',
                'description' => 'A web application that helps job seekers create professional resumes with the assistance of AI. It suggests better wording, optimizes for ATS systems, and offers multiple templates.',
                'category' => 'Development'
            ],
            [
                'title' => 'Social Network for Plant Enthusiasts',
                'description' => 'A niche social media platform where gardening lovers can share photos of their plants, exchange advice, and even trade cuttings with nearby members.',
                'category' => 'Marketing'
            ],
            [
                'title' => 'Interactive Learning Dashboard',
                'description' => 'A gamified education platform for students to track their course progress, participate in quizzes, and earn badges. Includes a dashboard with visual analytics.',
                'category' => 'Design'
            ],
            [
                'title' => 'Community-Driven Recipe App',
                'description' => 'A platform where users can post, rate, and save recipes. It features a unique meal planning tool and automatically generates grocery lists based on selected recipes.',
                'category' => 'Design'
            ],
            [
                'title' => 'Remote Job Board for Developers',
                'description' => 'A specialized job board focusing exclusively on remote software engineering roles. Features advanced filtering by tech stack, salary range, and timezone.',
                'category' => 'Marketing'
            ],
            [
                'title' => 'Personal Finance Tracker',
                'description' => 'A secure application for tracking daily expenses, setting savings goals, and visualizing spending habits with interactive charts and graphs.',
                'category' => 'Development'
            ],
            [
                'title' => 'Event Booking & Ticket System',
                'description' => 'A robust system for organizers to create events and for users to book tickets. Supports QR code generation for entry and automated email reminders.',
                'category' => 'Design'
            ],
            [
                'title' => 'Student Portfolio Generator',
                'description' => 'Help students build professional portfolios by automatically pulling their GitHub projects and presenting them in a clean, customizable web layout.',
                'category' => 'Design'
            ]
        ];

        $project = fake()->randomElement($projects);

        return [
            'title' => $project['title'],
            'description' => $project['description'],
            'category' => $project['category'],
            'owner_id' => User::factory(),
            'github_repo_url' => 'https://github.com/' . fake()->userName() . '/' . fake()->slug(),
            'github_repo_name' => fake()->userName() . '/' . fake()->slug(),
            'status' => fake()->randomElement(['planning', 'active', 'completed', 'archived']),
            'start_date' => $startDate = fake()->dateTimeBetween('-3 months', 'now'),
            'end_date' => (clone $startDate)->modify('+3 months'),
            'created_at' => $startDate,
        ];
    }
}
