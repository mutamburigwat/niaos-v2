<?php

namespace App\Filament\Pages;

use App\Models\Workspace;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class WorkspaceSwitcher extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';
    protected string $view = 'filament.pages.workspace-switcher';
    protected static ?string $slug = 'workspace-switcher';
    protected static ?string $title = 'Switch Workspace';

    public ?string $workspace_id = null;

    public function mount(): void
    {
        $this->workspace_id = Auth::user()->active_workspace_id;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('workspace_id')
                    ->label('Select Workspace')
                    ->options(fn () => Workspace::whereHas('members', fn ($q) => $q->where('user_id', Auth::id()))
                        ->pluck('business_name', 'id'))
                    ->default($this->workspace_id)
                    ->required(),
            ]);
    }

    public function switch(): void
    {
        $user = Auth::user();
        $user->active_workspace_id = $this->workspace_id;
        $user->save();

        $this->redirect(Dashboard::getUrl());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
