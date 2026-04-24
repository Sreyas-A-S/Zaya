<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware removed for now
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => ['required', 'string', function ($attribute, $value, $fail) {
                $storedCode = session('captcha_code');
                if (!$storedCode || strtoupper($value) !== $storedCode) {
                    $fail('The captcha code is incorrect.');
                }
            }],
        ]);

        // We can keep the forget here as it only runs if the above validate passes.
        // If it fails, Laravel throws an exception and this line is never reached.
        session()->forget('captcha_code');
    }

    public function showLoginForm(Request $request)
    {
        $redirect = $request->query('redirect');
        return view('zaya-login', compact('redirect'));
    }

    public function showAdminLoginForm()
    {
        return view('auth.login');
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'), $request->remember)) {
            $blockReason = auth()->user()->getLoginBlockReason();
            if ($blockReason) {
                auth()->logout();
                return redirect()->route('admin.login')->with('error', $blockReason);
            }

            $adminRoles = [
                'admin', 'super-admin', 'country-admin', 
                'financial-manager', 'finance-manager', 
                'financial_manager', 'finance_manager',
                'financialmanager', 'financemanager',
                'content-manager', 'user-manager',
                'content_manager', 'user_manager'
            ];

            $userRole = strtolower(trim(auth()->user()->role ?? ''));

            if (in_array($userRole, $adminRoles) || str_contains($userRole, 'admin') || str_contains($userRole, 'manager')) {
                // Ensure they aren't in the specific non-admin list just in case
                $nonAdminRoles = ['doctor', 'practitioner', 'mindfulness-practitioner', 'yoga-therapist', 'client', 'patient', 'translator'];
                if (!in_array($userRole, $nonAdminRoles)) {
                    return redirect()->intended('/admin');
                }
            }

            // Explicitly block non-admin roles from the Admin Portal
            $nonAdminRoles = ['doctor', 'practitioner', 'mindfulness-practitioner', 'yoga-therapist', 'client', 'patient', 'translator'];
            if (in_array(auth()->user()->role, $nonAdminRoles)) {
                auth()->logout();
                return redirect()->route('admin.login')->with('error', 'Access denied. Doctors, Practitioners, Clients and Translators must login via their respective portals.');
            }

            auth()->logout();
            return back()->with('error', 'You do not have admin access.');
        }

        return back()->withInput($request->only('email', 'remember'))->with('error', 'Invalid credentials.');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // This method is called by the standard 'login' route (Client/User Login)
        
        // Define allowed roles for this login page
        $allowedRoles = [
            'client', 
            'patient', 
            'practitioner', 
            'doctor', 
            'mindfulness-practitioner', 
            'mindfulness_practitioner',
            'yoga-therapist', 
            'yoga_therapist',
            'translator'
        ];
        
        // If it's a backend user (not in allowed list), log them out and redirect back with error
        if (!in_array(strtolower($user->role), $allowedRoles)) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Backend users are not allowed to login through this portal. Please use the Admin Portal.');
        }

        // Store promo code if present in the request
        $promoCode = trim((string) ($request->input('promo_code') ?: $request->input('promocode', '')));
        if ($promoCode !== '') {
            $user->promo_code = $promoCode;
            $user->save();

            // Track promo code in user_promo_codes table (unique per user per code)
            if (Schema::hasTable('user_promo_codes')) {
                $user->userPromoCodes()->firstOrCreate([
                    'promo_code' => $promoCode
                ]);
            }
        }

        $blockReason = $user->getLoginBlockReason();
        if ($blockReason) {
            auth()->logout();
            return redirect()->route('login')->with('error', $blockReason);
        }

        if ($request->has('redirect')) {
            $redirectUrl = $request->redirect;
            
            // Check if redirecting to booking page
            if (str_contains($redirectUrl, '/book-session')) {
                $clientRoles = ['client', 'patient'];
                if (!in_array(strtolower($user->role), $clientRoles)) {
                    return redirect()->route('dashboard')->with('warning', 'Booking a session requires a client account. Your current account type does not support this action.');
                }
            }

            return redirect($redirectUrl);
        }

        return redirect()->route('dashboard');
    }

    protected function loggedOut(Request $request)
    {
        if (str_contains(url()->previous(), '/admin')) {
            return redirect()->route('admin.login');
        }
        return redirect()->route('login');
    }
}
