<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safes', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('building_id')->nullable()->constrained('buildings');
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->integer('number_drawers')->default(1);
            $table->integer('number_of_locks')->default(1);
            $table->string('grade')->nullable();
            $table->json('drawers')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safes');
    }
};
