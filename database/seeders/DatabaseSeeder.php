<?php

namespace Database\Seeders;

use App\Models\Base;
use App\Models\Building;
use App\Models\GlobalTraining;
use App\Models\InprocessingAction;
use App\Models\Organization;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $systemUser = User::getSystemUser();
        $systemOrg = Organization::getSystemOrganization();

        $bases = Base::factory(5)
            ->has(Building::factory(fake()->numberBetween(2, 5))->forOrganization($systemOrg))
            ->create();

        $buildings = Building::all();

        $buildings->each(function ($building) use ($systemOrg) {
            Room::factory(fake()->numberBetween(2, 5))
                ->forOrganization($systemOrg)
                ->forBuilding($building)
                ->create();
        });

        $bases->each(function ($base) {
            Organization::factory(fake()->numberBetween(2, 5))
                ->create();
        });

        $organizations = Organization::all();

        // create buildings and rooms for each organization
        $organizations->each(function ($organization) use ($bases) {
            $buildings = Building::factory(fake()->numberBetween(1, 3))
                ->forOrganization($organization)
                ->forBase($bases->random())
                ->create();

            $buildings->each(function ($building) use ($organization) {
                Room::factory(fake()->numberBetween(2, 5))
                    ->forOrganization($organization)
                    ->forBuilding($building)
                    ->create();
            });
        });

        // create inprocessing actions for each organization
        $organizations->each(function ($organization) {
            InprocessingAction::factory(fake()->numberBetween(3, 10))
                ->forOrganization($organization)
                ->create();
        });

        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@us.af.mil',
        ]);

        // $users = User::factory(10)->create();

        // $trainings = GlobalTraining::factory(10)->create();

        // $user->fitnessTests()->create([
        //     'date' => now()->subYear(),
        //     'results' => [
        //         'pushups' => 100,
        //         'situps' => 100,
        //     ],
        //     'score' => 100,
        //     'notes' => 'Test notes',
        //     'test_location' => 'Test location',
        //     'passed' => true,
        //     'next_test_date' => now()->addYear(),
        //     'next_test_created' => false,
        // ]);

        // $organization = Organization::create([
        //     'name' => '15th Air Force',
        //     'abbr' => '15AF',
        //     'slug' => '15th-air-force',
        //     'approved' => true,
        // ]);

        // InprocessingAction::factory(10)->forOrganization($organization)->create();

        // $user->organizations()->attach($organization);
    }
}
