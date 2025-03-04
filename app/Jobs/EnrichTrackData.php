<?php

namespace App\Jobs;

use App\Models\Track;
use App\Services\AiEnrichmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnrichTrackData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Track $track
    ) {}

    public function handle(AiEnrichmentService $service): void
    {
        $service->enrichTrack($this->track);
    }
}
