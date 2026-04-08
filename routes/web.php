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

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Notifications\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Notifications\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Espace Administration
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Routes publiques (Authentification)
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.submit');

    // Routes protégées par middleware (Guard admin)
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
        
        // Dashboard Stats
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // Utilisateurs
        Route::get('/utilisateurs', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('utilisateurs.index');
        Route::put('/utilisateurs/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('utilisateurs.update');
        Route::delete('/utilisateurs/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('utilisateurs.destroy');
        Route::post('/utilisateurs/verify/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'validateIdentity'])->name('utilisateurs.verify');
        Route::post('/utilisateurs/reject/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'rejectIdentity'])->name('utilisateurs.reject');
        Route::view('/annonces', 'admin.annonces.index')->name('annonces.index');
        Route::view('/reservations', 'admin.reservations.index')->name('reservations.index');
        Route::view('/avis-signales', 'admin.avis_signales.index')->name('avis_signales.index');
        Route::view('/litiges', 'admin.litiges.index')->name('litiges.index');
        
        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::view('/paiements', 'admin.transactions.paiements')->name('paiements');
            Route::view('/versements', 'admin.transactions.versements')->name('versements');
            Route::view('/remboursements', 'admin.transactions.remboursements')->name('remboursements');
        });
    });
});
