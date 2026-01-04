<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PdfController;
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
    Route::delete('/{id}', [AppointmentController::class, 'destroy']);
});

// Medical Records (ჩაწერები)
Route::prefix('medical-records')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MedicalRecordController::class, 'index']);
    Route::get('/{id}', [MedicalRecordController::class, 'show']);
    Route::post('/', [MedicalRecordController::class, 'store']);
});

// Patients (პაციენტები)
Route::prefix('patients')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PatientController::class, 'index']);
    Route::get('/{id}', [PatientController::class, 'show']);
    Route::post('/', [PatientController::class, 'store']);
    Route::put('/{id}', [PatientController::class, 'update']);
    Route::patch('/{id}', [PatientController::class, 'update']);
    Route::delete('/{id}', [PatientController::class, 'destroy']);
});

// Users
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::patch('/{id}/password', [UserController::class, 'changePassword']);
});

// Payments
Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/by-doctor', [PaymentController::class, 'byDoctor']);
    Route::post('/', [PaymentController::class, 'store']);
});

// Services
Route::prefix('services')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::patch('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
});

// PDF Files
Route::prefix('pdf')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PdfController::class, 'index']);
    Route::get('/download/{filename}', [PdfController::class, 'download']);
});

