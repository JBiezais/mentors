<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'faculty_id' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(2),
            'code' => 'LPM',
//                fake()->randomElements(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'], fake()->numberBetween(2, 4)),
            'lriCode' => fake()->randomNumber(5),
            'level' => fake()->randomElement(['pamatstudijas', 'augstākā līmeņa studijas']),
        ];
    }
}
