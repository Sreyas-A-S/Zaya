<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the social provider authentication page.
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the social provider.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $existingUser = User::where($provider . '_id', $socialUser->id)->first();

            if ($existingUser) {
                Auth::login($existingUser);
                return redirect()->route('dashboard');
            } else {
                // Check if user exists by email
                $userByEmail = User::where('email', $socialUser->email)->first();

                if ($userByEmail) {
                    $userByEmail->update([
                        $provider . '_id' => $socialUser->id,
                    ]);
                    Auth::login($userByEmail);
                } else {
                    // Create new user
                    $nameParts = explode(' ', $socialUser->name, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';

                    $newUser = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $socialUser->email,
                        'role' => 'patient', // Default role
                        'password' => Hash::make(Str::random(16)), // Dummy password
                        $provider . '_id' => $socialUser->id,
                        'status' => 'active',
                    ]);

                    Auth::login($newUser);
                }

                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
