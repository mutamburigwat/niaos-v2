<?php

namespace App\Policies;

use App\Models\FileRecord;

class FileRecordPolicy extends WorkspaceScopedPolicy
{
    protected function modelClass(): string
    {
        return FileRecord::class;
    }
}
