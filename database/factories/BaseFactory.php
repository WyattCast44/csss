<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base>
 */
class BaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branch = Branch::inRandomOrder()->first();

        $name = fake()->word();

        $base = match ($branch->abbr) {
            'USAF' => 'Air Force Base',
            'USA' => 'Army Base',
            'USN' => 'Navy Station',
            default => 'Base',
        };

        $baseAbbr = match ($branch->abbr) {
            'USAF' => 'AFB',
            'USA' => 'Ft',
            'USN' => 'NAS',
            default => '',
        };

        $baseAbbr = str($baseAbbr)->limit(1, '')->upper();

        $baseAppr = match ($branch->abbr) {
            'USAF' => str($name)->limit(1, '')->upper().$baseAbbr,
            'USA' => $baseAbbr.' '.str($name)->limit(1, '')->upper() ,
            'USN' => str($name)->limit(1, '')->upper().$baseAbbr,
            default => '',
        };

        return [
            'name' => Str::title($name.' '.$base),
            'abbr' => $baseAppr,
            'branch_id' => $branch->id,
            'icao_code' => str(fake()->lexify('????'))->upper(),
        ];
    }
}
