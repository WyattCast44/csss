<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Organization;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EntryAccessList>
 */
class EntryAccessListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => null,
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'building_id' => null,
            'room_id' => null,
            'created_by_user_id' => null,
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
            'active' => true,
            'locked' => false,
        ];
    }

    public function forOrganization(Organization $organization): self
    {
        return $this->state(function (array $attributes) use ($organization) {
            return [
                'organization_id' => $organization->id,
                'building_id' => Building::factory()->forOrganization($organization),
                'room_id' => Room::factory()->forOrganization($organization),
                'created_by_user_id' => User::factory(),
            ];
        });
    }
}
