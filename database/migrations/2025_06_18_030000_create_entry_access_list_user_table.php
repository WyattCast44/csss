<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_access_list_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_access_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('added_by_user_id')->nullable()->constrained('users');
            $table->timestamp('added_at')->nullable();
            $table->timestamp('removed_at')->nullable();
            $table->foreignId('removed_by_user_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['entry_access_list_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_access_list_user');
    }
}; 