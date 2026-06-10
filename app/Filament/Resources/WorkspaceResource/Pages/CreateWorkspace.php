<?php

namespace App\Filament\Resources\WorkspaceResource\Pages;

use App\Filament\Resources\WorkspaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkspace extends CreateRecord
{
    protected static string $resource = WorkspaceResource::class;

    protected function afterCreate(): void
    {
        $this->record->members()->create([
            'user_id' => auth()->id(),
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }
}
