<?php

namespace App\Filament\Resources\BillingRecordResource\Pages;

use App\Filament\Resources\BillingRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillingRecords extends ListRecords
{
    protected static string $resource = BillingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
