<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Multi-step Registration Flow
    Route::get('/register/photo', [AuthController::class, 'showPhotoUpload'])->name('register.photo');
    Route::post('/register/photo', [AuthController::class, 'uploadPhoto'])->name('register.photo.submit');

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/verification-identite', [AuthController::class, 'verificationNotice'])->name('verification.notice');
    Route::post('/verification-identite', [AuthController::class, 'submitVerification'])->name('verification.submit');
});
