<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlayerController::class, 'index']);

Route::resource('players', PlayerController::class)->except(['show']);
