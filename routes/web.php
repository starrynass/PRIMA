<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkalaController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/master/skala-predikat', [MasterSkalaController::class, 'index'])->name('skala-predikat.index');
