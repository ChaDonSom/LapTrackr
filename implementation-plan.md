Below is the finalized, cohesive technical specification and implementation plan for "LapTrackr," written as a single, concrete, step-by-step guide optimized for execution by an AI agent (e.g., GitHub Copilot in Agent-mode with Claude). Each step includes explicit instructions, commands, and code snippets where applicable, ensuring the agent can follow sequentially with minimal ambiguity. The plan is ruthlessly optimized for efficiency, leverages the best tools, mitigates gotchas, and ensures all components work together seamlessly.

---

**Technical Specification & Implementation Plan: LapTrackr**

**Purpose**: A Laravel-based web app to track, analyze, and visualize lap times from racing sessions (real-life or simulated), with authentication, list views, charts, forms, screenshot ingestion, searchable attributes, performance metrics, and a custom stopwatch.

**Date**: March 04, 2025**Total Effort Estimate**: ~28-35 hours (reduced to ~14-18 hours with AI assistance)

**Tech Stack**

-   **Framework**: Laravel 11.x
-   **Database**: MySQL
-   **Frontend**: Livewire + Tailwind CSS
-   **Libraries**:
-   FilamentPHP 3.x (CRUD, UI)
-   Laravel Jetstream (auth)
-   Chart.js (charts, via Livewire)
-   thiagocordeiro/laravel-tesseract (OCR)
-   **Tools**: GitHub Copilot Agent-mode + Claude

---

**Step-by-Step Implementation Plan**

**✅ Step 1: Project Setup** (COMPLETED)

**Goal**: Bootstrap Laravel with required packages.**Duration**: 1-2 hours**Instructions**:

1. Create a new Laravel project:bash

```bash
composer create-project laravel/laravel LapTrackr
cd LapTrackr
```

2. Install FilamentPHP for admin panel and CRUD:bash

```bash
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels
```

3. Install Jetstream for authentication and Livewire for reactivity:bash

```bash
composer require laravel/jetstream livewire/livewire
php artisan jetstream:install livewire
```

4. Install Tesseract OCR for screenshot ingestion:bash

```bash
composer require thiagocordeiro/laravel-tesseract
```

5. Install frontend dependencies and run initial build:bash

```bash
npm install && npm run dev
```

6. Run initial migrations:bash

```bash
php artisan migrate
```

7. Note for agent: Ensure tesseract-ocr is installed on your local machine (apt-get install tesseract-ocr on Ubuntu or equivalent). Skip if unavailable; we’ll handle it in deployment.

✅ **Step 2: Configure Authentication (COMPLETED)**

**Goal**: Set up Jetstream for user auth and Filament for admin panel, avoiding conflicts.**Duration**: 1 hour**Instructions**:

1. Generate Filament panel:bash

```bash
php artisan make:filament-panel admin
```

2. Configure Filament to use Jetstream’s auth in app/Providers/Filament/AdminPanelProvider.php:php

```php
use App\Models\User;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
return $panel
->default()
->id('admin')
->path('admin')
->login()
->authGuard('web')
->authModel(User::class)
->middleware(['web'])
->authMiddleware(['auth']);
}
```

3. Update App\Providers\FortifyServiceProvider to redirect to /admin after login:php

```php
use Laravel\Fortify\Fortify;

public function boot()
{
Fortify::loginView(fn () => view('auth.login'));
Fortify::registerView(fn () => view('auth.register'));
$this->app->singleton(
\Laravel\Fortify\Http\Responses\LoginResponse::class,
fn ($app) => new class($app) implements \Laravel\Fortify\Contracts\LoginResponse {
public function toResponse($request)
{
return redirect('/admin');
}
}
);
}
```

4. Test: Run php artisan serve, visit /login, register a user, and ensure redirect to /admin.

✅ **Step 3: Database Schema**

**Goal**: Define models and migrations for Tracks, Vehicles, and Laps.**Duration**: 2-3 hours**Instructions**:

1. Create Track model and migration:bashEdit database/migrations/\*\_create_tracks_table.php:php

```bash
php artisan make:model Track -m
```

```php
use Illuminate\Database\Schema\Blueprint;

Schema::create('tracks', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('location')->nullable();
$table->timestamps();
});
```

