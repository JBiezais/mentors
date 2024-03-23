<?php

use Illuminate\Database\Migrations\Migration;
use src\Domain\User\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::query()->create([
            'name' => 'Luīze Driķīte',
            'email' => 'luize.drikite@rsu.lv',
            'password' => bcrypt('BurkaniZeme1'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
