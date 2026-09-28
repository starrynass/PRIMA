<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();
        $selectedPeriodeId = $request->get('periode_id', $periodes->first()->periode_id ?? null);

        $totalPegawai = Employee::count();

        // 1. Sudah Terverifikasi (status_verifikator: VERIFIED, VERIFIKASI, SELESAI, APPROVED)
        $sudahTerverifikasi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
            ->whereIn(DB::raw('UPPER(status_verifikator)'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
            ->count();

        // 2. Menunggu Verifikasi (status_nilai diajukan AND status_verifikator belum verifikasi)
        $menungguVerifikasi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
            ->whereIn(DB::raw('UPPER(status_nilai)'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI'])
            ->where(function ($q) {
                $q->whereNull('status_verifikator')
                  ->orWhereIn(DB::raw('UPPER(status_verifikator)'), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
            })
            ->count();

        // 3. Dikembalikan (status_nilai ATAU status_verifikator = DIKEMBALIKAN / REVISI)
        $dikembalikan = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
            ->where(function ($q) {
                $q->whereIn(DB::raw('UPPER(status_nilai)'), ['DIKEMBALIKAN', 'REVISI'])
                  ->orWhereIn(DB::raw('UPPER(status_verifikator)'), ['DIKEMBALIKAN', 'REVISI']);
            })
            ->count();

        // 4. Ada Koreksi / Perlu Ditinjau (status_verifikator: KOREKSI, PERLU DITINJAU)
        $dikoreksi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
            ->whereIn(DB::raw('UPPER(status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU'])
            ->count();

        // 5. At Risk / Lewat Batas (Jika ada kriteria khusus misal status AT_RISK)
        $atRisk = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
            ->whereIn(DB::raw('UPPER(status_nilai)'), ['AT_RISK', 'LEWAT BATAS'])
            ->count();

        // 6. Belum Dinilai
        $totalRecord = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)->count();
        $belumDinilai = max(0, $totalPegawai - $totalRecord);

        return view('dashboard', compact(
            'periodes',
            'selectedPeriodeId',
            'totalPegawai',
            'sudahTerverifikasi',
            'menungguVerifikasi',
            'dikembalikan',
            'dikoreksi',
            'atRisk',
            'belumDinilai'
        ));
    }

    public function getModalDetail(Request $request)
    {
        $type = $request->get('type');
        $periodeId = $request->get('periode_id');

        $query = DB::table('employee as e')
            ->leftJoin('dp3_trans_penilaian as tp', function ($join) use ($periodeId) {
                $join->on('e.pgw_id', '=', 'tp.pegawai_id')
                     ->where('tp.periode_id', '=', $periodeId);
            })
            ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
            ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->leftJoin('dp3_master_predikat_nilai as mp', function ($join) {
                $join->on('tp.total_nilai', '>=', 'mp.nilai_min')
                     ->on('tp.total_nilai', '<=', 'mp.nilai_max');
            })
            ->select(
                'e.pgw_id',
                'e.nup',
                'e.nama',
                'o.off_name as satker',
                'occ.occ_name as jabatan',
                'tp.status_nilai',
                'tp.status_verifikator',
                'tp.total_nilai',
                'mp.predikat'
            );

        // Filter Sesuai Kategori Card Modal
        switch ($type) {
            case 'terverifikasi':
                $query->whereIn(DB::raw('UPPER(tp.status_verifikator)'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED']);
                $title = "Pegawai Sudah Terverifikasi";
                break;

            case 'menunggu':
                $query->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI'])
                      ->where(function ($q) {
                          $q->whereNull('tp.status_verifikator')
                            ->orWhereIn(DB::raw('UPPER(tp.status_verifikator)'), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
                      });
                $title = "Pegawai Menunggu Verifikasi";
                break;

            case 'dikembalikan':
                $query->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['DIKEMBALIKAN', 'REVISI'])
                      ->orWhereIn(DB::raw('UPPER(tp.status_verifikator)'), ['DIKEMBALIKAN', 'REVISI']);
                });
                $title = "Penilaian Dikembalikan";
                break;

            case 'dikoreksi':
                $query->whereIn(DB::raw('UPPER(tp.status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU']);
                $title = "Penilaian Perlu Koreksi / Ditinjau";
                break;

            case 'at_risk':
                $query->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['AT_RISK', 'LEWAT BATAS']);
                $title = "Penilaian At Risk / Lewat Batas";
                break;

            case 'belum_dinilai':
                $query->whereNull('tp.status_nilai');
                $title = "Pegawai Belum Dinilai";
                break;

            case 'total':
            default:
                $title = "Total Pegawai";
                break;
        }

        $data = $query->get();

        return response()->json([
            'title' => $title,
            'total' => $data->count(),
            'data'  => $data
        ]);
    }
}