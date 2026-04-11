<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use src\Domain\Config\Services\AccentPaletteGenerator;

class AccentPaletteGeneratorTest extends TestCase
{
    public function test_valid_hex_returns_ten_shades(): void
    {
        $palette = AccentPaletteGenerator::fromHex('#f43f5e');

        $this->assertCount(10, $palette);
    }

    public function test_output_keys_are_50_through_900(): void
    {
        $palette = AccentPaletteGenerator::fromHex('#10b981');

        $expected = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
        $this->assertEquals($expected, array_keys($palette));
    }

    public function test_values_are_valid_hex_strings(): void
    {
        $palette = AccentPaletteGenerator::fromHex('#0ea5e9');

        foreach ($palette as $shade => $hex) {
            $this->assertMatchesRegularExpression('/^#[0-9a-f]{6}$/', $hex, "Shade {$shade} should be valid hex");
        }
    }

    public function test_shade_500_matches_base_color(): void
    {
        $baseHex = '#f43f5e';
        $palette = AccentPaletteGenerator::fromHex($baseHex);

        $this->assertEquals(strtolower($baseHex), $palette['500']);
    }

    public function test_invalid_hex_returns_default_palette(): void
    {
        $defaultPalette = [
            '50' => '#fff1f2', '100' => '#ffe4e6', '200' => '#fecdd3', '300' => '#fda4af',
            '400' => '#fb7185', '500' => '#f43f5e', '600' => '#e11d48', '700' => '#be123c',
            '800' => '#9f1239', '900' => '#881337',
        ];

        $palette = AccentPaletteGenerator::fromHex('#ff');
        $this->assertEquals($defaultPalette, $palette);

        $palette = AccentPaletteGenerator::fromHex('invalid');
        $this->assertEquals($defaultPalette, $palette);

        $palette = AccentPaletteGenerator::fromHex('gggggg');
        $this->assertEquals($defaultPalette, $palette);
    }

    public function test_hex_without_hash_is_accepted(): void
    {
        $palette = AccentPaletteGenerator::fromHex('f43f5e');

        $this->assertCount(10, $palette);
        $this->assertEquals('#f43f5e', $palette['500']);
    }

    public function test_is_valid_hex(): void
    {
        $this->assertTrue(AccentPaletteGenerator::isValidHex('#f43f5e'));
        $this->assertTrue(AccentPaletteGenerator::isValidHex('f43f5e'));
        $this->assertTrue(AccentPaletteGenerator::isValidHex('#FFFFFF'));
        $this->assertFalse(AccentPaletteGenerator::isValidHex('#ff'));
        $this->assertFalse(AccentPaletteGenerator::isValidHex('invalid'));
        $this->assertFalse(AccentPaletteGenerator::isValidHex(''));
    }
}
