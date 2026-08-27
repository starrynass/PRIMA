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

Route::get('/', [DashboardController::class, 'index']);
// SKALA NILAI
Route::post('/master/skala-nilai', [MasterSkalaController::class, 'store'])->name('skala-nilai.store');
Route::put('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'update'])->name('skala-nilai.update');
Route::delete('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'destroy'])->name('skala-nilai.destroy');

// PREDIKAT NILAI
Route::get('/master/skala-predikat', [MasterSkalaController::class, 'index'])->name('skala-predikat.index');
Route::post('/master/skala-nilai', [MasterSkalaController::class, 'store'])->name('skala-nilai.store');
Route::put('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'update'])->name('skala-nilai.update');
Route::delete('/master/skala-nilai/{skalaNilai}', [MasterSkalaController::class, 'destroy'])->name('skala-nilai.destroy');

Route::get('/master/skala-predikat', [MasterPredikatController::class, 'index'])->name('skala-predikat.index');
Route::post('/master/predikat/store', [MasterPredikatController::class, 'store'])->name('predikat.store');
Route::delete('/master/predikat/{id}', [MasterPredikatController::class, 'destroy'])->name('predikat.destroy');

// TEMPLATE PENILAIAN
Route::get('/master/template-penilaian', [TemplatePenilaianController::class, 'index'])->name('template-penilaian.index');

// PERIODE PENILAIAN
Route::get('/penilaian/periode-penilaian', [PeriodePenilaianController::class, 'index'])->name('periode-penilaian.index');

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

