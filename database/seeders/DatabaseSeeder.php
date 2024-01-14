<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Faculty;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->create([
             'name' => 'Juris Biezais',
             'email' => 'admin@biezais.lv',
             'password' => bcrypt('euR%J9&0zAg&')
         ]);

        User::factory()->create([
            'name' => 'Alise Rozentāle',
            'email' => 'aliroz@rsu.lv',
            'password' => bcrypt('Kapostins^135')
        ]);
    }
}
