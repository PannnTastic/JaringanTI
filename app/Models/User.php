<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be fillable for LDAP users.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'email_verified_at',
        'role_id',
        'user_type',
        'status',
        'user_photo',
        'latitude',
        'longitude',
        'address',
        'last_seen',
        'last_location_update',
        'location_tracking_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'user_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
            'last_location_update' => 'datetime',
            'location_tracking_enabled' => 'boolean',
            'current_latitude' => 'decimal:8',
            'current_longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Update user location with enhanced tracking data
     */
    public function updateLocationEnhanced(array $data): bool
    {
        $updateData = [
            'current_latitude' => $data['latitude'],
            'current_longitude' => $data['longitude'],
            'current_address' => $data['address'] ?? null,
            'last_location_update' => now(),
            'last_seen' => now(),
        ];

        // Add additional tracking fields if they exist in the database
        $trackingFields = ['accuracy', 'heading', 'speed', 'battery_level', 'is_charging', 'device_info'];
        foreach ($trackingFields as $field) {
            if (isset($data[$field]) && $this->isFillable($field)) {
                $updateData[$field] = $data[$field];
            }
        }

        return $this->update($updateData);
    }

    /**
     * Get user's distance from a given point
     */
    public function distanceFrom(float $latitude, float $longitude): ?float
    {
        if (!$this->current_latitude || !$this->current_longitude) {
            return null;
        }

        return $this->haversineDistance(
            (float) $this->current_latitude,
            (float) $this->current_longitude,
            $latitude,
            $longitude
        );
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Get user status based on last activity
     */
    public function getStatusAttribute(): string
    {
        if ($this->isOnline()) {
            return 'online';
        }

        if (!$this->last_seen) {
            return 'unknown';
        }

        $minutesAgo = $this->last_seen->diffInMinutes(now());
        
        if ($minutesAgo <= 30) {
            return 'recently_active';
        }

        return 'offline';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function substations()
    {
        return $this->hasMany(Substation::class, 'user_id', 'user_id');
    }

    public function knowledges()
    {
        return $this->hasMany(Knowledgebase::class, 'user_id', 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id', 'user_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('permission_name', $permission)
            ->exists();
    }

    // app/Models/User.php
    public function getAvatarUrlAttribute(): string
    {
        if ($this->user_photo) {
            return route('admin.storage', ['path' => $this->user_photo]);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=random';

    }

    public function hasRole($roles): bool
    {
        // Jika tidak ada role, return false
        if (! $this->role) {
            return false;
        }

        // Jika parameter roles adalah array
        if (is_array($roles)) {
            return in_array($this->role->role_name, $roles);
        }

        // Jika parameter roles adalah string
        return $this->role->role_name === $roles;
    }

    public function permits()
    {
        return $this->hasMany(Permit::class, 'user_id', 'user_id');
    }

    // Accessor untuk kompatibilitas dengan IDE
    public function getUserIdAttribute($value)
    {
        return $this->attributes['user_id'] ?? null;
    }

    public function getRoleIdAttribute($value)
    {
        return $this->attributes['role_id'] ?? null;
    }

    public function getRoleAttribute()
    {
        // Jika relation sudah di-load, gunakan itu. Jika tidak, load secara lazy
        if ($this->relationLoaded('role')) {
            return $this->getRelation('role');
        }

        return $this->role()->first();
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'user_id', 'user_id');
    }

    /**
     * Check if user is an LDAP user
     */
    public function isLdapUser(): bool
    {
        return $this->user_type === 'ldap';
    }

    /**
     * Get the identifier name for authentication
     */
    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    /**
     * Override to support username login
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)
            ->orWhere('username', $username)
            ->first();
    }

    /**
     * Update user's current location
     */
    public function updateLocation(float $latitude, float $longitude, ?string $address = null): bool
    {
        return $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'current_address' => $address,
            'last_location_update' => now(),
            'last_seen' => now(),
        ]);
    }

    /**
     * Check if user is currently online (last seen within 5 minutes)
     */
    public function isOnline(): bool
    {
        return $this->last_seen && $this->last_seen->diffInMinutes(now()) <= 5;
    }

    /**
     * Get user's current coordinates
     */
    public function getCurrentCoordinates(): ?array
    {
        if ($this->current_latitude && $this->current_longitude) {
            return [
                'lat' => (float) $this->current_latitude,
                'lng' => (float) $this->current_longitude,
            ];
        }
        return null;
    }

    /**
     * Scope for users with location tracking enabled
     */
    public function scopeWithLocationTracking($query)
    {
        return $query->where('location_tracking_enabled', true);
    }

    /**
     * Scope for online users (seen within last 5 minutes)
     */
    public function scopeOnline($query)
    {
        return $query->where('last_seen', '>=', now()->subMinutes(5));
    }
}
