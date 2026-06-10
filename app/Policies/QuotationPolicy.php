<?php

namespace App\Policies;

use App\Models\Quotation;

class QuotationPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return Quotation::class;
    }
}
