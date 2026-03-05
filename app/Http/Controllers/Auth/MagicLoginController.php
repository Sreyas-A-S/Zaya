<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicLoginController extends Controller
{
    public function login(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Invalid magic link.');
        }

        $magicLink = OneTimeLogin::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$magicLink) {
            return redirect()->route('login')->with('error', 'Magic link expired or already used.');
        }

        // Mark as used immediately
        $magicLink->update(['used_at' => now()]);

        // Login user
        Auth::login($magicLink->user);

        // Redirect based on role
        $role = $magicLink->user->role;

        if ($role === 'admin' || $role === 'Super Admin') {
            return redirect()->route('admin.dashboard');
        }

        // Add other role redirects as needed
        return redirect()->route('home');
    }
}
