<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'user_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
    public function substations()
    {
        return $this->hasMany(Substation::class, 'user_id', 'user_id');
    }
    public function knowledges()
    {
        return $this->hasMany(Knowledgebase::class, 'user_id', 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id', 'user_id');
    }
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }

    public function hasPermission(string $permission): bool
{
    if (!$this->role) {
        return false;
    }
    
    return $this->role->permissions()
        ->where('permission_name', $permission)
        ->exists();
    }
   // app/Models/User.php
    public function getAvatarUrlAttribute(): string
    {
        if ($this->user_photo) {
            return Storage::disk('public')->url($this->user_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';

    }

    public function hasRole($roles): bool
    {
        // Jika tidak ada role, return false
        if (!$this->role) {
            return false;
        }

        // Jika parameter roles adalah array
        if (is_array($roles)) {
            return in_array($this->role->role_name, $roles);
        }

        // Jika parameter roles adalah string
        return $this->role->role_name === $roles;
    }

    public function permits()
    {
        return $this->hasMany(Permit::class, 'user_id', 'user_id');
    }

    // Accessor untuk kompatibilitas dengan IDE
    public function getUserIdAttribute($value)
    {
        return $this->attributes['user_id'] ?? null;
    }

    public function getRoleIdAttribute($value)
    {
        return $this->attributes['role_id'] ?? null;
    }

    public function getRoleAttribute()
    {
        // Jika relation sudah di-load, gunakan itu. Jika tidak, load secara lazy
        if ($this->relationLoaded('role')) {
            return $this->getRelation('role');
        }
        
        return $this->role()->first();
    }

    public function contents(){
        return $this->hasMany(Content::class, 'user_id', 'user_id');
    }

}