<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::resource('players', PlayerController::class);
