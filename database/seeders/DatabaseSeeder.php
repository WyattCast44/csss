<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\GlobalTraining;
use App\Models\InprocessingAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $organizations = Organization::factory(10)->create();

        $organizations->each(function ($organization) {
            InprocessingAction::factory(fake()->numberBetween(3, 10))->forOrganization($organization)->create();
            Building::factory(fake()->numberBetween(3, 10))->forOrganization($organization)->create();
        });

        $users = User::factory(10)->create();

        $trainings = GlobalTraining::factory(10)->create();

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@us.af.mil',
        ]);

        $user->fitnessTests()->create([
            'date' => now()->subYear(),
            'results' => [
                'pushups' => 100,
                'situps' => 100,
            ],
            'score' => 100,
            'notes' => 'Test notes',
            'test_location' => 'Test location',
            'passed' => true,
            'next_test_date' => now()->addYear(),
            'next_test_created' => false,
        ]);

        $organization = Organization::create([
            'name' => '15th Air Force',
            'abbr' => '15AF',
            'slug' => '15th-air-force',
            'approved' => true,
        ]);

        InprocessingAction::factory(10)->forOrganization($organization)->create();

        $user->organizations()->attach($organization);
    }
}
