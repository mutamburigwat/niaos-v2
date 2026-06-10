<?php

namespace App\Traits;

use App\Models\Workspace;
use App\Services\WorkspaceContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWorkspace
{
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function scopeForWorkspace(Builder $query, string $workspaceId): Builder
    {
        return $query->where('workspace_id', $workspaceId);
    }

    public function scopeCurrentWorkspace(Builder $query): Builder
    {
        $workspaceId = WorkspaceContext::activeWorkspaceId();

        if ($workspaceId !== null) {
            $query->where('workspace_id', $workspaceId);
        }

        return $query;
    }

    protected static function bootBelongsToWorkspace(): void
    {
        static::addGlobalScope('workspace', function (Builder $builder) {
            $workspaceId = WorkspaceContext::activeWorkspaceId();

            if ($workspaceId !== null) {
                $builder->where('workspace_id', $workspaceId);
            }
        });
    }
}
