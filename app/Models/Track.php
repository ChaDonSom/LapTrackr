<?php

namespace App\Models;

use App\Jobs\EnrichTrackData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Track extends Model
{
    protected $fillable = [
        'name',
        'location',
        'ai_metadata',
        'ai_enriched'
    ];

    protected $casts = [
        'ai_metadata' => AsArrayObject::class,
        'ai_enriched' => 'boolean'
    ];

    protected static function booted()
    {
        static::created(function ($track) {
            EnrichTrackData::dispatch($track);
        });
    }

    public function laps()
    {
        return $this->hasMany(Lap::class);
    }

    // Helper methods to access AI metadata
    public function getDirection()
    {
        return $this->ai_metadata['direction'] ?? null;
    }

    public function getLength()
    {
        return $this->ai_metadata['length_km'] ?? null;
    }

    public function getNumberOfTurns()
    {
        return $this->ai_metadata['number_of_turns'] ?? null;
    }

    public function getSurfaceType()
    {
        return $this->ai_metadata['surface_type'] ?? null;
    }

    public function getElevationChange()
    {
        return $this->ai_metadata['elevation_change_meters'] ?? null;
    }
}
