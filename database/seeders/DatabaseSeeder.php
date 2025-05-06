<?php

namespace Database\Seeders;

use App\Models\GlobalTraining;
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

        $users = User::factory(10)->create();

        $trainings = GlobalTraining::factory(10)->create();

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@us.af.mil',
        ]);
    }
}
