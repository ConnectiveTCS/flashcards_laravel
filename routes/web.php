<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cards routes
    // Define the export route first to avoid it being caught by the resource routes
    Route::get('/cards-export', [CardsController::class, 'export'])->name('cards.export');
    Route::get('/cards/search', [CardsController::class, 'search'])->name('cards.search');
    Route::get('/cards/filter', [CardsController::class, 'filter'])->name('cards.filter');
    Route::post('/cards/import', [CardsController::class, 'import'])->name('cards.import');
    
    // Custom action routes
    Route::post('/cards/{id}/bookmark', [CardsController::class, 'bookmark'])->name('cards.bookmark');
    Route::post('/cards/{id}/correct', [CardsController::class, 'correct'])->name('cards.correct');
    Route::get('/cards/{id}/bookmark', [CardsController::class, 'bookmark'])->name('cards.bookmark.get');
    Route::get('/cards/{id}/unbookmark', [CardsController::class, 'unbookmark'])->name('cards.unbookmark');
    Route::get('/cards/{id}/correct', [CardsController::class, 'markCorrect'])->name('cards.markCorrect');
    Route::get('/cards/{id}/incorrect', [CardsController::class, 'markIncorrect'])->name('cards.markIncorrect');
    Route::get('/cards/{id}/review', [CardsController::class, 'review'])->name('cards.review');
    Route::get('/cards/{id}/review/next', [CardsController::class, 'nextReview'])->name('cards.nextReview');
    Route::get('/cards/{id}/review/previous', [CardsController::class, 'previousReview'])->name('cards.previousReview');
    
    // Resource routes for standard CRUD operations
    Route::resource('cards', CardsController::class);
});

require __DIR__.'/auth.php';
