<?php

namespace Database\Factories;

use App\Models\Base;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
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
            'name' => 'Building '.$number,
            'abbr' => 'BLDG'.$number,
            'address' => fake()->optional()->address,
            'base_id' => Base::factory(),
            'description' => fake()->optional()->sentence,
            'active' => fake()->boolean,
        ];
    }

    public function forOrganization(Organization $organization): self
    {
        return $this->state(function (array $attributes) use ($organization) {
            return ['organization_id' => $organization->id];
        });
    }

    public function forBase(Base $base): self
    {
        return $this->state(function (array $attributes) use ($base) {
            return ['base_id' => $base->id];
        });
    }
}
