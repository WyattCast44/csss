<?php

use App\Models\ProcessingActionCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processing_action_categories', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        $categories = [
            'General',
            'Security',
            'Medical',
            'Legal',
            'Financial',
            'Logistics',
            'Administrative',
            'Other',
            'Personnel',
            'Networks',
        ];

        foreach ($categories as $category) {
            ProcessingActionCategory::create([
                'name' => $category,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('processing_action_categories');
    }
};
