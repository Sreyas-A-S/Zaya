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

        // Start SVG content
        $svg = '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Background
        $svg .= '<rect width="100%" height="100%" fill="white" />';

        // Add some noise (lines)
        for ($i = 0; $i < 10; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="gray" stroke-width="1" opacity="0.3" />';
        }

        // Add some noise (circles)
        for ($i = 0; $i < 5; $i++) {
            $cx = rand(0, $width);
            $cy = rand(0, $height);
            $r = rand(5, 20);
            $svg .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="none" stroke="gray" stroke-width="1" opacity="0.2" />';
        }

        // Add text characters
        $codeLength = strlen($code);
        $charSpacing = $width / ($codeLength + 1);
        
        for ($i = 0; $i < $codeLength; $i++) {
            $char = $code[$i];
            $x = ($i + 0.5) * $charSpacing + rand(-5, 5);
            $y = ($height / 2) + 10 + rand(-5, 5);
            $rotate = rand(-25, 25);
            
            // Randomly choose between black and a dark gray
            $color = rand(0, 1) ? '#000000' : '#333333';
            
            $svg .= '<text x="' . $x . '" y="' . $y . '" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="' . $color . '" text-anchor="middle" transform="rotate(' . $rotate . ', ' . $x . ', ' . $y . ')">' . $char . '</text>';
        }

        // Add more noise (dots)
        for ($i = 0; $i < 50; $i++) {
            $cx = rand(0, $width);
            $cy = rand(0, $height);
            $svg .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="0.8" fill="gray" opacity="0.5" />';
        }

        $svg .= '</svg>';

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
