<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InboundUser>
 */
class InboundUserFactory extends Factory
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
            'report_date' => now()->addDays(fake()->numberBetween(1, 30)),
            'losing_organization_id' => Organization::factory(),
            'sponsor_id' => fake()->boolean() ? User::factory() : null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
