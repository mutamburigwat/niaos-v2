<?php

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use App\Services\PlanEntitlement;
use App\Services\WorkspaceContext;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workspace = WorkspaceContext::activeWorkspace();
        if ($workspace && ! PlanEntitlement::canAddQuotation($workspace)) {
            Notification::make()
                ->danger()
                ->title('Quotation limit reached')
                ->body('Your plan limits the number of quotations you can create. Upgrade your plan or contact support.')
                ->send();
            $this->halt();
        }
    }
}
