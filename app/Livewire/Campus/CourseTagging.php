<?php

declare(strict_types=1);

namespace App\Livewire\Campus;

use App\Models\Course;
use App\Models\Project;
use App\Models\SkillSwapRequest;
use Livewire\Component;

class CourseTagging extends Component
{
    public string $search = '';

    public string $selectedCourseCode = '';

    public string $newCode = '';

    public string $newName = '';

    public string $newDepartment = '';

    public string $newSemester = '';

    public function createCourse(): void
    {
        $this->validate([
            'newCode' => 'required|string|max:20|unique:courses,code',
            'newName' => 'required|string|max:255',
            'newDepartment' => 'nullable|string|max:100',
            'newSemester' => 'nullable|string|max:50',
        ]);

        Course::create([
            'code' => strtoupper(trim($this->newCode)),
            'name' => trim($this->newName),
            'department' => $this->newDepartment ? trim($this->newDepartment) : null,
            'semester' => $this->newSemester ? trim($this->newSemester) : null,
        ]);

        $this->reset(['newCode', 'newName', 'newDepartment', 'newSemester']);
        session()->flash('message', 'Mata kuliah berhasil ditambahkan!');
    }

    public function toggleUserCourse(int $courseId): void
    {
        $user = auth()->user();
        if ($user->courses()->where('courses.id', $courseId)->exists()) {
            $user->courses()->detach($courseId);
        } else {
            $user->courses()->attach($courseId);
        }
    }

    public function tagProjectCourse(int $projectId, int $courseId): void
    {
        $project = Project::findOrFail($projectId);
        if ($project->owner_id === auth()->id()) {
            if ($project->courses()->where('courses.id', $courseId)->exists()) {
                $project->courses()->detach($courseId);
            } else {
                $project->courses()->attach($courseId);
            }
        }
    }

    public function selectCourse(string $code): void
    {
        $this->selectedCourseCode = $this->selectedCourseCode === $code ? '' : $code;
    }

    public function render()
    {
        $courses = Course::withCount(['projects', 'users'])
            ->when($this->search !== '', function ($query) {
                $query->where('code', 'like', '%'.$this->search.'%')
                    ->orWhere('name', 'like', '%'.$this->search.'%')
                    ->orWhere('department', 'like', '%'.$this->search.'%');
            })
            ->get();

        $selectedCourse = $this->selectedCourseCode !== ''
            ? Course::where('code', $this->selectedCourseCode)->first()
            : null;

        $filteredProjects = collect();
        $filteredSkillSwaps = collect();

        if ($selectedCourse) {
            $filteredProjects = Project::whereHas('courses', function ($q) use ($selectedCourse) {
                $q->where('courses.id', $selectedCourse->id);
            })->with('owner')->get();

            // Skill swaps for users taking this course
            $userIds = $selectedCourse->users()->pluck('users.id');
            $filteredSkillSwaps = SkillSwapRequest::whereIn('requester_id', $userIds)
                ->with('requester')
                ->get();
        } else {
            $filteredProjects = Project::latest()->take(6)->get();
            $filteredSkillSwaps = SkillSwapRequest::latest()->take(6)->get();
        }

        $myProjects = Project::where('owner_id', auth()->id())->get();
        $myCourseIds = auth()->user()->courses()->pluck('courses.id')->toArray();

        return view('livewire.campus.course-tagging', [
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'filteredProjects' => $filteredProjects,
            'filteredSkillSwaps' => $filteredSkillSwaps,
            'myProjects' => $myProjects,
            'myCourseIds' => $myCourseIds,
        ])->layout('layouts.app');
    }
}
