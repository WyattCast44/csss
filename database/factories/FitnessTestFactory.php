<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FitnessTest>
 */
class FitnessTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => fake()->dateBetween('-1 year', 'now'),
            'results' => [],
            'score' => fake()->randomFloat(2, 0, 100),
            'notes' => fake()->optional()->sentence(),
            'test_location' => fake()->optional()->city(),
            'passed' => fake()->boolean(),
            'attachments' => [],
            'next_test_date' => fake()->dateBetween('now', '+1 year'),
        ];
    }
}
