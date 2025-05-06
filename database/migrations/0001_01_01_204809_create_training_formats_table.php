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
            $table->string('slug');
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
                'slug' => 'in-person',
                'description' => 'In Person Training',
            ],
            [
                'name' => 'Virtual',
                'slug' => 'virtual',
                'description' => 'Virtual Training',
            ],
            [
                'name' => 'Computer-Based Training',
                'slug' => 'cbt',
                'description' => 'Computer-Based Training',
            ],
            [
                'name' => 'On-the-Job Training',
                'slug' => 'ojt',
                'description' => 'On-the-Job Training',
            ],
            [
                'name' => 'In-House Training',
                'slug' => 'in-house',
                'description' => 'In-House Training',
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Other Training',
            ],
            [
                'name' => 'Formal Training',
                'slug' => 'formal-training',
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
