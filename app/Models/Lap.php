<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lap extends Model
{
    protected $fillable = [
        'track_id',
        'vehicle_id',
        'user_id',
        'lap_time',
        'sector1_time',
        'sector2_time',
        'sector3_time',
        'conditions',
        'telemetry_data',
    ];

    protected $casts = [
        'lap_time' => 'decimal:3',
        'sector1_time' => 'decimal:3',
        'sector2_time' => 'decimal:3',
        'sector3_time' => 'decimal:3',
        'conditions' => 'array',
        'telemetry_data' => 'array',
    ];

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedLapTimeAttribute(): string
    {
        return $this->formatTime($this->lap_time);
    }

    public function getFormattedSector1Attribute(): ?string
    {
        return $this->sector1_time ? $this->formatTime($this->sector1_time) : null;
    }

    public function getFormattedSector2Attribute(): ?string
    {
        return $this->sector2_time ? $this->formatTime($this->sector2_time) : null;
    }

    public function getFormattedSector3Attribute(): ?string
    {
        return $this->sector3_time ? $this->formatTime($this->sector3_time) : null;
    }

    protected function formatTime(float $timeInSeconds): string
    {
        $minutes = floor($timeInSeconds / 60);
        $seconds = $timeInSeconds % 60;
        return sprintf("%d:%06.3f", $minutes, $seconds);
    }
}