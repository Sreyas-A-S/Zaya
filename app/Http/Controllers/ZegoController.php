<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Booking;
use App\Support\ZEGO\ZegoErrorCodes;
use App\Support\ZEGO\ZegoServerAssistant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ZegoController extends Controller
{
    public function join(Request $request, string $channel, bool $isPublicMeeting = false)
    {
        $user = Auth::user();
        $isPublicMeeting = $isPublicMeeting || !$user;

        if (!$user) {
            $guestName = trim((string) $request->query('name', 'Guest'));
            $user = (object) [
                'id' => 0,
                'name' => $guestName !== '' ? $guestName : 'Guest',
                'email' => null,
                'role' => 'guest',
                'profile_pic' => null,
            ];
        }

        $roomId = $this->sanitizeRoomId($channel);
        $zegoAppId = (int) config('services.zego.app_id');
        $zegoError = null;
        $booking = Booking::where('id', function ($query) use ($channel) {
            $query->select('id')->from('bookings')->whereRaw("LOWER(REPLACE(invoice_no, '-', '')) = LOWER(?)", [str_replace('zaya-', '', $channel)]);
        })->orWhere('id', (int) str_replace('zaya-', '', $channel))->first();

        if ($zegoAppId <= 0 || trim((string) config('services.zego.server_secret')) === '') {
            $zegoError = 'ZEGOCLOUD configuration is incomplete. Set ZEGO_APP_ID and ZEGO_SERVER_SECRET in your .env file.';
        }

        return view('conference.zego', [
            'user' => $user,
            'channel' => $channel,
            'roomId' => $roomId,
            'zegoAppId' => $zegoAppId,
            'zegoError' => $zegoError,
            'booking' => $booking,
            'isPublicMeeting' => $isPublicMeeting,
        ]);
    }

    public function publicJoin(Request $request, string $channel)
    {
        return $this->join($request, $channel, true);
    }

    public function generateToken(Request $request, string $channel)
    {
        $roomId = $this->sanitizeRoomId($channel);
        $appId = (int) config('services.zego.app_id');
        $serverSecret = trim((string) config('services.zego.server_secret'));
        $effectiveTime = 3600;

        $user = Auth::user();
        if (!$user) {
            $guestName = trim((string) $request->input('name', $request->query('name', 'Guest')));
            $user = (object) [
                'id' => 0,
                'name' => $guestName !== '' ? $guestName : 'Guest',
                'role' => 'guest',
            ];
        }

        $userId = (string) ($request->input('user_id') ?: ($user->id ?: ('guest_' . substr(md5($channel . microtime(true)), 0, 8))));
        $token = ZegoServerAssistant::generateToken04($appId, $userId, $serverSecret, $effectiveTime, '');
        if (($token->code ?? -1) !== ZegoErrorCodes::success) {
            return response()->json([
                'success' => false,
                'message' => $token->message ?? 'Failed to generate ZEGOCLOUD token.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'token' => $token->token,
            'app_id' => $appId,
            'room_id' => $roomId,
            'user_id' => $userId,
        ]);
    }

    public function startRecording(Request $request, string $channel)
    {
        $roomId = $this->sanitizeRoomId($channel);
        $booking = $this->resolveBooking($channel);

        if (!$this->hasCloudRecordingStorageConfig()) {
            return response()->json([
                'success' => false,
                'message' => 'ZEGOCLOUD server-side recording requires AWS/S3 storage credentials. Fill AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, and AWS_BUCKET.',
            ], 422);
        }

        $conference = Conference::firstOrCreate(
            [
                'provider' => 'zegocloud',
                'room_name' => $roomId,
            ],
            [
                'booking_id' => $booking?->id,
                'start_time' => now(),
                'duration_minutes' => 0,
                'metadata' => [],
            ]
        );

        $existingTaskId = $conference->metadata['zego_recording_task_id'] ?? null;
        if ($existingTaskId) {
            return response()->json(['success' => true, 'task_id' => $existingTaskId, 'already_started' => true]);
        }

        $response = $this->zegoCloudRecordingRequest('StartRecord', [
            'RoomId' => $roomId,
            'RecordInputParams' => [
                'RecordMode' => 2,
                'StreamType' => 3,
                'ClientTaskId' => 'zaya-' . $booking->id . '-' . $roomId,
                'FillBlank' => true,
                'MaxIdleTime' => (int) config('services.zego.cloud_recording.max_idle_time', 30),
                'MaxRecordTime' => (int) config('services.zego.cloud_recording.max_record_time', 7200),
                'MixConfig' => [
                    'MixMode' => 2,
                    'IsAlwaysMix' => true,
                ],
            ],
            'RecordOutputParams' => [
                'OutputFileFormat' => 'mp4',
                'OutputFolder' => (string) config('services.zego.cloud_recording.output_folder', 'zego-recordings'),
                'OutputFileRule' => 1,
                'StorageParams' => [
                    'Vendor' => (int) config('services.zego.cloud_recording.storage_vendor', 1),
                    'Region' => (string) env('AWS_DEFAULT_REGION'),
                    'Bucket' => (string) env('AWS_BUCKET'),
                    'AccessKeyId' => (string) env('AWS_ACCESS_KEY_ID'),
                    'AccessKeySecret' => (string) env('AWS_SECRET_ACCESS_KEY'),
                ],
            ],
        ]);

        if (!$response['success']) {
            return response()->json(['success' => false, 'message' => $response['message']], 422);
        }

        $taskId = $response['data']['TaskId'] ?? null;
        $conference->update([
            'start_time' => now(),
            'metadata' => array_merge($conference->metadata ?? [], [
                'zego_recording_task_id' => $taskId,
                'zego_recording_status' => 'recording',
                'zego_recording_started_at' => now()->toIso8601String(),
            ]),
        ]);

        return response()->json(['success' => true, 'task_id' => $taskId]);
    }

    public function stopRecording(Request $request, string $channel)
    {
        $roomId = $this->sanitizeRoomId($channel);
        $booking = $this->resolveBooking($channel);

        $conference = Conference::where('provider', 'zegocloud')
            ->where('room_name', $roomId)
            ->when($booking, function ($query) use ($booking) {
                $query->where(function ($subQuery) use ($booking) {
                    $subQuery->where('booking_id', $booking->id)
                        ->orWhereNull('booking_id');
                });
            })
            ->latest('id')
            ->first();

        if (!$conference) {
            return response()->json(['success' => true, 'message' => 'No active recording task found for this conference.']);
        }

        $taskId = $request->input('task_id') ?: ($conference->metadata['zego_recording_task_id'] ?? null);
        if (!$taskId) {
            return response()->json(['success' => true, 'message' => 'Recording task was already cleared.']);
        }

        $stopResponse = $this->zegoCloudRecordingRequest('StopRecord', [
            'TaskId' => $taskId,
        ]);

        if (!$stopResponse['success']) {
            return response()->json(['success' => false, 'message' => $stopResponse['message']], 422);
        }

        $synced = $this->syncConferenceRecording($conference, $taskId);

        return response()->json([
            'success' => true,
            'task_id' => $taskId,
            'recording_url' => $conference->fresh()->recording_url,
            'pending' => empty($synced['recording_url']),
        ]);
    }

    public function syncRecordingStatus(Request $request, string $channel)
    {
        $roomId = $this->sanitizeRoomId($channel);
        $booking = $this->resolveBooking($channel);

        $conference = Conference::where('provider', 'zegocloud')
            ->where('room_name', $roomId)
            ->when($booking, function ($query) use ($booking) {
                $query->where(function ($subQuery) use ($booking) {
                    $subQuery->where('booking_id', $booking->id)
                        ->orWhereNull('booking_id');
                });
            })
            ->latest('id')
            ->first();

        if (!$conference) {
            return response()->json(['success' => false, 'message' => 'Conference not found.'], 404);
        }

        $taskId = $request->input('task_id') ?: ($conference->metadata['zego_recording_task_id'] ?? null);
        if (!$taskId) {
            return response()->json(['success' => true, 'recording_url' => $conference->recording_url]);
        }

        $synced = $this->syncConferenceRecording($conference, $taskId);

        return response()->json([
            'success' => true,
            'recording_url' => $synced['recording_url'] ?? $conference->fresh()->recording_url,
            'pending' => empty($synced['recording_url']),
        ]);
    }

    private function sanitizeRoomId(string $channel): string
    {
        $roomId = preg_replace('/[^A-Za-z0-9_-]+/', '-', trim($channel));
        $roomId = trim((string) $roomId, '-');

        return $roomId !== '' ? substr($roomId, 0, 128) : 'zaya-room';
    }

    private function resolveBooking(string $channel): ?Booking
    {
        return Booking::where('id', function ($query) use ($channel) {
            $query->select('id')->from('bookings')->whereRaw("LOWER(REPLACE(invoice_no, '-', '')) = LOWER(?)", [str_replace('zaya-', '', $channel)]);
        })->orWhere('id', (int) str_replace('zaya-', '', $channel))->first();
    }

    private function hasCloudRecordingStorageConfig(): bool
    {
        return (string) env('AWS_ACCESS_KEY_ID') !== ''
            && (string) env('AWS_SECRET_ACCESS_KEY') !== ''
            && (string) env('AWS_DEFAULT_REGION') !== ''
            && (string) env('AWS_BUCKET') !== '';
    }

    private function zegoCloudRecordingRequest(string $action, array $payload): array
    {
        $appId = (int) config('services.zego.app_id');
        $serverSecret = trim((string) config('services.zego.server_secret'));
        $timestamp = now()->timestamp;
        $nonce = bin2hex(random_bytes(8));
        $signature = md5($appId . $nonce . $serverSecret . $timestamp);
        $baseUrl = rtrim((string) config('services.zego.cloud_recording.api_base', 'https://cloudrecord-api.zego.im/'), '/');

        try {
            $response = Http::withQueryParameters([
                'Action' => $action,
                'AppId' => $appId,
                'SignatureNonce' => $nonce,
                'Timestamp' => $timestamp,
                'SignatureVersion' => '2.0',
                'Signature' => $signature,
            ])->acceptJson()->post($baseUrl, $payload);
        } catch (\Throwable $e) {
            \Log::error('ZEGOCLOUD cloud recording request failed', [
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'ZEGOCLOUD recording request failed: ' . $e->getMessage()];
        }

        $json = $response->json();
        if ($response->successful() && (int) ($json['Code'] ?? -1) === 0) {
            return ['success' => true, 'data' => $json['Data'] ?? []];
        }

        \Log::error('ZEGOCLOUD cloud recording API error', [
            'action' => $action,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return ['success' => false, 'message' => (string) ($json['Message'] ?? 'ZEGOCLOUD cloud recording request failed.')];
    }

    private function syncConferenceRecording(Conference $conference, string $taskId): array
    {
        $recordingUrl = null;
        $recordingStatus = 'stopped_pending_upload';

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $response = $this->zegoCloudRecordingRequest('DescribeRecordStatus', [
                'TaskId' => $taskId,
            ]);

            if ($response['success']) {
                $data = $response['data'] ?? [];
                $recordingStatus = match ((int) ($data['Status'] ?? 0)) {
                    2 => 'recording',
                    3 => 'ended',
                    4 => 'ended_abnormally',
                    5 => 'paused',
                    default => $recordingStatus,
                };
                foreach (($data['RecordFiles'] ?? []) as $file) {
                    if (!empty($file['FileUrl']) && in_array((int) ($file['Status'] ?? 0), [3, 4], true)) {
                        $recordingUrl = $file['FileUrl'];
                        $recordingStatus = (int) ($file['Status'] ?? 0) === 3 ? 'uploaded' : 'uploaded_to_backup_storage';
                        break 2;
                    }
                }
            }

            sleep(2);
        }

        $conference->update([
            'end_time' => now(),
            'duration_minutes' => max(Carbon::parse($conference->start_time ?? now())->diffInMinutes(now()), 0),
            'recording_url' => $recordingUrl ?: $conference->recording_url,
            'metadata' => array_merge($conference->metadata ?? [], [
                'zego_recording_task_id' => $taskId,
                'zego_recording_status' => $recordingUrl ? $recordingStatus : $recordingStatus,
                'zego_recording_synced_at' => now()->toIso8601String(),
            ]),
        ]);

        if ($recordingUrl) {
            $conference->booking?->update(['recording_url' => $recordingUrl]);
        }

        return ['recording_url' => $recordingUrl];
    }
}
