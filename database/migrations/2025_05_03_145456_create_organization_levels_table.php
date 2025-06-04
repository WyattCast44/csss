<?php

use App\Models\Branch;
use App\Models\OrganizationLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_levels', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('abbr');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        $branch = Branch::where('abbr', 'USAF')->first();

        $levels = [
            [
                'name' => 'Flight',
                'abbr' => 'FLT',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Squadron',
                'abbr' => 'SQ',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Group',
                'abbr' => 'GP',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Wing',
                'abbr' => 'WG',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Major Command',
                'abbr' => 'MAJCOM',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Numbered Air Force',
                'abbr' => 'NAF',
                'branch_id' => $branch->id,
            ],
            [
                'name' => 'Service',
                'abbr' => 'SVC',
            ],
            [
                'name' => 'Other',
                'abbr' => 'OTH',
            ],
            [
                'name' => 'Not Applicable',
                'abbr' => 'N/A',
            ],
        ];

        foreach ($levels as $level) {
            OrganizationLevel::create($level);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_levels');
    }
};
