<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Office;
use App\Models\Department;

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

        // Query Utama Transaksi Penilaian
        $query = Dp3TransPenilaian::with([
            'pegawai', 
            'penilai', 
            'verifikator', 
            'details',
            'office',
            'department'
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

        // Filter Penempatan (Office)
        if ($request->filled('penempatan')) {
            $query->where('pgw_off_id', $request->penempatan);
        }

        // Filter Departemen
        if ($request->filled('departemen')) {
            $query->where('pgw_id_dept', $request->departemen);
        }

        // Hitung Ringkasan Statistik Catatan[cite: 5]
        $totalCatatan = (clone $query)->count();
        $verifikasi   = (clone $query)->whereNotNull('catatan_verifikator')->where('catatan_verifikator', '!=', '')->count();
        $belum        = $totalCatatan - $verifikasi;

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
            'departemens'
        ));
    }
}