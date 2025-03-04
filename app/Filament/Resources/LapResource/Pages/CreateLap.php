<?php

namespace App\Filament\Resources\LapResource\Pages;

use App\Filament\Resources\LapResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLap extends CreateRecord
{
    protected static string $resource = LapResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
