<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Domain\Program\Models\Program;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Program\Models\Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

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
            'level' => fake()->randomElement(['pamatstudijas', 'augstākā līmeņa studijas']),
        ];
    }
}
