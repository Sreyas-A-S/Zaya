<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $adminRoles = [
            'admin', 'super-admin', 'country-admin', 
            'financial-manager', 'finance-manager', 
            'financial_manager', 'finance_manager',
            'financialmanager', 'financemanager',
            'content-manager', 'user-manager',
            'content_manager', 'user_manager'
        ];
        
        $userRole = strtolower(trim(auth()->user()->role ?? ''));
        
        if (auth()->check() && (in_array($userRole, $adminRoles) || str_contains($userRole, 'admin') || str_contains($userRole, 'manager'))) {
            // Exceptions for practitioners/clients who might have "manager" in their title if any
            $nonAdminRoles = ['doctor', 'practitioner', 'mindfulness-practitioner', 'yoga-therapist', 'client', 'patient', 'translator'];
            if (!in_array($userRole, $nonAdminRoles)) {
                return $next($request);
            }
        }

        return redirect()->route('zaya-login')->with('error', 'You do not have admin access.');
    }
}
