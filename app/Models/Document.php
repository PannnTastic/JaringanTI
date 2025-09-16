<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'doc_id';

    protected $guarded = [];
    protected $with = ['user','substations'];

    public function user() {
        return $this->belongsTo(User::class,'user_id','user_id');
    
    }

    public function substations() {
        return $this->belongsTo(Substation::class, 'substation_id', 'substation_id');
    }
    
    /**
     * Get the full URL for the document file with admin prefix
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->doc_file) {
            return null;
        }
        
        // Use admin storage route - converts storage/url to admin/storage/url
        return route('admin.storage', ['path' => $this->doc_file]);
    }
    
    /**
     * Check if file exists in storage
     */
    public function fileExists(): bool
    {
        if (!$this->doc_file) {
            return false;
        }
        
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->doc_file);
    }
}

