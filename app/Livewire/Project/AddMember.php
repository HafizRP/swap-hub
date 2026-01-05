<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectInvitationNotification; // We might need to create this later
use Livewire\Component;
use Livewire\Attributes\On;

class AddMember extends Component
{
    public Project $project;
    public $search = '';
    public $searchResults = [];
    public $selectedUsers = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $existingMemberIds = $this->project->members()->pluck('users.id')->toArray();
        $existingMemberIds[] = $this->project->owner_id; // Exclude owner too

        $this->searchResults = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })
            ->whereNotIn('id', $existingMemberIds)
            ->take(5)
            ->get();
    }

    public function addMember($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return;
        }

        // Add to project
        $this->project->members()->syncWithoutDetaching([
            $user->id => [
                'role' => 'member',
                'status' => 'active',
                'joined_at' => now(),
                'is_validated' => false
            ]
        ]);

        // Add to conversation conversation participants if exists
        if ($this->project->conversation) {
            $this->project->conversation->participants()->syncWithoutDetaching([$user->id]);
        }

        // Notify UI
        $this->dispatch('member-added');
        $this->dispatch('refresh-conversation-list'); // Update chat participants list

        // Clear search
        $this->search = '';
        $this->searchResults = [];

        session()->flash('message', "{$user->name} added successfully!");
    }

    public function render()
    {
        return view('livewire.project.add-member');
    }
}
