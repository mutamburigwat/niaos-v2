<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        if (! $this->shouldLog($model)) {
            return;
        }

        $this->log($model, 'created');
    }

    public function updated(Model $model): void
    {
        if (! $this->shouldLog($model)) {
            return;
        }

        $this->log($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        if (! $this->shouldLog($model)) {
            return;
        }

        $this->log($model, 'deleted');
    }

    protected function shouldLog(Model $model): bool
    {
        return in_array('App\Traits\BelongsToWorkspace', class_uses_recursive($model))
            && Auth::check();
    }

    protected function log(Model $model, string $action): void
    {
        $user = Auth::user();

        $entityType = $this->entityType($model);

        $details = match ($action) {
            'created' => 'Created ' . $entityType . ': ' . $this->label($model),
            'updated' => 'Updated ' . $entityType . ': ' . $this->label($model),
            'deleted' => 'Deleted ' . $entityType . ': ' . $this->label($model),
            default => $action . ' ' . $entityType,
        };

        ActivityLog::create([
            'workspace_id' => $model->workspace_id ?? $user->active_workspace_id,
            'actor_id' => $user->id,
            'actor_name' => $user->name,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $model->id,
            'details' => $details,
        ]);
    }

    protected function entityType(Model $model): string
    {
        return match (class_basename($model)) {
            'Customer' => 'customer',
            'Lead' => 'lead',
            'Task' => 'task',
            'Quotation' => 'quotation',
            'FileRecord' => 'file',
            default => strtolower(class_basename($model)),
        };
    }

    protected function label(Model $model): string
    {
        if (method_exists($model, 'activityLogLabel')) {
            return $model->activityLogLabel();
        }

        return $model->name
            ?? $model->title
            ?? $model->business_name
            ?? $model->file_name
            ?? substr($model->id, 0, 8);
    }
}
