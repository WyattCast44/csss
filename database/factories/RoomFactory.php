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
        $number = fake()->numberBetween(1, 1000);

        return [
            'organization_id' => Organization::factory(),
            'building_id' => Building::factory(),
            'number' => $number,
            'name' => 'Room '.$number,
            'description' => fake()->optional()->sentence,
            'active' => fake()->boolean,
            'has_eal' => fake()->boolean,
            'has_safes' => fake()->boolean,
        ];
    }

    public function forOrganization(Organization $organization): self
    {
        return $this->state(function (array $attributes) use ($organization) {
            return ['organization_id' => $organization->id];
        });
    }

    public function forBuilding(Building $building): self
    {
        return $this->state(function (array $attributes) use ($building) {
            return ['building_id' => $building->id];
        });
    }
}
