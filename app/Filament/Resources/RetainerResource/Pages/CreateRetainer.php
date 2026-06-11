<?php

namespace App\Filament\Resources\RetainerResource\Pages;

use App\Filament\Resources\RetainerResource;
use App\Services\WorkspaceContext;
use Filament\Resources\Pages\CreateRecord;

class CreateRetainer extends CreateRecord
{
    protected static string $resource = RetainerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }
}
