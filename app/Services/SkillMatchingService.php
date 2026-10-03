<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillSwapRequest;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Class SkillMatchingService
 *
 * Implements academic mathematical matching algorithms (Jaccard Similarity & Weighted Skill Scoring)
 * to evaluate compatibility between students, projects, and peer-to-peer skill swap requests.
 *
 * Formula:
 * Jaccard Index: J(A, B) = |A ∩ B| / |A ∪ B|
 * Requirement Coverage: Recall(A, B) = |A ∩ B| / |B|
 * Composite Match Score = (w1 * Jaccard) + (w2 * Coverage) + (w3 * ProficiencyMultiplier)
 */
class SkillMatchingService
{
    public const WEIGHT_JACCARD = 0.40;

    public const WEIGHT_COVERAGE = 0.40;

    public const WEIGHT_PROFICIENCY = 0.20;

    /**
     * Proficiency weights map (1.0 scale).
     */
    protected array $proficiencyWeights = [
        'expert' => 1.00,
        'advanced' => 0.85,
        'intermediate' => 0.70,
        'beginner' => 0.50,
    ];

    /**
     * Compute raw Jaccard Similarity Coefficient between two sets of IDs.
     * J(A, B) = |A ∩ B| / |A ∪ B|
     *
     * @param  array<int>  $setA
     * @param  array<int>  $setB
     */
    public function calculateJaccardIndex(array $setA, array $setB): float
    {
        $setA = array_unique(array_filter($setA));
        $setB = array_unique(array_filter($setB));

        if (empty($setA) && empty($setB)) {
            return 0.0;
        }

        $intersection = array_intersect($setA, $setB);
        $union = array_unique(array_merge($setA, $setB));

        if (count($union) === 0) {
            return 0.0;
        }

        return round(count($intersection) / count($union), 4);
    }

    /**
     * Compute requirement coverage (recall).
     * Coverage(A, B) = |A ∩ B| / |B|
     *
     * @param  array<int>  $candidateSkillIds  Set A
     * @param  array<int>  $requiredSkillIds  Set B
     */
    public function calculateCoverage(array $candidateSkillIds, array $requiredSkillIds): float
    {
        $candidateSkillIds = array_unique(array_filter($candidateSkillIds));
        $requiredSkillIds = array_unique(array_filter($requiredSkillIds));

        if (empty($requiredSkillIds)) {
            return empty($candidateSkillIds) ? 0.0 : 1.0;
        }

        $intersection = array_intersect($candidateSkillIds, $requiredSkillIds);

        return round(count($intersection) / count($requiredSkillIds), 4);
    }

