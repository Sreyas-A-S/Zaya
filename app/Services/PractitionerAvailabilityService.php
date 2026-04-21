<?php

namespace App\Services;

use App\Models\PractitionerAvailability;
use App\Models\Booking;
use App\Models\Practitioner;
use App\Models\Doctor;
use App\Models\MindfulnessPractitioner;
use App\Models\YogaTherapist;
use App\Models\Translator;
use Carbon\Carbon;

class PractitionerAvailabilityService
{
    /**
     * Get all bookable slots for a professional on a specific date.
     */
    public function getAvailableSlots($practitioner, $date)
    {
        // Find professional across all models
        $provider = $this->findProvider($practitioner);

        if (!$provider) {
            \Log::error("Professional not found: $practitioner");
            return [];
        }

        return $this->getAvailableSlotsForProvider($provider, $date);
    }

    /**
     * Get all bookable slots for a known provider model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $provider
     */
    public function getAvailableSlotsForProvider($provider, $date)
    {
        $dateObj = Carbon::parse($date);

        $providerId = $provider->id;
        $providerType = $provider->getMorphClass();
        \Log::info("Fetching slots for provider $providerId ($providerType) on $date");

        if (isset($provider->booking_window_days) && $provider->booking_window_days) {
            $maxDate = Carbon::today()->addDays((int) $provider->booking_window_days);
            if ($dateObj->gt($maxDate)) {
                return [];
            }
        }

        $dayOfWeek = $dateObj->dayOfWeek;

        // 1. Fetch criteria for this day (Polymorphic)
        $allDayEntries = PractitionerAvailability::where('practitioner_id', $providerId)
            ->where('practitioner_type', $providerType)
            ->whereDate('specific_date', $date)
            ->get();

        $isCustom = $allDayEntries->isNotEmpty();

        if (!$isCustom) {
            // Fallback to weekly schedule
            $allDayEntries = PractitionerAvailability::where('practitioner_id', $providerId)
                ->where('practitioner_type', $providerType)
                ->where('day_of_week', $dayOfWeek)
                ->whereNull('specific_date')
                ->get();
        }

        // 2. Check if full day is OFF
        $isFullDayOff = $allDayEntries->contains(fn($entry) => !$entry->is_available && is_null($entry->start_time));
        if ($isFullDayOff) return [];

        // 3. Separate available blocks and blocked ranges
        $availableBlocks = $allDayEntries->filter(fn($entry) => $entry->is_available && !is_null($entry->start_time));
        $blockedRanges = $allDayEntries->filter(fn($entry) => !$entry->is_available && !is_null($entry->start_time));

        if ($availableBlocks->isEmpty()) return [];

        // 4. Generate potential slots
        $generatedSlots = [];
        $providerNotice = $provider->min_notice_hours ?? 1;

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
                    'notice' => $providerNotice
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
                if ($slotStart->gte($blockStart) && $slotStart->lt($blockEnd)) return false;
            }
            return true;
        });

        // 6. Filter out existing bookings
        $bookingQuery = Booking::where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'paid']);
            
        if ($provider instanceof Translator) {
            $bookingQuery->where(function($q) use ($providerId) {
                $q->where(function($sq) use ($providerId) {
                    $sq->where('profile_id', $providerId)
                       ->where('practitioner_type', 'translator');
                })->orWhere('translator_id', $providerId);
            });
        } else {
            $bookingQuery->where('profile_id', $providerId)
                         ->where('practitioner_type', $providerType);
        }
        
        $existingBookings = $bookingQuery->pluck('booking_time')->toArray();

        $availableSlots = array_filter($availableSlots, function($slot) use ($existingBookings) {
            return !in_array($slot['time'], $existingBookings);
        });

        // 7. Notice period filtering
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

    private function findProvider($identifier)
    {
        $models = [Practitioner::class, Doctor::class, MindfulnessPractitioner::class, YogaTherapist::class, Translator::class];
        foreach ($models as $model) {
            $p = is_numeric($identifier) ? $model::find($identifier) : $model::where('slug', $identifier)->first();
            if ($p) return $p;
        }
        return null;
    }
}
