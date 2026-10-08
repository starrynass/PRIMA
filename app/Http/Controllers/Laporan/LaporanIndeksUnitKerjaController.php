<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\MasterTemplate;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanIndeksUnitKerjaExport;

class LaporanIndeksUnitKerjaController extends Controller
{
    public function index()
    {
        $occupations = Occupation::where('is_aktif', 1)
            ->orderBy('occ_name', 'asc')
            ->get();

        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        $unitkerja = UnitKerja::orderBy('kode_unit_kerja')->get();

        return view('laporan.IndeksUnitKerja.index', compact('occupations', 'periodes', 'unitkerja'));
    }

    public function export(Request $request)
    {
        $periodeId  = $request->input('periode') ?? $request->input('txt_periode');
        $jabatanId  = $request->input('jabatan');
        $unitKerja  = $request->input('unit_kerja') ?? $request->input('slc_cabang');
        $action     = $request->input('action'); // preview, excel, pdf

        // Query Utama
        $query = Dp3TransPenilaian::with(['unitKerja', 'periode']);

        // 1. FILTER PERIODE (Wajib jika dikirim dari form)
        if ($periodeId && $periodeId !== 'SEMUA PERIODE') {
            $query->where('periode_id', $periodeId);
        }
        // 2. FILTER HANYA YANG SUDAH DINILAI (Mengabaikan data yang belum diisi nilainya)
        $query->where(function ($q) {
            $q->whereNotNull('total_nilai_verifikator')
            ->orWhereNotNull('total_nilai');
        })->where(function ($q) {
            $q->where('total_nilai_verifikator', '>', 0)
            ->orWhere('total_nilai', '>', 0);
        });

        // 3. FILTER JABATAN
        if ($jabatanId && $jabatanId !== 'SEMUA JABATAN') {
            $query->where('pgw_id_jabatan', $jabatanId);
        }

        // 4. FILTER UNIT KERJA
        if ($unitKerja && $unitKerja !== 'SEMUA UNIT KERJA') {
            $query->where('pgw_kode_unit_kerja', $unitKerja);
        }

        $rawCollection = $query->get();

        $periodeText = 'SEMUA PERIODE';
        if ($periodeId && $periodeId !== 'SEMUA PERIODE') {
            $periodeData = Dp3TransPeriodePenilaian::find($periodeId) 
                ?? Dp3TransPeriodePenilaian::where('nama_periode', 'LIKE', "%{$periodeId}%")->first();

            if ($periodeData) {
                $periodeText = strtoupper($periodeData->nama_periode);
            } else {
                // Jika ID tidak ditemukan tapi input berupa string (misal: "FEBRUARI 2026")
                $periodeText = strtoupper($periodeId);
            }
        }

        // Grouping berdasarkan Lingkup Kerja dari relasi unitKerja
        $groupedRaw = $rawCollection->groupBy(function ($item) {
            // Jika relasi null, fallback ambil dari nama penempatan / office
            return $item->unitKerja->lingkup_kerja 
                ?? $item->office->lingkup_kerja 
                ?? 'LAINNYA';
        });

        $urutanLingkup = ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI', 'LAINNYA'];
        $groupedData = collect();

        foreach ($urutanLingkup as $lingkup) {
            if ($groupedRaw->has($lingkup)) {
                $itemsInLingkup = $groupedRaw->get($lingkup);

                $unitsInLingkup = $itemsInLingkup->groupBy(function ($item) {
                    // Ambil nama unit kerja dari relasi unitKerja atau office/pgw_off_name
                    return $item->unitKerja->nama_unit_kerja 
                        ?? $item->unitKerja->nama_unit 
                        ?? $item->office->off_name 
                        ?? $item->pgw_off_name 
                        ?? 'TANPA NAMA UNIT';
                })->map(function ($itemsPerUnit, $unitName) {
                    // Hitung jumlah pegawai unik yang dinilai di unit ini
                    $jumlahPegawai = $itemsPerUnit->pluck('pgw_id')->unique()->count() ?: $itemsPerUnit->count();

                    $avgNilai = $itemsPerUnit->avg(function ($i) {
                        return (float)($i->total_nilai_verifikator ?? $i->total_nilai ?? 0);
                    });

                    return [
                        'unit_kerja'     => $unitName,
                        'jumlah_pegawai' => $jumlahPegawai,
                        'nilai_rata'     => round($avgNilai, 2),
                        'predikat'       => $this->hitungPredikat($avgNilai)
                    ];
                });

                $groupedData->put($lingkup, $unitsInLingkup);
            }
        }
        
        $profile = [
            'namapdam' => 'PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR',
            'kota'     => 'Kab. Bogor',
            'alamat'   => 'Jl. Raya Sukahati No 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913',
            'logo'     => public_path('logo/logo_pdam.png')
        ];

        if ($action === 'pdf') {
            $pdf = Pdf::loadView('laporan.IndeksUnitKerja.pdf', [
                'groupedData' => $groupedData,
                'profile'     => $profile,
                'periodeText' => $periodeText,
                'tglNow'      => date('d-m-Y H:i:s')
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('Laporan_Indeks_Unit_Kerja_' . str_replace(' ', '_', $periodeText) . '.pdf');
        }

        // 2. Ekspor Ke Excel
        if ($action === 'excel') {
            return Excel::download(
                new LaporanIndeksUnitKerjaExport($groupedData, $periodeText),
                'Laporan_Indeks_Unit_Kerja_' . str_replace(' ', '_', $periodeText) . '.xlsx'
            );
        }

        // 3. Default: Preview HTML View
        return view('laporan.IndeksUnitKerja.preview', compact(
            'groupedData', 
            'profile', 
            'periodeText'
        ));
    }

    private function hitungPredikat($nilai)
    {
        return match (true) {
            $nilai >= 90 => 'LUAR BIASA',
            $nilai >= 80 => 'DIATAS EKSPEKTASI',
            $nilai >= 70 => 'SESUAI EKSPEKTASI',
            $nilai >= 60 => 'DIBAWAH EKSPEKTASI',
            $nilai > 0   => 'MENGECEWAKAN',
            default      => 'BELUM DINILAI',
        };
    }
}
