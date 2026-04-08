<?php

namespace App\Support\Google;

use GuzzleHttp\Client;

class GoogleDriveService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'timeout' => 120,
            'connect_timeout' => 20,
        ]);
    }

    public function isConfigured(): bool
    {
        return (string) config('services.google_drive.service_account_email') !== ''
            && (string) config('services.google_drive.service_account_private_key') !== '';
    }

    /**
     * Uploads a file to Google Drive and optionally makes it publicly previewable.
     *
     * @return array{file_id:string,web_view_link:?string,web_content_link:?string,thumbnail_link:?string,preview_url:string}
     */
    public function uploadResumable(string $filePath, string $fileName, string $mimeType = 'video/mp4', ?string $folderId = null): array
    {
        if (!is_file($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        $token = GoogleServiceAccount::accessToken([
            'https://www.googleapis.com/auth/drive',
        ]);

        $folderId = $folderId ?: (string) config('services.google_drive.folder_id');
        $metadata = [
            'name' => $fileName,
        ];
        if ($folderId !== '') {
            $metadata['parents'] = [$folderId];
        }

        $size = filesize($filePath) ?: 0;

        $startResponse = $this->http->request('POST', 'https://www.googleapis.com/upload/drive/v3/files', [
            'query' => [
                'uploadType' => 'resumable',
                'fields' => 'id,webViewLink,webContentLink,thumbnailLink',
            ],
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json; charset=UTF-8',
                'X-Upload-Content-Type' => $mimeType,
                'X-Upload-Content-Length' => (string) $size,
            ],
            'body' => json_encode($metadata),
        ]);

        $location = $startResponse->getHeaderLine('Location');
        if ($location === '') {
            throw new \RuntimeException('Google Drive resumable upload URL missing.');
        }

        $uploadResponse = $this->http->request('PUT', $location, [
            'headers' => [
                'Content-Type' => $mimeType,
                'Content-Length' => (string) $size,
            ],
            'body' => fopen($filePath, 'rb'),
        ]);

        $payload = json_decode((string) $uploadResponse->getBody(), true) ?: [];
        $fileId = (string) ($payload['id'] ?? '');
        if ($fileId === '') {
            throw new \RuntimeException('Google Drive upload failed (missing file id).');
        }

        if ((bool) config('services.google_drive.make_public', true)) {
            $this->http->request('POST', "https://www.googleapis.com/drive/v3/files/{$fileId}/permissions", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'type' => 'anyone',
                    'role' => 'reader',
                ]),
            ]);
        }

        $info = [];
        try {
            $infoResponse = $this->http->request('GET', "https://www.googleapis.com/drive/v3/files/{$fileId}", [
                'query' => [
                    'fields' => 'id,webViewLink,webContentLink,thumbnailLink',
                ],
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);
            $info = json_decode((string) $infoResponse->getBody(), true) ?: [];
        } catch (\Throwable) {
            $info = [];
        }

        $previewUrl = "https://drive.google.com/file/d/{$fileId}/preview";

        return [
            'file_id' => $fileId,
            'web_view_link' => $info['webViewLink'] ?? ($payload['webViewLink'] ?? null),
            'web_content_link' => $info['webContentLink'] ?? ($payload['webContentLink'] ?? null),
            'thumbnail_link' => $info['thumbnailLink'] ?? ($payload['thumbnailLink'] ?? null),
            'preview_url' => $previewUrl,
        ];
    }
}
