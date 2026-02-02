<?php

use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\UserController;
use App\Http\Controllers\{
    DashboardController, 
    TaskController, 
    CollaboratorController, 
    PresenceController, 
    CongeController, 
    PaieController, 
    MessageController,
    DocumentController,
    ProfileController,
   
};

// Redirection initiale
Route::get('/', function () { return redirect()->route('dashboard'); });

Route::middleware(['auth', 'verified'])->group(function () {
    
    // --- NAVIGATION DE BASE ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- PROFIL ---
    // Cette route permet d'afficher ton propre profil avec la barre bleue
    Route::get('/mon-profil', [CollaboratorController::class, 'showSelf'])->name('profile.self');
    
    // Page pour modifier ses informations (si besoin)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // --- COLLABORATEURS ---
    Route::get('/collaborateur/{user}', [CollaboratorController::class, 'show'])->name('collaborator.show');
    Route::post('/collaborateur/{user}/evaluate', [CollaboratorController::class, 'evaluate'])->name('collaborator.evaluate');

    // --- SECTION PRÉSENCES ---
    Route::get('/presences', [PresenceController::class, 'page'])->name('presences');
    Route::post('/presences/store', [PresenceController::class, 'store'])->name('presences.store');

    // --- SECTION CONGÉS ---
    Route::get('/conges', [CongeController::class, 'index'])->name('conges');
    Route::post('/conges/store', [CongeController::class, 'store'])->name('conges.store');

    // --- SECTION PAIE & MESSAGES ---
    Route::get('/paie', [PaieController::class, 'index'])->name('paie');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');

    // --- SECTION TÂCHES (Utilisateurs) ---
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/confirm', [TaskController::class, 'confirm'])->name('tasks.confirm');

    // --- DOCUMENTS ---
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/recus', function () { return view('pages.documents.recus'); })->name('recus');
        Route::get('/envoyes', function () { return view('pages.documents.envoyes'); })->name('envoyes');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/store', [DocumentController::class, 'store'])->name('store');
    });

    // --- ADMINISTRATION (Accès restreint) ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('/utilisateurs', [CollaboratorController::class, 'list'])->name('users.index');
        
        // --- TÂCHES ADMIN ---
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create/{user}', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');

        // --- PAIE ADMIN ---
        Route::get('/paie', [PaieController::class, 'create'])->name('paie');
        Route::get('/paie/create', [PaieController::class, 'create'])->name('paie.create');
        Route::post('/paie/store', [PaieController::class, 'adminStore'])->name('paie.store');

        // --- PRÉSENCES ADMIN ---
        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/presences/{user}/{slot}/point', [PresenceController::class, 'adminPoint'])->name('presences.point');
        Route::get('/presences/export', [PresenceController::class, 'export'])->name('presences.export');

        // --- CONGÉS ADMIN ---
        Route::post('/conges/{id}/approve', [CongeController::class, 'approve'])->name('conges.approve');
        Route::post('/conges/{id}/reject', [CongeController::class, 'reject'])->name('conges.reject');
        Route::patch('/conges/{id}/update', [CongeController::class, 'update'])->name('conges.update');
        
        // --- UTILISATEURS ADMIN: reset mot de passe ---
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('admin.users.reset_password');
    });

   

Route::middleware(['auth'])->group(function () {
    // Tes autres routes...
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::post('/users/store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');

});

require __DIR__.'/auth.php';