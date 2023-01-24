<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'faculty_id' => 1,
            'program_id' => 1,
            'mentor_id' => fake()->randomNumber(1),
            'name' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'phone' => fake()->phoneNumber,
            'email' => fake()->email,
            'comment' => fake()->paragraph,
            'lang' => fake()->randomElement([1,2,3]),
            'privacy' => 1
        ];
    }
}
