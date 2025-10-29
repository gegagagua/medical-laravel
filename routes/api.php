<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

// Appointments/Visits
Route::prefix('visits')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [AppointmentController::class, 'index']);
    Route::get('/{id}', [AppointmentController::class, 'show']);
    Route::post('/', [AppointmentController::class, 'store']);
    Route::patch('/{id}', [AppointmentController::class, 'update']);
});

// Medical Records (ჩაწერები)
Route::prefix('medical-records')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MedicalRecordController::class, 'index']);
    Route::get('/{id}', [MedicalRecordController::class, 'show']);
    Route::post('/', [MedicalRecordController::class, 'store']);
});

// Patients (პაციენტები)
Route::prefix('patients')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
});

// Users
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
});

// Payments
Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::post('/', [PaymentController::class, 'store']);
});

