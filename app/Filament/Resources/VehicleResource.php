<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('make')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('model')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('game')
                ->options(Vehicle::$knownGames)
                ->searchable()
                ->preload()
                ->required()
                ->createOptionAction(
                    fn(Forms\Components\Actions\Action $action) => $action->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Game')
                            ->required()
                    ])
                ),
            Forms\Components\Select::make('transmission')
                ->options(['manual' => 'Manual', 'auto' => 'Auto'])
                ->required(),
            Forms\Components\Select::make('drive_type')
                ->options(['FWD' => 'FWD', 'AWD' => 'AWD', 'RWD' => 'RWD'])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('make')->searchable(),
            Tables\Columns\TextColumn::make('model')->searchable(),
            Tables\Columns\TextColumn::make('game')->searchable(),
            Tables\Columns\TextColumn::make('year'),
            Tables\Columns\TextColumn::make('power')->label('Power (HP)'),
            Tables\Columns\TextColumn::make('weight')->label('Weight (kg)'),
            Tables\Columns\TextColumn::make('tire_size')->label('Tire Size'),
            Tables\Columns\IconColumn::make('ai_enriched')
                ->boolean()
                ->label('AI Enhanced'),
        ])->filters([
            Tables\Filters\SelectFilter::make('game')
                ->options(Vehicle::$knownGames)
                ->searchable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
