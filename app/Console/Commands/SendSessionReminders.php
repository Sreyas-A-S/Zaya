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
        $nowServer = Carbon::now();
        $this->info("Starting session reminders check at " . $nowServer->toDateTimeString() . " (" . config('app.timezone') . ")");
        
        // Fetch bookings that are online, confirmed, and not fully reminded
        // We broaded the date range to find any booking that might have pending sessions
        $bookings = Booking::with(['user', 'practitioner.user', 'translator.user'])
            ->where('mode', 'online')
            ->where('status', 'confirmed')
            ->where('reminder_sent', false)
            ->get();

        $this->info("Found " . $bookings->count() . " potential bookings to check for sessions.");
        $sentTotal = 0;

        foreach ($bookings as $booking) {
            try {
                $timezone = derive_timezone_from_user($booking->practitioner);
                $now = Carbon::now($timezone);
                
                // Get all sessions for this booking
                $sessions = [];
                if (!empty($booking->additional_info) && !empty($booking->additional_info['sessions'])) {
                    $sessions = $booking->additional_info['sessions'];
                } else {
                    // Fallback to main booking date/time if sessions array is missing
                    $sessions = [
                        [
                            'day' => $booking->booking_date->format('Y-m-d'),
                            'time' => $booking->booking_time,
                            'is_fallback' => true
                        ]
                    ];
                }

                $allSessionsSentOrPassed = true;
                $bookingUpdated = false;

                foreach ($sessions as $session) {
                    $sessionDate = $session['day'] ?? '';
                    $sessionTime = $session['time'] ?? '';
                    
                    if (empty($sessionDate) || empty($sessionTime)) continue;

                    try {
                        $startTime = Carbon::parse($sessionDate . ' ' . $sessionTime, $timezone);
                    } catch (\Exception $e) {
                        Log::error("Failed to parse session time for Booking #{$booking->id}: " . $e->getMessage());
                        continue;
                    }

                    $diff = $now->diffInMinutes($startTime, false);
                    $leadTime = $booking->practitioner->reminder_lead_time ?? 60;
                    
                    // Session identifier for unique email tracking
                    $sessionIdStr = $startTime->format('Y-m-d H:i');
                    $logSubject = "Session Reminder: #{$booking->invoice_no} ({$sessionIdStr})";

                    // If session is still in the future and far away, we haven't finished with this booking
                    if ($startTime->isFuture() && $diff > ($leadTime + 1)) {
                        $allSessionsSentOrPassed = false;
                        continue;
                    }

                    // Determine if this is a retry for a missed session
                    $isMissedRetry = false;
                    $failedAttempts = \App\Models\EmailLog::where('booking_id', $booking->id)
                        ->where('to', $booking->user->email) // Check client for failed attempts as indicator
                        ->where('subject', $logSubject)
                        ->where('status', 'error')
                        ->count();

                    // If session is in the past (more than 15 mins)
                    if ($diff < -15) {
                        // Only proceed if it's a retry of a failed reminder and not too old (e.g., within 24 hours)
                        if ($failedAttempts > 0 && $diff > -1440) {
                            $isMissedRetry = true;
                        } else {
                            continue;
                        }
                    }

                    // If we are in the window OR it's a missed retry, check if sent and send if not
                    if (($diff <= ($leadTime + 1) && $diff >= -15) || $isMissedRetry) {
                        $recipients = [];
                        $recipients[] = ['email' => $booking->user->email, 'type' => 'client'];
                        
                        if ($booking->practitioner && $booking->practitioner->user) {
                            $recipients[] = ['email' => $booking->practitioner->user->email, 'type' => 'practitioner'];
                        }
                        
                        if ($booking->need_translator && $booking->translator && $booking->translator->user) {
                            $recipients[] = ['email' => $booking->translator->user->email, 'type' => 'translator'];
                        }

                        // Generate the secure video link
                        $videoLink = route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']);

                        foreach ($recipients as $recipient) {
                            // Check if this specific session was already successfully sent to this recipient
                            $alreadySent = \App\Models\EmailLog::where('booking_id', $booking->id)
                                ->where('to', $recipient['email'])
                                ->where('subject', $logSubject)
                                ->where('status', 'success')
                                ->exists();

                            if ($alreadySent) continue;

                            // Check retries per recipient
                            $recipientFailedAttempts = \App\Models\EmailLog::where('booking_id', $booking->id)
                                ->where('to', $recipient['email'])
                                ->where('subject', $logSubject)
                                ->where('status', 'error')
                                ->count();

                            if ($recipientFailedAttempts >= 3) continue;

                            $startLog = microtime(true);
                            try {
                                Mail::to($recipient['email'])->send(new SessionReminderMail($booking, $recipient['type'], $videoLink, $session, $isMissedRetry));
                                $duration = microtime(true) - $startLog;
                                EmailLoggerService::log($recipient['email'], $logSubject, null, 'success', null, $duration, $booking->id);
                                $sentTotal++;
                            } catch (\Exception $e) {
                                $duration = microtime(true) - $startLog;
                                EmailLoggerService::log($recipient['email'], $logSubject, null, 'error', $e->getMessage(), $duration, $booking->id);
                                Log::error("Failed to send reminder for session {$sessionIdStr} to {$recipient['email']}: " . $e->getMessage());
                                $allSessionsSentOrPassed = false;
                            }
                        }
                    }
                }

                if ($allSessionsSentOrPassed) {
                    $booking->reminder_sent = true;
                    $booking->save();
                    $this->info("Completed reminders for Booking #{$booking->id}.");
                }
            } catch (\Exception $e) {
                Log::error("Error processing reminders for Booking #{$booking->id}: " . $e->getMessage());
            }
        }

        if ($sentTotal > 0) {
            $this->info("Successfully sent {$sentTotal} session reminders.");
        }
    }
}
