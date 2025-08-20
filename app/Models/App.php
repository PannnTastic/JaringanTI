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

    
}
