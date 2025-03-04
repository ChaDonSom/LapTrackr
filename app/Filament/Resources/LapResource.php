<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LapResource\Pages;
use App\Models\Lap;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LapResource extends Resource
{
    protected static ?string $model = Lap::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('track_id')
                ->relationship('track', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('vehicle_id')
                ->relationship('vehicle', 'model')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('lap_time')
                ->label('Lap Time (seconds)')
                ->numeric()
                ->required()
                ->step(0.001)
                ->hint('Format: seconds.milliseconds (e.g. 83.456)'),
            Forms\Components\TextInput::make('sector1_time')
                ->label('Sector 1 (seconds)')
                ->numeric()
                ->step(0.001),
            Forms\Components\TextInput::make('sector2_time')
                ->label('Sector 2 (seconds)')
                ->numeric()
                ->step(0.001),
            Forms\Components\TextInput::make('sector3_time')
                ->label('Sector 3 (seconds)')
                ->numeric()
                ->step(0.001),
            Forms\Components\KeyValue::make('conditions')
                ->label('Conditions')
                ->hint('Add weather, temperature, etc')
                ->keyLabel('Condition')
                ->valueLabel('Value')
                ->addable()
                ->deletable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('track.name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('vehicle.make')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('vehicle.model')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('formatted_lap_time')
                ->label('Lap Time')
                ->sortable('lap_time'),
            Tables\Columns\TextColumn::make('formatted_sector1')
                ->label('Sector 1'),
            Tables\Columns\TextColumn::make('formatted_sector2')
                ->label('Sector 2'),
            Tables\Columns\TextColumn::make('formatted_sector3')
                ->label('Sector 3'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('track')
                ->relationship('track', 'name')
                ->searchable()
                ->preload(),
            Tables\Filters\SelectFilter::make('vehicle')
                ->relationship('vehicle', 'model')
                ->searchable()
                ->preload(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaps::route('/'),
            'create' => Pages\CreateLap::route('/create'),
            'edit' => Pages\EditLap::route('/{record}/edit'),
        ];
    }
}