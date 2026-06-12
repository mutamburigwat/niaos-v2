<?php

namespace App\Filament\Resources\FileRecordResource\Pages;

use App\Filament\Resources\FileRecordResource;
use App\Services\WorkspaceContext;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateFileRecord extends CreateRecord
{
    protected static string $resource = FileRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        $data['uploaded_by'] = Auth::id();
        return $data;
    }
}
