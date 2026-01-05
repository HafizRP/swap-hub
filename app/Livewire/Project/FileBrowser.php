<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileBrowser extends Component
{
    use WithFileUploads;

    public Project $project;
    public $search = '';
    public $typeFilter = 'all'; // all, image, document

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function getFilesProperty()
    {
        if (!$this->project->conversation) {
            return collect([]);
        }

        return \App\Models\MessageAttachment::query()
            ->whereHas('message', function ($q) {
                $q->where('conversation_id', $this->project->conversation->id);
            })
            ->when($this->search, function ($q) {
                $q->where('file_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter !== 'all', function ($q) {
                if ($this->typeFilter === 'image') {
                    $q->where('file_type', 'like', 'image/%');
                } else {
                    $q->where('file_type', 'not like', 'image/%');
                }
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.project.file-browser', [
            'files' => $this->getFilesProperty()
        ]);
    }
}
