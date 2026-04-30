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
            $driver = Socialite::driver($provider);

            // Fix for cURL error 60: SSL certificate problem in local environment
            if (config('app.env') === 'local') {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }

            $socialUser = $driver->user();
            
            $existingUser = User::where($provider . '_id', $socialUser->id)->first();

            if ($existingUser) {
                $blockReason = $existingUser->getLoginBlockReason();
                if ($blockReason) {
                    return redirect()->route('login')->with('error', $blockReason);
                }
                Auth::login($existingUser);
                return $this->redirectBasedOnRole($existingUser);
            } else {
                // Check if user exists by email
                $userByEmail = User::where('email', $socialUser->email)->first();

                if ($userByEmail) {
                    $blockReason = $userByEmail->getLoginBlockReason();
                    if ($blockReason) {
                        return redirect()->route('login')->with('error', $blockReason);
                    }
                    
                    $updateData = [
                        $provider . '_id' => $socialUser->id,
                    ];
                    
                    // Update name if it's missing
                    if (!$userByEmail->name) {
                        $updateData['name'] = $socialUser->name;
                    }

                    $userByEmail->update($updateData);
                    Auth::login($userByEmail);
                    return $this->redirectBasedOnRole($userByEmail);
                } else {
                    // Create new user
                    $nameParts = explode(' ', $socialUser->name, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';

                    $newUser = User::create([
                        'name' => $socialUser->name,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $socialUser->email,
                        'role' => 'patient', // Default role
                        'password' => Hash::make(Str::random(16)), // Dummy password
                        $provider . '_id' => $socialUser->id,
                        'status' => 'active',
                    ]);

                    Auth::login($newUser);
                    return $this->redirectBasedOnRole($newUser);
                }
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole($user)
    {
        $adminRoles = ['super-admin', 'admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
        
        if (in_array($user->role, $adminRoles)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
