<?php

namespace src\Domain\Config\Services;

class AccentPaletteGenerator
{
    public const DEFAULT_HEX = '#f43f5e';

    private const SHADES = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];

    /**
     * Lightness values for each shade (0-100 scale). 500 uses base color's lightness.
     */
    private const LIGHTNESS_MAP = [
        '50' => 98,
        '100' => 95,
        '200' => 90,
        '300' => 80,
        '400' => 68,
        '500' => null, // base
        '600' => 55,
        '700' => 45,
        '800' => 35,
        '900' => 25,
    ];

    /**
     * Default palette (rose-like) for invalid hex fallback.
     */
    private const DEFAULT_PALETTE = [
        '50' => '#fff1f2',
        '100' => '#ffe4e6',
        '200' => '#fecdd3',
        '300' => '#fda4af',
        '400' => '#fb7185',
        '500' => '#f43f5e',
        '600' => '#e11d48',
        '700' => '#be123c',
        '800' => '#9f1239',
        '900' => '#881337',
    ];

    public static function fromHex(string $hex): array
    {
        $hex = self::normalizeHex($hex);
        if (!$hex || !self::isValidHex($hex)) {
            return self::DEFAULT_PALETTE;
        }

        [$r, $g, $b] = self::hexToRgb($hex);
        [$h, $s, $l] = self::rgbToHsl($r, $g, $b);

        $palette = [];
        foreach (self::SHADES as $shade) {
            $targetL = self::LIGHTNESS_MAP[$shade];
            $palette[$shade] = $targetL === null
                ? $hex
                : self::hslToHex($h, self::adjustSaturationForShade($s, $shade), $targetL);
        }

        return $palette;
    }

    public static function isValidHex(string $hex): bool
    {
        return (bool) preg_match('/^#?[0-9a-fA-F]{6}$/', $hex);
    }

    private static function normalizeHex(string $hex): string
    {
        $hex = trim($hex);
        if ($hex !== '' && $hex[0] !== '#') {
            $hex = '#' . $hex;
        }
        return strtolower($hex);
    }

    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        ];
    }

    private static function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max === $min) {
            return [0, 0, (int) round($l * 100)];
        }

        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

        switch ($max) {
            case $r:
                $h = (($g - $b) / $d) + ($g < $b ? 6 : 0);
                break;
            case $g:
                $h = (($b - $r) / $d) + 2;
                break;
            default:
                $h = (($r - $g) / $d) + 4;
        }
        $h /= 6;

        return [
            (int) round($h * 360),
            (int) round($s * 100),
            (int) round($l * 100),
        ];
    }

    private static function adjustSaturationForShade(int $s, string $shade): int
    {
        $lightShades = ['50', '100', '200'];
        if (in_array($shade, $lightShades, true)) {
            return (int) min(100, $s * 0.7);
        }
        return $s;
    }

    private static function hslToHex(int $h, int $s, int $l): string
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        if ($h < 60) {
            [$r, $g, $b] = [$c, $x, 0];
        } elseif ($h < 120) {
            [$r, $g, $b] = [$x, $c, 0];
        } elseif ($h < 180) {
            [$r, $g, $b] = [0, $c, $x];
        } elseif ($h < 240) {
            [$r, $g, $b] = [0, $x, $c];
        } elseif ($h < 300) {
            [$r, $g, $b] = [$x, 0, $c];
        } else {
            [$r, $g, $b] = [$c, 0, $x];
        }

        return sprintf(
            '#%02x%02x%02x',
            (int) round(($r + $m) * 255),
            (int) round(($g + $m) * 255),
            (int) round(($b + $m) * 255)
        );
    }
}
