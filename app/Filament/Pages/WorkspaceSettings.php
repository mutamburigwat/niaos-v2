<?php

namespace App\Filament\Pages;

use App\Services\WorkspaceContext;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class WorkspaceSettings extends Page
{
    protected string $view = 'filament.pages.workspace-settings';

    protected static ?string $slug = 'workspace-settings';

    protected static ?string $title = 'Workspace Settings';

    public function getWorkspace(): ?\App\Models\Workspace
    {
        return WorkspaceContext::activeWorkspace();
    }

    public function getUserRole(): ?string
    {
        $workspace = $this->getWorkspace();
        if (! $workspace) {
            return null;
        }

        $member = $workspace->members()
            ->where('user_id', Auth::id())
            ->first();

        return $member?->role;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
