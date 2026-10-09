<?php
namespace App\Application;

/**
 * Mean luminance of an image, scaled 0..100.
 * Used as a proxy biomarker for MRI contrast uptake.
 */
class ImageIntensityService
{
    public function meanIntensity(string $filePath): int
    {
        $raw = @file_get_contents($filePath);
        $img = $raw !== false ? @imagecreatefromstring($raw) : false;
        if ($img === false) {
            throw new \RuntimeException('Unsupported image format (png/jpg only)');
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $sum = 0.0;
        $n   = 0;

        // Sample every 4th pixel — fast enough, statistically accurate
        for ($y = 0; $y < $h; $y += 4) {
            for ($x = 0; $x < $w; $x += 4) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b =  $rgb        & 0xFF;
                $sum += 0.299 * $r + 0.587 * $g + 0.114 * $b;
                $n++;
            }
        }
        imagedestroy($img);

        if ($n === 0) return 0;
        $mean255 = $sum / $n;              // 0..255
        return (int) round($mean255 / 2.55); // 0..100
    }
}