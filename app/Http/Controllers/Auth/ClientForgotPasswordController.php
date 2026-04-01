<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OneTimeLogin;
use App\Mail\AdminOTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientForgotPasswordController extends Controller
{
    /**
     * Non-admin roles that can use the client forgot-password flow.
     */
    protected $clientRoles = ['client', 'patient', 'doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];

    public function showEmailForm()
    {
        return view('auth.client-passwords.email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => __('We could not find an account with that email address.')]);
        }

        // Rate limiting: check if an OTP was sent in the last 1 minute
        $lastOtp = OneTimeLogin::where('user_id', $user->id)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($lastOtp) {
            return back()->with('error', __('Please wait at least 1 minute before requesting another OTP.'));
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
            Mail::to($user->email)->send(new AdminOTPMail($otp));

            \App\Services\EmailLoggerService::log(
                $user->email,
                'Password Reset OTP',
                "Your OTP is: $otp. This OTP will expire in 5 minutes.",
                'sent'
            );
        } catch (\Exception $e) {
            Log::error("Client OTP Sending Failed", [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            \App\Services\EmailLoggerService::log(
                $user->email,
                'Password Reset OTP',
                "Your OTP is: $otp. This OTP will expire in 5 minutes.",
                'failed',
                $e->getMessage()
            );

            return back()->with('error', __('Failed to send OTP. Please try again later.'));
        }

        session(['client_reset_email' => $user->email]);

        return redirect()->route('client.forgot-password.otp')->with('status', __('An OTP has been sent to your email.'));
    }

    public function showOtpForm()
    {
        if (!session('client_reset_email')) {
            return redirect()->route('client.forgot-password');
        }
        return view('auth.client-passwords.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $email = session('client_reset_email');
        if (!$email) {
            return redirect()->route('client.forgot-password');
        }

        $user = User::where('email', $email)->first();

        $otpRecord = OneTimeLogin::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('used_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => __('Invalid or expired OTP.')]);
        }

        $otpRecord->update(['used_at' => Carbon::now()]);
        session(['client_otp_verified' => true]);

        return redirect()->route('client.forgot-password.reset');
    }

    public function showResetForm()
    {
        if (!session('client_otp_verified') || !session('client_reset_email')) {
            return redirect()->route('client.forgot-password');
        }
        return view('auth.client-passwords.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('client_otp_verified') || !session('client_reset_email')) {
            return redirect()->route('client.forgot-password');
        }

        $user = User::where('email', session('client_reset_email'))->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget(['client_reset_email', 'client_otp_verified']);

        return redirect()->route('login')->with('success', __('Your password has been reset successfully. Please login.'));
    }
}
