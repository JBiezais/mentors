<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use src\Domain\Mail\Models\Mail;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\src\Domain\Mail\Models\Mail>
 */
class MailFactory extends Factory
{
    protected $model = Mail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => fake()->randomElement(['verification', 'verificationPassed', 'mentorData', 'menteeData', 'custom']),
            'mentor_ids' => [],
            'student_ids' => [],
            'content' => fake()->paragraph(),
            'sent' => false,
        ];
    }
}
