<?php

use App\Enums\RankType;
use App\Models\Branch;
use App\Models\Rank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('abbr');
            $table->string('type')->index();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }

    protected function seed(): void
    {
        $usaf = Branch::where('abbr', 'USAF')->first();

        Rank::create([
            'name' => '2nd Lieutenant',
            'abbr' => '2d Lt',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => '1st Lieutenant',
            'abbr' => '1st Lt',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Captain',
            'abbr' => 'Capt',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Major',
            'abbr' => 'Maj',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Lieutenant Colonel',
            'abbr' => 'Lt Col',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Colonel',
            'abbr' => 'Col',
            'type' => RankType::OFFICER,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Airman Basic',
            'abbr' => 'AB',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Airman',
            'abbr' => 'Amn',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Airman First Class',
            'abbr' => 'A1C',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Senior Airman',
            'abbr' => 'SrA',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Staff Sergeant',
            'abbr' => 'SSgt',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Technical Sergeant',
            'abbr' => 'TSgt',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Master Sergeant',
            'abbr' => 'MSgt',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Senior Master Sergeant',
            'abbr' => 'SMSgt',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Chief Master Sergeant',
            'abbr' => 'CMSgt',
            'type' => RankType::ENLISTED,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Civilian',
            'abbr' => 'Civ',
            'type' => RankType::CIVILIAN,
            'branch_id' => $usaf->id,
        ]);

        Rank::create([
            'name' => 'Other',
            'abbr' => 'Other',
            'type' => RankType::OTHER,
            'branch_id' => $usaf->id,
        ]);
    }
};
