<?php

use App\Models\PurchaseCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_categories', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('organization_id')->nullable()->constrained('organizations');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        $categories = [
            'Office Supplies',
            'Building Supplies',
            'Technology / IT',
            'Software / Apps',
            'Services',
            'Furniture',
            'Transportation',
            'Other',
        ];

        foreach ($categories as $category) {
            PurchaseCategory::create(['name' => $category]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_categories');
    }
};
