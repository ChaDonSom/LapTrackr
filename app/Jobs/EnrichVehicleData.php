<?php

namespace App\Jobs;

use App\Models\Vehicle;
use App\Services\AiEnrichmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnrichVehicleData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Vehicle $vehicle
    ) {}

    public function handle(AiEnrichmentService $service): void
    {
        $service->enrichVehicle($this->vehicle);
    }
}
