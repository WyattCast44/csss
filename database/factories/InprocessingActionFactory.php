<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InprocessingAction>
 */
class InprocessingActionFactory extends Factory
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
            'name' => fake()->words(fake()->numberBetween(1, 3), true),
            'description' => fake()->optional()->sentence,
            'category' => fake()->optional()->word(),
            'active' => fake()->boolean(),
        ];
    }

    public function active(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    public function inactive(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    public function forOrganization(Organization $organization): Factory
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organization->id,
        ]);
    }
}
