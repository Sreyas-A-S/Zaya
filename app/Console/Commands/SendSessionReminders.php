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
        $globalSetting = \App\Models\HomepageSetting::where('key', 'global_reminder_lead_times')->first();
        if ($globalSetting) {
            $globalLeadTimes = json_decode($globalSetting->value, true) ?: [1440, 60, 10];
        } else {
            // Fallback: check if old settings exist
            $legacySettings = \App\Models\HomepageSetting::whereIn('key', [
                'reminder_mail_advance',
                'reminder_mail_one_hour',
                'reminder_mail_final',
            ])->pluck('value', 'key');

            $globalLeadTimes = [];
            if (isset($legacySettings['reminder_mail_advance']) && $legacySettings['reminder_mail_advance'] !== '') {
                $globalLeadTimes[] = (int) $legacySettings['reminder_mail_advance'];
            } else {
                $globalLeadTimes[] = 1440;
            }

            if (isset($legacySettings['reminder_mail_one_hour']) && $legacySettings['reminder_mail_one_hour'] !== '') {
                $globalLeadTimes[] = (int) $legacySettings['reminder_mail_one_hour'];
            } else {
                $globalLeadTimes[] = 60;
            }

            if (isset($legacySettings['reminder_mail_final']) && $legacySettings['reminder_mail_final'] !== '') {
                $globalLeadTimes[] = (int) $legacySettings['reminder_mail_final'];
            } else {
                $globalLeadTimes[] = 10;
            }

            $globalLeadTimes = array_values(array_unique($globalLeadTimes));
            rsort($globalLeadTimes);
        }

        $this->info("Global Lead Times: " . implode(', ', $globalLeadTimes) . " min");

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

                    // Determine lead times to use: practitioner-custom or global fallback
                    $rawLeadTime = $booking->practitioner ? $booking->practitioner->getRawOriginal('reminder_lead_time') : null;
                    if (!empty($rawLeadTime)) {
                        $leadTimes = $booking->practitioner->reminder_lead_time;
                    } else {
                        $leadTimes = $globalLeadTimes;
                    }

                    foreach ($leadTimes as $leadTime) {
                        $customSubject = "Session Reminder ({$leadTime} Min): #{$booking->invoice_no} ({$sessionIdStr})";
                        if ($leadTime >= 60 && $leadTime % 60 === 0) {
                            $hrs = $leadTime / 60;
                            if ($hrs >= 24 && $hrs % 24 === 0) {
                                $days = $hrs / 24;
                                $customSubject = "Session Reminder ({$days} Day" . ($days != 1 ? 's' : '') . "): #{$booking->invoice_no} ({$sessionIdStr})";
                            } else {
                                $customSubject = "Session Reminder ({$hrs} Hour" . ($hrs != 1 ? 's' : '') . "): #{$booking->invoice_no} ({$sessionIdStr})";
                            }
                        }
                        
                        $isFinal = ($leadTime === min($leadTimes));

                        $isMissedRetry = false;
                        if ($isFinal && $diff < -15) {
                            // Session passed — retry only if there were failures, within 24 h
                            $failedAttempts = \App\Models\EmailLog::where('booking_id', $booking->id)
                                ->where('to', $booking->user->email)
                                ->where('subject', $customSubject)
                                ->where('status', 'error')
                                ->count();

                            if ($failedAttempts > 0 && $diff > -1440) {
                                $isMissedRetry = true;
                            } else {
                                continue;
                            }
                        }

                        if ($isFinal) {
                            $inWindow = ($diff <= ($leadTime + 1) && $diff >= -15) || $isMissedRetry;
                        } else {
                            $inWindow = ($diff >= ($leadTime - 5) && $diff <= ($leadTime + 5));
                        }

                        if ($inWindow) {
                            $sentTotal += $this->dispatchToRecipients(
                                $recipients, $booking, $session, $videoLink,
                                $customSubject, $sessionIdStr, $isMissedRetry
                            );
                        } elseif ($startTime->isFuture() && $diff > ($leadTime + ($isFinal ? 1 : 5))) {
                            // Reminder window hasn't arrived yet
                            $allSessionsSentOrPassed = false;
                        }
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