2. Create Vehicle model and migration:bashEdit database/migrations/\*\_create_vehicles_table.php:php

```bash
php artisan make:model Vehicle -m
```

```php
Schema::create('vehicles', function (Blueprint $table) {
$table->id();
$table->string('make');
$table->string('model');
$table->string('transmission');// e.g., 'manual', 'auto'
$table->string('drive_type');// e.g., 'FWD', 'AWD', 'RWD'
$table->string('game');// e.g., 'beamng.drive', 'real life'
$table->timestamps();
});
```

3. Create Lap model and migration:bashEdit database/migrations/\*\_create_laps_table.php:php

```bash
php artisan make:model Lap -m
```

```php
Schema::create('laps', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->index();
$table->foreignId('track_id')->constrained()->index();
$table->foreignId('vehicle_id')->constrained()->index();
$table->float('time')->index();// Seconds
$table->date('date')->index();
$table->boolean('is_out_lap')->default(false);
$table->json('notes')->nullable();// {rating: 3, shift_quality: 4, ...}
$table->timestamps();
});
```

4. Define relationships in models:

-   app/Models/Track.php: public function laps() { return $this->hasMany(Lap::class); }
-   app/Models/Vehicle.php: public function laps() { return $this->hasMany(Lap::class); }
-   app/Models/Lap.php:php

```php
public function user() { return $this->belongsTo(User::class); }
public function track() { return $this->belongsTo(Track::class); }
public function vehicle() { return $this->belongsTo(Vehicle::class); }
```

5. Run migrations:bash

```bash
php artisan migrate
```

**Step 4: CRUD with Filament**

**Goal**: Build list views and forms for Tracks, Vehicles, and Laps using Filament.**Duration**: 5-6 hours**Instructions**:

1. Generate Filament resources:bash

```bash
php artisan make:filament-resource Track --simple
php artisan make:filament-resource Vehicle --simple
php artisan make:filament-resource Lap
```

2. Customize app/Filament/Resources/TrackResource.php:php

```php
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;

class TrackResource extends Resource
{
protected static ?string $model = \App\Models\Track::class;
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
]);
}
}
```

3. Customize app/Filament/Resources/VehicleResource.php:php

```php
class VehicleResource extends Resource
{
protected static ?string $model = \App\Models\Vehicle::class;
public static function form(Form $form): Form
{
return $form->schema([
Forms\Components\TextInput::make('make')->required(),
Forms\Components\TextInput::make('model')->required(),
Forms\Components\Select::make('transmission')->options(['manual' => 'Manual', 'auto' => 'Auto'])->required(),
Forms\Components\Select::make('drive_type')->options(['FWD' => 'FWD', 'AWD' => 'AWD', 'RWD'])->required(),
Forms\Components\Select::make('game')->options(['beamng.drive' => 'BeamNG.drive', 'assetto_corsa' => 'Assetto Corsa', 'real_life' => 'Real Life'])->required(),
]);
}
public static function table(Table $table): Table
{
return $table->columns([
Tables\Columns\TextColumn::make('make')->searchable(),
Tables\Columns\TextColumn::make('model')->searchable(),
Tables\Columns\TextColumn::make('game'),
])->filters([
Tables\Filters\SelectFilter::make('game')->options(['beamng.drive', 'assetto_corsa', 'real_life']),
]);
}
}
```

4. Customize app/Filament/Resources/LapResource.php:php

