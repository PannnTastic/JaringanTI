<?php

use App\Http\Controllers\Api\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Location tracking API routes - using web auth for compatibility with Filament
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/location/update', [LocationController::class, 'updateLocation']);
    Route::post('/location/update-enhanced', [LocationController::class, 'updateLocationEnhanced']);
    Route::get('/location/current', [LocationController::class, 'getCurrentLocation']);
    Route::get('/location/active', [LocationController::class, 'getActiveLocations']);
    Route::post('/location/start-tracking', [LocationController::class, 'startTracking']);
    Route::post('/location/stop-tracking', [LocationController::class, 'stopTracking']);
    Route::post('/location/last-seen', [LocationController::class, 'updateLastSeen']);
});

// Public location API routes (for admin dashboard) 
Route::middleware(['web'])->prefix('location')->group(function () {
    Route::get('/users/active', [LocationController::class, 'getActiveUserLocations']);
    Route::get('/predefined', [LocationController::class, 'getPredefinedLocations']);
    Route::get('/stats', [LocationController::class, 'getLocationStats']);
});
