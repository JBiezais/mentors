<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use src\Domain\Student\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::factory(50)->create();
    }
}
