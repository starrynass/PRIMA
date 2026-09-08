<?php

namespace App\Http\Controllers;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use Illuminate\Http\Request;

class KelolaPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();

        $selectedPeriodeId = $request->get('periode_id');
        $selectedPeriode = null;
        $hasPeriodeSelected = false;

        // Inisialisasi data ringkasan & tabel
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

                // 2. Total pegawai yang wajib dinilai
                $totalPegawai = Employee::count();

                // 3. Query transaksi penilaian berdasarkan periode_id yang dipilih
                $queryPenilaian = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId);

                // Filter Pencarian (Search by Nama / NUP) jika form filter diisi
                if ($request->filled('search')) {
                    $search = $request->get('search');
                    $queryPenilaian->where(function($q) use ($search) {
                        $q->where('pgw_nama', 'like', "%{$search}%")
                          ->orWhere('pgw_nup', 'like', "%{$search}%");
                    });
                }

                // Ambil daftar transaksi penilaian
                $penilaians = $queryPenilaian->get();

                // 4. Hitung Statistik Ringkasan berdasarkan kolom status_nilai
                $sudahDiajukan = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                                    ->where('status_nilai', 'DIAJUKAN')->count();
                                    
                $dikembalikan  = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                                    ->where('status_nilai', 'DIKEMBALIKAN')->count();

                $masihDraft    = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                                    ->where('status_nilai', 'DRAFT')->count();

                // Belum diisi = Sisa pegawai yang belum dibuatkan record transaksi di periode ini
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
