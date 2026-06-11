<?php

namespace App\Providers\Filament;

use App\Filament\Pages\CreateClientWorkspace;
use App\Filament\Pages\PlatformDashboard;
use App\Filament\Pages\PlatformPlans;
use App\Filament\Pages\PlatformSettings;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\WorkspaceResource;
use App\Http\Middleware\EnsurePlatformAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class PlatformPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('platform')
            ->path('platform')
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
                WorkspaceResource::class,
                UserResource::class,
            ])
            ->pages([
                PlatformDashboard::class,
                CreateClientWorkspace::class,
                PlatformPlans::class,
                PlatformSettings::class,
            ])
            ->homeUrl(fn (): string => PlatformDashboard::getUrl())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('Platform')
                        ->items([
                            NavigationItem::make('Platform Dashboard')
                                ->icon('heroicon-o-globe-alt')
                                ->url(fn () => PlatformDashboard::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.pages.platform')),
                            NavigationItem::make('Workspaces')
                                ->icon('heroicon-o-building-office')
                                ->url(fn () => WorkspaceResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.resources.workspaces.*')),
                            NavigationItem::make('Users')
                                ->icon('heroicon-o-users')
                                ->url(fn () => UserResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.resources.users.*')),
                            NavigationItem::make('Plans')
                                ->icon('heroicon-o-currency-dollar')
                                ->url(fn () => PlatformPlans::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.pages.plans')),
                            NavigationItem::make('Settings')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->url(fn () => PlatformSettings::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.pages.settings')),
                        ]),
                ]);
            })
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
                EnsurePlatformAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
