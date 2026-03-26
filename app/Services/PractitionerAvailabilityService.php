<?php

namespace App\Services;

use App\Models\PractitionerAvailability;
use App\Models\Booking;
use Carbon\Carbon;

class PractitionerAvailabilityService
{
    /**
     * Get all bookable slots for a practitioner on a specific date.
     */
    public function getAvailableSlots($practitionerId, $date)
    {
        $dateObj = Carbon::parse($date);
        $practitioner = \App\Models\Practitioner::find($practitionerId);
        if ($practitioner && $practitioner->booking_window_days) {
            $maxDate = Carbon::today()->addDays((int) $practitioner->booking_window_days);
            if ($dateObj->gt($maxDate)) {
                return [];
            }
        }
        $dayOfWeek = $dateObj->dayOfWeek;

        // 1. Fetch criteria for this day
        // Check for specific date custom slots first
        $allDayEntries = PractitionerAvailability::where('practitioner_id', $practitionerId)
            ->where('specific_date', $date)
            ->get();

        $isCustom = $allDayEntries->isNotEmpty();

        if (!$isCustom) {
            // Fallback to weekly schedule
            $allDayEntries = PractitionerAvailability::where('practitioner_id', $practitionerId)
                ->where('day_of_week', $dayOfWeek)
                ->whereNull('specific_date')
                ->get();
        }

        // 2. Check if day is explicitly marked as OFF
        // (is_available = false AND start_time is NULL)
        $isFullDayOff = $allDayEntries->contains(fn($entry) => !$entry->is_available && is_null($entry->start_time));
        if ($isFullDayOff) {
            return [];
        }

        // 3. Separate available blocks and blocked ranges
        $availableBlocks = $allDayEntries->filter(fn($entry) => $entry->is_available && !is_null($entry->start_time));
        $blockedRanges = $allDayEntries->filter(fn($entry) => !$entry->is_available && !is_null($entry->start_time));

        if ($availableBlocks->isEmpty()) {
            return [];
        }

        // 4. Generate potential slots from available blocks
        $generatedSlots = [];
        foreach ($availableBlocks as $block) {
            $start = Carbon::parse($block->start_time);
            $end = Carbon::parse($block->end_time);
            $duration = $block->slot_duration ?? 60;

            $current = clone $start;
            while ($current->copy()->addMinutes($duration)->lte($end)) {
                $generatedSlots[] = [
                    'time' => $current->format('h:i A'),
                    'start_raw' => $current->format('H:i:s'),
                    'duration' => $duration,
                    'notice' => $block->min_notice_hours ?? 0
                ];
                $current->addMinutes($duration);
            }
        }

        // 5. Filter out blocked time ranges
        $availableSlots = array_filter($generatedSlots, function($slot) use ($blockedRanges) {
            $slotStart = Carbon::parse($slot['start_raw']);
            foreach ($blockedRanges as $range) {
                $blockStart = Carbon::parse($range->start_time);
                $blockEnd = Carbon::parse($range->end_time);
                // If slot starts within a blocked range, it's unavailable
                if ($slotStart->gte($blockStart) && $slotStart->lt($blockEnd)) {
                    return false;
                }
            }
            return true;
        });

        // 6. Filter out existing bookings
        $existingBookings = Booking::where('practitioner_id', $practitionerId)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->pluck('booking_time')
            ->toArray();

        $availableSlots = array_filter($availableSlots, function($slot) use ($existingBookings) {
            // We assume booking_time is stored in a format like "10:00 AM" or similar
            // Let's normalize for comparison
            $slotTime = $slot['time'];
            return !in_array($slotTime, $existingBookings);
        });

        // 7. Filter out slots that violate minimum notice period (if date is today)
        if ($dateObj->isToday()) {
            $now = Carbon::now();
            $availableSlots = array_filter($availableSlots, function($slot) use ($now) {
                $slotTime = Carbon::parse($slot['start_raw']);
                $noticeHours = $slot['notice'] ?? 0;
                return $now->copy()->addHours($noticeHours)->lte($slotTime);
            });
        }

        return array_values($availableSlots);
    }
}
