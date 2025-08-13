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

    protected $fillable = [
        'role_name',
        'role_status'
    ];

    protected $casts = [
        'role_status' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relationship dengan Permission (many-to-many)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    // Helper method untuk mengecek permission
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('permission_name', $permissionName)->exists();
    }
}
