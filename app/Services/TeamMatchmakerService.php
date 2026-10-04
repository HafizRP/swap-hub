<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TeamMatchmakerService
{
    /**
     * Calculate match percentage based on user's skills vs project's required skills.
     *
     * @return array{score: int, matching_skills: array, missing_skills: array}
     */
    public function calculateMatch(User $user, Project $project): array
    {
        $projectSkills = $project->requiredSkills;
        if ($projectSkills->isEmpty()) {
            return [
                'score' => 100,
                'matching_skills' => [],
                'missing_skills' => [],
            ];
        }

        $projectSkillIds = $projectSkills->pluck('id')->toArray();
        $userSkillIds = $user->skills->pluck('id')->toArray();

        $matchingIds = array_intersect($projectSkillIds, $userSkillIds);
        $missingIds = array_diff($projectSkillIds, $userSkillIds);

        $matchingSkills = $projectSkills->whereIn('id', $matchingIds)->pluck('name')->values()->toArray();
        $missingSkills = $projectSkills->whereIn('id', $missingIds)->pluck('name')->values()->toArray();

        $score = (int) round((count($matchingIds) / count($projectSkillIds)) * 100);

        return [
            'score' => $score,
            'matching_skills' => $matchingSkills,
            'missing_skills' => $missingSkills,
        ];
    }

    /**
     * Find users whose skills best match missing skills of the project.
     *
     * @return Collection<int, User>
     */
    public function findSuggestedMembers(Project $project, int $limit = 5): Collection
    {
        $requiredSkillIds = $project->requiredSkills->pluck('id')->toArray();

        if (empty($requiredSkillIds)) {
            return User::where('id', '!=', $project->owner_id)
                ->limit($limit)
                ->get();
        }

        $memberUserIds = $project->members->pluck('id')->push($project->owner_id)->unique()->toArray();

        return User::whereNotIn('id', $memberUserIds)
            ->whereHas('skills', function ($query) use ($requiredSkillIds) {
                $query->whereIn('skills.id', $requiredSkillIds);
            })
            ->withCount(['skills as matching_skills_count' => function ($query) use ($requiredSkillIds) {
                $query->whereIn('skills.id', $requiredSkillIds);
            }])
            ->orderByDesc('matching_skills_count')
            ->limit($limit)
            ->get();
    }
}
