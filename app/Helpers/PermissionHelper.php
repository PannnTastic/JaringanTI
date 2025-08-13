<?php


namespace App\Helpers;

class PermissionHelper
{
    public static function canAccessResource(string $resource): bool
    {
        $user = auth()->user();
        
        if (!$user || !$user->role) {
            return false;
        }
        
        return $user->role->permissions()
            ->where('permission_name', $resource)
            ->exists();
    }
}