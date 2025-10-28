<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/auth', function () {
    return view('auth');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth:sanctum');
