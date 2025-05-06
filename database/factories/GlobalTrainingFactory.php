<?php

namespace Database\Factories;

use App\Enums\TrainingFrequency;
use App\Models\TrainingFormat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlobalTraining>
 */
class GlobalTrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formats = TrainingFormat::all();

        $startDate = fake()->optional()->date();

        $endDate = $startDate ? fake()->dateTimeBetween($startDate, '+2 years') : null;

        $active = $startDate && $endDate ? fake()->boolean(50) : true;

        return [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'url' => fake()->url(),
            'url_text' => fake()->sentence(),
            'source_document_url' => fake()->url(),
            'source_document_text' => fake()->sentence(),
            'format_id' => $formats->random()->id,
            'frequency' => fake()->randomElement(TrainingFrequency::cases()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'active' => $active,
        ];
    }

    public function withFormat(TrainingFormat $format): self
    {
        return $this->state([
            'format_id' => $format->id,
        ]);
    }

    public function withStartDate(string $startDate): self
    {
        return $this->state([
            'start_date' => $startDate,
        ]);
    }

    public function withEndDate(string $endDate): self
    {
        return $this->state([
            'end_date' => $endDate,
        ]);
    }

    public function active(bool $active = true): self
    {
        return $this->state([
            'active' => $active,
        ]);
    }
}
