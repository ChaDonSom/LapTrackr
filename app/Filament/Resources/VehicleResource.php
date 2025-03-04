public static function table(Table $table): Table
{
return $table->columns([
Tables\Columns\TextColumn::make('make')->searchable(),
Tables\Columns\TextColumn::make('model')->searchable(),
Tables\Columns\TextColumn::make('game'),
Tables\Columns\TextColumn::make('getYear')->label('Year'),
Tables\Columns\TextColumn::make('getPower')->label('Power (HP)'),
Tables\Columns\TextColumn::make('getWeight')->label('Weight (kg)'),
Tables\Columns\TextColumn::make('getTireSize')->label('Tire Size'),
Tables\Columns\IconColumn::make('ai_enriched')
->boolean()
->label('AI Enhanced'),
])->filters([
Tables\Filters\SelectFilter::make('game')
->options(['beamng.drive', 'assetto_corsa', 'real_life']),
]);
}