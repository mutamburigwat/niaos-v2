<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Users';
    }

    public function getDescription(): ?string
    {
        return 'Manage platform users and their administrative permissions.';
    }

    public function getEmptyStateHeading(): string
    {
        return 'No users found';
    }

    public function getEmptyStateDescription(): ?string
    {
        return 'Users are created automatically when workspaces are onboarded or when you invite them.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Auth::user()?->isPlatformAdmin()),
        ];
    }
}
