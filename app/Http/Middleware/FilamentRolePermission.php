<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentRolePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('filament.admin.auth.login');
        }

        // Ambil route name untuk menentukan resource
        $routeName = $request->route()->getName();
        
        // Extract resource name dari route
        if (preg_match('/filament\.admin\.resources\.(.+?)\./', $routeName, $matches)) {
            $resourceName = $matches[1];
            
            // Cek apakah user punya permission untuk resource ini
            if (!$this->hasResourcePermission($user, $resourceName)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki permission untuk mengakses menu ini.');
            }
        }
        // Allow access to pages (like notifications) for authenticated users
        elseif (preg_match('/filament\.admin\.pages\./', $routeName)) {
            // Pages are accessible to all authenticated users
            // You can add specific page permissions here if needed
        }

        return $next($request);
    }
    
    private function hasResourcePermission($user, $resourceName): bool
    {
        if (!$user->role) {
            return false;
        }
        
        return $user->role->permissions()
            ->where('permission_name', $resourceName)
            ->exists();
    }
}