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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('direction')->nullable();
            $table->decimal('length', 8, 3)->nullable()->comment('Length in kilometers');
            $table->integer('number_of_turns')->nullable();
            $table->string('surface_type')->nullable();
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
        Schema::dropIfExists('tracks');
    }
};
