<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory;

    protected $primaryKey = 'content_id';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Accessor untuk photo URL  
    public function getPhotoUrlAttribute()
    {
        if ($this->content_photo) {
            return asset('storage/' . $this->content_photo);
        }
        return null;
    }
}
