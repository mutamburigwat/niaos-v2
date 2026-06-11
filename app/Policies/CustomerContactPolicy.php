<?php

namespace App\Policies;

use App\Models\CustomerContact;

class CustomerContactPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return CustomerContact::class;
    }
}
