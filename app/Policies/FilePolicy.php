<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    public function update(User $user, File $file): bool
    {
        return $file->user_id === $user->id
            || $file->space->owner_id === $user->id;
    }

    public function delete(User $user, File $file): bool
    {
        return $file->user_id === $user->id
            || $file->space->owner_id === $user->id;
    }
}
