<?php

namespace App\Policies;

use App\Models\JoseConversation;

class JoseConversationPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return JoseConversation::class;
    }
}
