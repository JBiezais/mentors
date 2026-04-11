<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const DEFAULT_HEX = '#f43f5e';

    /**
     * Scheme key to base hex (500 shade) mapping for migration. Preserved from color-schemes config.
     */
    private const SCHEME_TO_HEX = [
        'pink' => '#f43f5e',
        'blue' => '#0ea5e9',
        'red' => '#8e1719',
        'green' => '#10b981',
        'amber' => '#14b8a6',
        'indigo' => '#8b5cf6',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schemeKeys = array_keys(self::SCHEME_TO_HEX);

        DB::table('configs')->where('type', 'color')->orderBy('id')->each(function ($config) use ($schemeKeys) {
            $value = trim($config->value);

            if (preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
                return; // Already hex, keep it
            }

            $newValue = isset(self::SCHEME_TO_HEX[$value]) ? self::SCHEME_TO_HEX[$value] : self::DEFAULT_HEX;

            DB::table('configs')->where('id', $config->id)->update(['value' => $newValue]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse - old scheme keys are no longer used
    }
};
