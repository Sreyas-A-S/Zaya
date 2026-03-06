<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        $code = strtoupper(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5));
        Session::put('captcha_code', $code);

        $width = 160;
        $height = 58; 
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 240, 240, 240); // Very light gray noise
        
        imagefilledrectangle($image, 0, 0, $width, $height, $white);

        // Subtle background noise
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $gray);
        }

        $codeLength = strlen($code);
        $fontSize = 5; // Built-in base font
        $charWidth = imagefontwidth($fontSize);
        $charHeight = imagefontheight($fontSize);
        
        // Scale factor
        $scale = 2.2;
        $scaledW = (int)($charWidth * $scale);
        $scaledH = (int)($charHeight * $scale);

        // Calculate total width with upscaled chars
        $spacing = $scaledW + 4; 
        $totalWidth = $spacing * $codeLength - 4;
        $x = ($width - $totalWidth) / 2;
        $y = ($height - $scaledH) / 2;

        for ($i = 0; $i < $codeLength; $i++) {
            $char = $code[$i];
            
            // Create a small temp canvas for one character
            $charImg = imagecreatetruecolor($charWidth, $charHeight);
            $cWhite = imagecolorallocate($charImg, 255, 255, 255);
            $cBlack = imagecolorallocate($charImg, 0, 0, 0);
            imagefilledrectangle($charImg, 0, 0, $charWidth, $charHeight, $cWhite);
            imagechar($charImg, $fontSize, 0, 0, $char, $cBlack);

            // Resample into main image
            $charX = $x + ($i * $spacing);
            $charY = $y + rand(-3, 3);
            
            imagecopyresampled(
                $image, $charImg, 
                (int)$charX, (int)$charY, 0, 0, 
                $scaledW, $scaledH, $charWidth, $charHeight
            );
            
            imagedestroy($charImg);
        }

        // Add overlapping noise (lines and circles)
        $noiseColor = imagecolorallocate($image, 100, 100, 100);
        for ($i = 0; $i < 6; $i++) {
            imageline($image, 0, rand(0, $height), $width, rand(0, $height), $noiseColor);
        }
        for ($i = 0; $i < 3; $i++) {
            imageellipse($image, rand(0, $width), rand(0, $height), rand(20, 60), rand(20, 40), $noiseColor);
        }

        ob_start();
        imagepng($image);
        $content = ob_get_clean();
        imagedestroy($image);

        return response($content)->header('Content-Type', 'image/png');
    }
}
