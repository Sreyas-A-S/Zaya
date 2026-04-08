<?php

namespace App\Jobs;

use App\Models\Conference;
use App\Support\Google\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UploadZegoRecordingToGoogleDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [60, 300, 900];

    public function __construct(public int $conferenceId)
    {
    }

    public function handle(GoogleDriveService $drive): void
    {
        if (!$drive->isConfigured()) {
            return;
        }

        $conference = Conference::with('booking')->find($this->conferenceId);
        if (!$conference) {
            return;
        }

        $metadata = $conference->metadata ?? [];
        if (!empty($metadata['google_drive_file_id'])) {
            return;
        }

        $sourceUrl = (string) ($metadata['zego_recording_file_url'] ?? $conference->recording_url ?? '');
        if ($sourceUrl === '') {
            return;
        }

        $tmpDir = storage_path('app/tmp/zego-recordings');
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        $booking = $conference->booking;
        $safeRoom = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) ($conference->room_name ?? 'room'));
        $safeRoom = trim((string) $safeRoom, '-');
        $fileName = 'zego-' . ($booking?->invoice_no ?: $conference->id) . '-' . ($safeRoom ?: 'room') . '.mp4';
        $tmpPath = $tmpDir . DIRECTORY_SEPARATOR . $fileName;

        try {
            $download = Http::timeout(120)
                ->retry(3, 2000)
                ->withOptions(['sink' => $tmpPath])
                ->get($sourceUrl);

            if (!$download->successful() || !is_file($tmpPath) || filesize($tmpPath) === 0) {
                throw new \RuntimeException('Failed to download recording from source URL.');
            }

            $uploaded = $drive->uploadResumable($tmpPath, $fileName, 'video/mp4');

            $metadata = array_merge($metadata, [
                'zego_recording_file_url' => $sourceUrl,
                'google_drive_file_id' => $uploaded['file_id'],
                'google_drive_preview_url' => $uploaded['preview_url'],
                'google_drive_web_view_link' => $uploaded['web_view_link'],
                'google_drive_web_content_link' => $uploaded['web_content_link'],
                'google_drive_thumbnail_link' => $uploaded['thumbnail_link'],
                'google_drive_uploaded_at' => now()->toIso8601String(),
            ]);

            $conference->update([
                'recording_url' => $uploaded['preview_url'],
                'metadata' => $metadata,
            ]);

            if ($booking) {
                $booking->update([
                    'recording_url' => $uploaded['preview_url'],
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Google Drive upload failed for ZEGOCLOUD recording', [
                'conference_id' => $conference->id,
                'source_url' => $sourceUrl,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            if (is_file($tmpPath)) {
                @unlink($tmpPath);
            }
        }
    }
}

