<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AccountSettings;
use App\Filament\Pages\ChangePassword;
use App\Filament\Pages\CreateClientWorkspace;
use App\Filament\Pages\PlatformDashboard;
use App\Filament\Pages\PlatformSettings;
use App\Filament\Pages\Profile as ProfilePage;
use App\Filament\Resources\PlanResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\WorkspaceResource;
use App\Http\Middleware\EnsurePlatformAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\HtmlString;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
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
            ->login(\App\Filament\Auth\Login::class)
            ->passwordReset()
            ->colors([
                'primary' => '#C9A84C',
            ])
            ->font('Manrope')
            ->brandName('NiaOS')
            ->brandLogo(new HtmlString(view('components.niaos-brand-logo')->render()))
            ->brandLogoHeight('2rem')
            ->favicon(asset('icon.png'))
            ->viteTheme('resources/css/app.css')
            ->resources([
                WorkspaceResource::class,
                UserResource::class,
                PlanResource::class,
            ])
            ->pages([
                ProfilePage::class,
                AccountSettings::class,
                ChangePassword::class,
                PlatformDashboard::class,
                CreateClientWorkspace::class,
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
                                ->url(fn () => PlanResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.resources.plans.*')),
                            NavigationItem::make('Settings')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->url(fn () => PlatformSettings::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.platform.pages.settings')),
                        ]),
                ]);
            })
            ->userMenuItems([
                MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn () => ProfilePage::getUrl()),
                MenuItem::make()
                    ->label('Account Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn () => AccountSettings::getUrl()),
                MenuItem::make()
                    ->label('Change Password')
                    ->icon('heroicon-o-lock-closed')
                    ->url(fn () => ChangePassword::getUrl()),
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
                EnsurePlatformAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
