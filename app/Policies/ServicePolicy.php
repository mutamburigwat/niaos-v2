<?php

namespace App\Policies;

use App\Models\Service;

class ServicePolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Service::class;
    }
}
