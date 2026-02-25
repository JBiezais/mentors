<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MigrateColorToSchemeTest extends TestCase
{
    use RefreshDatabase;

    protected function migrateUp(): void
    {
        $migration = include base_path('database/migrations/2026_02_25_195941_migrate_color_to_scheme_in_configs.php');
        $migration->up();
    }

    public function test_hex_color_value_becomes_pink(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => '#e085f9',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $this->assertDatabaseHas('configs', [
            'type' => 'color',
            'value' => 'pink',
        ]);
    }

    public function test_another_hex_value_becomes_pink(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => '#ff0000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $this->assertDatabaseHas('configs', [
            'type' => 'color',
            'value' => 'pink',
        ]);
    }

    public function test_valid_scheme_key_remains_unchanged(): void
    {
        DB::table('configs')->insert([
            'type' => 'color',
            'value' => 'blue',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrateUp();

        $this->assertDatabaseHas('configs', [
            'type' => 'color',
            'value' => 'blue',
        ]);
    }
}