```php
class LapResource extends Resource
{
protected static ?string $model = \App\Models\Lap::class;
public static function form(Form $form): Form
{
return $form->schema([
Forms\Components\Select::make('track_id')->relationship('track', 'name')->required(),
Forms\Components\Select::make('vehicle_id')->relationship('vehicle', 'model')->required(),
Forms\Components\TextInput::make('time')->numeric()->required(),
Forms\Components\DatePicker::make('date')->required(),
Forms\Components\Toggle::make('is_out_lap')->label('Out Lap'),
Forms\Components\Section::make('Performance Metrics')->schema([
Forms\Components\Select::make('notes.rating')->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5])->label('Rating'),
Forms\Components\Select::make('notes.shift_quality')->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]),
Forms\Components\Select::make('notes.braking_efficiency')->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]),
Forms\Components\Select::make('notes.cornering_stability')->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]),
Forms\Components\Select::make('notes.throttle_control')->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]),
Forms\Components\Toggle::make('notes.two_tires_off'),
Forms\Components\Toggle::make('notes.spun_out'),
]),
]);
}
public static function table(Table $table): Table
{
return $table->columns([
Tables\Columns\TextColumn::make('time')->sortable(),
Tables\Columns\TextColumn::make('track.name')->searchable(),
Tables\Columns\TextColumn::make('vehicle.model')->searchable(),
Tables\Columns\TextColumn::make('date')->sortable(),
])->filters([
Tables\Filters\SelectFilter::make('track_id')->relationship('track', 'name'),
Tables\Filters\SelectFilter::make('vehicle_id')->relationship('vehicle', 'model'),
Tables\Filters\SelectFilter::make('game')->options(['beamng.drive', 'assetto_corsa', 'real_life'])->query(fn($query, $state) => $query->whereHas('vehicle', fn($q) => $q->where('game', $state))),
]);
}
}
```

5. Test: Visit /admin, create a Track, Vehicle, and Lap, and verify sorting/filtering.

**Step 5: Screenshot Ingestion**

**Goal**: Extract lap times from stopwatch screenshots with manual override.**Duration**: 6-7 hours**Instructions**:

1. Create a Livewire component:bash

```bash
php artisan make:livewire LapUploader
```

2. Edit app/Http/Livewire/LapUploader.php:php

```php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use thiagoalessio\TesseractOCR\TesseractOCR;

class LapUploader extends Component
{
use WithFileUploads;

public $screenshot;
public $manualTimes = [];
public $excludeOutLap = false;

public function upload()
{
$path = $this->screenshot->store('screenshots');
$text = (new TesseractOCR(storage_path("app/{$path}")))->run();
$times = preg_match_all('/(\d+:\d+\.\d+)/', $text, $matches) ? $matches[1] : [];
$this->manualTimes = array_map([$this, 'timeToSeconds'], $times);
}

public function save()
{
foreach ($this->manualTimes as $index => $time) {
\App\Models\Lap::create([
'user_id' => auth()->id(),
'track_id' => 1,// Replace with form input later
'vehicle_id' => 1,// Replace with form input later
'time' => $time,
'date' => now()->toDateString(),
'is_out_lap' => $index === 0 && $this->excludeOutLap,
'notes' => [],
]);
}
$this->reset();
}

private function timeToSeconds($time)
{
[$minutes, $seconds] = explode(':', $time);
return ($minutes * 60) + floatval($seconds);
}

public function render()
{
return view('livewire.lap-uploader');
}
}
```

3. Create resources/views/livewire/lap-uploader.blade.php:blade

```
<div>
    <form wire:submit.prevent="upload">
        <input type="file" wire:model="screenshot">
        <button type="submit">Upload</button>
    </form>
    @if (!empty($manualTimes))
    <div>
        <label><input type="checkbox" wire:model="excludeOutLap"> Exclude Out Lap</label>
        @foreach ($manualTimes as $index => $time)
        <input wire:model="manualTimes.{{ $index }}" type="number" step="0.01">
        @endforeach
        <button wire:click="save">Save</button>
    </div>
    @endif
</div>
```

4. Add route in routes/web.php:php

```php
Route::get('/upload-laps', \App\Http\Livewire\LapUploader::class)->middleware('auth');
```

5. Test: Upload a screenshot with times (e.g., “1:23.45”), edit manually if needed, and save.

**Step 6: Charts**

**Goal**: Visualize lap data with shareable URLs using Chart.js.**Duration**: 5-6 hours**Instructions**:

1. Create a Livewire component:bash

```bash
php artisan make:livewire LapChart
```

2. Edit app/Http/Livewire/LapChart.php:php

