<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbound_user_inprocessing_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_user_id')->cascadeOnDelete();
            $table->foreignId('inprocessing_organization_id')->cascadeOnDelete();
            $table->foreignId('inprocessing_action_id')->cascadeOnDelete();
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_user_inprocessing_action');
    }
};
