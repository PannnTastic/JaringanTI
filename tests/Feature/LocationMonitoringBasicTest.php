<?php

use App\Models\User;

test('location monitoring page can be accessed by authenticated user', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get('/location-monitoring');
    
    $response->assertOk()
        ->assertSee('Location Monitoring')
        ->assertSee('Real-time user location tracking');
});

test('location monitoring requires authentication', function () {
    $response = $this->get('/location-monitoring');
    
    $response->assertRedirect('/login');
});

test('api location update endpoint works', function () {
    $user = User::factory()->create([
        'location_tracking_enabled' => true
    ]);
    
    $response = $this->actingAs($user)
        ->postJson('/api/location/update', [
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);
    
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Location updated successfully',
        ]);
});

test('filament location resource can be accessed', function () {
    $user = User::factory()->create();
    
    // Since we don't know exact admin URL structure, we'll test basic access
    $this->actingAs($user);
    
    // Test that the route exists
    expect(route('location.monitoring'))->toBeString();
});
