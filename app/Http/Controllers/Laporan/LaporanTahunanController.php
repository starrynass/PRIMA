<?php
namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTahunanExport;

class LaporanTahunanController extends Controller
{
    /**
     * Halaman Form Filter Laporan Tahunan
     */
    public function index()
    {
        // Mengambil data Jabatan (Occupation) yang aktif untuk dropdown filter
        $occupations = Occupation::where('is_aktif', 1)
            ->orderBy('occ_name', 'asc')
            ->get();

        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('laporan.tahunan.index', compact('occupations', 'periodes'));

    }

    /**
     * Process & Export Laporan Tahunan (Preview / PDF / Excel)
     */
    public function export(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $jabatanId = $request->input('jabatan');
        $action = $request->input('action'); // preview, excel, pdf

        // Query ke tabel dp3_trans_penilaian
        $query = Dp3TransPenilaian::with(['pegawai', 'office', 'department'])
            ->whereHas('periode', function ($q) use ($tahun) {
                // Filter berdasarkan tahun pada tabel/relasi periode
                $q->where('tahun', $tahun);
            });

        // Filter berdasarkan Jabatan (pgw_id_jabatan)
        if ($jabatanId && $jabatanId !== 'SEMUA JABATAN') {
            $query->where('pgw_id_jabatan', $jabatanId);
        }

        $data = $query->get();

        // Data Profile Header PDAM untuk Kop Surat
        $profile = [
            'namapdam' => 'PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR',
            'kota'     => 'Kab. Bogor',
            'alamat'   => 'Jl. Raya Sukahati No 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913',
            'logo'     => public_path('logo/logo_pdam.png') // Path lokal logo untuk PDF
        ];

        // 1. Ekspor Ke PDF
        if ($action === 'pdf') {
            $pdf = Pdf::loadView('laporan.tahunan.pdf', [
                'data'    => $data,
                'profile' => $profile,
                'tahun'   => $tahun,
                'tglNow'  => date('d-m-Y H:i:s')
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('Laporan_Penilaian_Tahunan_'.$tahun.'.pdf');
        }

        // 2. Ekspor Ke Excel
        if ($action === 'excel') {
            return Excel::download(
                new LaporanTahunanExport($data, $tahun), 
                'Laporan_Penilaian_Tahunan_'.$tahun.'.xlsx'
            );
        }

        // 3. Default: Preview HTML View
        return view('laporan.tahunan.preview', compact('data', 'profile', 'tahun'));
    }
}