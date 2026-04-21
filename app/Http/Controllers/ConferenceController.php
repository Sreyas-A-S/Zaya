<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Conference;
use App\Support\Google\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ConferenceController extends Controller
{
    /**
     * Pre-authorizes an instant meeting ID or generates a Google Meet link.
     */
    public function initInstantMeeting(Request $request)
    {
        $user = Auth::user();
        $userRole = strtolower(trim($user->role ?? ''));
        $allowedRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist', 'translator', 'client', 'patient', 'admin', 'super-admin'];
        
        if (!$user || !in_array($userRole, $allowedRoles)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $provider = $request->input('provider', 'zegocloud');

        if ($provider === 'google_meet') {
            $googleService = new GoogleCalendarService();
            if (!$googleService->isConfigured()) {
                return response()->json(['success' => false, 'message' => 'Google Meet is not configured on the server.'], 500);
            }

            try {
                $meeting = $googleService->createMeeting(
                    "Instant Session by " . $user->name,
                    now()->toIso8601String(),
                    60,
                    [$user->email] // Add practitioner as attendee
                );

                return response()->json([
                    'success' => true,
                    'channel' => $meeting['id'], // Store Google event ID
                    'redirect_url' => $meeting['hangout_link']
                ]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Google Meet Error: ' . $e->getMessage()], 500);
            }
        }

        // ZegoCloud logic fallback
        $channel = 'zaya-' . strtolower(Str::random(10));
        
        // Temporarily authorize this channel ID for this specific user in cache (10 min expiry)
        Cache::put('instant_meeting_' . $channel, $user->id, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'channel' => $channel
        ]);
    }
}
