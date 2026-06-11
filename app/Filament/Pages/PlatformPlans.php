<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PlatformPlans extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.platform-plans';
    protected static ?string $slug = 'plans';
    protected static ?string $title = 'Plans';
    protected static ?string $navigationLabel = 'Plans';
    protected static string | \UnitEnum | null $navigationGroup = 'Platform';

    public static function canAccess(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getPlans(): array
    {
        return [
            [
                'key' => 'free_internal',
                'name' => 'Free Internal',
                'description' => 'For internal use by Akudzwe Digital Partners and NiaOS operations. No billing.',
                'price' => 'Free',
                'badge_color' => 'info',
            ],
            [
                'key' => 'starter',
                'name' => 'Starter',
                'description' => 'Entry-level plan for small businesses getting started with NiaOS.',
                'price' => 'TBD',
                'badge_color' => 'gray',
            ],
            [
                'key' => 'growth',
                'name' => 'Growth',
                'description' => 'For growing teams that need more workspace features and capacity.',
                'price' => 'TBD',
                'badge_color' => 'warning',
            ],
            [
                'key' => 'business',
                'name' => 'Business',
                'description' => 'Full-featured plan for established businesses with advanced requirements.',
                'price' => 'TBD',
                'badge_color' => 'success',
            ],
            [
                'key' => 'custom',
                'name' => 'Custom',
                'description' => 'Tailored plan for enterprise clients with custom needs and dedicated support.',
                'price' => 'Custom',
                'badge_color' => 'info',
            ],
        ];
    }
}
