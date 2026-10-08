<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\MasterTemplate;
use App\Models\UnitKerja;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekapNilaiExport;

class LaporanRekapNilaiController extends Controller
{
    public function index()
    {
        $templates = MasterTemplate::orderBy('nama_template')->get();
        $occupations = Occupation::where('is_aktif', 1)
            ->orderBy('occ_name', 'asc')
            ->get();
        $unitKerja = UnitKerja::orderBy('kode_unit_kerja')->get();

        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('laporan.RekapNilai.index', compact('occupations', 'periodes', 'templates', 'unitKerja'));
    }

    public function export(Request $request)
{
    $periodeId  = $request->input('periode');
    $jabatanId  = $request->input('jabatan');
    $templateId = $request->input('template_penilaian');
    $unitKerja  = $request->input('unit_kerja');
    $action     = $request->input('action'); 

    // 1. Ambil Data Pegawai
    $queryPegawai = Employee::with(['occupation', 'office', 'unitKerja']);

    // Filter Unit Kerja
    if ($unitKerja && $unitKerja !== 'SEMUA UNIT KERJA') {
        $queryPegawai->where(function($q) use ($unitKerja) {
            $q->where('pgw_kode_unit_kerja', $unitKerja)
              ->orWhere('kode_unit_kerja', $unitKerja)
              ->orWhere('off_id', $unitKerja);
        });
    }

    // Filter Jabatan
    if ($jabatanId && $jabatanId !== 'SEMUA JABATAN') {
        $queryPegawai->where(function($q) use ($jabatanId) {
            $q->where('occ_id', $jabatanId)
              ->orWhere('pgw_id_jabatan', $jabatanId);
        });
    }

    $pegawaiList = $queryPegawai->get();

    // 2. Ambil Penilaian Sesuai Periode
    $queryPenilaian = Dp3TransPenilaian::query();
    if ($periodeId) {
        $queryPenilaian->where('periode_id', $periodeId);
    }
    
    $penilaianMap = $queryPenilaian->get()->keyBy('pgw_id');

    $periodeData = Dp3TransPeriodePenilaian::find($periodeId);
    $periodeText = $periodeData ? strtoupper($periodeData->nama_periode) : 'SEMUA PERIODE';

    // 3. Mapping Data dengan Pengecekan Kolom yang Lebih Lengkap
    $mappedData = $pegawaiList->map(function ($pgw) use ($penilaianMap) {
        // Ambil data transaksi berdasarkan ID Pegawai
        $pgwId = $pgw->id ?? $pgw->pgw_id;
        $penilaian = $penilaianMap->get($pgwId);

        // Pengecekan nama pegawai di berbagai kemungkinan nama kolom
        $namaPegawai = $pgw->pgw_nama 
            ?? $pgw->nama_pegawai 
            ?? $pgw->nama 
            ?? $penilaian->pgw_nama 
            ?? '-';

        // Pengecekan nama jabatan
        $namaJabatan = $pgw->occupation->occ_name 
            ?? $pgw->jabatan->nama_jabatan 
            ?? $pgw->pgw_jabatan_name 
            ?? $penilaian->pgw_jabatan_name 
            ?? '-';

        // Pengecekan nama unit kerja
        $namaUnitKerja = $pgw->unitKerja->nama_unit_kerja 
            ?? $pgw->office->off_name 
            ?? $pgw->pgw_off_name 
            ?? $penilaian->pgw_off_name 
            ?? 'UNIT KERJA LAINNYA';

        return [
            'nama'        => $namaPegawai,
            'jabatan'     => $namaJabatan,
            'unit_kerja'  => $namaUnitKerja,
            
            // Kolom Nilai Alfabet
            'g1'          => $penilaian->g1 ?? $penilaian->q1 ?? 'B',
            'g2'          => $penilaian->g2 ?? $penilaian->q2 ?? 'B',
            'g3'          => $penilaian->g3 ?? $penilaian->q3 ?? 'B',
            'g4'          => $penilaian->g4 ?? $penilaian->q4 ?? 'C',
            'g5'          => $penilaian->g5 ?? $penilaian->q5 ?? 'B',
            'g6'          => $penilaian->g6 ?? $penilaian->q6 ?? 'C',
            's1'          => $penilaian->s1 ?? 'B',
            's2'          => $penilaian->s2 ?? 'C',
            
            'total_nilai' => ($penilaian && ($penilaian->total_nilai_verifikator || $penilaian->total_nilai)) 
                ? number_format($penilaian->total_nilai_verifikator ?? $penilaian->total_nilai, 2) 
                : '0.00',
            'predikat'    => ($penilaian && $penilaian->predikat) 
                ? strtoupper($penilaian->predikat) 
                : '-'
        ];
    });

    // Grouping Berdasarkan Unit Kerja
    $groupedData = $mappedData->groupBy('unit_kerja');

    $profile = [
        'namapdam' => 'PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR',
        'kota'     => 'Kab. Bogor',
        'alamat'   => 'Jl. Raya Sukahati No 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913',
        'logo'     => public_path('logo/logo_pdam.png')
    ];

    // Response View
    if ($action === 'pdf') {
        $pdf = Pdf::loadView('laporan.RekapNilai.pdf', compact('groupedData', 'profile', 'periodeText'))->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_Rekap_Nilai.pdf');
    }

    if ($action === 'excel') {
        return Excel::download(new LaporanRekapNilaiExport($groupedData, $periodeText), 'Laporan_Rekap_Nilai.xlsx');
    }

    return view('laporan.RekapNilai.preview', compact('groupedData', 'profile', 'periodeText'));
}
}
