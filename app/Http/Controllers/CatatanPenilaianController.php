<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Office;
use App\Models\Department;
use App\Models\UnitKerja;

class CatatanPenilaianController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar semua periode penilaian untuk selector
        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $selectedPeriodeId = $request->get('periode_id');
        $hasPeriodeSelected = !empty($selectedPeriodeId);
        $selectedPeriode = $hasPeriodeSelected ? Dp3TransPeriodePenilaian::find($selectedPeriodeId) : null;

        $listPenempatan = Office::orderBy('off_name', 'asc')->get();
        $listDepartemen = Department::where('isaktif', true)->orderBy('dept_name', 'asc')->get();
        $listUnitKerja = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();

        // Query Utama Transaksi Penilaian
        $query = Dp3TransPenilaian::with([
            'pegawai', 
            'penilai', 
            'verifikator', 
            'details',
            'office',
            'department',
            'unitKerja',
        ]);

        if ($hasPeriodeSelected) {
            $query->where('periode_id', $selectedPeriodeId);
        }

        $query->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(status_nilai)'), [
            'SUBMITTED',
            'SUBMIT',
            'DIAJUKAN',
            'DIKEMBALIKAN',
            'VERIFIED',
        ]);

        // Filter: Hanya ambil yang memiliki catatan penilai atau catatan verifikator
        $query->where(function ($q) {
            $q->where(function($sub) {
                $sub->whereNotNull('catatan')->where('catatan', '!=', '');
            })
            ->orWhere(function($sub) {
                $sub->whereNotNull('catatan_verifikator')->where('catatan_verifikator', '!=', '');
            })
            ->orWhereHas('details', function ($det) {
                $det->whereNotNull('catatan')->where('catatan', '!=', '')
                     ->orWhereNotNull('verif_catatan')->where('verif_catatan', '!=', '');
            });
        });

        // Filter Pencarian Teks (Nama Pegawai / NUP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pgw_nama', 'like', "%{$search}%")
                  ->orWhere('pgw_nup', 'like', "%{$search}%");
            });
        }

        // Filter Unit Kerja (Diterapkan langsung ke $query Eloquent)
        if ($request->filled('kode_unit_kerja')) {
            $kodeUnitKerja = (string) $request->kode_unit_kerja;
            $query->where(function ($q) use ($kodeUnitKerja) {
                $q->where('pgw_kode_unit_kerja', $kodeUnitKerja)
                  ->orWhereHas('pegawai', function ($sub) use ($kodeUnitKerja) {
                      $sub->where('kode_unit_kerja', $kodeUnitKerja);
                  });
            });
        }

        // Hitung Ringkasan Statistik Catatan
        $totalCatatan = (clone $query)->count();
        $verifikasi   = (clone $query)->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(status_nilai)'), ['VERIFIED', 'VERIFIKASI'])->count();
        $belum        = (clone $query)->whereIn(\Illuminate\Support\Facades\DB::raw('UPPER(status_nilai)'), ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'DIKEMBALIKAN'])->count();

        // Paginasi Data
        $penilaians = $query->paginate(25)->appends($request->all());

        // Master Data Filter Dropdown
        $penempatans = Office::all();
        $departemens = Department::where('isaktif', 1)->get();

        return view('penilaian.catatan_penilaian', compact(
            'periodes',
            'selectedPeriode',
            'hasPeriodeSelected',
            'totalCatatan',
            'verifikasi',
            'belum',
            'penilaians',
            'penempatans',
            'departemens',
            'listDepartemen',
            'listPenempatan',
            'listUnitKerja'
        ));
    }
}