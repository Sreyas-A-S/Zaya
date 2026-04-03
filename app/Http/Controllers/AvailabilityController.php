<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PractitionerAvailability;
use App\Models\Practitioner;
use App\Models\Doctor;
use App\Models\MindfulnessPractitioner;
use App\Models\YogaTherapist;
use App\Models\Translator;
use App\Models\BookingReservation;
use App\Services\PractitionerAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    private function getProfessionalProfile($user)
    {
        return $user->profile;
    }

    public function index()
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);

        if (!$profile) {
            $availabilities = collect();
            return view('availability.index', compact('user', 'availabilities'))->with('warning', 'No professional profile linked to this account.');
        }

        $availabilities = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('availability.index', compact('user', 'availabilities', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);

        if (!$profile) {
            return back()->with('error', 'You need a professional profile to create slots.');
        }

        $request->validate([
            'day_of_week' => 'nullable|integer|min:0|max:6',
            'specific_date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration' => 'required|integer|min:1|max:480',
            'off_slots' => 'nullable|string',
        ]);

        if ($request->specific_date && !$request->has('day_of_week')) {
            $request->merge(['day_of_week' => \Carbon\Carbon::parse($request->specific_date)->dayOfWeek]);
        }

        $offSlots = $request->off_slots ? explode(',', $request->off_slots) : [];

        // 1. Clear existing slots for this pattern/date
        $query = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile));
            
        if ($request->specific_date) {
            $query->where('specific_date', $request->specific_date);
        } else {
            $query->where('day_of_week', $request->day_of_week)->whereNull('specific_date');
        }
        $query->delete();

        // 2. Generate and create slots
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $duration = (int) $request->slot_duration;

        $current = $start->copy();
        while ($current->copy()->addMinutes($duration)->lte($end)) {
            $currentTimeStr = $current->format('H:i:s');
            
            // Check if this slot start time is in our "off" list
            $isOff = false;
            foreach($offSlots as $os) {
                if (\Carbon\Carbon::parse($os)->format('H:i:s') === $currentTimeStr) {
                    $isOff = true;
                    break;
                }
            }

            PractitionerAvailability::create([
                'practitioner_id' => $profile->id,
                'practitioner_type' => get_class($profile),
                'day_of_week' => $request->day_of_week,
                'specific_date' => $request->specific_date,
                'start_time' => $current->format('H:i:s'),
                'end_time' => $current->copy()->addMinutes($duration)->format('H:i:s'),
                'slot_duration' => $duration,
                'is_available' => !$isOff
            ]);
            
            $current->addMinutes($duration);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Availability updated.']);
        }

        return back()->with('success', 'Availability updated successfully.');
    }

    public function getDateSlots($date)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $dateObj = \Carbon\Carbon::parse($date);
        $dayOfWeek = $dateObj->dayOfWeek;

        $customSlots = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->where('specific_date', $date)
            ->orderBy('start_time')
            ->get();

        $isCustom = $customSlots->isNotEmpty();
        $slots = $isCustom ? $customSlots : PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->where('day_of_week', $dayOfWeek)
            ->whereNull('specific_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'status' => true,
            'date' => $date,
            'formatted_date' => $dateObj->format('M d, Y'),
            'is_custom' => $isCustom,
            'slots' => $slots->map(function($s) {
                return [
                    'id' => $s->id,
                    'start' => $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('h:i A') : null,
                    'start_24' => $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : null,
                    'end' => $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('h:i A') : null,
                    'end_24' => $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : null,
                    'duration' => $s->slot_duration,
                    'is_available' => (bool)$s->is_available
                ];
            })
        ]);
    }

    public function getGeneratedSlots(Request $request, $practitioner, $date)
    {
        $service = new PractitionerAvailabilityService();
        $slots = $service->getAvailableSlots($practitioner, $date);

        return response()->json([
            'date' => $date,
            'slots' => $slots
        ]);
    }

    public function getOffDays($practitioner)
    {
        // Find practitioner by ID or slug across all models
        $p = $this->findProvider($practitioner);
            
        if (!$p) {
            return response()->json(['off_days' => [], 'off_day_indexes' => []], 404);
        }

        // Get weekly off days
        $offDayIndexes = PractitionerAvailability::where('practitioner_id', $p->id)
            ->where('practitioner_type', get_class($p))
            ->whereNull('specific_date')
            ->whereNull('start_time')
            ->where('is_available', false)
            ->pluck('day_of_week')
            ->toArray();

        // Get specific date off days
        $offDateDays = PractitionerAvailability::where('practitioner_id', $p->id)
            ->where('practitioner_type', get_class($p))
            ->whereNotNull('specific_date')
            ->whereNull('start_time')
            ->where('is_available', false)
            ->where('specific_date', '>=', Carbon::today()->toDateString())
            ->where('specific_date', '<=', Carbon::today()->addDays(30)->toDateString())
            ->pluck('specific_date')
            ->map(fn($date) => $date instanceof Carbon ? $date->toDateString() : $date)
            ->toArray();

        return response()->json([
            'off_days' => $offDateDays,
            'off_day_indexes' => $offDayIndexes
        ]);
    }

    private function findProvider($identifier)
    {
        $models = [Practitioner::class, Doctor::class, MindfulnessPractitioner::class, YogaTherapist::class, Translator::class];
        foreach ($models as $model) {
            $p = is_numeric($identifier) ? $model::find($identifier) : $model::where('slug', $identifier)->first();
            if ($p) return $p;
        }
        return null;
    }

    public function getBookedSlots($practitioner, $date)
    {
        $p = $this->findProvider($practitioner);
            
        if (!$p) {
            return response()->json(['booked_slots' => []], 404);
        }

        try {
            // Get only truly booked slots
            $query = Booking::where('booking_date', $date)
                ->whereIn('status', ['confirmed', 'paid', 'completed']);

            if ($p instanceof Translator) {
                // For translators, we check both if they are the main professional 
                // OR if they are assigned as a translator to another professional's session
                $query->where(function($q) use ($p) {
                    $q->where(function($sq) use ($p) {
                        $sq->where('practitioner_id', $p->id)
                           ->where('practitioner_type', Translator::class);
                    })->orWhere('translator_id', $p->id);
                });
            } else {
                $query->where('practitioner_id', $p->id)
                      ->where('practitioner_type', get_class($p));
            }

            $bookedSlots = $query->pluck('booking_time')->toArray();

            return response()->json([
                'date' => $date,
                'booked_slots' => $bookedSlots
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getBookedSlots: ' . $e->getMessage());
            return response()->json(['booked_slots' => []], 200);
        }
    }

    public function resetToWeekly(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate(['date' => 'required|date']);

        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->where('specific_date', $request->date)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function updateBookingSettings(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);

        if (!$profile) {
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
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return back()->with('error', 'No profile linked.');

        $request->validate([
            'off_days' => 'nullable|array',
            'off_days.*' => 'integer|min:0|max:6',
        ]);

        $offDays = $request->input('off_days', []);

        // Clear all future custom overrides
        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->whereNotNull('specific_date')
            ->where('specific_date', '>=', now()->toDateString())
            ->delete();

        // 1. Remove all current weekly off records
        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->whereNull('specific_date')
            ->whereNull('start_time')
            ->where('is_available', false)
            ->delete();

        // 2. Add new weekly off records
        foreach ($offDays as $day) {
            // Delete any existing active weekly slots for this day
            PractitionerAvailability::where('practitioner_id', $profile->id)
                ->where('practitioner_type', get_class($profile))
                ->whereNull('specific_date')
                ->where('day_of_week', $day)
                ->delete();

            PractitionerAvailability::create([
                'practitioner_id' => $profile->id,
                'practitioner_type' => get_class($profile),
                'day_of_week' => (int)$day,
                'is_available' => false
            ]);
        }

        return back()->with('success', 'Weekly off days updated.');
    }

    public function updateWeeklySlots(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return back()->with('error', 'No profile linked.');

        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'slot_duration' => 'required|integer|min:1|max:480',
            'off_slots' => 'nullable|string',
        ]);

        $offSlots = $request->off_slots ? explode(',', $request->off_slots) : [];

        // Clear all future custom overrides
        PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->whereNotNull('specific_date')
            ->where('specific_date', '>=', now()->toDateString())
            ->delete();

        // Get days that are NOT off days
        $offDayIndexes = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->whereNull('specific_date')
            ->whereNull('start_time')
            ->where('is_available', false)
            ->pluck('day_of_week')
            ->toArray();

        $allDayIndexes = [0, 1, 2, 3, 4, 5, 6];
        $workingDayIndexes = array_diff($allDayIndexes, $offDayIndexes);

        foreach ($workingDayIndexes as $dayIndex) {
            // Remove existing weekly active slots for this day
            PractitionerAvailability::where('practitioner_id', $profile->id)
                ->where('practitioner_type', get_class($profile))
                ->whereNull('specific_date')
                ->where('day_of_week', $dayIndex)
                ->where('is_available', true)
                ->delete();

            $start = \Carbon\Carbon::parse($request->start_time);
            $end = \Carbon\Carbon::parse($request->end_time);
            $duration = (int) $request->slot_duration;

            $current = $start->copy();
            while ($current->copy()->addMinutes($duration)->lte($end)) {
                $currentTimeStr = $current->format('H:i:s');
                $isOff = false;
                foreach($offSlots as $os) {
                    if (\Carbon\Carbon::parse($os)->format('H:i:s') === $currentTimeStr) {
                        $isOff = true;
                        break;
                    }
                }

                PractitionerAvailability::create([
                    'practitioner_id' => $profile->id,
                    'practitioner_type' => get_class($profile),
                    'day_of_week' => $dayIndex,
                    'start_time' => $current->format('H:i:s'),
                    'end_time' => $current->copy()->addMinutes($duration)->format('H:i:s'),
                    'slot_duration' => $duration,
                    'is_available' => !$isOff
                ]);
                
                $current->addMinutes($duration);
            }
        }

        return back()->with('success', 'Weekly working hours updated for all active days.');
    }

    public function toggleOffDay(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate(['date' => 'required|date']);

        $existing = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->where('specific_date', $request->date)
            ->whereNull('start_time')
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'available', 'message' => 'Date marked as available']);
        } else {
            PractitionerAvailability::where('practitioner_id', $profile->id)
                ->where('practitioner_type', get_class($profile))
                ->where('specific_date', $request->date)
                ->delete();

            PractitionerAvailability::create([
                'practitioner_id' => $profile->id,
                'practitioner_type' => get_class($profile),
                'specific_date' => $request->date,
                'is_available' => false
            ]);
            return response()->json(['status' => 'off', 'message' => 'Date marked as off']);
        }
    }

    public function toggleOffTime(Request $request)
    {
        $user = Auth::user();
        $profile = $this->getProfessionalProfile($user);
        if (!$profile) return response()->json(['error' => 'No profile'], 403);

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        PractitionerAvailability::create([
            'practitioner_id' => $profile->id,
            'practitioner_type' => get_class($profile),
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
        $profile = $this->getProfessionalProfile($user);

        $availability = PractitionerAvailability::where('practitioner_id', $profile->id)
            ->where('practitioner_type', get_class($profile))
            ->where('id', $id)
            ->firstOrFail();

        $availability->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Time slot removed.');
    }
}
