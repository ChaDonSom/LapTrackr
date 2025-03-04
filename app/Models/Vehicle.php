<?php

namespace App\Models;

use App\Jobs\EnrichVehicleData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Vehicle extends Model
{
    protected $fillable = [
        'make',
        'model',
        'transmission',
        'drive_type',
        'game',
        'ai_metadata',
        'ai_enriched'
    ];

    protected $casts = [
        'ai_metadata' => AsArrayObject::class,
        'ai_enriched' => 'boolean'
    ];

    /**
     * List of known games that can be used as default options
     */
    public static $knownGames = [
        'beamng.drive' => 'BeamNG.drive',
        'assetto_corsa' => 'Assetto Corsa',
        'real_life' => 'Real Life',
        'iracing' => 'iRacing',
        'project_cars_2' => 'Project CARS 2',
        'acc' => 'Assetto Corsa Competizione',
        'forza_motorsport' => 'Forza Motorsport',
        'gran_turismo_7' => 'Gran Turismo 7',
        'rfactor_2' => 'rFactor 2'
    ];

    protected static function booted()
    {
        static::created(function ($vehicle) {
            EnrichVehicleData::dispatch($vehicle);
        });
    }

    public function laps()
    {
        return $this->hasMany(Lap::class);
    }

    // Helper methods to access AI metadata
    public function getYear()
    {
        return $this->ai_metadata['year'] ?? null;
    }

    public function getPower()
    {
        return $this->ai_metadata['power_hp'] ?? null;
    }

    public function getWeight()
    {
        return $this->ai_metadata['weight_kg'] ?? null;
    }

    public function getTireSize()
    {
        return $this->ai_metadata['typical_tire_size'] ?? null;
    }

    public function getCommonMods()
    {
        return $this->ai_metadata['common_modifications'] ?? null;
    }
}
