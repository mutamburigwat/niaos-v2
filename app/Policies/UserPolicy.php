<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPlatformAdmin();
    }

    public function view(User $user, User $target): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        if ($user->id === $target->id) {
            return true;
        }

        return $user->workspaces()
            ->whereIn('workspace_id', $target->workspaces()->pluck('workspace_id'))
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->isPlatformAdmin();
    }

    public function update(User $user, User $target): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        return $user->id === $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        return $user->isPlatformAdmin() && $user->id !== $target->id;
    }
}
