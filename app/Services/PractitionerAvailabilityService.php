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
    public function getAvailableSlots($practitioner, $date)
    {
        $dateObj = Carbon::parse($date);
        
        // Find practitioner by ID or slug
        $p = is_numeric($practitioner) 
            ? \App\Models\Practitioner::find($practitioner)
            : \App\Models\Practitioner::where('slug', $practitioner)->first();
            
        if (!$p) {
            \Log::error("Practitioner not found: $practitioner");
            return [];
        }
        
        $practitionerId = $p->id;
        \Log::info("Fetching slots for practitioner $practitionerId on $date (Day: " . $dateObj->dayOfWeek . ")");
        
        if ($p->booking_window_days) {
            $maxDate = Carbon::today()->addDays((int) $p->booking_window_days);
            if ($dateObj->gt($maxDate)) {
                \Log::info("Date $date is beyond maxDate " . $maxDate->toDateString());
                return [];
            }
        }
        $dayOfWeek = $dateObj->dayOfWeek;

        // 1. Fetch criteria for this day
        // Check for specific date custom slots first
        $allDayEntries = PractitionerAvailability::where('practitioner_id', $practitionerId)
            ->whereDate('specific_date', $date)
            ->get();

        $isCustom = $allDayEntries->isNotEmpty();
        \Log::info("isCustom: " . ($isCustom ? 'Yes' : 'No') . " entries count: " . $allDayEntries->count());

        if (!$isCustom) {
            // Fallback to weekly schedule
            $allDayEntries = PractitionerAvailability::where('practitioner_id', $practitionerId)
                ->where('day_of_week', $dayOfWeek)
                ->whereNull('specific_date')
                ->get();
            \Log::info("Weekly entries count: " . $allDayEntries->count());
        }

        // 2. Check if day is explicitly marked as OFF
        // (is_available = false AND start_time is NULL)
        $isFullDayOff = $allDayEntries->contains(fn($entry) => !$entry->is_available && is_null($entry->start_time));
        if ($isFullDayOff) {
            \Log::info("Day is marked as FULL OFF");
            return [];
        }

        // 3. Separate available blocks and blocked ranges
        $availableBlocks = $allDayEntries->filter(fn($entry) => $entry->is_available && !is_null($entry->start_time));
        $blockedRanges = $allDayEntries->filter(fn($entry) => !$entry->is_available && !is_null($entry->start_time));

        \Log::info("availableBlocks count: " . $availableBlocks->count());
        if ($availableBlocks->isEmpty()) {
            \Log::info("No available blocks found for practitioner $practitionerId on $date");
            return [];
        }

        // 4. Generate potential slots from available blocks
        $generatedSlots = [];
        foreach ($availableBlocks as $block) {
            $start = Carbon::parse($block->start_time);
            $end = Carbon::parse($block->end_time);
            $duration = $block->slot_duration ?? 60;

            \Log::info("Generating slots for block: " . $start->toTimeString() . " to " . $end->toTimeString() . " duration: " . $duration);

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

        \Log::info("Generated slots count: " . count($generatedSlots));

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

        \Log::info("Available slots after blocked ranges filtering: " . count($availableSlots));

        // 6. Filter out existing bookings
        $existingBookings = Booking::where('practitioner_id', $practitionerId)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->pluck('booking_time')
            ->toArray();

        $availableSlots = array_filter($availableSlots, function($slot) use ($existingBookings) {
            $slotTime = $slot['time'];
            return !in_array($slotTime, $existingBookings);
        });

        \Log::info("Available slots after booking filtering: " . count($availableSlots));

        // 7. Filter out slots that violate minimum notice period (if date is today)
        if ($dateObj->isToday()) {
            $now = Carbon::now();
            $availableSlots = array_filter($availableSlots, function($slot) use ($now) {
                $slotTime = Carbon::parse($slot['start_raw']);
                $noticeHours = $slot['notice'] ?? 0;
                return $now->copy()->addHours($noticeHours)->lte($slotTime);
            });
            \Log::info("Available slots after notice period filtering (Today): " . count($availableSlots));
        }

        return array_values($availableSlots);
    }
}
