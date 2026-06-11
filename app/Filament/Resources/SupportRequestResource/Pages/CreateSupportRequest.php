<?php

namespace App\Filament\Resources\SupportRequestResource\Pages;

use App\Filament\Resources\SupportRequestResource;
use App\Services\WorkspaceContext;
use Filament\Resources\Pages\CreateRecord;

class CreateSupportRequest extends CreateRecord
{
    protected static string $resource = SupportRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }
}
