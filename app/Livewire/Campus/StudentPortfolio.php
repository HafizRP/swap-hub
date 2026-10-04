<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\User;
use App\Services\StudentPortfolioService;
use Livewire\Component;

class StudentPortfolio extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load([
            'skills',
            'badges',
            'courses',
            'projects.requiredSkills',
            'projectMemberships.project',
            'githubActivities',
        ]);
    }

    public function render(StudentPortfolioService $portfolioService)
    {
        $data = $portfolioService->getPortfolioData($this->user);

        return view('livewire.campus.student-portfolio', array_merge($data, [
            'user' => $this->user,
        ]))->layout('layouts.app');
    }
}
