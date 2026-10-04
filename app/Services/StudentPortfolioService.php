<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProjectMember;
use App\Models\SkillSwapRequest;
use App\Models\User;

class StudentPortfolioService
{
    /**
     * Aggregate user skills, verified project contributions, badges, completed skill swaps, and GitHub activity count.
     *
     * @return array{
     *     user: User,
     *     skills: \Illuminate\Database\Eloquent\Collection,
     *     verified_projects: \Illuminate\Database\Eloquent\Collection,
     *     badges: \Illuminate\Database\Eloquent\Collection,
     *     completed_skill_swaps_count: int,
     *     github_activity_count: int
     * }
     */
    public function getPortfolioData(User $user): array
    {
        $user->load(['skills', 'badges']);

        $verifiedProjects = ProjectMember::with('project')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('is_validated', true)
            ->get();

        $completedSkillSwapsCount = SkillSwapRequest::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
                ->orWhere('provider_id', $user->id);
        })
            ->where('status', 'completed')
            ->count();

        $githubActivityCount = $user->githubActivities()->count();

        return [
            'user' => $user,
            'skills' => $user->skills,
            'verified_projects' => $verifiedProjects,
            'badges' => $user->badges,
            'completed_skill_swaps_count' => $completedSkillSwapsCount,
            'github_activity_count' => $githubActivityCount,
        ];
    }
}
