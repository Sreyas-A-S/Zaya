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

        // ── Load global reminder lead times ─────────────────────────────────
        $globalSettings = \App\Models\HomepageSetting::whereIn('key', [
            'reminder_mail_advance',
            'reminder_mail_one_hour',
            'reminder_mail_final',
        ])->pluck('value', 'key');

        // Advance reminder: stored in minutes (default 1440 = 24 hours)
        $advanceLeadTime = isset($globalSettings['reminder_mail_advance']) && $globalSettings['reminder_mail_advance'] !== ''
            ? (int) $globalSettings['reminder_mail_advance']
            : 1440;

        // 1-Hour reminder: stored in minutes (default 60 = 1 hour)
        $oneHourLeadTime = isset($globalSettings['reminder_mail_one_hour']) && $globalSettings['reminder_mail_one_hour'] !== ''
            ? (int) $globalSettings['reminder_mail_one_hour']
            : 60;

        // Final reminder: stored in minutes (default 10 minutes)
        $finalLeadTime = isset($globalSettings['reminder_mail_final']) && $globalSettings['reminder_mail_final'] !== ''
            ? (int) $globalSettings['reminder_mail_final']
            : 10;

        $this->info("Advance: {$advanceLeadTime} min | 1-Hour: {$oneHourLeadTime} min | Final: {$finalLeadTime} min");

        // ── Fetch eligible bookings ──────────────────────────────────────────
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
                $now      = Carbon::now($timezone);

                // Get all sessions for this booking
                $sessions = [];
                if (!empty($booking->additional_info) && !empty($booking->additional_info['sessions'])) {
                    $sessions = $booking->additional_info['sessions'];
                } else {
                    $sessions = [
                        [
                            'day'         => $booking->booking_date->format('Y-m-d'),
                            'time'        => $booking->booking_time,
                            'is_fallback' => true,
                        ]
                    ];
                }

                $allSessionsSentOrPassed = true;

                foreach ($sessions as $session) {
                    $sessionDate = $session['day']  ?? '';
                    $sessionTime = $session['time'] ?? '';

                    if (empty($sessionDate) || empty($sessionTime)) continue;

                    try {
                        $startTime = Carbon::parse($sessionDate . ' ' . $sessionTime, $timezone);
                    } catch (\Exception $e) {
                        Log::error("Failed to parse session time for Booking #{$booking->id}: " . $e->getMessage());
                        continue;
                    }

                    $diff          = $now->diffInMinutes($startTime, false); // positive = future
                    $sessionIdStr  = $startTime->format('Y-m-d H:i');
                    $videoLink     = route('conference.join', ['channel' => $booking->invoice_no, 'provider' => 'jaas']);

                    // Build recipient list once per session
                    $recipients = [
                        ['email' => $booking->user->email, 'type' => 'client'],
                    ];
                    if ($booking->practitioner && $booking->practitioner->user) {
                        $recipients[] = ['email' => $booking->practitioner->user->email, 'type' => 'practitioner'];
                    }
                    if ($booking->need_translator && $booking->translator && $booking->translator->user) {
                        $recipients[] = ['email' => $booking->translator->user->email, 'type' => 'translator'];
                    }

                    // ── 1. ADVANCE REMINDER ──────────────────────────────────
                    // Window: ±5 minutes around the advance lead time
                    $advanceSubject = "Session Advance Reminder: #{$booking->invoice_no} ({$sessionIdStr})";
                    $inAdvanceWindow = $startTime->isFuture()
                        && $diff >= ($advanceLeadTime - 5)
                        && $diff <= ($advanceLeadTime + 5);

                    if ($inAdvanceWindow) {
                        $sentTotal += $this->dispatchToRecipients(
                            $recipients, $booking, $session, $videoLink,
                            $advanceSubject, $sessionIdStr, false
                        );
                    } elseif ($startTime->isFuture() && $diff > ($advanceLeadTime + 5)) {
                        // Still far in the future — booking not complete yet
                        $allSessionsSentOrPassed = false;
                    }

                    // ── 2. 1-HOUR REMINDER ───────────────────────────────────
                    // Window: ±5 minutes around the 1-hour lead time
                    $oneHourSubject = "Session 1-Hour Reminder: #{$booking->invoice_no} ({$sessionIdStr})";
                    $inOneHourWindow = $startTime->isFuture()
                        && $diff >= ($oneHourLeadTime - 5)
                        && $diff <= ($oneHourLeadTime + 5);

                    if ($inOneHourWindow) {
                        $sentTotal += $this->dispatchToRecipients(
                            $recipients, $booking, $session, $videoLink,
                            $oneHourSubject, $sessionIdStr, false
                        );
                    } elseif ($startTime->isFuture() && $diff > ($oneHourLeadTime + 5)) {
                        // Still far in the future for 1-hour reminder
                        $allSessionsSentOrPassed = false;
                    }

                    // ── 3. FINAL REMINDER ────────────────────────────────────
                    // Window: from finalLeadTime+1 down to -15 minutes (catches cron delays)
                    $finalSubject = "Session Final Reminder: #{$booking->invoice_no} ({$sessionIdStr})";

                    $isMissedRetry = false;

                    if ($diff < -15) {
                        // Session passed — retry only if there were failures, within 24 h
                        $failedAttempts = \App\Models\EmailLog::where('booking_id', $booking->id)
                            ->where('to', $booking->user->email)
                            ->where('subject', $finalSubject)
                            ->where('status', 'error')
                            ->count();

                        if ($failedAttempts > 0 && $diff > -1440) {
                            $isMissedRetry = true;
                        } else {
                            // Session fully passed with no retry needed
                            continue;
                        }
                    }

                    $inFinalWindow = ($diff <= ($finalLeadTime + 1) && $diff >= -15) || $isMissedRetry;

                    if ($inFinalWindow) {
                        $sentTotal += $this->dispatchToRecipients(
                            $recipients, $booking, $session, $videoLink,
                            $finalSubject, $sessionIdStr, $isMissedRetry
                        );
                    } elseif ($startTime->isFuture() && $diff > ($finalLeadTime + 1)) {
                        // Final reminder window hasn't arrived yet
                        $allSessionsSentOrPassed = false;
                    }
                }

                if ($allSessionsSentOrPassed) {
                    $booking->reminder_sent = true;
                    $booking->save();
                    $this->info("Completed all reminders for Booking #{$booking->id}.");
                }

            } catch (\Exception $e) {
                Log::error("Error processing reminders for Booking #{$booking->id}: " . $e->getMessage());
            }
        }

        if ($sentTotal > 0) {
            $this->info("Successfully sent {$sentTotal} session reminder emails.");
        }
    }

    /**
     * Dispatch a reminder email to all recipients, skipping already-sent ones.
     *
     * @return int  Number of emails successfully sent this call
     */
    private function dispatchToRecipients(
        array $recipients,
        Booking $booking,
        array $session,
        string $videoLink,
        string $logSubject,
        string $sessionIdStr,
        bool $isMissedRetry
    ): int {
        $sent = 0;

        foreach ($recipients as $recipient) {
            // Skip if already sent successfully
            $alreadySent = \App\Models\EmailLog::where('booking_id', $booking->id)
                ->where('to', $recipient['email'])
                ->where('subject', $logSubject)
                ->where('status', 'success')
                ->exists();

            if ($alreadySent) continue;

            // Skip if retried too many times
            $failedCount = \App\Models\EmailLog::where('booking_id', $booking->id)
                ->where('to', $recipient['email'])
                ->where('subject', $logSubject)
                ->where('status', 'error')
                ->count();

            if ($failedCount >= 3) continue;

            $startLog = microtime(true);
            try {
                Mail::to($recipient['email'])->send(
                    new SessionReminderMail($booking, $recipient['type'], $videoLink, $session, $isMissedRetry)
                );
                $duration = microtime(true) - $startLog;
                EmailLoggerService::log($recipient['email'], $logSubject, null, 'success', null, $duration, $booking->id);
                $sent++;
            } catch (\Exception $e) {
                $duration = microtime(true) - $startLog;
                EmailLoggerService::log($recipient['email'], $logSubject, null, 'error', $e->getMessage(), $duration, $booking->id);
                Log::error("Failed to send [{$logSubject}] to {$recipient['email']}: " . $e->getMessage());
            }
        }

        return $sent;
    }
}
