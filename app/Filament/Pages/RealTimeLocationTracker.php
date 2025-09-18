<?php

namespace App\Filament\Pages;

use App\Models\Location;
use App\Models\User;
use Filament\Pages\Page;
use Livewire\Attributes\On;

class RealTimeLocationTracker extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Live Location Tracker';

    protected static ?string $title = 'Real-Time Location Tracker';

    protected static ?string $slug = 'real-time-location-tracker';

    protected static string $view = 'filament.pages.real-time-location-tracker';

    // Polling every 5 seconds for real-time updates
    public function mount(): void
    {
        // Mount the page - polling will be handled in the view
    }

    #[On('location-updated')]
    public function refreshLocations(): void
    {
        // This will trigger a re-render when location is updated
        $this->dispatch('$refresh');
    }

    public function getActiveUsers()
    {
        // Get users with recent location updates - using enhanced tracking fields
        return User::select(
                'user_id as id',
                'name', 
                'email', 
                'latitude', 
                'longitude', 
                'address',
                'last_location_update',
                'last_seen',
                'location_tracking_enabled',
                'updated_at'
            )
            ->where(function($query) {
                // Users with current location data
                $query->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            })
            ->where('location_tracking_enabled', true)
            ->where(function($query) {
                // Recently active users (last seen within 30 minutes OR recent location update)
                $query->where('last_seen', '>=', now()->subMinutes(30))
                      ->orWhere('last_location_update', '>=', now()->subMinutes(30));
            })
            ->orderBy('last_seen', 'desc')
            ->limit(100) // Limit to prevent performance issues
            ->get();
    }

    public function getAllLocations()
    {
        // Get active predefined locations - using correct field names
        return Location::select(
                'id_location as id',
                'location', 
                'latitude', 
                'longitude', 
                'description',
                'address', 
                'status'
            )
            ->where('status', 1) // Active status is 1 (tinyint)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('location')
            ->limit(50) // Limit to prevent performance issues
            ->get();
    }

    public function poll(): void
    {
        $this->dispatch('refresh-map');
    }
}
