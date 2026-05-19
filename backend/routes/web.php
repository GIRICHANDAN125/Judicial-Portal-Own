<?php

use Illuminate\Support\Facades\Route;

// API Health Check & Console Status
Route::get('/status', function () {
    return view('welcome');
});

// Serve compiled React SPA
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '^(?!api).*$');
