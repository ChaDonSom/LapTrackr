<?php

namespace App\Services;

use App\Models\Track;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEnrichmentService
{
    public function enrichTrack(Track $track): void
    {
        if ($track->ai_enriched) {
            return;
        }

        try {
            // Example API call to OpenAI or similar
            $response = Http::post(config('services.openai.endpoint'), [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a racing track information expert. Return JSON with these fields: direction, length_km, number_of_turns, surface_type, elevation_change_meters'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Tell me about the race track '{$track->name}' located in {$track->location}"
                    ]
                ]
            ]);

            $metadata = json_decode($response->body(), true);

            $track->update([
                'ai_metadata' => $metadata,
                'ai_enriched' => true
            ]);
        } catch (\Exception $e) {
            Log::error('AI track enrichment failed', [
                'track_id' => $track->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function enrichVehicle(Vehicle $vehicle): void
    {
        if ($vehicle->ai_enriched) {
            return;
        }

        try {
            // Example API call to OpenAI or similar
            $response = Http::post(config('services.openai.endpoint'), [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a vehicle specifications expert. Return JSON with these fields: year, power_hp, weight_kg, typical_tire_size, common_modifications'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Tell me about the {$vehicle->make} {$vehicle->model} in {$vehicle->game} format"
                    ]
                ]
            ]);

            $metadata = json_decode($response->body(), true);

            $vehicle->update([
                'ai_metadata' => $metadata,
                'ai_enriched' => true
            ]);
        } catch (\Exception $e) {
            Log::error('AI vehicle enrichment failed', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
