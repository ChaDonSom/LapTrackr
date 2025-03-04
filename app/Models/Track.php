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

    protected $appends = [
        'direction',
        'length',
        'number_of_turns',
        'surface_type',
        'elevation_change'
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

    // Helper methods to access AI metadata as attributes
    public function getDirectionAttribute()
    {
        return $this->ai_metadata['direction'] ?? null;
    }

    public function getLengthAttribute()
    {
        return $this->ai_metadata['length_km'] ?? null;
    }

    public function getNumberOfTurnsAttribute()
    {
        return $this->ai_metadata['number_of_turns'] ?? null;
    }

    public function getSurfaceTypeAttribute()
    {
        return $this->ai_metadata['surface_type'] ?? null;
    }

    public function getElevationChangeAttribute()
    {
        return $this->ai_metadata['elevation_change_meters'] ?? null;
    }
}
