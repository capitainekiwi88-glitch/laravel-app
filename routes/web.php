<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');

Route::get('/game/{id}', [GameController::class, 'show'])
    ->whereNumber('id')
    ->name('games.show');

Route::get('/wishlist', function () {
    return view('wishlist');
})->name('wishlist');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/games/create',
    [GameController::class, 'create'])
    ->name('games.create');

Route::post('/games',
    [GameController::class, 'store'])
    ->name('games.store');