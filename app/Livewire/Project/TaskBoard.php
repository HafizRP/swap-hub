<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class TaskBoard extends Component
{
    public Project $project;

    // Create Task State
    public $showCreateModal = false;
    public $title = '';
    public $description = '';
    public $assigned_to = null;
    public $priority = 'medium';
    public $due_date = null;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'priority' => 'required|in:low,medium,high',
        'assigned_to' => 'nullable|exists:users,id',
        'due_date' => 'nullable|date',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function createTask()
    {
        $this->validate();

        $this->project->tasks()->create([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to ?: null,
            'priority' => $this->priority,
            'status' => 'todo',
            'created_by' => auth()->id(),
            'due_date' => $this->due_date,
        ]);

        $this->reset(['title', 'description', 'assigned_to', 'priority', 'due_date', 'showCreateModal']);
        $this->dispatch('task-created');
    }

    public function updateStatus($taskId, $newStatus)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $task->update(['status' => $newStatus]);
    }

    public function deleteTask($taskId)
    {
        $task = $this->project->tasks()->findOrFail($taskId);
        $task->delete();
    }

    public function render()
    {
        $tasks = $this->project->tasks()
            ->with('assignee')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('livewire.project.task-board', [
            'tasks' => $tasks,
            'members' => $this->project->members
        ]);
    }
}
