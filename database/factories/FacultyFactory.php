<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Domain\Faculty\Models\Faculty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Faculty\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    protected $model = Faculty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(2),
            'code' => fake()->unique()->lexify('??'),
        ];
    }
}
