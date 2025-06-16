<?php

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
        Schema::create('fitness_tests', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->date('date');
            $table->json('results')->nullable();
            $table->float('score')->nullable();
            $table->text('notes')->nullable();
            $table->text('test_location')->nullable();
            $table->boolean('passed')->nullable();
            $table->json('attachments')->nullable();
            $table->date('next_test_date')->nullable();
            $table->boolean('next_test_created')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fitness_tests');
    }
};
