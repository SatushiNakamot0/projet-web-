<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\AnnonceController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Les routes dyal les annonces lli kaytalbo l'authentification (Membre)
    Route::get('/mes-annonces', [\App\Http\Controllers\AnnonceController::class, 'mesAnnonces'])->name('annonces.mine');
    Route::resource('annonces', \App\Http\Controllers\AnnonceController::class)->except(['index', 'show']);

    // Routes de la Messagerie
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

// Routes publiques pour les visiteurs (Rechercher, Consulter)
Route::resource('annonces', \App\Http\Controllers\AnnonceController::class)->only(['index', 'show']);

// ==========================================
// ROUTES ADMINISTRATION (Modération & Users)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    
    // Modération des annonces
    Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('moderation.index');
    Route::get('/moderation/{annonce}', [\App\Http\Controllers\Admin\ModerationController::class, 'show'])->name('moderation.show');
    Route::patch('/moderation/{annonce}/approve', [\App\Http\Controllers\Admin\ModerationController::class, 'approve'])->name('moderation.approve');
    Route::patch('/moderation/{annonce}/reject', [\App\Http\Controllers\Admin\ModerationController::class, 'reject'])->name('moderation.reject');
    Route::delete('/moderation/{annonce}', [\App\Http\Controllers\Admin\ModerationController::class, 'destroy'])->name('moderation.destroy');

    // Gestion des utilisateurs
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

});

require __DIR__.'/auth.php';
