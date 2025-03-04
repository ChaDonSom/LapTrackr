<?php

namespace App\Filament\Resources\LapResource\Pages;

use App\Filament\Resources\LapResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaps extends ListRecords
{
    protected static string $resource = LapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}