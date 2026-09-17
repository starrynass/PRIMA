<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();

        $selectedPeriodeId = $request->get('periode_id');
        $selectedPeriode = null;
        $hasPeriodeSelected = false;

        // Inisialisasi variabel statistik
        $menungguVerifikasi = 0;
        $sudahVerified      = 0;
        $adaKoreksi         = 0;
        $dikembalikan       = 0;

        $penilaians = collect();

        if ($selectedPeriodeId) {
            $selectedPeriode = Dp3TransPeriodePenilaian::where('periode_id', $selectedPeriodeId)->first();

            if ($selectedPeriode) {
                $hasPeriodeSelected = true;

                // 1. Query Utama untuk Tabel Data Pegawai
                $queryPenilaian = DB::table('dp3_trans_penilaian as tp')
                    ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                    ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
                    ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
                    ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
                    ->leftJoin('department_sub as ds', 'e.subdept_id', '=', 'ds.subdept_id')
                    ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
                    ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    // LOGIKA UTAMA: Hanya tampilkan pegawai yang sudah di-SUBMIT / DIAJUKAN / DIKEMBALIKAN / VERIFIED
                    ->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['SUBMIT', 'DIAJUKAN', 'DIKEMBALIKAN', 'VERIFIED'])
                    ->select(
                        'tp.*',
                        'e.nup',
                        'e.nama',
                        'o.off_name as penempatan',
                        'occ.occ_name as jabatan',
                        'd.dept_name as departemen',
                        'ds.subdept_name as sub_departemen',
                        'tp.status_nilai',            // Status alur pengisian
                        'tp.status_verifikator',      // Status verifikasi
                        'tp.status_nilai as status_penilaian',
                        DB::raw('COALESCE(tp.total_nilai_verifikator, 0) as nilai_akhir'), // Murni nilai verifikator
                        'tp.predikat',
                        'penilai.nama as nama_penilai',
                        'verifikator.nama as nama_verifikator'
                    );

                // Filter Pencarian (Nama / NUP)
                if ($request->filled('search')) {
                    $search = trim($request->search);
                    $queryPenilaian->where(function ($q) use ($search) {
                        $q->where('e.nama', 'like', "%{$search}%")
                            ->orWhere('e.nup', 'like', "%{$search}%");
                    });
                }

                // Filter Status Penilaian
                if ($request->filled('status')) {
                    $status = strtoupper((string) $request->status);
                    $queryPenilaian->where(function ($q) use ($status) {
                        $q->whereRaw('UPPER(COALESCE(tp.status_nilai, "")) = ?', [$status])
                          ->orWhereRaw('UPPER(COALESCE(tp.status_verifikator, "")) = ?', [$status]);
                    });
                }

                // Filter Departemen
                if ($request->filled('dept_id')) {
                    $queryPenilaian->where('e.dept_id', $request->dept_id);
                }

                // Filter Sub Departemen
                if ($request->filled('subdept_id')) {
                    $queryPenilaian->where('e.subdept_id', $request->subdept_id);
                }

                // Sorting Data
                $sort = $request->get('sort', 'nama_asc');
                switch ($sort) {
                    case 'nama_desc':
                        $queryPenilaian->orderBy('e.nama', 'desc');
                        break;
                    case 'nilai_desc':
                        $queryPenilaian->orderByRaw('COALESCE(tp.total_nilai_verifikator, 0) DESC');
                        break;
                    case 'nilai_asc':
                        $queryPenilaian->orderByRaw('COALESCE(tp.total_nilai_verifikator, 0) ASC');
                        break;
                    case 'status':
                        $queryPenilaian->orderBy('tp.status_nilai', 'asc')
                                       ->orderBy('tp.status_verifikator', 'asc');
                        break;
                    case 'nama_asc':
                    default:
                        $queryPenilaian->orderBy('e.nama', 'asc');
                        break;
                }

                $penilaians = $queryPenilaian->paginate(25)->appends($request->query());

                // 2. Kalkulasi Angka Kartu Ringkasan Statistik
                $menungguVerifikasi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_nilai)'), ['DIAJUKAN', 'SUBMIT', 'MENUNGGU VERIFIKASI'])
                    ->count();

                $sudahVerified = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_verifikator)'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
                    ->count();

                $adaKoreksi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU'])
                    ->count();

                $dikembalikan = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->where(function ($q) {
                        $q->whereIn(DB::raw('UPPER(status_nilai)'), ['DIKEMBALIKAN', 'REVISI'])
                          ->orWhereIn(DB::raw('UPPER(status_verifikator)'), ['DIKEMBALIKAN', 'REVISI']);
                    })->count();
            }
        }

        return view('penilaian.verifikasi_penilaian', compact(
            'periodes',
            'selectedPeriode',
            'hasPeriodeSelected',
            'menungguVerifikasi',
            'sudahVerified',
            'adaKoreksi',
            'dikembalikan',
            'penilaians'
        ));
    }
}