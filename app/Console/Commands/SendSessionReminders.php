<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\SessionReminderMail;
use App\Services\EmailLoggerService;
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
                
                // Use practitioner's specific lead time or fallback to 60 minutes
                $leadTime = $booking->practitioner->reminder_lead_time ?? 60;
                
                // Send reminder if current time is within the lead time window before the session
                // We also check if the session hasn't already started long ago (e.g., within the last 15 mins is fine if we just missed it)
                if ($now->diffInMinutes($startTime, false) <= $leadTime && $now->diffInMinutes($startTime, false) >= -15) {
                    
                    // Generate the secure video link using invoice_no as channel name
                    $videoLink = route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']);

                    $recipients = [];
                    $recipients[] = ['email' => $booking->user->email, 'type' => 'client'];
                    
                    if ($booking->practitioner && $booking->practitioner->user) {
                        $recipients[] = ['email' => $booking->practitioner->user->email, 'type' => 'practitioner'];
                    }
                    
                    if ($booking->need_translator && $booking->translator && $booking->translator->user) {
                        $recipients[] = ['email' => $booking->translator->user->email, 'type' => 'translator'];
                    }

                    foreach ($recipients as $recipient) {
                        $start = microtime(true);
                        try {
                            Mail::to($recipient['email'])->send(new SessionReminderMail($booking, $recipient['type'], $videoLink));
                            $duration = microtime(true) - $start;
                            EmailLoggerService::log($recipient['email'], "Session Reminder: #{$booking->invoice_no}", null, 'success', null, $duration, $booking->id);
                        } catch (\Exception $e) {
                            $duration = microtime(true) - $start;
                            EmailLoggerService::log($recipient['email'], "Session Reminder: #{$booking->invoice_no}", null, 'error', $e->getMessage(), $duration, $booking->id);
                            Log::error("Failed to send reminder to {$recipient['email']} for booking #{$booking->id}: " . $e->getMessage());
                        }
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
