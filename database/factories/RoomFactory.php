<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
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
            'name' => fake()->word,
            'description' => fake()->optional()->sentence,
            'building_id' => Building::factory(),
            'active' => fake()->boolean,
            'has_eal' => fake()->boolean,
            'has_safes' => fake()->boolean,
        ];
    }
}
