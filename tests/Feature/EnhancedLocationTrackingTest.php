<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;

// Test basic API endpoints without database changes

test('api returns active user locations for dashboard', function () {
    $response = $this->getJson('/api/location/users/active');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $data = $response->json('data');
    expect($data)->toBeArray();
});

test('api returns predefined locations', function () {
    $response = $this->getJson('/api/location/predefined');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $data = $response->json('data');
    expect($data)->toBeArray();
});

test('api returns location stats', function () {
    $response = $this->getJson('/api/location/stats');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $stats = $response->json('data');
    expect($stats)->toHaveKeys(['total_users', 'active_users', 'tracking_enabled']);
});

test('user model has location tracking methods', function () {
    // Test that methods exist without actually running them
    $user = new User();
    
    expect(method_exists($user, 'isOnline'))->toBeTrue();
    expect(method_exists($user, 'updateLocation'))->toBeTrue();
    expect(method_exists($user, 'updateLocationEnhanced'))->toBeTrue();
    expect(method_exists($user, 'distanceFrom'))->toBeTrue();
});

test('haversine distance calculation works correctly', function () {
    // Test the distance calculation logic directly
    $user = new User();
    
    // Test points in Batam area
    $lat1 = 1.1304;
    $lon1 = 104.0530;
    $lat2 = 1.1758;
    $lon2 = 104.0497;
    
    // Use reflection to test private method
    $reflection = new ReflectionClass($user);
    $method = $reflection->getMethod('haversineDistance');
    $method->setAccessible(true);
    
    $distance = $method->invoke($user, $lat1, $lon1, $lat2, $lon2);
    
    expect($distance)->toBeGreaterThan(4);
    expect($distance)->toBeLessThan(7);
});

test('location tracking routes are properly registered', function () {
    // Test that routes exist
    $routes = collect(Route::getRoutes())->map(fn($route) => $route->uri);
    
    expect($routes->contains('api/location/users/active'))->toBeTrue();
    expect($routes->contains('api/location/predefined'))->toBeTrue();
    expect($routes->contains('api/location/stats'))->toBeTrue();
    expect($routes->contains('api/location/update-enhanced'))->toBeTrue();
});
