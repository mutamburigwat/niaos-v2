<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Workspace $workspace): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        return $user->workspaces()->where('workspace_id', $workspace->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isPlatformAdmin();
    }

    public function update(User $user, Workspace $workspace): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        return $user->workspaces()
            ->where('workspace_id', $workspace->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->isPlatformAdmin();
    }
}
