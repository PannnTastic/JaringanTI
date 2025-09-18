<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Only update if it's been more than 2 minutes since last update
            // to avoid too frequent database writes
            if (
                !$user->last_seen || 
                $user->last_seen->diffInMinutes(now()) >= 2
            ) {
                $user->update(['last_seen' => now()]);
            }
        }

        return $next($request);
    }
}
