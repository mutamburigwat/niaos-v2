<?php

namespace App\Filament\Resources\BillingRecordResource\Pages;

use App\Filament\Resources\BillingRecordResource;
use App\Services\WorkspaceContext;
use Filament\Resources\Pages\CreateRecord;

class CreateBillingRecord extends CreateRecord
{
    protected static string $resource = BillingRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }
}
