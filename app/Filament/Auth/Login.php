<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public function getHeading(): string | Htmlable | null
    {
        return 'Sign in';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'The Modern African Business OS';
    }

    public function hasLogo(): bool
    {
        return true;
    }
}
