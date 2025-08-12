<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'role_id';

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
