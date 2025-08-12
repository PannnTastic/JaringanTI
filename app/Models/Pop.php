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

    public function gardus()
    {
        return $this->hasMany(Gardu::class, 'gardu_pop', 'pop_id');
    }
}
