<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PlatformDashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?int $navigationSort = 0;
    protected string $view = 'filament.pages.platform-dashboard';
    protected static ?string $slug = 'platform';
    protected static ?string $title = 'Platform Admin';
    protected static string | \UnitEnum | null $navigationGroup = 'Platform';

    public static function canAccess(): bool
    {
        return Auth::user()?->isPlatformAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getTotalWorkspaces(): int
    {
        return Workspace::count();
    }

    public function getActiveWorkspaces(): int
    {
        return Workspace::active()->count();
    }

    public function getSuspendedWorkspaces(): int
    {
        return Workspace::suspended()->count();
    }

    public function getOnboardingWorkspaces(): int
    {
        return Workspace::onboarding()->count();
    }

    public function getTotalUsers(): int
    {
        return User::count();
    }

    public function getPlatformAdmins(): int
    {
        return User::where('is_platform_admin', true)->count();
    }

    public function getTotalCustomers(): int
    {
        return Customer::count();
    }

    public function getTotalLeads(): int
    {
        return Lead::count();
    }

    public function getTotalTasks(): int
    {
        return Task::count();
    }
}
