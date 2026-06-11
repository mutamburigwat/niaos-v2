<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Services\PlanEntitlement;
use App\Services\WorkspaceContext;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workspace = WorkspaceContext::activeWorkspace();
        if ($workspace && ! PlanEntitlement::canAddCustomer($workspace)) {
            Notification::make()
                ->danger()
                ->title('Customer limit reached')
                ->body('Your plan limits the number of customers you can add. Upgrade your plan or contact support.')
                ->send();
            $this->halt();
        }
    }
}
