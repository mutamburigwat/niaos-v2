<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Task;
use App\Services\WorkspaceContext;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.dashboard';
    protected static ?string $slug = 'dashboard';
    protected static ?string $title = 'Workspace Overview';

    public function getUser(): \App\Models\User
    {
        return Auth::user();
    }

    public function getWorkspaceName(): string
    {
        $ws = WorkspaceContext::activeWorkspace();
        return $ws?->business_name ?? 'No Workspace Selected';
    }

    public function getCustomerCount(): int
    {
        return Customer::query()->currentWorkspace()->count();
    }

    public function getActiveLeadCount(): int
    {
        return Lead::query()->currentWorkspace()
            ->whereNotIn('stage', ['won', 'lost'])
            ->count();
    }

    public function getPendingTaskCount(): int
    {
        return Task::query()->currentWorkspace()
            ->where('status', 'pending')
            ->count();
    }

    public function getDraftQuotationCount(): int
    {
        return Quotation::query()->currentWorkspace()
            ->where('status', 'draft')
            ->count();
    }

    public function getRecentLeads(): \Illuminate\Database\Eloquent\Collection
    {
        return Lead::query()->currentWorkspace()
            ->with('customer')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getTasksDueSoon(): \Illuminate\Database\Eloquent\Collection
    {
        return Task::query()->currentWorkspace()
            ->with('customer')
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->limit(5)
            ->get();
    }

    public function getRecentActivity(): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::query()->currentWorkspace()
            ->latest()
            ->limit(10)
            ->get();
    }
}
