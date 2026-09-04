<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkalaController;
use App\Http\Controllers\MasterPredikatController;
use App\Http\Controllers\TemplatePenilaianController;
use App\Http\Controllers\PeriodePenilaianController;
use App\Http\Controllers\KelolaPenilaianController;
use App\Http\Controllers\VerifikasiPenilaianController;
use App\Http\Controllers\CatatanPenilaianController;
use App\Http\Controllers\LaporanTahunanController;
use App\Http\Controllers\LaporanIndexUnitKerjaController;
use App\Http\Controllers\LaporanRekapNilaiController;
use App\Http\Controllers\LaporanIndexKompetensiController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard-penilaian.index');

// SKALA NILAI
Route::post('/master/skala', [MasterSkalaController::class, 'store'])->name('skala.store');
Route::put('/master/skala/{skalaNilai}', [MasterSkalaController::class, 'update'])->name('skala.update');
Route::delete('/master/skala/{skalaNilai}', [MasterSkalaController::class, 'destroy'])->name('skala.destroy');

// PREDIKAT NILAI

Route::get('/master/skala-predikat', [MasterPredikatController::class, 'index'])->name('skala-predikat.index');
Route::post('/master/predikat/store', [MasterPredikatController::class, 'store'])->name('predikat.store');
Route::put('/master/predikat/{id}', [MasterPredikatController::class, 'update'])->name('predikat.update');
Route::delete('/master/predikat/{id}', [MasterPredikatController::class, 'destroy'])->name('predikat.destroy');

// TEMPLATE PENILAIAN
Route::get('/master/template-penilaian', [TemplatePenilaianController::class, 'index'])->name('template-penilaian.index');
Route::post('master/template-penilaian/store', [TemplatePenilaianController::class, 'store'])->name('template-penilaian.store');
Route::put('master/template-penilaian/{template}', [TemplatePenilaianController::class, 'update'])->name('template-penilaian.update');
Route::delete('master/template-penilaian/{template}', [TemplatePenilaianController::class, 'destroy'])->name('template-penilaian.destroy');
 
// PERIODE PENILAIAN
Route::get('/penilaian/periode-penilaian', [PeriodePenilaianController::class, 'index'])->name('periode-penilaian.index');
Route::post('/penilaian/periode-penilaian', [PeriodePenilaianController::class, 'store'])->name('periode.store');
Route::put('/penilaian/periode-penilaian/{id}', [PeriodePenilaianController::class, 'update'])->name('periode.update');
Route::delete('/penilaian/periode-penilaian/{id}', [PeriodePenilaianController::class, 'destroy'])->name('periode.destroy');
Route::patch('/penilaian/periode-penilaian/{id}/toggle-lock', [PeriodePenilaianController::class, 'toggleLock'])->name('periode.toggleLock');

// KELOLA PENILAIAN
Route::get('/penilaian/kelola-penilaian', [KelolaPenilaianController::class, 'index'])->name('kelola-penilaian.index');

// VERIFIKASI PENILAIAN
Route::get('/penilaian/verifikasi-penilaian', [VerifikasiPenilaianController::class, 'index'])->name('verifikasi-penilaian.index');

// CATATAN PENILAIAN
Route::get('/penilaian/catatan-penilaian', [CatatanPenilaianController::class, 'index'])->name('catatan-penilaian.index');

// LAPORAN TAHUNAN
Route::get('/laporan/laporan-tahunan', [LaporanTahunanController::class, 'index'])->name('laporan-tahunan.index');

//LAPORAN INDEX UNIT KERJA
Route::get('/laporan/laporan-index-unit-kerja', [LaporanIndexUnitKerjaController::class, 'index'])->name('laporan-index-unit-kerja.index');

//LAPORAN REKAP NILAI
Route::get('/laporan/laporan-rekap-nilai', [LaporanRekapNilaiController::class, 'index'])->name('laporan-rekap-nilai.index');

//LAPORAN INDEX KOMPETENSI
Route::get('/laporan/laporan-index-kompetensi', [LaporanIndexKompetensiController::class, 'index'])->name('laporan-index-kompetensi.index');

