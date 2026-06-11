<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    public function getTitle(): string
    {
        return 'Plans';
    }

    public function getDescription(): ?string
    {
        return 'Manage subscription plans and their feature limits across NiaOS.';
    }

    public function getEmptyStateHeading(): string
    {
        return 'No plans configured';
    }

    public function getEmptyStateDescription(): ?string
    {
        return 'Create a plan to define subscription tiers and resource limits for workspaces.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
