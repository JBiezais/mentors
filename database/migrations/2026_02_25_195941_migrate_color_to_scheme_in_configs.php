<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schemeKeys = ['pink', 'blue', 'red', 'green', 'amber', 'indigo'];

        DB::table('configs')->where('type', 'color')->orderBy('id')->each(function ($config) use ($schemeKeys) {
            $value = $config->value;

            if (str_starts_with($value, '#') || ! in_array($value, $schemeKeys)) {
                DB::table('configs')
                    ->where('id', $config->id)
                    ->update(['value' => 'pink']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('configs')
            ->where('type', 'color')
            ->update(['value' => '#e085f9']);
    }
};
