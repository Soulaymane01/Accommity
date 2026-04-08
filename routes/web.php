<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controllers\Evaluations\EvaluationController;
use App\Http\Controllers\Admin\AdminEvaluationController;
use App\Http\Controllers\Admin\AdminLitigeController;

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

    /*
    |--------------------------------------------------------------------------
    | Évaluations — PK_Evaluations (RG27, RG28, RG30, RO10, RO11, RO12)
    |--------------------------------------------------------------------------
    */
    // Liste des évaluations reçues
    Route::get('/mes-evaluations', [EvaluationController::class, 'index'])->name('mes-evaluations');

    // Créer une évaluation pour une réservation terminée
    Route::get('/reservations/{reservation}/evaluation/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/reservations/{reservation}/evaluation', [EvaluationController::class, 'store'])->name('evaluations.store');

    // Modifier / Supprimer un avis (RO12)
    Route::get('/evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');

    // Signaler un avis (RO10)
    Route::post('/evaluations/{evaluation}/signaler', [EvaluationController::class, 'signaler'])->name('evaluations.signaler');
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

        // Emplacements vides (Views placeholders)
        Route::view('/utilisateurs', 'admin.utilisateurs.index')->name('utilisateurs.index');
        Route::view('/annonces', 'admin.annonces.index')->name('annonces.index');
        Route::view('/reservations', 'admin.reservations.index')->name('reservations.index');
        
        // Avis signalés — Contrôleur dédié (RG31)
        Route::get('/avis-signales', [AdminEvaluationController::class, 'indexSignales'])->name('avis_signales.index');
        Route::delete('/evaluations/{evaluation}', [AdminEvaluationController::class, 'supprimerAvis'])->name('evaluations.supprimer');
        Route::post('/evaluations/{evaluation}/conserver', [AdminEvaluationController::class, 'conserverAvis'])->name('evaluations.conserver');

        // Litiges — Contrôleur dédié (RO10)
        Route::get('/litiges', [AdminLitigeController::class, 'index'])->name('litiges.index');
        Route::get('/litiges/{ticket}', [AdminLitigeController::class, 'show'])->name('litiges.show');
        Route::put('/litiges/{ticket}', [AdminLitigeController::class, 'modifierStatut'])->name('litiges.update');
        
        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::view('/paiements', 'admin.transactions.paiements')->name('paiements');
            Route::view('/versements', 'admin.transactions.versements')->name('versements');
            Route::view('/remboursements', 'admin.transactions.remboursements')->name('remboursements');
        });
    });
});
