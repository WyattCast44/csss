<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rank>
 */
class RankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
            'abbr' => fake()->word(),
            'type' => fake()->randomElement([Rank::TYPE_OFFICER, Rank::TYPE_ENLISTED, Rank::TYPE_CIVILIAN, Rank::TYPE_OTHER]),
            'branch_id' => Branch::factory(),
        ];
    }

    public function officer(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => Rank::TYPE_OFFICER,
        ]);
    }

    public function enlisted(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => Rank::TYPE_ENLISTED,
        ]);
    }

    public function civilian(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => Rank::TYPE_CIVILIAN,
        ]);
    }

    public function forBranch(Branch $branch): Factory
    {
        return $this->state(fn (array $attributes) => [
            'branch_id' => $branch->id,
        ]);
    }
}
