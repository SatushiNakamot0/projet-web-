<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AnnonceController::class, 'index'])->name('home');
Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');


Route::middleware(['auth'])->group(function () {
    Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
});

Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/mes-annonces', [AnnonceController::class, 'mesAnnonces'])->name('annonces.mes');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::get('/annonces/{annonce}/edit', [AnnonceController::class, 'edit'])->name('annonces.edit');
    Route::put('/annonces/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update');
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';