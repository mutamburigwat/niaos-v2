<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Services\PlanEntitlement;
use App\Services\WorkspaceContext;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['workspace_id'] = WorkspaceContext::activeWorkspaceId();
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workspace = WorkspaceContext::activeWorkspace();
        if ($workspace && ! PlanEntitlement::canAddTask($workspace)) {
            Notification::make()
                ->danger()
                ->title('Task limit reached')
                ->body('Your plan limits the number of tasks you can create. Upgrade your plan or contact support.')
                ->send();
            $this->halt();
        }
    }
}
