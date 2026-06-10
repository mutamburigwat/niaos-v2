<?php

namespace App\Filament\Resources\FileRecordResource\Pages;

use App\Filament\Resources\FileRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFileRecord extends EditRecord
{
    protected static string $resource = FileRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
