<?php

use App\Models\CommissionSource;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_sources', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name');
            $table->string('abbr', 10)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        CommissionSource::create([
            'name' => 'Reserve Officer Training Corps',
            'abbr' => 'ROTC',
        ]);

        CommissionSource::create([
            'name' => 'Direct Commission',
            'abbr' => 'DC',
        ]);

        CommissionSource::create([
            'name' => 'Officer Training School',
            'abbr' => 'OTS',
        ]);

        CommissionSource::create([
            'name' => 'Military Academy',
            'abbr' => 'MA',
        ]);

        CommissionSource::create([
            'name' => 'Not Applicable',
            'abbr' => 'N/A',
        ]);

        CommissionSource::create([
            'name' => 'Other',
            'abbr' => 'OTH',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_sources');
    }
};
