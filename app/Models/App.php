<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    /** @use HasFactory<\Database\Factories\AppFactory> */
    use HasFactory;

    protected $table = 'app';
    protected $primaryKey = 'app_id';

    protected $guarded = [];

    /**
     * Get the icon URL with admin prefix
     */
    public function getIconUrlAttribute(): ?string
    {
        if ($this->app_icon) {
            // Check if it's SVG or HTML content (starts with < or contains svg)
            if (str_starts_with(trim($this->app_icon), '<') || str_contains($this->app_icon, 'svg')) {
                // It's SVG or HTML content, return as is
                return $this->app_icon;
            } else {
                // It's a file path, use admin storage route
                return route('admin.storage', ['path' => $this->app_icon]);
            }
        }
        return null;
    }

    
}
