<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gardu extends Model
{
    /** @use HasFactory<\Database\Factories\GarduFactory> */
    use HasFactory;
    protected $primaryKey = 'gardu_id';
    protected $guarded = [];

}
