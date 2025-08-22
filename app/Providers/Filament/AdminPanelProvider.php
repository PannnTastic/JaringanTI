<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\LatestActivity;
use App\Filament\Widgets\UserVendorChart;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\FilamentRolePermission;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            
            ->login()

            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                
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
                        $unreadCount = Auth::user()?->unreadNotifications()->count() ?? 0;
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
                FilamentRolePermission::class
            ]);
    }
}
