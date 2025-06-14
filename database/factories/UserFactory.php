<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $emailDomain = fake()->randomElement(['us.af.mil']);

        $email = fake()->unique()->userName().'@'.$emailDomain;

        return [
            'dodid' => fake()->regexify('[0-9]{10}'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_name' => fake()->firstName(), // middle name
            'nickname' => fake()->firstName(), // basically display name
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar' => null,
            'phone_numbers' => null,
            'emails' => null,
            'branch_id' => Branch::query()->inRandomOrder()->first()->id,
            'rank_id' => Rank::query()->inRandomOrder()->first()->id,
            'job_duty_code' => str(str()->random(6))->upper(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withBranch(Branch $branch): static
    {
        return $this->state(fn (array $attributes) => [
            'branch_id' => $branch->id,
        ]);
    }

    public function withRank(Rank $rank): static
    {
        return $this->state(fn (array $attributes) => [
            'rank_id' => $rank->id,
        ]);
    }

    public function withJobDutyCode(string $jobDutyCode): static
    {
        return $this->state(fn (array $attributes) => [
            'job_duty_code' => $jobDutyCode,
        ]);
    }
}
