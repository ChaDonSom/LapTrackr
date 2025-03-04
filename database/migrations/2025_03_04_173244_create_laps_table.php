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
        Schema::create('laps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('lap_time', 8, 3)->comment('Lap time in seconds');
            $table->decimal('sector1_time', 8, 3)->nullable();
            $table->decimal('sector2_time', 8, 3)->nullable();
            $table->decimal('sector3_time', 8, 3)->nullable();
            $table->json('conditions')->nullable()->comment('Weather, temperature, etc');
            $table->json('telemetry_data')->nullable();
            $table->timestamps();

            // Index for querying fastest laps
            $table->index(['track_id', 'vehicle_id', 'lap_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laps');
    }
};
