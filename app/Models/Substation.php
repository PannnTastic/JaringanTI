<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Substation extends Model
{
    /** @use HasFactory<\Database\Factories\SubstationFactory> */
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'substation_id';
    protected $guarded = [];

    // Relationship dengan User
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship dengan Pop
    public function pops()
    {
        return $this->belongsTo(Pop::class, 'pop_id', 'pop_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'substation_id', 'substation_id');
    }
}
