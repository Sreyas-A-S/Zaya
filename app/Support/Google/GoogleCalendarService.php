<?php

namespace App\Support\Google;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleCalendarService
{
    protected string $baseUrl = 'https://www.googleapis.com/calendar/v3';

    public function isConfigured(): bool
    {
        return (string) config('services.google_meet.master_account') !== '';
    }

    /**
     * Creates a Google Calendar event with a Google Meet link.
     *
     * @param string $summary Event title
     * @param string $startTime ISO8601 start time
     * @param int $durationMinutes Duration in minutes
     * @param array $attendees Array of email addresses
     * @return array{id:string,hangout_link:string}
     */
    public function createMeeting(string $summary, string $startTime, int $durationMinutes = 60, array $attendees = []): array
    {
        $masterEmail = (string) config('services.google_meet.master_account');
        
        $token = GoogleServiceAccount::accessToken(
            ['https://www.googleapis.com/auth/calendar', 'https://www.googleapis.com/auth/calendar.events'],
            3500,
            $masterEmail // Impersonate the master workspace account
        );

        $endTime = date(\DateTime::ISO8601, strtotime($startTime . " +{$durationMinutes} minutes"));

        $attendeeData = array_map(fn($email) => ['email' => $email], array_unique($attendees));

        $payload = [
            'summary' => $summary,
            'description' => 'Zaya Wellness Consultation Session',
            'start' => [
                'dateTime' => $startTime,
                'timeZone' => config('app.timezone', 'UTC'),
            ],
            'end' => [
                'dateTime' => $endTime,
                'timeZone' => config('app.timezone', 'UTC'),
            ],
            'attendees' => $attendeeData,
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => Str::uuid()->toString(),
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ],
        ];

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/calendars/primary/events?conferenceDataVersion=1", $payload);

        if (!$response->successful()) {
            Log::error('Google Calendar API Error:', [
                'status' => $response->status(),
                'body' => $response->json(),
                'master' => $masterEmail
            ]);
            throw new \RuntimeException('Failed to create Google Meet event: ' . ($response->json()['error']['message'] ?? 'Unknown error'));
        }

        $data = $response->json();

        return [
            'id' => $data['id'],
            'hangout_link' => $data['hangoutLink'] ?? '',
        ];
    }
}
