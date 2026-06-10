<?php

namespace App\Policies;

use App\Models\Task;

class TaskPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Task::class;
    }
}
