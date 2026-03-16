<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Upload a base64 encoded image.
     *
     * @param string $base64String
     * @param string $directory
     * @return string|null
     */
    protected function uploadBase64($base64String, $directory = 'profiles')
    {
        if (empty($base64String)) {
            return null;
        }

        try {
            $image_parts = explode(";base64,", $base64String);
            if (count($image_parts) < 2) {
                return null;
            }

            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'png';
            $image_base64 = base64_decode($image_parts[1]);
            
            $fileName = $directory . '/' . Str::random(20) . '.' . $image_type;

            Storage::disk('public')->put($fileName, $image_base64);

            return $fileName;
        } catch (\Exception $e) {
            \Log::error('Base64 Upload Error: ' . $e->getMessage());
            return null;
        }
    }
}
