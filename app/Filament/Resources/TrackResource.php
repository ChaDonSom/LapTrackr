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