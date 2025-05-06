<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->index();
            $table->string('dodid')->unique()->index();
            $table->string('name'); // basically display name
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();

            // Contact Information
            $table->json('phone_numbers')->nullable();
            $table->json('emails')->nullable();

            // military information
            $table->foreignId('branch_id')->nullable()->constrained();

            // organization information
            $table->foreignId('personal_organization_id')->nullable();
            $table->foreignId('current_organization_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
