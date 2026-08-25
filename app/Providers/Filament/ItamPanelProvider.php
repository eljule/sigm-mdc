<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Auth\Login;
use App\Filament\Admin\Pages\Auth\Register;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ItamPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('itam')
            ->path('itam')
            ->login(Login::class)
            ->registration(Register::class)
                        ->navigationItems([
                NavigationItem::make('Volver al Portal Principal')
                    ->url(fn () => url('/'))
                    ->icon('heroicon-o-home')
                    ->sort(-1000),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Volver al Portal Principal')
                    ->url(fn () => url('/'))
                    ->icon('heroicon-o-home')
                    ->sort(-1000),
            ])
            ->colors([
                'primary' => '#008435',
                'gray' => Color::Zinc,
            ])
            ->brandLogo(fn () => request()->routeIs('*.auth.login') ? asset('logo-green.png') : asset('logo-banner.png'))
            ->brandLogoHeight('4rem')
            ->discoverResources(in: app_path('Filament/Itam/Resources'), for: 'App\Filament\Itam\Resources')
            ->discoverPages(in: app_path('Filament/Itam/Pages'), for: 'App\Filament\Itam\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Itam/Widgets'), for: 'App\Filament\Itam\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                'subsystem:itam',
            ])
            ->renderHook(
                'panels::head.end',
                fn (): string => '<link rel="stylesheet" href="'.asset('css/custom.css').'?v='.time().'">',
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::SIMPLE_LAYOUT_START,
                fn (): string => request()->routeIs('*.auth.login') ? view('filament.auth.login-cover')->render() : '',
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => view('filament.auth.back-link')->render(),
            );
    }
}
