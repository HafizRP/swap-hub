<?php

namespace App\Livewire\Project;

use App\Models\GitHubActivity;
use App\Models\Project;
use Livewire\Component;

class GithubFeed extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $activities = GitHubActivity::with('user')
            ->where('project_id', $this->project->id)
            ->orderBy('activity_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('livewire.project.github-feed', [
            'activities' => $activities,
            'project' => $this->project,
        ]);
    }
}
