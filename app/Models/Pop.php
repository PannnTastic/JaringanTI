<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pop extends Model
{
    /** @use HasFactory<\Database\Factories\PopFactory> */
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'pop_id';
    protected $guarded = [];

    public function substations()
    {
        return $this->hasMany(Substation::class, 'pop_id', 'pop_id');
    }
}
