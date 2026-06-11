<?php

namespace App\Filament\Pages;

use App\Services\WorkspaceContext;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';

    protected static ?string $slug = 'profile';

    protected static ?string $title = 'My Profile';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getUser(): \App\Models\User
    {
        return Auth::user();
    }

    public function getActiveWorkspace(): ?\App\Models\Workspace
    {
        return WorkspaceContext::activeWorkspace();
    }
}
