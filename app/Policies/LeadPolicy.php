<?php

namespace App\Policies;

use App\Models\Lead;

class LeadPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Lead::class;
    }
}
