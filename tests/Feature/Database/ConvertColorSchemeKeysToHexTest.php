<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConvertColorSchemeKeysToHexTest extends TestCase
{
    use RefreshDatabase;

    protected function migrateUp(): void
    {
        $migration = include base_path('database/migrations/2026_02_26_000000_convert_color_scheme_keys_to_hex.php');
        $migration->up();
    }

    public function test_scheme_key_pink_becomes_hex(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => 'pink',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $config = DB::table('configs')->where('type', 'color')->first();
        $this->assertSame('#f43f5e', $config->value);
    }

    public function test_scheme_key_blue_becomes_hex(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => 'blue',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $config = DB::table('configs')->where('type', 'color')->first();
        $this->assertSame('#0ea5e9', $config->value);
    }

    public function test_valid_hex_remains_unchanged(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => '#10b981',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $config = DB::table('configs')->where('type', 'color')->first();
        $this->assertSame('#10b981', $config->value);
    }

    public function test_invalid_value_becomes_default_hex(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => 'invalid_scheme',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $config = DB::table('configs')->where('type', 'color')->first();
        $this->assertSame('#f43f5e', $config->value);
    }
}
