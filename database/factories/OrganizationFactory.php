<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phones = [
            'main' => fake()->phoneNumber(),
            'alternate' => fake()->phoneNumber(),
            'fax' => fake()->phoneNumber(),
        ];

        $mailingAddresses = [
            'main' => fake()->address(),
            'alternate' => fake()->address(),
        ];

        $physicalAddresses = [
            'main' => fake()->address(),
            'alternate' => fake()->address(),
        ];

        return [
            'name' => fake()->company(),
            'abbr' => fake()->word(),
            'slug' => fake()->slug(),
            'description' => fake()->sentence(),
            'pas_code' => fake()->word(),
            'mailing_addresses' => fake()->boolean() ? $mailingAddresses : null,
            'physical_addresses' => fake()->boolean() ? $physicalAddresses : null,
            'email' => fake()->email(),
            'phone_numbers' => fake()->boolean() ? $phones : null,
            'avatar' => fake()->imageUrl(),
            'approved' => fake()->boolean(),
        ];
    }
}
