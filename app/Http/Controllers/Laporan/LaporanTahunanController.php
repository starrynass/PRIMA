<?php
namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTahunanExport;

class LaporanTahunanController extends Controller
{
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
        $filters = $request->validate([
            'jenis_filter' => ['required', 'in:per_tahun,per_periode'],
            'tahun' => ['required_if:jenis_filter,per_tahun', 'nullable', 'integer', 'digits:4'],
            'periode_awal' => ['required_if:jenis_filter,per_periode', 'nullable', 'exists:dp3_trans_periode_penilaian,periode_id'],
            'periode_akhir' => ['required_if:jenis_filter,per_periode', 'nullable', 'exists:dp3_trans_periode_penilaian,periode_id'],
            'jenis_laporan' => ['required', 'in:tahunan,rata_rata,rekapitulasi,grafik_divisi,grafik_unit_kerja'],
            'action' => ['nullable', 'in:preview,excel,pdf'],
        ]);

        $jenisFilter = $filters['jenis_filter'];
        $jenisLaporan = $filters['jenis_laporan'];
        $periods = Dp3TransPeriodePenilaian::orderBy('tahun')->orderBy('bulan')->get();
        $selectedPeriods = $jenisFilter === 'per_periode'
            ? $this->getPeriodsInRange($request, $periods)
            : $periods->where('tahun', (int) $tahun)->values();
        $periodIds = $selectedPeriods->pluck('periode_id');

        $query = Dp3TransPenilaian::with([
            'pegawai',
            'office',
            'department',
            'occupation',
            'unitKerja',
            'periode',
        ])->whereIn('periode_id', $periodIds)
            ->whereRaw(
                'UPPER(status_nilai) IN (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'MENUNGGU VERIFIKASI', 'VERIFIED', 'TERVERIFIKASI', 'VERIFIKASI', 'SELESAI', 'APPROVED']
            );

        // Filter berdasarkan Jabatan (pgw_id_jabatan)
        if ($jabatanId && $jabatanId !== 'SEMUA JABATAN') {
            $query->where('pgw_id_jabatan', $jabatanId);
        }

