<?php

namespace App\Services;

use App\Models\EmailLog;
use Illuminate\Support\Facades\Log;

class EmailLoggerService
{
    /**
     * Log an email attempt.
     *
     * @param string $to
     * @param string $subject
     * @param string|null $body
     * @param string $status 'success' or 'error'
     * @param string|null $errorMessage
     * @param int|null $bookingId
     * @return EmailLog
     */
    public static function log($to, $subject, $body, $status, $errorMessage = null, $duration = null, $bookingId = null)
    {
        try {
            return EmailLog::create([
                'booking_id' => $bookingId,
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
                'status' => $status,
                'duration' => $duration,
                'error_message' => $errorMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write email log: ' . $e->getMessage());
            return null;
        }
    }
}