```php
namespace App\Http\Livewire;

use Livewire\Component;

class LapChart extends Component
{
public $trackId, $vehicleId;

public function mount()
{
$this->trackId = request('track_id');
$this->vehicleId = request('vehicle_id');
}

public function render()
{
$laps = \App\Models\Lap::query()
->when($this->trackId, fn($q) => $q->where('track_id', $this->trackId))
->when($this->vehicleId, fn($q) => $q->where('vehicle_id', $this->vehicleId))
->where('is_out_lap', false)
->orderBy('date')
->get();
$data = $laps->pluck('time')->toArray();
$labels = $laps->pluck('date')->toArray();
return view('livewire.lap-chart', compact('data', 'labels'));
}
}
```

3. Create resources/views/livewire/lap-chart.blade.php:blade

```
<div>
    <canvas id="lapChart"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('lapChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Lap Times',
                    data: @json($data),
                    borderColor: 'blue',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
```

4. Add routes in routes/web.php:php

```php
Route::get('/charts', \App\Http\Livewire\LapChart::class)->middleware('auth')->name('charts.auth');
Route::get('/public-charts', \App\Http\Livewire\LapChart::class)->name('charts.public');
```

5. Test: Visit /charts?track_id=1, ensure chart renders, and share URL.

**Step 7: Custom Stopwatch (Optional)**

**Goal**: Add an in-app timer for lap recording.**Duration**: 3-4 hours**Instructions**:

1. Create a Livewire component:bash

```bash
php artisan make:livewire Stopwatch
```

2. Edit app/Http/Livewire/Stopwatch.php:php

```php
namespace App\Http\Livewire;

use Livewire\Component;

class Stopwatch extends Component
{
public $laps = [];
public $running = false;

public function lap()
{
$this->laps[] = now()->timestamp;// Placeholder; JS will handle timing
}

public function save()
{
foreach ($this->laps as $time) {
\App\Models\Lap::create([
'user_id' => auth()->id(),
'track_id' => 1,// Replace with form input later
'vehicle_id' => 1,// Replace with form input later
'time' => $time,
'date' => now()->toDateString(),
'notes' => [],
]);
}
$this->reset();
}

public function render()
{
return view('livewire.stopwatch');
}
}
```

3. Create resources/views/livewire/stopwatch.blade.php:blade

```
<div>
    <button wire:click="running = true">Start</button>
    <button wire:click="lap">Lap</button>
    <button wire:click="save">Save</button>
    <div id="timer">0.00</div>
    <ul>
        @foreach ($laps as $lap)
        <li>{{ $loop->index + 1 }}: {{ $lap }}</li>
        @endforeach
    </ul>
    <script>
        let time = 0,
            interval;
        document.querySelector('[wire\\:click="running = true"]').onclick = () => {
            interval = setInterval(() => {
                time += 0.01;
                document.getElementById('timer').innerText = time.toFixed(2);
            }, 10);
        };
        document.querySelector('[wire\\:click="lap"]').onclick = () => {
            Livewire.emit('lap', time);
        };
    </script>
</div>
```

4. Add route in routes/web.php:php

```php
Route::get('/stopwatch', \App\Http\Livewire\Stopwatch::class)->middleware('auth');
```

5. Test: Start timer, record laps, and save.

**Step 8: Testing & Deployment**

**Goal**: Verify functionality and deploy.**Duration**: 3-4 hours**Instructions**:

1. Create a test:bashEdit tests/Feature/LapTest.php:php

```bash
php artisan make:test LapTest
```

```php
use App\Models\Lap;
use App\Models\User;

public function test_lap_creation()
{
$user = User::factory()->create();
$this->actingAs($user);
$response = $this->post('/admin/laps/create', [
'track_id' => 1,
'vehicle_id' => 1,
'time' => 90.5,
'date' => '2025-03-04',
]);
$this->assertDatabaseHas('laps', ['time' => 90.5]);
}
```

2. Run tests:bash

```bash
php artisan test
```

3. Deploy:

-   Push to GitHub.
-   Use Laravel Forge or similar; run:bash

```bash
apt-get install tesseract-ocr
composer install
php artisan migrate
npm install && npm run prod
```

---

**Final Notes for AI Agent**

-   Execute each step in order.
-   Use php artisan serve to test locally after each major step.
-   If a step fails (e.g., Tesseract unavailable), skip and note for manual setup later.
-   Replace placeholder track_id and vehicle_id with form inputs in Steps 5-7 once UI is refined.

This plan is explicit, cohesive, and ready for an AI agent to implement.
