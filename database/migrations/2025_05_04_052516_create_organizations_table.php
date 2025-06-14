<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->json('pas_codes')->nullable(); // example: ["NUC15OS", "NUC15OS-1"]
            $table->json('mailing_addresses')->nullable();
            $table->json('physical_addresses')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->json('phone_numbers')->nullable();

            // Misc
            $table->string('avatar')->nullable();
            $table->boolean('personal')->default(false);
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('level_id')->nullable()->constrained('organization_levels');
            $table->foreignId('command_id')->nullable()->constrained('organization_commands');

            // Parent Organization
            $table->foreignId('parent_id')->nullable()->constrained('organizations');

            // Approval
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        // we need to create a default organization for the system that users can use as a fallback
        Organization::create([
            'name' => 'Not Applicable - Placeholder Organization',
            'abbr' => 'N/A',
            'slug' => 'na-org',
            'description' => 'This is a placeholder organization for users who are not associated with any other organization. It is used to prevent errors when users are not associated with any other organization.',
            'personal' => false,
            'approved' => true,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
