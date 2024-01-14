<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use src\Domain\Program\Models\Program;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::factory(30)->create();
    }
}
