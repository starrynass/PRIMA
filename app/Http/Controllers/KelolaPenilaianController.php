<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Office;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();

        $selectedPeriodeId = $request->get('periode_id');
        $selectedPeriode = null;
        $hasPeriodeSelected = false;

        $totalPegawai = 0;
        $sudahDiajukan = 0;
        $dikembalikan = 0;
        $masihDraft = 0;
        $belumDiisi = 0;
        $penilaians = collect();

        $listPenempatan = Office::orderBy('off_name', 'asc')->get();
        $listDepartemen = Department::where('isaktif', true)->orderBy('dept_name', 'asc')->get();
        $listStatus = [
                        'BELUM DIISI'  => 'Belum Diisi',
                        'DRAFT'        => 'Draft',
                        'SUBMIT'       => 'Submit',
                        'DIKEMBALIKAN' => 'Dikembalikan (Revisi)',
                        'VERIFIED'     => 'Verified',
                    ];

        if ($selectedPeriodeId) {
            $selectedPeriode = Dp3TransPeriodePenilaian::where('periode_id', $selectedPeriodeId)->first();

            if ($selectedPeriode) {
                $hasPeriodeSelected = true;

                $totalPegawai = Employee::count();

                $queryPenilaian = DB::table('dp3_trans_penilaian as tp')
                ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
                ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
                ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
                ->leftJoin('department_sub as sd', 'e.subdept_id', '=', 'sd.subdept_id')
                ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
                // Tambahkan LEFT JOIN ke master predikat berdasarkan rentang nilai_min & nilai_max
                ->leftJoin('dp3_master_predikat_nilai as mp', function ($join) {
                    $join->on('tp.total_nilai', '>=', 'mp.nilai_min')
                         ->on('tp.total_nilai', '<=', 'mp.nilai_max');
                })
                ->where('tp.periode_id', $selectedPeriodeId)
                ->select(
                    'tp.*',
                    'e.pgw_id as pegawai_id',
                    'e.nup',
                    'e.nama',
                    'o.off_name as penempatan',
                    'occ.occ_name as jabatan',
                    'd.dept_name as departemen',
                    'sd.subdept_name as sub_departemen',
                    'tp.status_nilai as status_penilaian',
                    'tp.total_nilai as nilai_akhir',
                    'penilai.nama as nama_penilai',
                    'mp.predikat as predikat' // Mengambil teks predikat dinamis dari tabel master
                );

                // Filter Search
                if ($request->filled('search')) {
                    $search = trim($request->search);
                    $queryPenilaian->where(function ($q) use ($search) {
                        $q->where('e.nama', 'like', "%{$search}%")
                        ->orWhere('e.nup', 'like', "%{$search}%")
                        ->orWhere('tp.pgw_nama', 'like', "%{$search}%")
                        ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
                    });
                }

                // Filter Penempatan (Office)
                if ($request->filled('off_id')) {
                    $offId = (string) $request->off_id;
                    $queryPenilaian->where(function ($q) use ($offId) {
                        $q->where('o.off_id', $offId)
                        ->orWhere('e.off_id', $offId)
                        ->orWhere('tp.pgw_off_id', $offId);
                    });
                }

                // Filter Departemen
                if ($request->filled('dept_id')) {
                    $deptId = (string) $request->dept_id;
                    $queryPenilaian->where(function ($q) use ($deptId) {
                        $q->where('d.dept_id', $deptId)
                        ->orWhere('e.dept_id', $deptId)
                        ->orWhere('tp.pgw_id_dept', $deptId);
                    });
                }

                // Filter Status
                if ($request->filled('status')) {
                    $status = strtoupper((string) $request->status);

                    if ($status === 'SUBMIT') {
                        // Cukup cari nilai 'SUBMIT' (atau 'SUBMITTED' jika ada data lama)
                        $queryPenilaian->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['SUBMIT', 'SUBMITTED']);
                    } elseif ($status === 'VERIFIED') {
                        // Cari status Verified
                        $queryPenilaian->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['VERIFIED', 'TERVERIFIKASI']);
                    } elseif ($status === 'BELUM DIISI') {
                        $queryPenilaian->where(function ($q) {
                            $q->whereNull('tp.status_nilai')
                            ->orWhereRaw('UPPER(tp.status_nilai) = ?', ['BELUM DIISI']);
                        });
                    } else {
                        // Untuk status DRAFT dan DIKEMBALIKAN
                        $queryPenilaian->whereRaw('UPPER(tp.status_nilai) = ?', [$status]);
                    }
                }

                $sort = $request->get('sort', 'nama_asc');
                switch ($sort) {
                    case 'nama_desc':
                        $queryPenilaian->orderBy('e.nama', 'desc');
                        break;
                    case 'nilai_desc':
                        $queryPenilaian->orderBy('tp.total_nilai', 'desc');
                        break;
                    case 'nilai_asc':
                        $queryPenilaian->orderBy('tp.total_nilai', 'asc');
                        break;
                    case 'status':
                        $queryPenilaian->orderBy('tp.status_nilai', 'asc');
                        break;
                    case 'nama_asc':
                    default:
                        $queryPenilaian->orderBy('e.nama', 'asc');
                        break;
                }

                $penilaians = $queryPenilaian->paginate(25)->appends($request->query());

                $sudahDiajukan = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereRaw('UPPER(status_nilai) IN (?, ?, ?)', ['SUBMITTED', 'SUBMIT', 'DIAJUKAN'])
                    ->count();

                $dikembalikan  = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->where('status_nilai', 'DIKEMBALIKAN')->count();

                $masihDraft    = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->where('status_nilai', 'DRAFT')->count();

                $totalRecord   = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)->count();
                $belumDiisi    = max(0, $totalPegawai - $totalRecord);
            }
        }

        return view('penilaian.kelola_penilaian', compact(
            'periodes',
            'selectedPeriode',
            'hasPeriodeSelected',
            'totalPegawai',
            'sudahDiajukan',
            'dikembalikan',
            'masihDraft',
            'belumDiisi',
            'penilaians',
            'listPenempatan',
            'listDepartemen',
            'listStatus'
        ));
    }
}