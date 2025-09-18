<?php

use App\Models\User;
use App\Models\Location;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'location_tracking_enabled' => true,
        'current_latitude' => -6.2088,
        'current_longitude' => 106.8456,
        'current_address' => 'Jakarta, Indonesia',
        'last_seen' => now(),
        'last_location_update' => now(),
    ]);
    
    $this->location = Location::factory()->create([
        'location' => 'Test Office',
        'latitude' => -6.2000,
        'longitude' => 106.8000,
        'status' => true,
    ]);
});

test('location monitoring page can be rendered', function () {
    $this->actingAs($this->user)
        ->get('/location-monitoring')
        ->assertOk()
        ->assertSeeLivewire('location-monitoring');
});

test('location monitoring shows users with location tracking', function () {
    $this->actingAs($this->user);
    
    Volt::test('location-monitoring')
        ->assertSet('users', function ($users) {
            return $users->count() > 0;
        })
        ->assertSee($this->user->name)
        ->assertSee('Online');
});

test('location monitoring can filter online users', function () {
    // Create offline user
    $offlineUser = User::factory()->create([
        'location_tracking_enabled' => true,
        'current_latitude' => -6.1000,
        'current_longitude' => 106.7000,
        'last_seen' => now()->subMinutes(10), // Offline
    ]);
    
    $this->actingAs($this->user);
    
    Volt::test('location-monitoring')
        ->set('filterStatus', 'online')
        ->call('loadData')
        ->assertSet('users', function ($users) use ($offlineUser) {
            return $users->contains('user_id', $this->user->user_id) && 
                   !$users->contains('user_id', $offlineUser->user_id);
        });
});

test('location monitoring can search users', function () {
    $anotherUser = User::factory()->create([
        'name' => 'John Doe',
        'location_tracking_enabled' => true,
        'current_latitude' => -6.3000,
        'current_longitude' => 106.9000,
    ]);
    
    $this->actingAs($this->user);
    
    Volt::test('location-monitoring')
        ->set('searchQuery', 'John')
        ->call('loadData')
        ->assertSet('users', function ($users) use ($anotherUser) {
            return $users->contains('user_id', $anotherUser->user_id) && 
                   $users->count() === 1;
        });
});

test('location monitoring can select user', function () {
    $this->actingAs($this->user);
    
    Volt::test('location-monitoring')
        ->call('selectUser', $this->user->user_id)
        ->assertSet('selectedUser.user_id', $this->user->user_id)
        ->assertSee($this->user->email);
});

test('location monitoring refreshes data', function () {
    $this->actingAs($this->user);
    
    Volt::test('location-monitoring')
        ->call('refreshData')
        ->assertDispatched('locations-updated');
});

test('user can update location via api', function () {
    $this->actingAs($this->user);
    
    $response = $this->postJson('/api/location/update', [
        'latitude' => -6.2500,
        'longitude' => 106.8500,
    ]);
    
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Location updated successfully',
        ]);
    
    $this->user->refresh();
    expect($this->user->current_latitude)->toBe(-6.2500);
    expect($this->user->current_longitude)->toBe(106.8500);
});

test('api returns active locations', function () {
    $this->actingAs($this->user);
    
    $response = $this->getJson('/api/location/active');
    
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'count' => 1,
        ])
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'user_id',
                    'name',
                    'email',
                    'current_latitude',
                    'current_longitude',
                    'is_online',
                ]
            ]
        ]);
});

test('user can start and stop location tracking', function () {
    $this->actingAs($this->user);
    
    // Start tracking
    $response = $this->postJson('/api/location/start-tracking');
    $response->assertOk()
        ->assertJson(['success' => true]);
    
    // Stop tracking
    $response = $this->postJson('/api/location/stop-tracking');
    $response->assertOk()
        ->assertJson(['success' => true]);
});

test('location api validates coordinates', function () {
    $this->actingAs($this->user);
    
    $response = $this->postJson('/api/location/update', [
        'latitude' => 'invalid',
        'longitude' => 200, // Out of range
    ]);
    
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude', 'longitude']);
});

test('user model has location tracking methods', function () {
    expect($this->user->isOnline())->toBeTrue();
    expect($this->user->getCurrentCoordinates())->toBe([
        'lat' => -6.2088,
        'lng' => 106.8456,
    ]);
    
    $this->user->updateLocation(-6.3000, 106.9000, 'New Address');
    $this->user->refresh();
    
    expect($this->user->current_latitude)->toBe(-6.3000);
    expect($this->user->current_longitude)->toBe(106.9000);
    expect($this->user->current_address)->toBe('New Address');
});

test('location model has geospatial methods', function () {
    $nearbyLocations = Location::withinRadius(-6.2088, 106.8456, 50)->get();
    expect($nearbyLocations->count())->toBeGreaterThan(0);
    
    expect($this->location->coordinates)->toBe([
        'lat' => -6.2000,
        'lng' => 106.8000,
    ]);
});
