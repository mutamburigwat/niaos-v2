<?php

namespace App\Services;

use App\Enums\WorkspaceStatus;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class WorkspaceContext
{
    public static function activeWorkspaceId(): ?string
    {
        return Auth::check() ? Auth::user()->active_workspace_id : null;
    }

    public static function activeWorkspace(): mixed
    {
        $id = static::activeWorkspaceId();

        if ($id === null) {
            return null;
        }

        return Auth::user()->workspaces()->where('workspace_id', $id)->first();
    }

    public static function userBelongsToWorkspace(?string $workspaceId): bool
    {
        if ($workspaceId === null || ! Auth::check()) {
            return false;
        }

        return Auth::user()->workspaces()->where('workspace_id', $workspaceId)->exists();
    }

    public static function activeWorkspaceIsSuspended(): bool
    {
        $id = static::activeWorkspaceId();

        if ($id === null) {
            return false;
        }

        $workspace = Workspace::find($id);

        if (! $workspace) {
            return false;
        }

        if ($workspace->isInternal()) {
            return false;
        }

        return $workspace->status === WorkspaceStatus::Suspended;
    }

    public static function isInternalWorkspace(): bool
    {
        $id = static::activeWorkspaceId();

        if ($id === null) {
            return false;
        }

        $workspace = Workspace::find($id);

        return $workspace && $workspace->isInternal();
    }

    public static function isPlatformAdmin(): bool
    {
        return Auth::check() && Auth::user()->isPlatformAdmin();
    }
}
