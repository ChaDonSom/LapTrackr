<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\Track;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('location'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('location'),
            Tables\Columns\TextColumn::make('getDirection')->label('Direction'),
            Tables\Columns\TextColumn::make('getLength')->label('Length (km)'),
            Tables\Columns\TextColumn::make('getNumberOfTurns')->label('Turns'),
            Tables\Columns\TextColumn::make('getSurfaceType')->label('Surface'),
            Tables\Columns\IconColumn::make('ai_enriched')
                ->boolean()
                ->label('AI Enhanced'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}
