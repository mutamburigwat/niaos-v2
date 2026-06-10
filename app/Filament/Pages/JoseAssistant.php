<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class JoseAssistant extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-sparkles';
    protected string $view = 'filament.pages.jose-assistant';
    protected static ?string $slug = 'jose-assistant';
    protected static ?string $title = 'Jose Assistant';
    protected static string | \UnitEnum | null $navigationGroup = 'AI';

    public function getViewData(): array
    {
        return [
            'groq_configured' => filled(env('GROQ_API_KEY')),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
