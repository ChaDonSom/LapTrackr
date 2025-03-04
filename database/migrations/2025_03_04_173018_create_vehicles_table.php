<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->string('game');  // Changed from enum to string
            $table->integer('year')->nullable();
            $table->integer('power')->nullable()->comment('Power in HP');
            $table->decimal('weight', 8, 2)->nullable()->comment('Weight in kg');
            $table->string('tire_size')->nullable();
            $table->boolean('ai_enriched')->default(false);
            $table->json('ai_metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
