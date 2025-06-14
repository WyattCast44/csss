<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttachedUser>
 */
class AttachedUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'user_id' => User::factory(),
            'attached_by_id' => User::factory(),
            'attached_at' => now(),
            'attached_until' => now()->addDays(30),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
