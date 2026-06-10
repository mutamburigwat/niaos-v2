<?php

namespace App\Providers\Filament;

use App\Filament\Pages\JoseAssistant;
use App\Filament\Pages\WorkspaceSwitcher;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('Manrope')
            ->brandName('NiaOS')
            ->favicon(asset('favicon.ico'))
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->groups([
                        NavigationGroup::make('Workspace')
                            ->items([
                                NavigationItem::make('Dashboard')
                                    ->icon('heroicon-o-home')
                                    ->url(fn () => Pages\Dashboard::getUrl()),
                                ...WorkspaceResourceNavigation(),
                            ]),
                        NavigationGroup::make('CRM')
                            ->items([
                                ...CustomerResourceNavigation(),
                                ...LeadResourceNavigation(),
                            ]),
                        NavigationGroup::make('Operations')
                            ->items([
                                ...TaskResourceNavigation(),
                                ...QuotationResourceNavigation(),
                            ]),
                        NavigationGroup::make('Records')
                            ->items([
                                ...FileRecordResourceNavigation(),
                                ...ActivityLogResourceNavigation(),
                            ]),
                        NavigationGroup::make('AI')
                            ->items([
                                NavigationItem::make('Jose Assistant')
                                    ->icon('heroicon-o-sparkles')
                                    ->url(fn () => JoseAssistant::getUrl())
                                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.jose-assistant')),
                            ]),
                    ]);
            })
            ->userMenuItems([
                MenuItem::make()
                    ->label('Switch Workspace')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->url(fn () => WorkspaceSwitcher::getUrl()),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

function WorkspaceResourceNavigation(): array
{
    return [
        NavigationItem::make('Workspaces')
            ->icon('heroicon-o-building-office')
            ->url(fn () => \App\Filament\Resources\WorkspaceResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.workspaces.*')),
    ];
}

function CustomerResourceNavigation(): array
{
    return [
        NavigationItem::make('Customers')
            ->icon('heroicon-o-users')
            ->url(fn () => \App\Filament\Resources\CustomerResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.customers.*')),
    ];
}

function LeadResourceNavigation(): array
{
    return [
        NavigationItem::make('Leads')
            ->icon('heroicon-o-trending-up')
            ->url(fn () => \App\Filament\Resources\LeadResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.leads.*')),
    ];
}

function TaskResourceNavigation(): array
{
    return [
        NavigationItem::make('Tasks')
            ->icon('heroicon-o-check-circle')
            ->url(fn () => \App\Filament\Resources\TaskResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.tasks.*')),
    ];
}

function QuotationResourceNavigation(): array
{
    return [
        NavigationItem::make('Quotations')
            ->icon('heroicon-o-document-text')
            ->url(fn () => \App\Filament\Resources\QuotationResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.quotations.*')),
    ];
}

function FileRecordResourceNavigation(): array
{
    return [
        NavigationItem::make('Files')
            ->icon('heroicon-o-folder')
            ->url(fn () => \App\Filament\Resources\FileRecordResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.file-records.*')),
    ];
}

function ActivityLogResourceNavigation(): array
{
    return [
        NavigationItem::make('Activity Logs')
            ->icon('heroicon-o-clock')
            ->url(fn () => \App\Filament\Resources\ActivityLogResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.activity-logs.*')),
    ];
}
