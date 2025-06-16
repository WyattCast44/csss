<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_trainings', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('title');
            $table->string('description')->nullable();
            $table->text('url')->nullable();
            $table->string('url_text')->nullable();
            $table->string('source_document_url')->nullable();
            $table->string('source_document_text')->nullable();
            $table->foreignId('format_id')->nullable()->constrained('training_formats');
            $table->string('frequency', 50)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('deactivated')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_trainings');
    }
};
