<?php

namespace App\Policies;

use App\Models\User;
use App\Services\WorkspaceContext;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

abstract class WorkspaceScopedPolicy
{
    use HandlesAuthorization;

    abstract protected function modelClass(): string;

    protected function isUserAllowed(User $user): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        if (WorkspaceContext::activeWorkspaceIsSuspended()) {
            return false;
        }

        return WorkspaceContext::activeWorkspaceId() !== null;
    }

    public function viewAny(User $user): bool
    {
        return $this->isUserAllowed($user);
    }

    public function view(User $user, Model $record): bool
    {
        return $this->isUserAllowed($user) && $this->userCanAccessRecord($user, $record);
    }

    public function create(User $user): bool
    {
        return $this->isUserAllowed($user);
    }

    public function update(User $user, Model $record): bool
    {
        return $this->isUserAllowed($user) && $this->userCanAccessRecord($user, $record);
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->isUserAllowed($user) && $this->userCanAccessRecord($user, $record);
    }

    protected function userCanAccessRecord(User $user, Model $record): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        $workspaceId = $record->getAttribute('workspace_id');

        if ($workspaceId === null) {
            return false;
        }

        return $user->workspaces()->where('workspace_id', $workspaceId)->exists();
    }
}
