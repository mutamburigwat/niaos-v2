<?php

namespace App\Policies;

use App\Models\SupportRequest;

class SupportRequestPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return SupportRequest::class;
    }
}
