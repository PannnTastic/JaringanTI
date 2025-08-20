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

    public function user() {
        return $this->belongsTo(User::class,'user_id','user_id');
    
    }

    public function substations() {
        return $this->belongsTo(Substation::class, 'substation_id', 'substation_id');
    }
}

