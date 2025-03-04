<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->json('ai_metadata')->nullable()->comment('AI-retrieved track details');
            $table->boolean('ai_enriched')->default(false)->comment('Whether AI has attempted to enrich this record');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->json('ai_metadata')->nullable()->comment('AI-retrieved vehicle details');
            $table->boolean('ai_enriched')->default(false)->comment('Whether AI has attempted to enrich this record');
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn(['ai_metadata', 'ai_enriched']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['ai_metadata', 'ai_enriched']);
        });
    }
};
