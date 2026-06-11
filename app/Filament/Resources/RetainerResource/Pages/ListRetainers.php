<?php

namespace App\Filament\Resources\RetainerResource\Pages;

use App\Filament\Resources\RetainerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetainers extends ListRecords
{
    protected static string $resource = RetainerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
