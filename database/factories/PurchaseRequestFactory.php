<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Organization;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequest>
 */
class PurchaseRequestFactory extends Factory
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
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['equipment', 'supplies', 'furniture', 'electronics']),
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => fake()->numberBetween(100, 10000),
            'est_total_price' => fn (array $attributes) => $attributes['quantity'] * $attributes['unit_price'],
            'money_source' => fake()->randomElement(['DOD', 'DODAC', 'DODAC-1', 'DODAC-2']),
            'link' => fake()->url(),
            'requires_contract' => fake()->boolean(),
            'building_id' => Building::factory(),
            'room_id' => Room::factory(),
            'notes' => fake()->paragraph(),
            'attachments' => null,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'cancelled', 'completed']),
            'approval_notes' => fake()->optional()->paragraph(),
            'actioned_by_id' => User::factory(),
            'actioned_at' => fake()->optional()->dateTime(),
            'shipped_date' => fake()->optional()->date(),
            'recieved_date' => fake()->optional()->date(),
        ];
    }
}
