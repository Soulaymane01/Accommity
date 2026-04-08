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

    Route::get('/dashboard', function() {
        if (auth()->user()->est_hote) {
            return redirect()->route('hote.annonces.index');
        }
        return redirect()->route('voyageur.dashboard');
    })->name('dashboard');

    Route::get('/voyageur/dashboard', [\App\Http\Controllers\Utilisateurs\VoyageurController::class, 'dashboard'])->name('voyageur.dashboard');
    Route::get('/verification-identite', [AuthController::class, 'verificationNotice'])->name('verification.notice');
    Route::post('/verification-identite', [AuthController::class, 'submitVerification'])->name('verification.submit');

    // Host - Annonces Management
    Route::get('/hote/annonces', [\App\Http\Controllers\Annonces\AnnonceController::class, 'mesAnnonces'])->name('hote.annonces.index');
    Route::get('/hote/annonces/create', [\App\Http\Controllers\Annonces\AnnonceController::class, 'create'])->name('hote.annonces.create');
    Route::post('/hote/annonces', [\App\Http\Controllers\Annonces\AnnonceController::class, 'store'])->name('hote.annonces.store');
    Route::get('/hote/annonces/{id}/edit', [\App\Http\Controllers\Annonces\AnnonceController::class, 'edit'])->name('hote.annonces.edit');
    Route::put('/hote/annonces/{id}', [\App\Http\Controllers\Annonces\AnnonceController::class, 'update'])->name('hote.annonces.update');
    Route::delete('/hote/annonces/{id}', [\App\Http\Controllers\Annonces\AnnonceController::class, 'destroy'])->name('hote.annonces.destroy');

    // Host - Calendrier
    Route::get('/hote/calendrier', [\App\Http\Controllers\Annonces\CalendrierController::class, 'index'])->name('hote.calendrier.index');
    Route::post('/hote/calendrier/{id}/bloquer', [\App\Http\Controllers\Annonces\CalendrierController::class, 'bloquer'])->name('hote.calendrier.bloquer');
    Route::post('/hote/calendrier/{id}/debloquer', [\App\Http\Controllers\Annonces\CalendrierController::class, 'debloquer'])->name('hote.calendrier.debloquer');

    // Réservations - Voyageur
    Route::post('/reservations', [\App\Http\Controllers\Reservations\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations', function() {
        return redirect()->route('voyageur.reservations.index');
    });
    Route::get('/mes-voyages', [\App\Http\Controllers\Reservations\ReservationController::class, 'mesVoyages'])->name('voyageur.reservations.index');
    Route::post('/reservations/{id}/cancel', [\App\Http\Controllers\Reservations\ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Réservations - Hôte
    Route::get('/hote/reservations', [\App\Http\Controllers\Reservations\ReservationController::class, 'demandes'])->name('hote.reservations.demandes');
    Route::post('/hote/reservations/{id}/accept', [\App\Http\Controllers\Reservations\ReservationController::class, 'accept'])->name('hote.reservations.accept');
    Route::post('/hote/reservations/{id}/refuse', [\App\Http\Controllers\Reservations\ReservationController::class, 'refuse'])->name('hote.reservations.refuse');
});

// Public Annonces
Route::get('/annonces', [\App\Http\Controllers\Annonces\AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{id}', [\App\Http\Controllers\Annonces\AnnonceController::class, 'show'])->name('annonces.show');

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

        // Emplacements vides (Views placeholders)
        Route::view('/utilisateurs', 'admin.utilisateurs.index')->name('utilisateurs.index');
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
