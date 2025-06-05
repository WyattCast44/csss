<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbound_users', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('organization_id')->constrained('organizations');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('report_date')->nullable();
            $table->foreignId('losing_organization_id')->constrained('organizations');
            $table->foreignId('sponsor_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamp('inprocess_at')->nullable();
            $table->foreignId('inprocess_by_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_users');
    }
};
