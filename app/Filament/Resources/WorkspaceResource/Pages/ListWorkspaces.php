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

    public function getEmptyStateHeading(): string
    {
        return 'No workspaces yet';
    }

    public function getEmptyStateDescription(): ?string
    {
        return 'Create your first workspace to get started with NiaOS.';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'paid_clients' => Tab::make('Paid Clients')
                ->query(fn (Builder $query) => $query->where('workspace_type', WorkspaceType::PaidClient)),
            'trials' => Tab::make('Trials')
                ->query(fn (Builder $query) => $query->where('billing_status', BillingStatus::Trial)),
            'active' => Tab::make('Active')
                ->query(fn (Builder $query) => $query->where('status', WorkspaceStatus::Active)),
            'suspended' => Tab::make('Suspended')
                ->query(fn (Builder $query) => $query->where('status', WorkspaceStatus::Suspended)),
            'internal' => Tab::make('Internal')
                ->query(fn (Builder $query) => $query->where('workspace_type', WorkspaceType::Internal)),
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
