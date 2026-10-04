<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\Project;
use App\Models\User;
use App\Services\TeamMatchmakerService;
use Livewire\Component;

class TeamMatchmaker extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        if ($project->owner_id !== auth()->id() && ! $project->activeMembers()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized.');
        }

        $this->project = $project->load(['requiredSkills', 'members']);
    }

    public function render(TeamMatchmakerService $matchmakerService)
    {
        $existingMemberIds = $this->project->members->pluck('id')
            ->push($this->project->owner_id)
            ->unique()
            ->toArray();

        $requiredSkillIds = $this->project->requiredSkills->pluck('id')->toArray();

        $recommendedPeers = User::with('skills')
            ->whereNotIn('id', $existingMemberIds)
            ->whereHas('skills', function ($q) use ($requiredSkillIds) {
                $q->whereIn('skills.id', $requiredSkillIds);
            })
            ->take(12)
            ->get()
            ->map(function (User $user) use ($matchmakerService) {
                $match = $matchmakerService->calculateMatch($user, $this->project);
                $user->match_percentage = $match['score'];

                return $user;
            })
            ->sortByDesc('match_percentage');

        return view('livewire.campus.team-matchmaker', [
            'recommendedPeers' => $recommendedPeers,
        ])->layout('layouts.app');
    }
}
