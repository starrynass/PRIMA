<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
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

        if ($selectedPeriodeId) {
            $selectedPeriode = Dp3TransPeriodePenilaian::find($selectedPeriodeId);

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
                    ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    ->select(
                        'tp.*',
                        'e.nup',
                        'e.nama',
                        'o.off_name as penempatan',
                        'occ.occ_name as jabatan',
                        'd.dept_name as departemen',
                        'sd.subdept_name as sub_departemen',
                        'tp.status_nilai as status_penilaian',
                        'tp.total_nilai as nilai_akhir',
                        'penilai.nama as nama_penilai',
                        'verifikator.nama as nama_verifikator'
                    );

                if ($request->filled('search')) {
                    $search = trim($request->search);
                    $queryPenilaian->where(function ($q) use ($search) {
                        $q->where('e.nama', 'like', "%{$search}%")
                            ->orWhere('e.nup', 'like', "%{$search}%")
                            ->orWhere('tp.pgw_nama', 'like', "%{$search}%")
                            ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
                    });
                }

                if ($request->filled('status')) {
                    $status = strtoupper((string) $request->status);
                    $queryPenilaian->whereRaw('UPPER(COALESCE(tp.status_nilai, "BELUM DIISI")) = ?', [$status]);
                }

                if ($request->filled('dept_id')) {
                    $deptId = (string) $request->dept_id;
                    $queryPenilaian->where(function ($q) use ($deptId) {
                        $q->where('d.dept_id', $deptId)
                            ->orWhere('e.dept_id', $deptId)
                            ->orWhere('tp.pgw_id_dept', $deptId);
                    });
                }

                if ($request->filled('subdept_id')) {
                    $subDeptId = (string) $request->subdept_id;
                    $queryPenilaian->where(function ($q) use ($subDeptId) {
                        $q->where('sd.subdept_id', $subDeptId)
                            ->orWhere('e.subdept_id', $subDeptId)
                            ->orWhere('tp.pgw_id_subdept', $subDeptId);
                    });
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
                    ->where('status_nilai', 'DIAJUKAN')->count();

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
            'penilaians'
        ));
    }
}
