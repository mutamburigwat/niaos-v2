<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PlatformSettings extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.platform-settings';
    protected static ?string $slug = 'settings';
    protected static ?string $title = 'Settings';
    protected static ?string $navigationLabel = 'Settings';
    protected static string | \UnitEnum | null $navigationGroup = 'Platform';

    public static function canAccess(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
