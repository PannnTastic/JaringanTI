<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Location;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LocationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Get stats using our new API method
        try {
            $controller = new \App\Http\Controllers\Api\LocationController();
            $response = $controller->getLocationStats();
            $responseData = json_decode($response->getContent(), true);
            
            if ($responseData['success']) {
                $stats = $responseData['data'];
                
                return [
                    Stat::make('Total Users', $stats['total_users'])
                        ->description('Active users in system')
                        ->descriptionIcon('heroicon-m-user-group')
                        ->color('primary'),

                    Stat::make('Tracking Enabled', $stats['tracking_enabled'])
                        ->description('Users with location tracking on')
                        ->descriptionIcon('heroicon-m-signal')
                        ->color('success'),

                    Stat::make('Users with Location', $stats['users_with_location'])
                        ->description('Users with location data')
                        ->descriptionIcon('heroicon-m-map-pin')
                        ->color('info'),

                    Stat::make('Online Now', $stats['online_users'])
                        ->description('Users active in last 5 minutes')
                        ->descriptionIcon('heroicon-m-check-circle')
                        ->color('success'),

                    Stat::make('Recent Updates', $stats['recent_location_updates'])
                        ->description('Location updates in last hour')
                        ->descriptionIcon('heroicon-m-clock')
                        ->color('warning'),

                    Stat::make('Predefined Locations', $stats['active_predefined_locations'])
                        ->description('Active registered locations')
                        ->descriptionIcon('heroicon-m-building-office')
                        ->color('gray'),
                ];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to get location stats for widget', [
                'error' => $e->getMessage()
            ]);
        }
        
        // Fallback to direct queries if API fails
        $totalUsers = User::where('user_status', 1)->count();
        $onlineUsers = User::where('user_status', 1)
            ->where('last_seen', '>=', now()->subMinutes(5))
            ->count();
        $usersWithLocation = User::where('user_status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();
        $activeLocations = Location::where('status', 1)->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('Active users in system')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Online Users', $onlineUsers)
                ->description('Users online in last 5 minutes')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),

            Stat::make('Users with Location', $usersWithLocation)
                ->description('Users with location data')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),

            Stat::make('Active Locations', $activeLocations)
                ->description('Registered active locations')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
        ];
    }

    protected function getColumns(): int
    {
        return 3; // Changed to 3 for better layout with 6 stats
    }

    protected static ?int $sort = 1;
}