    /**
     * Calculate comprehensive match analysis between a user and a project.
     */
    public function evaluateProjectMatch(User $user, Project $project): array
    {
        // Load user skills with pivot proficiency if not already loaded
        $userSkills = $user->relationLoaded('skills') ? $user->skills : $user->skills()->get();
        $projectSkills = $project->relationLoaded('skills') ? $project->skills : $project->skills()->get();

        $userSkillIds = $userSkills->pluck('id')->all();
        $projectSkillIds = $projectSkills->pluck('id')->all();

        // 1. If project has no explicit skills registered yet, fallback to neutral matching
        if (empty($projectSkillIds)) {
            return [
                'jaccard_index' => 0.0,
                'coverage' => 0.0,
                'composite_score' => 0.0,
                'match_percentage' => 0,
                'matched_count' => 0,
                'required_count' => 0,
                'matched_skills' => collect(),
                'missing_skills' => collect(),
                'extra_skills' => $userSkills,
                'label' => 'Terbuka Untuk Semua Skill',
                'badge_class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                'is_recommended' => false,
            ];
        }

        // 2. Compute Set Intersections and Differences
        $intersectIds = array_values(array_intersect($userSkillIds, $projectSkillIds));
        $missingIds = array_values(array_diff($projectSkillIds, $userSkillIds));
        $extraIds = array_values(array_diff($userSkillIds, $projectSkillIds));

        $matchedSkills = $userSkills->whereIn('id', $intersectIds)->values();
        $missingSkills = $projectSkills->whereIn('id', $missingIds)->values();
        $extraSkills = $userSkills->whereIn('id', $extraIds)->values();

        // 3. Compute Metrics
        $jaccard = $this->calculateJaccardIndex($userSkillIds, $projectSkillIds);
        $coverage = $this->calculateCoverage($userSkillIds, $projectSkillIds);

        // 4. Calculate Average Proficiency for matched skills
        $proficiencyMultiplier = 0.70; // default intermediate
        if ($matchedSkills->isNotEmpty()) {
            $totalWeight = 0;
            foreach ($matchedSkills as $skill) {
                $level = $skill->pivot->proficiency_level ?? 'intermediate';
                $totalWeight += ($this->proficiencyWeights[$level] ?? 0.70);
            }
            $proficiencyMultiplier = $totalWeight / $matchedSkills->count();
        }

        // 5. Composite Score Calculation
        $composite = (self::WEIGHT_JACCARD * $jaccard)
            + (self::WEIGHT_COVERAGE * $coverage)
            + (self::WEIGHT_PROFICIENCY * ($coverage * $proficiencyMultiplier));

        $percentage = (int) round(min(100, $composite * 100));

        // 6. Label & Rating Categorization
        [$label, $badgeClass, $isRecommended] = match (true) {
            $percentage >= 75 => ['Sangat Cocok', 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800', true],
            $percentage >= 50 => ['Cocok', 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-800', true],
            $percentage >= 25 => ['Cukup Cocok', 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800', false],
            default => ['Belum Cocok', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-700', false],
        };

        return [
            'jaccard_index' => $jaccard,
            'coverage' => $coverage,
            'composite_score' => round($composite, 4),
            'match_percentage' => $percentage,
            'matched_count' => count($intersectIds),
            'required_count' => count($projectSkillIds),
            'matched_skills' => $matchedSkills,
            'missing_skills' => $missingSkills,
            'extra_skills' => $extraSkills,
            'label' => $label,
            'badge_class' => $badgeClass,
            'is_recommended' => $isRecommended,
        ];
    }

    /**
     * Calculate Skill Swap compatibility between a prospective applicant and a SkillSwapRequest.
     * Evaluates whether User A has what User B wants and vice versa.
     */
    public function evaluateSkillSwapCompatibility(User $candidate, SkillSwapRequest $request): array
    {
        $candidateSkillIds = $candidate->skills()->pluck('skills.id')->all();

        $candidateOffersRequested = in_array($request->requested_skill_id, $candidateSkillIds);
        $candidateWantsOffered = in_array($request->offered_skill_id, $candidateSkillIds);

        // Reciprocal matching logic
        $score = 0;
        $status = 'Perlu Belajar';
        $badgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';

        if ($candidateOffersRequested && ! $candidateWantsOffered) {
            $score = 100;
            $status = 'Kecocokan Sempurna (100%)';
            $badgeClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800';
        } elseif ($candidateOffersRequested) {
            $score = 85;
            $status = 'Bisa Mengajar (85%)';
            $badgeClass = 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-800';
        } elseif ($candidateWantsOffered) {
            $score = 60;
            $status = 'Bisa Belajar (60%)';
            $badgeClass = 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800';
        }

        return [
            'score' => $score,
            'status' => $status,
            'badge_class' => $badgeClass,
            'can_fulfill_requested' => $candidateOffersRequested,
            'already_has_offered' => $candidateWantsOffered,
        ];
    }

    /**
     * Rank a collection of projects for a given user by match percentage.
     */
    public function rankProjectsForUser(User $user, iterable $projects): Collection
    {
        $collection = collect($projects);

        return $collection->map(function (Project $project) use ($user) {
            $analysis = $this->evaluateProjectMatch($user, $project);
            $project->skill_match_analysis = $analysis;
            $project->skill_match_percentage = $analysis['match_percentage'];

            return $project;
        })->sortByDesc('skill_match_percentage')->values();
    }
}
