<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

            $adminRoles = ['Admin', 'Super Admin', 'Country Admin', 'Financial Manager', 'Content Manager', 'User Manager', 'admin', 'super-admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];

            if (in_array(auth()->user()->role, $adminRoles)) {
                return redirect()->intended('/admin');
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
        // If an admin tries to login via the client login page, log them out and redirect.
        $adminRoles = ['Admin', 'Super Admin', 'Country Admin', 'Financial Manager', 'Content Manager', 'User Manager', 'admin', 'super-admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
        
        if (in_array($user->role, $adminRoles)) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Admins must login via the Admin Portal.');
        }

        $blockReason = $user->getLoginBlockReason();
        if ($blockReason) {
            auth()->logout();
            return redirect()->route('login')->with('error', $blockReason);
        }

        if ($request->has('redirect')) {
            return redirect($request->redirect);
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
