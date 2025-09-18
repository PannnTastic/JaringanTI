<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LocationUpdateController;
use App\Events\LocationUpdated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LocationController extends Controller
{
    /**
     * Get all active users locations (for admin/monitoring) - alias for getActiveLocations
     */
    public function getActiveUserLocations(): JsonResponse
    {
        return $this->getActiveLocations();
    }

    /**
     * Get all active users locations (for admin/monitoring)
     */
    public function getActiveLocations(): JsonResponse
    {
        try {
            $activeUsers = User::where('user_status', 1)
                ->where('location_tracking_enabled', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->select('user_id', 'name', 'email', 'latitude', 'longitude', 
                        'address', 'last_location_update', 'last_seen', 'updated_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $activeUsers->map(function($user) {
                    return [
                        'user_id' => $user->user_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'latitude' => $user->latitude,
                        'longitude' => $user->longitude,
                        'address' => $user->address,
                        'last_location_update' => $user->last_location_update,
                        'last_seen' => $user->last_seen,
                        'is_online' => $user->last_seen && $user->last_seen->diffInMinutes() <= 5,
                    ];
                }),
                'count' => $activeUsers->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get location tracking statistics
     */
    public function getLocationStats(): JsonResponse
    {
        $totalUsers = User::count();
        $usersWithLocation = User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();
        $trackingEnabled = User::where('location_tracking_enabled', true)->count();
        $onlineUsers = User::where('last_seen', '>=', now()->subMinutes(5))->count();
        $recentLocationUpdates = User::where('last_location_update', '>=', now()->subHour())->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'users_with_location' => $usersWithLocation,
                'tracking_enabled' => $trackingEnabled,
                'online_users' => $onlineUsers,
                'recent_location_updates' => $recentLocationUpdates,
                'total_predefined_locations' => 5,
                'active_predefined_locations' => 5,
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get predefined locations (for quick location selection)
     */
    public function getPredefinedLocations(): JsonResponse
    {
        $predefinedLocations = [
            [
                'id' => 1,
                'name' => 'Batam Center',
                'latitude' => 1.1456,
                'longitude' => 104.0305,
                'address' => 'Batam Center, Batam, Indonesia'
            ],
            [
                'id' => 2,
                'name' => 'Sekupang',
                'latitude' => 1.1193,
                'longitude' => 103.9738,
                'address' => 'Sekupang, Batam, Indonesia'
            ],
            [
                'id' => 3,
                'name' => 'Batu Aji',
                'latitude' => 1.0778,
                'longitude' => 103.9464,
                'address' => 'Batu Aji, Batam, Indonesia'
            ],
            [
                'id' => 4,
                'name' => 'Nongsa',
                'latitude' => 1.1732,
                'longitude' => 104.0530,
                'address' => 'Nongsa, Batam, Indonesia'
            ],
            [
                'id' => 5,
                'name' => 'Lubuk Baja',
                'latitude' => 1.1040,
                'longitude' => 104.0340,
                'address' => 'Lubuk Baja, Batam, Indonesia'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $predefinedLocations,
            'count' => count($predefinedLocations),
        ]);
    }

    /**
     * Update current user's location
     */
    public function updateLocation(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'address' => 'nullable|string|max:500'
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Update user location
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->last_location_update = now();
            $user->last_seen = now(); // Also update last seen
            
            // If address is provided, use it; otherwise try to reverse geocode
            if ($request->has('address') && !empty($request->address)) {
                $user->address = $request->address;
            } else {
                // Simple address format for now - could be enhanced with reverse geocoding
                $user->address = "Lat: {$request->latitude}, Lng: {$request->longitude}";
            }

            $user->save();

            // Broadcast location update event
            broadcast(new LocationUpdated($user));

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => [
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'address' => $user->address,
                    'last_location_update' => $user->last_location_update,
                    'last_seen' => $user->last_seen
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enhanced location update with additional features
     */
    public function updateLocationEnhanced(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
                'accuracy' => 'nullable|numeric|min:0',
                'altitude' => 'nullable|numeric',
                'heading' => 'nullable|numeric|between:0,360',
                'speed' => 'nullable|numeric|min:0'
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Update user location with enhanced data
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->last_location_update = now();
            $user->last_seen = now();
            
            if ($request->has('address') && !empty($request->address)) {
                $user->address = $request->address;
            } else {
                $user->address = "Lat: {$request->latitude}, Lng: {$request->longitude}";
            }

            $user->save();

            // Broadcast location update event
            broadcast(new LocationUpdated($user));

            // Log additional location metadata if needed
            Log::info('Enhanced location update', [
                'user_id' => $user->user_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'heading' => $request->heading,
                'speed' => $request->speed,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enhanced location updated successfully',
                'data' => [
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'address' => $user->address,
                    'last_location_update' => $user->last_location_update,
                    'last_seen' => $user->last_seen,
                    'metadata' => [
                        'accuracy' => $request->accuracy,
                        'altitude' => $request->altitude,
                        'heading' => $request->heading,
                        'speed' => $request->speed
                    ]
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update enhanced location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's location
     */
    public function getCurrentLocation(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'address' => $user->address,
                    'last_location_update' => $user->last_location_update,
                    'last_seen' => $user->last_seen,
                    'location_tracking_enabled' => $user->location_tracking_enabled
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get current location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start location tracking for current user
     */
    public function startTracking(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->location_tracking_enabled = true;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Location tracking started',
                'data' => [
                    'location_tracking_enabled' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start tracking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop location tracking for current user
     */
    public function stopTracking(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->location_tracking_enabled = false;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Location tracking stopped',
                'data' => [
                    'location_tracking_enabled' => false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop tracking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update last seen timestamp for current user
     */
    public function updateLastSeen(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->last_seen = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Last seen updated',
                'data' => [
                    'last_seen' => $user->last_seen
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update last seen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
