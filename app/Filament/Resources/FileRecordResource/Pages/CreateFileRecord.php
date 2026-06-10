<?php

namespace App\Filament\Resources\FileRecordResource\Pages;

use App\Filament\Resources\FileRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFileRecord extends CreateRecord
{
    protected static string $resource = FileRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = auth()->user()->active_workspace_id;
        return $data;
    }
}
