<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Programmatically self-heal symbolic storage links on cloud servers (e.g. Render/Railway)
if (!file_exists(public_path('storage'))) {
    try {
        Artisan::call('storage:link');
    } catch (\Exception $e) {
        // Safe fallback
    }
}

// API Health Check & Console Status
Route::get('/status', function () {
    return view('welcome');
});

// Serve compiled React SPA
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '^(?!api).*$');
