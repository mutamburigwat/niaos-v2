<?php

namespace App\Filament\Resources\WorkspaceResource\Pages;

use App\Enums\BillingStatus;
use App\Enums\WorkspaceStatus;
use App\Enums\WorkspaceType;
use App\Filament\Pages\CreateClientWorkspace;
use App\Filament\Resources\WorkspaceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListWorkspaces extends ListRecords
{
    protected static string $resource = WorkspaceResource::class;

    public function getTitle(): string
    {
        return 'Workspaces';
    }

    public function getDescription(): ?string
    {
        return 'Manage client, internal, demo and partner workspaces across NiaOS.';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'paid_clients' => Tab::make('Paid Clients')
                ->modifyQuery(fn (Builder $query) => $query->where('workspace_type', WorkspaceType::PaidClient)),
            'trials' => Tab::make('Trials')
                ->modifyQuery(fn (Builder $query) => $query->where('billing_status', BillingStatus::Trial)),
            'active' => Tab::make('Active')
                ->modifyQuery(fn (Builder $query) => $query->where('status', WorkspaceStatus::Active)),
            'suspended' => Tab::make('Suspended')
                ->modifyQuery(fn (Builder $query) => $query->where('status', WorkspaceStatus::Suspended)),
            'internal' => Tab::make('Internal')
                ->modifyQuery(fn (Builder $query) => $query->where('workspace_type', WorkspaceType::Internal)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('onboard_client')
                ->label('Onboard Client')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->url(fn () => CreateClientWorkspace::getUrl())
                ->visible(fn () => Auth::user()?->isPlatformAdmin()),
        ];
    }
}
