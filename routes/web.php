<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkalaController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/master/skala-predikat', [MasterSkalaController::class, 'index'])->name('skala-predikat.index');
Route::post('/master/skala-nilai', [MasterSkalaController::class, 'store'])->name('skala-nilai.store');
Route::put('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'update'])->name('skala-nilai.update');
Route::delete('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'destroy'])->name('skala-nilai.destroy');
