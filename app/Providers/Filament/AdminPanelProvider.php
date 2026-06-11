<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\JoseAssistant;
use App\Filament\Pages\WorkspaceSwitcher;
use App\Http\Middleware\EnsureActiveWorkspaceIsValid;
use App\Filament\Resources\ActivityLogResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\FileRecordResource;
use App\Filament\Resources\LeadResource;
use App\Filament\Resources\QuotationResource;
use App\Filament\Resources\RetainerResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\SupportRequestResource;
use App\Filament\Resources\TaskResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            ->id('app')
            ->path('app')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('Manrope')
            ->brandName('NiaOS')
            ->favicon(asset('favicon.ico'))
            ->viteTheme('resources/css/app.css')
            ->resources([
                CustomerResource::class,
                LeadResource::class,
                TaskResource::class,
                QuotationResource::class,
                RetainerResource::class,
                SupportRequestResource::class,
                ServiceResource::class,
                FileRecordResource::class,
                ActivityLogResource::class,
            ])
            ->pages([
                Dashboard::class,
                WorkspaceSwitcher::class,
                JoseAssistant::class,
            ])
            ->homeUrl(fn (): string => Dashboard::getUrl())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $groups = [];

                $groups[] = NavigationGroup::make('Dashboard')
                    ->items([
                        NavigationItem::make('Workspace Overview')
                            ->icon('heroicon-o-home')
                            ->url(fn () => Dashboard::getUrl())
                            ->isActiveWhen(fn () => request()->routeIs('filament.app.pages.dashboard')),
                    ]);

                $groups[] = NavigationGroup::make('CRM')
                    ->items([
                        ...CustomerResourceNavigation(),
                        ...LeadResourceNavigation(),
                    ]);

                $groups[] = NavigationGroup::make('Operations')
                    ->items([
                        ...TaskResourceNavigation(),
                        ...QuotationResourceNavigation(),
                        ...RetainerResourceNavigation(),
                        ...SupportRequestResourceNavigation(),
                    ]);

                $groups[] = NavigationGroup::make('Workspace')
                    ->items([
                        ...ServiceResourceNavigation(),
                        ...FileRecordResourceNavigation(),
                        ...ActivityLogResourceNavigation(),
                    ]);

                $groups[] = NavigationGroup::make('AI')
                    ->items([
                        NavigationItem::make('Jose Assistant')
                            ->icon('heroicon-o-sparkles')
                            ->url(fn () => JoseAssistant::getUrl())
                            ->isActiveWhen(fn () => request()->routeIs('filament.app.pages.jose-assistant')),
                    ]);

                return $builder->groups($groups);
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
                EnsureActiveWorkspaceIsValid::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

function CustomerResourceNavigation(): array
{
    return [
        NavigationItem::make('Customers')
            ->icon('heroicon-o-users')
            ->url(fn () => \App\Filament\Resources\CustomerResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.customers.*')),
    ];
}

function LeadResourceNavigation(): array
{
    return [
        NavigationItem::make('Leads')
            ->icon('heroicon-o-arrow-trending-up')
            ->url(fn () => \App\Filament\Resources\LeadResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.leads.*')),
    ];
}

function TaskResourceNavigation(): array
{
    return [
        NavigationItem::make('Tasks')
            ->icon('heroicon-o-check-circle')
            ->url(fn () => \App\Filament\Resources\TaskResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.tasks.*')),
    ];
}

function QuotationResourceNavigation(): array
{
    return [
        NavigationItem::make('Quotations')
            ->icon('heroicon-o-document-text')
            ->url(fn () => \App\Filament\Resources\QuotationResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.quotations.*')),
    ];
}

function FileRecordResourceNavigation(): array
{
    return [
        NavigationItem::make('Files')
            ->icon('heroicon-o-folder')
            ->url(fn () => \App\Filament\Resources\FileRecordResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.file-records.*')),
    ];
}

function ActivityLogResourceNavigation(): array
{
    return [
        NavigationItem::make('Activity Logs')
            ->icon('heroicon-o-clock')
            ->url(fn () => \App\Filament\Resources\ActivityLogResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.activity-logs.*')),
    ];
}

function RetainerResourceNavigation(): array
{
    return [
        NavigationItem::make('Retainers')
            ->icon('heroicon-o-credit-card')
            ->url(fn () => \App\Filament\Resources\RetainerResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.retainers.*')),
    ];
}

function SupportRequestResourceNavigation(): array
{
    return [
        NavigationItem::make('Support Requests')
            ->icon('heroicon-o-lifebuoy')
            ->url(fn () => \App\Filament\Resources\SupportRequestResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.support-requests.*')),
    ];
}

function ServiceResourceNavigation(): array
{
    return [
        NavigationItem::make('Services')
            ->icon('heroicon-o-wrench')
            ->url(fn () => \App\Filament\Resources\ServiceResource::getUrl())
            ->isActiveWhen(fn () => request()->routeIs('filament.app.resources.services.*')),
    ];
}
