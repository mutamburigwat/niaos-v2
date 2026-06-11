<?php

namespace App\Policies;

use App\Models\Retainer;

class RetainerPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Retainer::class;
    }
}