        $data = $query->orderBy('penilaian_id')->get();
        $monthColumns = $this->getMonthColumns($selectedPeriods, $jenisFilter, $tahun);
        $unitMatrix = $this->buildUnitMatrix($data, $monthColumns);
        $scopeOrder = ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI', 'LAINNYA'];
        $unitsByScope = $unitMatrix->groupBy('scope')
            ->sortBy(fn ($units, $scope) => array_search($scope, $scopeOrder, true));
        $scopeSummaries = $this->buildScopeSummaries($data, $monthColumns);
        $periodText = $this->getPeriodText($selectedPeriods, $jenisFilter, $tahun);
        $reportTitle = $this->getReportTitle($jenisLaporan);

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
                'jenisLaporan' => $jenisLaporan,
                'reportTitle' => $reportTitle,
                'periodText' => $periodText,
                'monthColumns' => $monthColumns,
                'unitsByScope' => $unitsByScope,
                'unitMatrix' => $unitMatrix,
                'scopeSummaries' => $scopeSummaries,
                'tglNow'  => date('d-m-Y H:i:s')
            ])->setPaper('a4', 'landscape');

            return $pdf->stream('Laporan_' . str_replace(' ', '_', $reportTitle) . '_' . $tahun . '.pdf');
        }

        // 2. Ekspor Ke Excel
        if ($action === 'excel') {
            return Excel::download(
                new LaporanTahunanExport(
                    $data,
                    $tahun,
                    $jenisLaporan,
                    $reportTitle,
                    $periodText,
                    $monthColumns,
                    $unitsByScope,
                    $unitMatrix,
                    $scopeSummaries
                ),
                'Laporan_' . str_replace(' ', '_', $reportTitle) . '_' . $tahun . '.xlsx'
            );
        }

        // 3. Default: Preview HTML View
        return view('laporan.tahunan.preview', compact(
            'data',
            'profile',
            'tahun',
            'jenisLaporan',
            'reportTitle',
            'periodText',
            'monthColumns',
            'unitsByScope',
            'unitMatrix',
            'scopeSummaries'
        ));
    }

    private function getPeriodsInRange(Request $request, $periods)
    {
        $startId = $request->input('periode_awal');
        $endId = $request->input('periode_akhir');
        if (!$startId || !$endId) {
            return collect();
        }

        $periodIds = $periods->pluck('periode_id')->all();
        $startIndex = array_search($startId, $periodIds, true);
        $endIndex = array_search($endId, $periodIds, true);
        if ($startIndex === false || $endIndex === false) {
            return collect();
        }

        return $periods->slice(min($startIndex, $endIndex), abs($endIndex - $startIndex) + 1)->values();
    }

    private function getMonthColumns($selectedPeriods, string $jenisFilter, $tahun): array
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        if ($jenisFilter === 'per_periode') {
            return $selectedPeriods->map(fn ($period) => [
                'key' => sprintf('%04d-%02d', $period->tahun, $period->bulan),
                'label' => $months[(int) $period->bulan] . ' ' . $period->tahun,
                'month' => (int) $period->bulan,
                'year' => (int) $period->tahun,
            ])->all();
        }

        return collect($months)->map(fn ($label, $month) => [
            'key' => sprintf('%04d-%02d', $tahun, $month),
            'label' => $label,
            'month' => $month,
            'year' => (int) $tahun,
        ])->values()->all();
    }

    private function buildUnitMatrix($data, array $monthColumns)
    {
        $rows = collect();
        $monthKeys = array_fill_keys(array_column($monthColumns, 'key'), true);
        foreach ($data as $assessment) {
            $unitName = $assessment->unitKerja->nama_unit_kerja
                ?? $assessment->pegawai?->unitKerja?->nama_unit_kerja
                ?? $assessment->pgw_off_name
                ?? 'UNIT KERJA LAINNYA';
            $scope = $this->lingkupKerja($unitName);
            $periodKey = sprintf('%04d-%02d', $assessment->periode->tahun, $assessment->periode->bulan);
            if (!isset($monthKeys[$periodKey])) {
                continue;
            }

            if (!$rows->has($unitName)) {
                $rows->put($unitName, [
                    'name' => $unitName,
                    'scope' => $scope,
                    'scores' => [],
                ]);
            }

            $score = (float) ($assessment->total_nilai_verifikator ?? $assessment->total_nilai ?? 0);
            $row = $rows->get($unitName);
            $row['scores'][$periodKey][] = $score;
            $rows->put($unitName, $row);
        }

        return $rows->map(function ($row) use ($monthColumns) {
            $monthlyValues = [];
            $allScores = [];
            foreach ($monthColumns as $month) {
                $scores = $row['scores'][$month['key']] ?? [];
                $monthlyValues[$month['key']] = $scores ? array_sum($scores) / count($scores) : null;
                array_push($allScores, ...$scores);
            }
            $row['monthly_values'] = $monthlyValues;
            $row['average'] = $allScores ? array_sum($allScores) / count($allScores) : null;
            $row['average_predicate'] = $this->hitungPredikat($row['average']);
            $row['predicates'] = collect($monthlyValues)->map(fn ($score) => $this->hitungPredikat($score))->all();

            return $row;
        })->sortBy([
            fn ($row) => array_search($row['scope'], ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI', 'LAINNYA'], true),
            fn ($row) => $row['name'],
        ], SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function buildScopeSummaries($data, array $monthColumns): array
    {
        $scoresByScope = [];
        $monthKeys = array_fill_keys(array_column($monthColumns, 'key'), true);
        foreach ($data as $assessment) {
            $unitName = $assessment->unitKerja->nama_unit_kerja
                ?? $assessment->pegawai?->unitKerja?->nama_unit_kerja
                ?? $assessment->pgw_off_name
                ?? 'UNIT KERJA LAINNYA';
            $scope = $this->lingkupKerja($unitName);
            $periodKey = sprintf('%04d-%02d', $assessment->periode->tahun, $assessment->periode->bulan);
            if (!isset($monthKeys[$periodKey])) {
                continue;
            }

            $scoresByScope[$scope][$periodKey][] = (float) (
                $assessment->total_nilai_verifikator ?? $assessment->total_nilai ?? 0
            );
        }

        $summaries = [];
        foreach ($scoresByScope as $scope => $scoresByMonth) {
            $values = [];
            $allScores = [];
            foreach ($monthColumns as $month) {
                $scores = $scoresByMonth[$month['key']] ?? [];
                $values[$month['key']] = $scores ? array_sum($scores) / count($scores) : null;
                array_push($allScores, ...$scores);
            }
            $summaries[$scope] = [
                'monthly_values' => $values,
                'average' => $allScores ? array_sum($allScores) / count($allScores) : null,
                'average_predicate' => $this->hitungPredikat(
                    $allScores ? array_sum($allScores) / count($allScores) : null
                ),
                'predicates' => collect($values)->map(fn ($score) => $this->hitungPredikat($score))->all(),
            ];
        }

        $scopeOrder = ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI', 'LAINNYA'];
        uksort($summaries, fn ($left, $right) => array_search($left, $scopeOrder, true) <=> array_search($right, $scopeOrder, true));

        return $summaries;
    }

    private function getPeriodText($selectedPeriods, string $jenisFilter, $tahun): string
    {
        if ($jenisFilter !== 'per_periode') {
            return 'TAHUN ' . $tahun;
        }
        if ($selectedPeriods->isEmpty()) {
            return 'RENTANG PERIODE BELUM DIPILIH';
        }

        return strtoupper($selectedPeriods->first()->nama_periode)
            . ' - ' . strtoupper($selectedPeriods->last()->nama_periode);
    }

    private function getReportTitle(string $jenisLaporan): string
    {
        return match ($jenisLaporan) {
            'rata_rata' => 'LAPORAN RATA-RATA PENILAIAN KINERJA SEMUA JABATAN',
            'rekapitulasi' => 'LAPORAN REKAPITULASI PENILAIAN KINERJA SEMUA JABATAN',
            'grafik_divisi' => 'LAPORAN GRAFIK DIVISI TAHUNAN SEMUA JABATAN',
            'grafik_unit_kerja' => 'LAPORAN GRAFIK UNIT KERJA TAHUNAN SEMUA JABATAN',
            default => 'LAPORAN BERKALA PENILAIAN KINERJA PEGAWAI',
        };
    }

    private function lingkupKerja(string $namaUnit): string
    {
        $name = strtolower($namaUnit);
        if (str_contains($name, 'cabang')) {
            return 'CABANG';
        }
        if (str_contains($name, 'instalasi')) {
            return 'INSTALASI';
        }
        if (str_contains($name, 'spi') || str_contains($name, 'renbang') || str_contains($name, 'sekretariat')
            || str_contains($name, 'pengawasan intern') || str_contains($name, 'perencanaan') || str_contains($name, 'mis')) {
            return 'DIVISI UTAMA';
        }
        if (str_contains($name, 'pemasaran') || str_contains($name, 'humas') || str_contains($name, 'keuangan')
            || str_contains($name, 'sdm') || str_contains($name, 'sumber daya') || str_contains($name, 'umum')
            || str_contains($name, 'pengadaan')) {
            return 'DIVISI UMUM';
        }
        if (str_contains($name, 'distribusi') || str_contains($name, 'nrw') || str_contains($name, 'produksi')
            || str_contains($name, 'penjaminan mutu') || str_contains($name, 'k3') || str_contains($name, 'pelaksana')
            || str_contains($name, 'teknik')) {
            return 'DIVISI OPERASIONAL';
        }

        return 'LAINNYA';
    }

    private function hitungPredikat(?float $nilai): string
    {
        return match (true) {
            $nilai === null => '-',
            $nilai >= 90 => 'LUAR BIASA',
            $nilai >= 80 => 'DIATAS EKSPEKTASI',
            $nilai >= 70 => 'SESUAI EKSPEKTASI',
            $nilai >= 60 => 'DIBAWAH EKSPEKTASI',
            $nilai > 0 => 'MENGECEWAKAN',
            default => 'BELUM DINILAI',
        };
    }
}