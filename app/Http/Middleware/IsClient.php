<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $clientRoles = ['client', 'patient', 'doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];
            
            if (!in_array(strtolower($user->role), $clientRoles)) {
                $adminRoles = ['Admin', 'Super Admin', 'Country Admin', 'Financial Manager', 'Content Manager', 'User Manager', 'admin', 'super-admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
                
                if (in_array(strtolower($user->role), $adminRoles)) {
                    // Only redirect if NOT already on admin dashboard to avoid loop
                    if (!$request->routeIs('admin.dashboard')) {
                        return redirect()->route('admin.dashboard')->with('warning', 'Admins cannot access the client panel.');
                    }
                    return $next($request);
                }

                // For practitioners, doctors, etc. 
                // They share the dashboard route with clients but the middleware is applied to it.
                // We must allow them to view the dashboard itself.
                if ($request->routeIs('dashboard')) {
                    return $next($request);
                }

                // If they are on a booking related route, give a specific message
                if ($request->is('book-session*') || $request->is('bookings*')) {
                    return redirect()->route('dashboard')->with('warning', 'Booking a session requires a client account. Your current account type does not support this action.');
                }

                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
