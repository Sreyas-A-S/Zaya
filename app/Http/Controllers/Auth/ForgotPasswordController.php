<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OneTimeLogin;
use App\Mail\AdminOTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    protected $adminRoles = ['Admin', 'Super Admin', 'Country Admin', 'Financial Manager', 'Content Manager', 'User Manager', 'admin', 'super-admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user || !in_array($user->role, $this->adminRoles)) {
            return back()->withErrors(['email' => 'We could not find an admin with that email address.']);
        }

        // Rate limiting: check if an OTP was sent in the last 1 minute
        $lastOtp = OneTimeLogin::where('user_id', $user->id)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($lastOtp) {
            return back()->with('error', 'Please wait at least 1 minute before requesting another OTP.');
        }

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Save OTP Record
        OneTimeLogin::create([
            'user_id' => $user->id,
            'token' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            // Send Email
            Mail::to($user->email)->send(new AdminOTPMail($otp));

            // Log Email to DB (Success)
            \App\Services\EmailLoggerService::log(
                $user->email, 
                'Admin Password Reset OTP', 
                "Your OTP is: $otp. This OTP will expire in 5 minutes.", 
                'sent'
            );

            // Log to Laravel Logs for debugging
            \Illuminate\Support\Facades\Log::info("Admin OTP Sent Successfully", [
                'email' => $user->email,
                'otp' => $otp
            ]);

        } catch (\Exception $e) {
            // Log the failure to Laravel Logs
            \Illuminate\Support\Facades\Log::error("Admin OTP Sending Failed", [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Log the failure to EmailLog Table
            \App\Services\EmailLoggerService::log(
                $user->email, 
                'Admin Password Reset OTP', 
                "Your OTP is: $otp. This OTP will expire in 5 minutes.", 
                'failed', 
                $e->getMessage()
            );

            return back()->with('error', 'Mail server error: ' . $e->getMessage());
        }

        // Store email in session to know who we are resetting
        session(['reset_email' => $user->email]);

        return redirect()->route('admin.forgot-password.otp.show')->with('status', 'An OTP has been sent to your email.');
    }

    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('admin.forgot-password.show');
        }
        return view('auth.passwords.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('admin.forgot-password.show');
        }

        $user = User::where('email', $email)->first();

        $otpRecord = OneTimeLogin::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('used_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Mark OTP as used
        $otpRecord->update(['used_at' => Carbon::now()]);

        // Set a session flag that OTP is verified
        session(['otp_verified' => true]);

        return redirect()->route('admin.forgot-password.reset.show');
    }

    public function showResetForm()
    {
        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('admin.forgot-password.show');
        }
        return view('auth.passwords.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('admin.forgot-password.show');
        }

        $user = User::where('email', session('reset_email'))->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('admin.login')->with('status', 'Your password has been reset successfully. Please login.');
    }
}
