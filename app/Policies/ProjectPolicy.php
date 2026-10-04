<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(?User $user, Project $project): bool
    {
        if ($project->is_public) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $project->activeMembers()->where('user_id', $user->id)->exists() || $project->owner_id === $user->id;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || $user->isAdmin();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || $user->isAdmin();
    }

    public function manageMembers(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || $user->isAdmin();
    }
}
