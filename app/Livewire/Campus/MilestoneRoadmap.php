<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Livewire\Component;

class MilestoneRoadmap extends Component
{
    public Project $project;

    public string $viewMode = 'kanban'; // kanban, timeline

    // New Milestone Form
    public string $title = '';

    public string $description = '';

    public string $dueDate = '';

    // Link Task Form
    public ?int $selectedTaskMilestoneId = null;

    public ?int $selectedTaskId = null;

    public function mount(Project $project): void
    {
        $this->project = $project;

        if ($project->owner_id !== auth()->id() && ! $project->activeMembers()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized.');
        }
    }

    public function switchView(string $mode): void
    {
        $this->viewMode = in_array($mode, ['kanban', 'timeline']) ? $mode : 'kanban';
    }

    public function addMilestone(): void
    {
        if ($this->project->owner_id !== auth()->id() && ! $this->project->activeMembers()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized.');
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dueDate' => 'nullable|date',
        ]);

        ProjectMilestone::create([
            'project_id' => $this->project->id,
            'title' => trim($this->title),
            'description' => $this->description ? trim($this->description) : null,
            'due_date' => $this->dueDate ?: null,
            'status' => 'pending',
        ]);

        $this->reset(['title', 'description', 'dueDate']);
        session()->flash('message', 'Milestone berhasil dibuat!');
    }

    public function updateMilestoneStatus(int $milestoneId, string $status): void
    {
        if ($this->project->owner_id !== auth()->id() && ! $this->project->activeMembers()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized.');
        }

        if (! in_array($status, ['pending', 'in_progress', 'completed'])) {
            return;
        }

        $milestone = ProjectMilestone::where('project_id', $this->project->id)->findOrFail($milestoneId);
        $milestone->update(['status' => $status]);
    }

    public function linkTaskToMilestone(int $taskId, ?int $milestoneId): void
    {
        if ($this->project->owner_id !== auth()->id() && ! $this->project->activeMembers()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized.');
        }

        $task = Task::where('project_id', $this->project->id)->findOrFail($taskId);
        $task->update(['milestone_id' => $milestoneId]);
    }

    public function render()
    {
        $milestones = ProjectMilestone::where('project_id', $this->project->id)
            ->with(['tasks' => function ($q) {
                $q->with('assignee');
            }])
            ->orderBy('due_date', 'asc')
            ->get();

        $unlinkedTasks = Task::where('project_id', $this->project->id)
            ->whereNull('milestone_id')
            ->get();

        return view('livewire.campus.milestone-roadmap', [
            'milestones' => $milestones,
            'unlinkedTasks' => $unlinkedTasks,
        ])->layout('layouts.app');
    }
}
