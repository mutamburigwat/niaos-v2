<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Services\PlanEntitlement;
use App\Services\WorkspaceContext;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workspace = WorkspaceContext::activeWorkspace();
        if ($workspace && ! PlanEntitlement::canAddLead($workspace)) {
            Notification::make()
                ->danger()
                ->title('Lead limit reached')
                ->body('Your plan limits the number of leads you can add. Upgrade your plan or contact support.')
                ->send();
            $this->halt();
        }
    }
}
