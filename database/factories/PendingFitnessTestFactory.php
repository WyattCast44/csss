<?php

namespace Database\Factories;

use App\Models\FitnessTest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PendingFitnessTest>
 */
class PendingFitnessTestFactory extends Factory
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
            'due_date' => fake()->dateBetween('now', '+1 year'),
            'notes' => fake()->optional()->sentence(),
            'previous_test_id' => FitnessTest::factory(),
        ];
    }
}
