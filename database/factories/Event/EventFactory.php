<?php

namespace Database\Factories\Event;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Domain\Event\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Event\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'mentors_training' => false,
            'mentees_applying' => false,
            'link' => fake()->url(),
            'sent' => false,
        ];
    }
}
