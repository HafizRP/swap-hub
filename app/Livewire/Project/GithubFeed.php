<?php

namespace App\Livewire\Project;

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
        $activities = \DB::table('github_activities') // Assuming no model yet or using generic approach
            ->where('project_id', $this->project->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // If Model exists use it
        if (class_exists(\App\Models\GithubActivity::class)) {
            $activities = \App\Models\GithubActivity::where('project_id', $this->project->id)
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('livewire.project.github-feed', [
            'activities' => $activities
        ]);
    }
}
