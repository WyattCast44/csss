<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('type_id')->constrained('leave_types');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->integer('duration');
            $table->string('notes')->nullable();

            // approval
            $table->boolean('requires_approval')->default(true);
            $table->foreignId('route_to')->nullable()->constrained('users');
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
