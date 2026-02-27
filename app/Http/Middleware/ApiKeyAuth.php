<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $configuredKey = config('services.chatbot.api_key');

        if (!$configuredKey) {
            return response()->json([
                'success' => false,
                'message' => 'API configuration error: API Key not set.'
            ], 500);
        }

        if ($apiKey !== $configuredKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid API Key.'
            ], 401);
        }

        return $next($request);
    }
}
