<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkalaController;
use App\Http\Controllers\MasterPredikatController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/master/skala-predikat', [MasterPredikatController::class, 'index'])->name('skala-predikat.index');

Route::post('/master/predikat/store', [MasterPredikatController::class, 'store'])->name('predikat.store');
Route::delete('/master/predikat/{id}', [MasterPredikatController::class, 'destroy'])->name('predikat.destroy');