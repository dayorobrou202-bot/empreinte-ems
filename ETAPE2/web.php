<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\PaieController; // Importation indispensable
use Illuminate\Support\Facades\DB;

// --- ROUTES PUBLIQUES ---
Route::get('/', function () { 
    return redirect()->route('dashboard'); 
});

// --- ROUTES PROTÉGÉES (Utilisateurs connectés) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Module Présences
    Route::get('/presences', [PresenceController::class, 'page'])->name('presences');
    Route::post('/presences/store', [PresenceController::class, 'store'])->name('presences.store');
    Route::get('/presences/history', [PresenceController::class, 'history'])->name('presences.history');

    // --- MODULE TÂCHES ---
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{task}/confirm', [TaskController::class, 'confirm'])->name('tasks.confirm');

    // --- MODULE CONGÉS ---
    Route::get('/conges', [CongeController::class, 'index'])->name('conges');
    Route::post('/conges/store', [CongeController::class, 'store'])->name('conges.store');

    // --- MODULE PAIE (Collaborateur) ---
    Route::get('/paie', [PaieController::class, 'index'])->name('paie');

    // Pages simples
    Route::get('/messages', function () { return view('pages.messages'); })->name('messages');
    Route::get('/sondages', function () { return view('pages.sondages'); })->name('sondages');

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/recus', function () { return view('pages.documents.recus'); })->name('recus');
        Route::get('/envoyes', function () { return view('pages.documents.envoyes'); })->name('envoyes');
    });

    Route::get('/profil/{user}', [CollaboratorController::class, 'show'])->name('collaborator.show');

    // --- ESPACE ADMIN ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/utilisateurs', [CollaboratorController::class, 'list'])->name('users.index');
        
        // --- MODULE PAIE (ADMIN) : Corrigé ---
        // Affiche le formulaire (Appelle la fonction create du controller)
        Route::get('/paie', [PaieController::class, 'create'])->name('paie'); 
        // Enregistre le paiement
        Route::post('/paie/store', [PaieController::class, 'adminStore'])->name('paie.store');
        
        Route::get('/presences/equipe', [PresenceController::class, 'index'])->name('presences.index');
        Route::get('/presences/export', [PresenceController::class, 'export'])->name('presences.export');
    });
});

require __DIR__.'/auth.php';
