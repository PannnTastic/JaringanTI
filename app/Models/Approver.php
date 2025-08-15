<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    /** @use HasFactory<\Database\Factories\ApproverFactory> */
    use HasFactory;

    protected $guarded = [];

    public function permits(){
        return $this->belongsToMany(Permit::class, 'permit_id', 'permit_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_id', 'user_id');
    }
}
