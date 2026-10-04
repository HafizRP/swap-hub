<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        $project = $task->project;

        return $project->owner_id === $user->id || $project->activeMembers()->where('user_id', $user->id)->exists() || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        $project = $task->project;

        return $project->owner_id === $user->id || $task->created_by === $user->id;
    }
}
