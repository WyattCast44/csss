<?php

use App\Models\TrainingFormat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_formats', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name');
            $table->string('abbr');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    protected function seed(): void
    {
        $formats = [
            [
                'name' => 'In Person',
                'abbr' => 'IN-PER',
                'description' => 'In Person Training',
            ],
            [
                'name' => 'Virtual',
                'abbr' => 'VIRT',
                'description' => 'Virtual Training',
            ],
            [
                'name' => 'Computer-Based Training',
                'abbr' => 'CBT',
                'description' => 'Computer-Based Training',
            ],
            [
                'name' => 'On-the-Job Training',
                'abbr' => 'OJT',
                'description' => 'On-the-Job Training',
            ],
            [
                'name' => 'In-House Training',
                'abbr' => 'IN-HOUSE',
                'description' => 'In-House Training',
            ],
            [
                'name' => 'Other',
                'abbr' => 'OTHER',
                'description' => 'Other Training',
            ],
            [
                'name' => 'Formal Training',
                'abbr' => 'FT',
                'description' => 'Formal Training',
            ],
        ];

        foreach ($formats as $format) {
            TrainingFormat::create($format);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_formats');
    }
};
