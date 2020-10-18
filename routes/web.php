<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlayerController::class, 'index']);

Route::resource('players', PlayerController::class)->except(['show']);

Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
