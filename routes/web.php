<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkalaController;
use App\Http\Controllers\MasterPredikatController;
use App\Http\Controllers\TemplatePenilaianController;
use App\Http\Controllers\KategoriPenilaianController;
use App\Http\Controllers\PertanyaanPenilaianController;
use App\Http\Controllers\PeriodePenilaianController;
use App\Http\Controllers\KelolaPenilaianController;
use App\Http\Controllers\VerifikasiPenilaianController;
use App\Http\Controllers\CatatanPenilaianController;
use App\Http\Controllers\Laporan\LaporanTahunanController;
use App\Http\Controllers\Laporan\LaporanIndeksUnitKerjaController;
use App\Http\Controllers\Laporan\LaporanRekapNilaiController;
use App\Http\Controllers\Laporan\LaporanIndeksKompetensiController;
use App\Http\Controllers\FormPenilaianController;
use App\Http\Controllers\PreviewPenilaianController;
use App\Http\Controllers\PreviewVerifikasiController;

Route::get('/', [AuthController::class, 'redirectToApp']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/akses-tidak-tersedia', 'auth.no-access')->name('access.denied');

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::middleware('permission:settings.hak-akses,view')->group(function () {
            Route::get('/hak-akses', [\App\Http\Controllers\UserAccessController::class, 'index'])->name('hak-akses');
            Route::post('/hak-akses', [\App\Http\Controllers\UserAccessController::class, 'store'])->name('hak-akses.store');
            Route::post('/hak-akses/{user}', [\App\Http\Controllers\UserAccessController::class, 'update'])->name('hak-akses.update');
            Route::delete('/hak-akses/{user}', [\App\Http\Controllers\UserAccessController::class, 'destroy'])->name('hak-akses.destroy');
        });

        Route::middleware('permission-or-superadmin:settings.otorisasi-menu,view')->group(function () {
            Route::get('/otorisasi-menu', [\App\Http\Controllers\UserAccessController::class, 'otorisasiMenu'])->name('otorisasi-menu');
            Route::post('/otorisasi-menu', [\App\Http\Controllers\UserAccessController::class, 'saveOtorisasiMenu'])->name('otorisasi-menu.store');
        });

        Route::middleware('permission:settings.roles,view')->group(function () {
            Route::get('/roles', [\App\Http\Controllers\UserAccessController::class, 'roles'])->name('roles');
            Route::post('/roles', [\App\Http\Controllers\UserAccessController::class, 'storeRole'])->name('roles.store');
            Route::put('/roles/{role}', [\App\Http\Controllers\UserAccessController::class, 'updateRole'])->name('roles.update');
            Route::delete('/roles/{role}', [\App\Http\Controllers\UserAccessController::class, 'destroyRole'])->name('roles.destroy');
        });
    });

    Route::middleware('permission:dashboard,view')->group(function () {
        Route::get('/dashboard-penilaian', [DashboardController::class, 'index'])->name('dashboard.penilaian');
        Route::get('/dashboard-penilaian/modal-detail', [DashboardController::class, 'getModalDetail'])->name('dashboard.modal-detail');
    });

    Route::middleware('permission:master.skala-predikat,view')->group(function () {
        // SKALA NILAI
        Route::post('/master/skala', [MasterSkalaController::class, 'store'])->name('skala.store');
        Route::put('/master/skala/{skalaNilai}', [MasterSkalaController::class, 'update'])->name('skala.update');
        Route::delete('/master/skala/{skalaNilai}', [MasterSkalaController::class, 'destroy'])->name('skala.destroy');

        // PREDIKAT NILAI
        Route::get('/master/skala-predikat', [MasterPredikatController::class, 'index'])->name('skala-predikat.index');
        Route::post('/master/predikat/store', [MasterPredikatController::class, 'store'])->name('predikat.store');
        Route::put('/master/predikat/{id}', [MasterPredikatController::class, 'update'])->name('predikat.update');
        Route::delete('/master/predikat/{id}', [MasterPredikatController::class, 'destroy'])->name('predikat.destroy');
    });

    Route::middleware('permission:master.template-penilaian,view')->group(function () {
        // TEMPLATE PENILAIAN
        Route::get('/master/template-penilaian', [TemplatePenilaianController::class, 'index'])->name('template-penilaian.index');
        Route::post('master/template-penilaian/store', [TemplatePenilaianController::class, 'store'])->name('template-penilaian.store');
        Route::put('master/template-penilaian/{template}', [TemplatePenilaianController::class, 'update'])->name('template-penilaian.update');
        Route::delete('master/template-penilaian/{template}', [TemplatePenilaianController::class, 'destroy'])->name('template-penilaian.destroy');

        // KATEGORI TEMPLATE PENILAIAN
        Route::post('master/kategori-penilaian/store', [KategoriPenilaianController::class, 'store'])->name('kategori-penilaian.store');
        Route::put('master/kategori-penilaian/{kategori}', [KategoriPenilaianController::class, 'update'])->name('kategori-penilaian.update');
        Route::delete('master/kategori-penilaian/{kategori}', [KategoriPenilaianController::class, 'destroy'])->name('kategori-penilaian.destroy');

        // PERTANYAAN TEMPLATE PENILAIAN
        Route::post('master/pertanyaan-penilaian/store', [PertanyaanPenilaianController::class, 'store'])->name('pertanyaan-penilaian.store');
        Route::put('master/pertanyaan-penilaian/{pertanyaan}', [PertanyaanPenilaianController::class, 'update'])->name('pertanyaan-penilaian.update');
        Route::delete('master/pertanyaan-penilaian/{pertanyaan}', [PertanyaanPenilaianController::class, 'destroy'])->name('pertanyaan-penilaian.destroy');
    });

    Route::middleware('permission:penilaian.periode,view')->group(function () {
        // PERIODE PENILAIAN
        Route::get('/penilaian/periode-penilaian', [PeriodePenilaianController::class, 'index'])->name('periode-penilaian.index');
        Route::post('/penilaian/periode-penilaian', [PeriodePenilaianController::class, 'store'])->name('periode.store');
        Route::put('/penilaian/periode-penilaian/{id}', [PeriodePenilaianController::class, 'update'])->name('periode.update');
        Route::delete('/penilaian/periode-penilaian/{id}', [PeriodePenilaianController::class, 'destroy'])->name('periode.destroy');
        Route::patch('/penilaian/periode-penilaian/{id}/toggle-lock', [PeriodePenilaianController::class, 'toggleLock'])->name('periode.toggleLock');
        Route::post('/penilaian/periode-penilaian/{id}/generate', [PeriodePenilaianController::class, 'generate'])->name('periode.generate');
        Route::get('/penilaian/periode-penilaian/{id}/detail', [PeriodePenilaianController::class, 'detail'])->name('periode.detail');
        Route::get('/penilaian/periode-penilaian/{id}/employees/search', [PeriodePenilaianController::class, 'searchEmployees'])->name('periode.employees.search');
        Route::get('/penilaian/periode-penilaian/{id}/export', [PeriodePenilaianController::class, 'export'])->name('periode.export');
        Route::post('/penilaian/periode-penilaian/{id}/employees', [PeriodePenilaianController::class, 'addEmployee'])->name('periode.employees.add');
        Route::post('/penilaian/periode-penilaian/{id}/employees/update', [PeriodePenilaianController::class, 'updateEmployees'])->name('periode.employees.update');
        Route::post('/penilaian/periode-penilaian/{id}/employees/deactivate', [PeriodePenilaianController::class, 'deactivateEmployees'])->name('periode.employees.deactivate');
        Route::post('/penilaian/periode-penilaian/{id}/evaluators', [PeriodePenilaianController::class, 'assignEvaluator'])->name('periode.evaluators.assign');
        Route::post('/penilaian/periode-penilaian/{id}/evaluators/automatic', [PeriodePenilaianController::class, 'resetEvaluator'])->name('periode.evaluators.automatic');
    });

    Route::middleware('permission:penilaian.kelola,view')->group(function () {
        // KELOLA PENILAIAN
        Route::get('/penilaian/kelola-penilaian', [KelolaPenilaianController::class, 'index'])->name('kelola-penilaian.index');
        Route::get('/penilaian/preview/{id}', [PreviewPenilaianController::class, 'show'])->name('penilaian.preview');

        // FORM PENILAIAN
        Route::get('/penilaian/form/{penilaian_id}', [FormPenilaianController::class, 'index'])->name('penilaian.form');
        Route::post('/penilaian/store/{penilaian_id}', [FormPenilaianController::class, 'store'])->name('penilaian.store');
    });

    Route::middleware('permission:penilaian.verifikasi,view')->group(function () {
        // VERIFIKASI PENILAIAN
        Route::get('/penilaian/verifikasi-penilaian', [VerifikasiPenilaianController::class, 'index'])->name('verifikasi-penilaian.index');
        Route::get('/penilaian/verifikasi-penilaian/{penilaian_id}/form', [VerifikasiPenilaianController::class, 'form'])->name('verifikasi-penilaian.form');
        Route::post('/penilaian/verifikasi-penilaian/{penilaian_id}/store', [VerifikasiPenilaianController::class, 'storeForm'])->name('verifikasi-penilaian.store-form');
        Route::get('/verifikasi/preview/{id}', [PreviewVerifikasiController::class, 'show'])->name('verifikasi.preview');
    });

    Route::middleware('permission:penilaian.catatan,view')->group(function () {
        // CATATAN PENILAIAN
        Route::get('/penilaian/catatan-penilaian', [CatatanPenilaianController::class, 'index'])->name('catatan-penilaian.index');
        Route::get('/penilaian/catatan-penilaian/export', [CatatanPenilaianController::class, 'exportExcel'])->name('catatan-penilaian.export');
    });

    Route::middleware('permission:laporan.tahunan,view')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/tahunan', [LaporanTahunanController::class, 'index'])->name('tahunan.index');
        Route::get('/tahunan/export', [LaporanTahunanController::class, 'export'])->name('tahunan.export');
    });

    Route::middleware('permission:laporan.indeks-unit-kerja,view')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/unit-kerja', [LaporanIndeksUnitKerjaController::class, 'index'])->name('indeks-unit-kerja.index');
        Route::get('/unit-kerja/export', [LaporanIndeksUnitKerjaController::class, 'export'])->name('indeks-unit-kerja.export');
    });

    Route::middleware('permission:laporan.rekap-nilai,view')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/rekap-nilai', [LaporanRekapNilaiController::class, 'index'])->name('rekap-nilai.index');
        Route::get('/rekap-nilai/export', [LaporanRekapNilaiController::class, 'export'])->name('rekap-nilai.export');
    });

    Route::middleware('permission:laporan.indeks-kompetensi,view')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/kompetensi', [LaporanIndeksKompetensiController::class, 'index'])->name('indeks-kompetensi.index');
        Route::get('/kompetensi/export', [LaporanIndeksKompetensiController::class, 'export'])->name('indeks-kompetensi.export');
    });
});