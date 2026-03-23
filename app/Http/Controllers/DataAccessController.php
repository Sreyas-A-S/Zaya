<?php

namespace App\Http\Controllers;

use App\Models\DataAccessRequest;
use App\Models\User;
use App\Mail\DataAccessOTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataAccessController extends Controller
{
    /**
     * Practitioner requests access to client data (triggers OTP).
     */
    public function requestAccess(Request $request)
    {
        $practitioner = Auth::user();
        $clientId = $request->client_id;
        $client = User::findOrFail($clientId);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Create or update request
        $accessRequest = DataAccessRequest::updateOrCreate(
            [
                'requester_id' => $practitioner->id,
                'client_id' => $client->id,
            ],
            [
                'otp' => $otp,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'approved_at' => null,
            ]
        );

        try {
            Mail::to($client->email)->send(new DataAccessOTPMail($otp, $practitioner->name));
            return response()->json(['success' => 'OTP has been sent to the client\'s email.']);
        } catch (\Exception $e) {
            Log::error('Data Access OTP Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send OTP. Please try again.'], 500);
        }
    }

    /**
     * Practitioner verifies the OTP provided by the client.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'otp' => 'required|string|size:6',
        ]);

        $practitioner = Auth::user();
        $accessRequest = DataAccessRequest::where('requester_id', $practitioner->id)
            ->where('client_id', $request->client_id)
            ->where('status', 'pending')
            ->first();

        if (!$accessRequest) {
            return response()->json(['error' => 'No pending access request found.'], 404);
        }

        if ($accessRequest->expires_at->isPast()) {
            $accessRequest->update(['status' => 'expired']);
            return response()->json(['error' => 'OTP has expired. Please request a new one.'], 422);
        }

        if ($accessRequest->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP. Please check and try again.'], 422);
        }

        // Grant access
        $accessRequest->update([
            'status' => 'approved',
            'approved_at' => Carbon::now(),
            'otp' => null, // Clear OTP after use
        ]);

        return response()->json(['success' => 'Access granted successfully! You can now view the client\'s data.']);
    }

    /**
     * Check if practitioner has access to client data.
     */
    public static function hasAccess($practitionerId, $clientId)
    {
        return DataAccessRequest::where('requester_id', $practitionerId)
            ->where('client_id', $clientId)
            ->where('status', 'approved')
            ->exists();
    }
}
