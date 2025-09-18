<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channel for location tracking - everyone can listen
Broadcast::channel('location-tracking', function () {
    return true;
});

// Private channel for admin location tracking
Broadcast::channel('admin-location-tracking', function ($user) {
    // Only allow users with admin role or specific permissions
    return $user && ($user->hasRole('admin') || $user->hasPermission('view_location_tracking'));
});