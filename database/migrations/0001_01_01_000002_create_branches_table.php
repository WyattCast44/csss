<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name', 100)->unique()->index();
            $table->string('abbr', 10)->unique()->index();
            $table->string('short_name', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    protected function seed(): void
    {
        $branches = [
            ['name' => 'United States Army', 'abbr' => 'USA', 'short_name' => 'US Army'],
            ['name' => 'United States Navy', 'abbr' => 'USN', 'short_name' => 'US Navy'],
            ['name' => 'United States Air Force', 'abbr' => 'USAF', 'short_name' => 'US Air Force'],
            ['name' => 'United States Space Force', 'abbr' => 'USSF', 'short_name' => 'US Space Force'],
            ['name' => 'United States Marine Corps', 'abbr' => 'USMC', 'short_name' => 'US Marine Corps'],
            ['name' => 'United States Coast Guard', 'abbr' => 'USCG', 'short_name' => 'US Coast Guard'],
            ['name' => 'United States Department of Defense', 'abbr' => 'DOD', 'short_name' => 'US DoD'],
            ['name' => 'United States Goverment Schedule Employee', 'abbr' => 'GSE', 'short_name' => 'US GSE'],
            ['name' => 'Contractor', 'abbr' => 'CON', 'short_name' => 'Contractor'],
        ];

        foreach ($branches as $branch) {
            DB::table('branches')->insert(array_merge($branch, [
                // only have to do this because we're using the DB facade
                // and not Eloquent. Normally auto-generated ULIDs are used.
                'ulid' => str()->ulid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
