<?php

namespace App\Http\Controllers;

use App\Models\DataAccessRequest;
use App\Models\User;
use App\Mail\DataAccessOTPMail;
use App\Mail\ReferralOTPMail;
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
        $type = $request->input('type', 'access');
        $meta = $request->input('meta'); // array of names or similar

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Create or update request
        $accessRequest = DataAccessRequest::updateOrCreate(
            [
                'requester_id' => $practitioner->id,
                'client_id' => $client->id,
                'type' => $type,
            ],
            [
                'otp' => $otp,
                'meta' => $meta,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'approved_at' => null,
            ]
        );

        try {
            if ($type === 'referral' && !empty($meta)) {
                $proNames = is_array($meta) ? implode(', ', $meta) : $meta;
                Mail::to($client->email)->send(new ReferralOTPMail($otp, $practitioner->name, $proNames));
            } else {
                Mail::to($client->email)->send(new DataAccessOTPMail($otp, $practitioner->name));
            }
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
            'type' => 'nullable|string'
        ]);

        $type = $request->input('type', 'access');
        $practitioner = Auth::user();
        $accessRequest = DataAccessRequest::where('requester_id', $practitioner->id)
            ->where('client_id', $request->client_id)
            ->where('type', $type)
            ->where('status', 'pending')
            ->first();

        if (!$accessRequest) {
            return response()->json(['error' => 'No pending request found.'], 404);
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
            'otp' => null, 
        ]);

        return response()->json(['success' => 'Access granted successfully!']);
    }

    /**
     * Client toggles (enables/disables) a practitioner's access.
     */
    public function toggleAccess(Request $request)
    {
        $user = Auth::user();
        $requestId = $request->request_id;
        $enabled = $request->enabled; // boolean

        $accessRequest = DataAccessRequest::where('client_id', $user->id)
            ->findOrFail($requestId);

        $accessRequest->update([
            'status' => $enabled ? 'approved' : 'revoked',
            'approved_at' => $enabled ? Carbon::now() : $accessRequest->approved_at,
        ]);

        return response()->json([
            'success' => $enabled ? 'Access enabled successfully.' : 'Access revoked successfully.',
            'status' => $accessRequest->status
        ]);
    }

    /**
     * Internal helper to grant access without OTP (e.g. upon referral confirmation).
     */
    public static function grantAccess($practitionerId, $clientId, $type = 'access')
    {
        return DataAccessRequest::updateOrCreate(
            [
                'requester_id' => $practitionerId,
                'client_id' => $clientId,
                'type' => $type,
            ],
            [
                'status' => 'approved',
                'approved_at' => Carbon::now(),
                'otp' => null,
            ]
        );
    }

    /**
     * Check if practitioner has access to client data.
     */
    public static function hasAccess($practitionerId, $clientId, $type = 'access')
    {
        $client = User::find($clientId);
        
        // Global master switch check (if patient profile exists)
        if ($client && $client->patient && !$client->patient->data_sharing_consent) {
            return false;
        }

        return DataAccessRequest::where('requester_id', $practitionerId)
            ->where('client_id', $clientId)
            ->where('type', $type)
            ->where('status', 'approved')
            ->exists();
    }
}
