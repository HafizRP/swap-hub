<?php

namespace App\Livewire\Project;

use App\Jobs\CreateGoogleCalendarEvent;
use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class TaskBoard extends Component
{
    public Project $project;

    // View State
    public $viewType = 'board'; // board or list

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

    protected function authorizeMember(): void
    {
        $userId = auth()->id();
        if (! $userId) {
            abort(403, 'Unauthorized.');
        }

        $isMember = $this->project->owner_id === $userId
            || $this->project->activeMembers()->where('user_id', $userId)->exists();

        if (! $isMember) {
            abort(403, 'You are not authorized to modify tasks for this project.');
        }
    }

    public function createTask()
    {
        $this->authorizeMember();
        $this->validate();

        $task = $this->project->tasks()->create([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to ?: null,
            'priority' => $this->priority,
            'status' => 'todo',
            'created_by' => auth()->id(),
            'due_date' => $this->due_date,
        ]);

        CreateGoogleCalendarEvent::dispatch($task);

        $this->reset(['title', 'description', 'assigned_to', 'priority', 'due_date', 'showCreateModal']);
        $this->dispatch('task-created');
    }

    public function updateStatus($taskId, $newStatus)
    {
        $this->authorizeMember();

        if (! in_array($newStatus, ['todo', 'in_progress', 'done'], true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'status' => 'Invalid task status.',
            ]);
        }

        $task = $this->project->tasks()->findOrFail($taskId);
        $task->update(['status' => $newStatus]);
    }

    public function deleteTask($taskId)
    {
        $this->authorizeMember();

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
            'members' => $this->project->activeMembers,
        ]);
    }
}
