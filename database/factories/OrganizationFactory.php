<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Organization;
use App\Models\OrganizationCommand;
use App\Models\OrganizationLevel;
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
        $level = OrganizationLevel::inRandomOrder()->first();

        $number = fake()->numberBetween(11, 999);

        $type = fake()->randomElement([
            'Attack',
            'Bomb',
            'Combat Rescue',
            'Test and Evaluation',
            'Training',
        ]);

        $name = $number.' '.$type.' '.$level->name;

        $abbr = str($name)->limit(8, '')->upper();

        return [
            'name' => $name,
            'abbr' => $abbr,
            'description' => fake()->sentence(),
            'pas_codes' => null,
            'mailing_addresses' => null,
            'physical_addresses' => null,
            'email' => fake()->email(),
            'phone_numbers' => null,
            'avatar' => null,
            'personal' => false,
            'approved' => fake()->boolean(),
            'parent_id' => fake()->boolean() ? Organization::factory() : null,
            'branch_id' => Branch::inRandomOrder()->first()->id,
            'level_id' => $level->id,
            'command_id' => OrganizationCommand::inRandomOrder()->first()->id,
        ];
    }

    public function personal(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'personal' => true,
        ]);
    }

    public function approved(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'approved' => true,
        ]);
    }

    public function forBranch(Branch $branch): Factory
    {
        return $this->state(fn (array $attributes) => [
            'branch_id' => $branch->id,
        ]);
    }

    public function forParent(Organization $parent): Factory
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }

    public function forLevel(OrganizationLevel $level): Factory
    {
        return $this->state(fn (array $attributes) => [
            'level_id' => $level->id,
        ]);
    }

    public function forCommand(OrganizationCommand $command): Factory
    {
        return $this->state(fn (array $attributes) => [
            'command_id' => $command->id,
        ]);
    }
}
