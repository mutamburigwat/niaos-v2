<?php

namespace App\Policies;

use App\Models\Customer;

class CustomerPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Customer::class;
    }
}
