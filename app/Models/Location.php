<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $primaryKey = 'id_location';

    protected $fillable = [
        'location',
        'latitude',
        'longitude',
        'description',
        'address',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean',
    ];

    /**
     * Get locations that are active/enabled
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get locations within a radius (in kilometers)
     */
    public function scopeWithinRadius($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw('
                *,
                (6371 * acos(cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude)))) AS distance
            ', [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }

    /**
     * Get coordinate attribute as array
     */
    protected function coordinates(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => [
                'lat' => (float) ($attributes['latitude'] ?? 0),
                'lng' => (float) ($attributes['longitude'] ?? 0),
            ]
        );
    }

    /**
     * Get formatted location string with coordinates
     */
    public function getFullLocationAttribute(): string
    {
        return $this->location." ({$this->latitude}, {$this->longitude})";
    }
}
