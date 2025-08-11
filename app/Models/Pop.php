<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pop extends Model
{
    /** @use HasFactory<\Database\Factories\PopFactory> */
    use HasFactory;

    protected $primaryKey = 'pop_id';
    protected $guarded = [];
}
