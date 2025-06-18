<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_access_lists', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->foreignId('organization_id')->constrained();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('building_id')->nullable()->constrained();
            $table->foreignId('room_id')->nullable()->constrained();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users'); // the user who created the access list
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('locked')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_access_lists');
    }
};
