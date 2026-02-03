<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        if (!\Illuminate\Support\Facades\Auth::user()->hasPermission($permission)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized. You do not have permission to perform this action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
