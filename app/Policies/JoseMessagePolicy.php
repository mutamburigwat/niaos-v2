<?php

namespace App\Policies;

use App\Models\JoseMessage;

class JoseMessagePolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return JoseMessage::class;
    }
}
