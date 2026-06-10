<?php

namespace App\Filament\Resources\FileRecordResource\Pages;

use App\Filament\Resources\FileRecordResource;
use App\Services\WorkspaceContext;
use Filament\Resources\Pages\CreateRecord;

class CreateFileRecord extends CreateRecord
{
    protected static string $resource = FileRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        $data['uploaded_by'] = auth()->id();
        return $data;
    }
}
