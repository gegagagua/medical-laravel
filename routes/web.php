<?php

use Illuminate\Support\Facades\Route;

// Single page application - all routes serve the Vue app
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
