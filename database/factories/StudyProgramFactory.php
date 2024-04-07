<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Program\Models\Program>
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
            'lriCode' => fake()->randomNumber(5),
            'level' => fake()->randomElement(['pamatstudijas', 'augstākā līmeņa studijas']),
        ];
    }
}
