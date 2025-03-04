<?php

use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin');
    }
    return view('welcome');
});

// Redirect /dashboard to admin panel (for Jetstream default redirects)
Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);
