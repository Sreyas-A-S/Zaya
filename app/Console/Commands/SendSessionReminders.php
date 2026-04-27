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
        $this->info("Starting session reminders check at " . $now->toDateTimeString() . " (" . config('app.timezone') . ")");
        
        // Fetch bookings for yesterday, today and tomorrow that are online, confirmed, and reminder not sent
        // We include yesterday to catch sessions in timezones that are behind the server time
        $bookings = Booking::with(['user', 'practitioner.user', 'translator.user'])
            ->where('mode', 'online')
            ->where('status', 'confirmed')
            ->where('reminder_sent', false)
            ->whereBetween('booking_date', [
                $now->copy()->subDay()->toDateString(),
                $now->copy()->addDay()->toDateString()
            ])
            ->get();

        $this->info("Found " . $bookings->count() . " potential bookings in the date range.");
        $sentCount = 0;

        foreach ($bookings as $booking) {
            try {
                // Get the practitioner's timezone
                $timezone = derive_timezone_from_user($booking->practitioner);
                
                // Combine date and time and parse in the practitioner's timezone
                $startTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time, $timezone);
                
                // Use practitioner's specific lead time or fallback to 60 minutes
                $leadTime = $booking->practitioner->reminder_lead_time ?? 60;
                
                $diff = $now->diffInMinutes($startTime, false);
                
                $this->info("Checking Booking #{$booking->id}: Start Time: {$startTime->toDateTimeString()} ({$timezone}), Diff: {$diff} mins, Lead Time: {$leadTime} mins");

                // Send reminder if current time is within the lead time window before the session
                if ($diff <= $leadTime && $diff >= -15) {
                    $this->info("Match found for Booking #{$booking->id}. Sending reminder...");
                    
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
