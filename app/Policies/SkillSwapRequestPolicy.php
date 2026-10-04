<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SkillSwapRequest;
use App\Models\User;

class SkillSwapRequestPolicy
{
    public function accept(User $user, SkillSwapRequest $request): bool
    {
        return $user->id !== $request->requester_id && $request->status === 'pending';
    }

    public function complete(User $user, SkillSwapRequest $request): bool
    {
        return $user->id === $request->requester_id && in_array($request->status, ['accepted', 'in_progress'], true);
    }

    public function cancel(User $user, SkillSwapRequest $request): bool
    {
        return ($user->id === $request->requester_id || $user->id === $request->provider_id)
            && $request->status !== 'completed';
    }
}
