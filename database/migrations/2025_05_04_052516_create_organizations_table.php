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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->index();
            // General Information
            $table->string('name')->index(); // example: 15th Test And Evaluation Squadron
            $table->string('abbr')->index(); // example: 15TES
            $table->string('slug')->unique(); // example: 15tes
            $table->text('description')->nullable(); // example: The 15th Test and Evaluation Squadron is responsible for the testing and evaluation of the Air Force's weapons systems.

            // Location / Unit Information
            $table->string('pas_code')->nullable(); // example: NUC15OS
            $table->json('mailing_addresses')->nullable();
            $table->json('physical_addresses')->nullable();

            // Contact Information
            $table->string('email');
            $table->json('phone_numbers')->nullable();

            // Misc
            $table->string('avatar')->nullable(); // example: https://example.com/logo.png

            // Approval
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
