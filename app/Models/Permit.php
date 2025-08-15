<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permit extends Model
{
    /** @use HasFactory<\Database\Factories\PermitFactory> */
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'permit_id';

    protected $guarded = [];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function substations(){
        return $this->belongsTo(Substation::class, 'substation_id', 'substation_id');
    }

    // Permit.php
    public function approvers()
    {
        return $this->belongsToMany(Role::class, 'approvers','permit_id','role_id')
            ->withPivot(['approver_status', 'approved_at'])
            ->withTimestamps();
    }

}
