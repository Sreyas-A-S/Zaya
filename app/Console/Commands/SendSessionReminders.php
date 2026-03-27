<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\SessionReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSessionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders with video conference links to clients and practitioners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Fetch bookings for today and tomorrow that are online, confirmed, and reminder not sent
        $bookings = Booking::with(['user', 'practitioner.user', 'translator.user'])
            ->where('mode', 'online')
            ->where('status', 'confirmed')
            ->where('reminder_sent', false)
            ->whereBetween('booking_date', [
                $now->toDateString(), 
                $now->copy()->addDay()->toDateString()
            ])
            ->get();

        $sentCount = 0;

        foreach ($bookings as $booking) {
            try {
                // Combine date and time
                // Assuming booking_time is like "10:00 AM" or "14:30"
                $startTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                
                // Reminder timing is fixed system-wide at 60 minutes.
                $leadTime = 60;
                
                // Send reminder if current time is within the lead time window before the session
                // We also check if the session hasn't already started long ago (e.g., within the last 15 mins is fine if we just missed it)
                if ($now->diffInMinutes($startTime, false) <= $leadTime && $now->diffInMinutes($startTime, false) >= -10) {
                    
                    // Generate the Agora link using invoice_no as channel name
                    $videoLink = route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']);

                    // Send to Client
                    Mail::to($booking->user->email)->send(new SessionReminderMail($booking, 'client', $videoLink));
                    
                    // Send to Practitioner
                    if ($booking->practitioner && $booking->practitioner->user) {
                        Mail::to($booking->practitioner->user->email)->send(new SessionReminderMail($booking, 'practitioner', $videoLink));
                    }
                    
                    // Send to Translator
                    if ($booking->need_translator && $booking->translator && $booking->translator->user) {
                        Mail::to($booking->translator->user->email)->send(new SessionReminderMail($booking, 'translator', $videoLink));
                    }

                    $booking->reminder_sent = true;
                    $booking->save();
                    
                    $sentCount++;
                }
            } catch (\Exception $e) {
                Log::error("Error sending reminder for booking #{$booking->id}: " . $e->getMessage());
            }
        }

        if ($sentCount > 0) {
            $this->info("Successfully sent {$sentCount} session reminders.");
        }
    }
}
