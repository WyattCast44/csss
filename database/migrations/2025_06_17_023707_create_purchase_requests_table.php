<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->foreignId('organization_id')->constrained('organizations');
            $table->foreignId('user_id')->nullable()->constrained('users'); // the user who is requesting the purchase
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('category')->nullable(); // equipment, supplies, etc. move this to another model in a bit
            $table->integer('quantity')->nullable();
            $table->integer('unit_price')->nullable();
            $table->integer('est_total_price')->nullable();
            $table->text('money_source')->nullable(); // money source, e.g. "DOD", "DODAC", "DODAC-1", "DODAC-2", etc.
            $table->text('link')->nullable();
            $table->boolean('requires_contract')->default(false);
            $table->foreignId('building_id')->nullable()->constrained('buildings');
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->string('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled, completed, move this to a enum or model
            $table->string('approval_notes')->nullable();
            $table->foreignId('actioned_by_id')->nullable()->constrained('users');
            $table->timestamp('actioned_at')->nullable();
            $table->date('shipped_date')->nullable();
            $table->date('recieved_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
