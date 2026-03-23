<?php

namespace App\Http\Controllers;

use App\Models\PractitionerAvailability;
use App\Models\Practitioner;
use App\Services\PractitionerAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    private function getTestPractitioner($user)
    {
        if ($user->practitioner) return $user->practitioner;
        if ($user->patient) return $user->patient;
        if (in_array($user->role, ['admin', 'super-admin'])) {
            return Practitioner::first();
        }
        return null;
    }

    public function index()
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);

        if (!$profile) {
            $availabilities = collect();
            return view('availability.index', compact('user', 'availabilities'))->with('warning', 'No professional profile linked to this account.');
        }

        $availabilities = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('availability.index', compact('user', 'availabilities', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);

        if (!$profile) {
            return back()->with('error', 'You need a professional profile to create slots.');
        }

        $request->validate([
            'day_of_week' => 'nullable|integer|min:0|max:6',
            'specific_date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration' => 'required|integer|min:1|max:480',
        ]);

        // Clear any "Full Day Off" record for this specific day/weekly pattern first
        $query = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('is_available', false)
            ->whereNull('start_time');
        
        if ($request->specific_date) {
            $query->where('specific_date', $request->specific_date);
        } else {
            $query->where('day_of_week', $request->day_of_week)->whereNull('specific_date');
        }
        $query->delete();

        PractitionerAvailability::create([
            'practitioner_id' => $profile->id,
            'day_of_week' => $request->day_of_week,
            'specific_date' => $request->specific_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'slot_duration' => $request->slot_duration,
            'is_available' => true
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Availability updated.']);
        }

        return back()->with('success', 'Availability updated successfully.');
    }

    public function getDateSlots($date)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $dateObj = \Carbon\Carbon::parse($date);
        $dayOfWeek = $dateObj->dayOfWeek;

        $customSlots = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('specific_date', $date)
            ->get();

        $isCustom = $customSlots->isNotEmpty();
        $slots = $isCustom ? $customSlots : PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('day_of_week', $dayOfWeek)
            ->whereNull('specific_date')
            ->get();

        return response()->json([
            'date' => $date,
            'formatted_date' => $dateObj->format('M d, Y'),
            'is_custom' => $isCustom,
            'slots' => $slots->map(function($s) {
                return [
                    'id' => $s->id,
                    'start' => $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('h:i A') : null,
                    'end' => $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('h:i A') : null,
                    'duration' => $s->slot_duration,
                    'is_available' => $s->is_available
                ];
            })
        ]);
    }

    public function getGeneratedSlots(Request $request, $practitionerId, $date)
    {
        $service = new PractitionerAvailabilityService();
        $slots = $service->getAvailableSlots($practitionerId, $date);

        return response()->json([
            'date' => $date,
            'slots' => $slots
        ]);
    }

    public function resetToWeekly(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate(['date' => 'required|date']);

        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('specific_date', $request->date)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function updateBookingSettings(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);

        if (!$profile || !($profile instanceof Practitioner)) {
            return back()->with('error', 'Booking settings can only be managed for professional profiles.');
        }

        $request->validate([
            'booking_window_days' => 'required|integer|min:1|max:365',
        ]);

        $profile->update([
            'booking_window_days' => $request->booking_window_days,
        ]);

        return back()->with('success', 'Booking settings updated successfully.');
    }

    public function updateWeeklyOffDays(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);
        if (!$profile) return back()->with('error', 'No profile linked.');

        $request->validate([
            'off_days' => 'nullable|array',
            'off_days.*' => 'integer|min:0|max:6',
        ]);

        $offDays = $request->input('off_days', []);

        // 1. Remove all current weekly off records
        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->whereNull('specific_date')
            ->whereNull('start_time')
            ->where('is_available', false)
            ->delete();

        // 2. Add new weekly off records
        foreach ($offDays as $day) {
            // Delete any existing active weekly slots for this day if we're making it an off day
            // Optional: User might want to keep slots but just hide them. 
            // Better to delete them to avoid confusion if we're marking the day as "Usually Off"
            PractitionerAvailability::where('practitioner_id', $profile->id)
                ->whereNull('specific_date')
                ->where('day_of_week', $day)
                ->delete();

            PractitionerAvailability::create([
                'practitioner_id' => $profile->id,
                'day_of_week' => (int)$day,
                'is_available' => false
            ]);
        }

        return back()->with('success', 'Weekly off days updated.');
    }

    public function toggleOffDay(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate(['date' => 'required|date']);

        $existing = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('specific_date', $request->date)
            ->whereNull('start_time')
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'available', 'message' => 'Date marked as available']);
        } else {
            PractitionerAvailability::where('practitioner_id', $profile->id)
                ->where('specific_date', $request->date)
                ->delete();

            PractitionerAvailability::create([
                'practitioner_id' => $profile->id,
                'specific_date' => $request->date,
                'is_available' => false
            ]);
            return response()->json(['status' => 'off', 'message' => 'Date marked as off']);
        }
    }

    public function toggleOffTime(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        PractitionerAvailability::create([
            'practitioner_id' => $profile->id,
            'specific_date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => false
        ]);

        return response()->json(['status' => 'success', 'message' => 'Time range blocked']);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $profile = $this->getTestPractitioner($user);

        $availability = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('id', $id)
            ->firstOrFail();

        $availability->delete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Time slot removed.');
    }
}
