<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class SetPasswordController extends Controller
{
    public function show(Request $request)
    {
        $email = (string) $request->query('email', '');
        $token = (string) $request->query('token', '');

        if ($email === '' || $token === '') {
            abort(404);
        }

        $user = User::where('email', $email)->first();
        if (!$user || !Password::broker()->tokenExists($user, $token)) {
            abort(403, 'This password setup link is invalid or has expired.');
        }

        return view('auth.set-password', [
            'email' => $email,
            'token' => $token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->remember_token = Str::random(60);
                $user->status = 'active';
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                }
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => __($status)])->withInput();
        }

        return redirect()->route('login')->with('success', 'Your password has been set successfully. Please log in.');
    }
}
