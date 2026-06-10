<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class JoseAssistant extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static string $view = 'filament.pages.jose-assistant';
    protected static ?string $slug = 'jose-assistant';
    protected static ?string $title = 'Jose Assistant';
    protected static ?string $navigationGroup = 'AI';

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
