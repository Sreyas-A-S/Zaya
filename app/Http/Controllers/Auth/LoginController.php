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

    public function showLoginForm()
    {
        return view('zaya-login');
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
            if (auth()->user()->role === 'admin') {
                return redirect()->intended('/admin');
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
        // This method is called by the standard 'login' route (Client Login)
        // If an admin tries to login via the client login page, log them out and redirect.
        if ($user->role === 'admin') {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Admins must login via the Admin Portal.');
        }

        return redirect()->intended('/');
    }

    protected function loggedOut(Request $request)
    {
        if (str_contains(url()->previous(), '/admin')) {
            return redirect()->route('admin.login');
        }
        return redirect()->route('login');
    }
}
