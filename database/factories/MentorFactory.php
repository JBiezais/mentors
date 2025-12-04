<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Domain\Mentor\Models\Mentor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Mentor\Models\Mentor>
 */
class MentorFactory extends Factory
{
    protected $model = Mentor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'faculty_id' => fake()->randomElement([1,2,3]),
            'program_id' => fake()->numberBetween(1,25),
            'name' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'phone' => fake()->phoneNumber,
            'email' => fake()->email,
            'about' => fake()->paragraph,
            'why' => fake()->paragraph,
            'mentees' => fake()->numberBetween(1,5),
            'year' => fake()->numberBetween(1,5),
            'lv' => fake()->boolean,
            'ru' => fake()->boolean,
            'en' => fake()->boolean,
            'privacy' => 1,
            'status' => 0,
            'key' => fake()->uuid(),
        ];
    }
}
