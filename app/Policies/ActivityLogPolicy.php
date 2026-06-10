<?php

namespace App\Policies;

use App\Models\ActivityLog;

class ActivityLogPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return ActivityLog::class;
    }
}
