<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $locationData;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->locationData = [
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->role_name,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
            'address' => $user->address,
            'last_location_update' => $user->last_location_update?->toISOString(),
            'last_seen' => $user->last_seen?->toISOString(),
            'is_online' => $user->isOnline(),
            'coordinates' => $user->getCurrentCoordinates(),
            'avatar_url' => $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random',
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('location-tracking'),
            new PrivateChannel('admin-location-tracking'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user' => $this->locationData,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'location.updated';
    }
}
