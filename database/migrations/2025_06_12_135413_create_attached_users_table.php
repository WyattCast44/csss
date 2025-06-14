<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attached_users', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('organization_id')->constrained('organizations'); // the organization that the user is attached to
            $table->foreignId('user_id')->constrained('users'); // the user that is attached
            $table->foreignId('attached_by_id')->constrained('users');
            $table->timestamp('attached_at')->nullable(); // the date the user was attached
            $table->timestamp('attached_until')->nullable(); // the date until the user is attached
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('deleted_by_id')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attached_users');
    }
};
