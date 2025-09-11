<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\LatestActivity;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UserVendorChart;
use App\Http\Middleware\FilamentRolePermission;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            ->login(\App\Filament\Pages\Auth\Login::class)

            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Notifications::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                \App\Filament\Widgets\UserFieldChart::class,
                // KnowledgebaseChart::class,
                UserVendorChart::class,
                LatestActivity::class,
                Widgets\AccountWidget::class,
            ])
            ->favicon('/img/favicon16x16.ico')

            ->brandLogo('/img/pln batam low res (3).png')
            ->brandLogoHeight('3rem')

            ->spa(true)

            ->userMenuItems([
                MenuItem::make()
                    ->label(function () {
                        $unreadCount = \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\\Models\\User')
                            ->where('notifiable_id', Auth::id())
                            ->whereNull('read_at')
                            ->count();
                        $label = 'Notifications';
                        if ($unreadCount > 0) {
                            $label .= " ({$unreadCount})";
                        }

                        return $label;
                    })
                    ->url('/admin/notifications')
                    ->icon('heroicon-o-bell')
                    ->sort(-1),
                MenuItem::make()
                    ->label('Edit Profile')
                    ->url(fn () => Filament::getPanel('admin')->getProfileUrl())
                    ->icon('heroicon-o-user-circle'),
                MenuItem::make()
                    ->label('Seputar JARTI')
                    ->url('/')
                    ->icon('heroicon-o-information-circle'),
            ])

            ->profile(EditProfile::class)

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
                FilamentRolePermission::class,
            ]);
    }
}
