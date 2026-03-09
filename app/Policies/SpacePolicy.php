<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;

class SpacePolicy
{
    public function view(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id
            || $space->members()->where('user_id', $user->id)->exists()
            || $space->is_public;
    }

    public function update(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id
            || $space->members()->where('user_id', $user->id)->where('role', 'admin')->exists();
    }

    public function delete(User $user, Space $space): bool
    {
        return $space->owner_id === $user->id;
    }
}
