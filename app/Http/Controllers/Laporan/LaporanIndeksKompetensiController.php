<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\MasterTemplate;
use App\Models\UnitKerja;
use App\Exports\LaporanIndeksKompetensiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanIndeksKompetensiController extends Controller
{
    public function index()
    {
        $templates = MasterTemplate::orderBy('nama_template')->get();
        $occupations = Occupation::where('is_aktif', 1)
            ->orderBy('occ_name', 'asc')
            ->get();

        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        $unitkerja = UnitKerja::orderBy('kode_unit_kerja')->get();

        return view('laporan.IndeksKompetensi.index', compact('occupations', 'periodes', 'templates', 'unitkerja'));
    }

    public function export(Request $request)
    {
        $filters = $request->validate([
            'jenis_filter' => ['nullable', 'in:per_bulan,per_periode'],
            'periode' => ['nullable', 'string'],
            'periode_awal' => ['nullable', 'string'],
            'periode_akhir' => ['nullable', 'string'],
            'jabatan' => ['nullable', 'string'],
            'template_penilaian' => ['required', 'string', 'exists:dp3_master_template_penilaian,template_id'],
            'unit_kerja' => ['nullable', 'string'],
            'action' => ['nullable', 'in:preview,pdf,excel'],
        ]);

        $template = MasterTemplate::with(['kategoris.pertanyaans'])
            ->findOrFail($filters['template_penilaian']);
        $questions = $template->kategoris
            ->flatMap(function ($kategori) {
                $group = preg_match('/special|spesial|khusus/i', $kategori->nama . ' ' . $kategori->kode)
                    ? 'SPECIALITY'
                    : 'GENERAL';

                return $kategori->pertanyaans->map(fn ($question) => [
                    'id' => $question->pertanyaan_id,
                    'label' => $question->pertanyaan,
                    'group' => $group,
                ]);
            })
            ->unique('id')
            ->values();

        $questionCounters = ['GENERAL' => 0, 'SPECIALITY' => 0];
        $questions = $questions->map(function ($question) use (&$questionCounters) {
            $group = $question['group'];
            $questionCounters[$group]++;
            $question['code'] = ($group === 'SPECIALITY' ? 'S' : 'G') . $questionCounters[$group];

            return $question;
        });

        $periods = Dp3TransPeriodePenilaian::orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        $selectedPeriodIds = $this->resolvePeriodIds($filters, $periods);
        $periodeText = $this->formatPeriodeText($selectedPeriodIds, $periods);

        $employeeQuery = Employee::with(['unitKerja', 'occupation']);
        $occupationIds = $template->occ_id_array;
        if ($occupationIds) {
            $employeeQuery->whereIn('occ_id', $occupationIds);
        }
        if (!empty($filters['jabatan']) && $filters['jabatan'] !== 'SEMUA JABATAN') {
            $employeeQuery->where('occ_id', $filters['jabatan']);
        }
        if (!empty($filters['unit_kerja']) && $filters['unit_kerja'] !== 'SEMUA UNIT KERJA') {
            $employeeQuery->where('kode_unit_kerja', $filters['unit_kerja']);
        }
        $employees = $employeeQuery->get();
        $employeeIds = $employees->pluck('pgw_id');
        $questionIds = $questions->pluck('id');

        $assessmentQuery = Dp3TransPenilaian::with([
            'details' => fn ($query) => $query->whereIn('pertanyaan_id', $questionIds),
        ])->whereIn('pegawai_id', $employeeIds);
        if ($selectedPeriodIds !== null) {
            $assessmentQuery->whereIn('periode_id', $selectedPeriodIds);
        }
        if ($questionIds->isNotEmpty()) {
            $assessmentQuery->whereHas('details', fn ($query) => $query->whereIn('pertanyaan_id', $questionIds));
        }
        $assessmentQuery->whereRaw(
            'UPPER(status_nilai) IN (?, ?, ?, ?, ?, ?)',
            ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'MENUNGGU VERIFIKASI', 'VERIFIED', 'TERVERIFIKASI']
        );

        $assessments = $assessmentQuery
            ->orderByDesc('penilaian_id')
            ->get()
            ->unique(fn ($assessment) => $assessment->pegawai_id . ':' . $assessment->periode_id)
            ->values();

        $scopeOrder = ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI', 'LAINNYA'];
        $reportEmployees = $employees->whereIn('pgw_id', $assessments->pluck('pegawai_id')->unique());
        $employeesByScope = $reportEmployees->groupBy(function ($employee) {
            return $this->lingkupKerja($employee->unitKerja?->nama_unit_kerja ?? '');
        });
        $scopeNames = collect($scopeOrder)
            ->filter(fn ($scope) => $employeesByScope->has($scope))
            ->values();

        $scoresByScope = [];
        $overallScores = [];
        foreach ($scopeNames as $scope) {
            $scoresByScope[$scope] = $questions->mapWithKeys(fn ($question) => [$question['id'] => []])->all();
        }

        foreach ($assessments as $assessment) {
            $employee = $reportEmployees->firstWhere('pgw_id', $assessment->pegawai_id);
            if (!$employee) {
                continue;
            }

            $scope = $this->lingkupKerja($employee->unitKerja?->nama_unit_kerja ?? '');
            foreach ($assessment->details as $detail) {
                $questionId = $detail->pertanyaan_id;
                $score = $detail->verif_nilai_angka ?? $detail->nilai_angka;
                if (!is_numeric($score) || !array_key_exists($questionId, $scoresByScope[$scope] ?? [])) {
                    continue;
                }

                $score = (float) $score;
                $scoresByScope[$scope][$questionId][] = $score;
                $overallScores[$questionId][] = $score;
            }
        }

        $reportRows = $scopeNames->map(function ($scope, $index) use ($questions, $scoresByScope) {
            $values = $questions->mapWithKeys(function ($question) use ($scope, $scoresByScope) {
                $scores = $scoresByScope[$scope][$question['id']];

                return [$question['id'] => $scores ? array_sum($scores) / count($scores) : null];
            });

            return [
                'no' => $index + 1,
                'lingkup' => $scope,
                'values' => $values,
            ];
        });

        $overallValues = $questions->mapWithKeys(function ($question) use ($overallScores) {
            $scores = $overallScores[$question['id']] ?? [];

            return [$question['id'] => $scores ? array_sum($scores) / count($scores) : null];
        });

        $unitKerjaText = 'SEMUA UNIT KERJA';
        if (!empty($filters['unit_kerja']) && $filters['unit_kerja'] !== 'SEMUA UNIT KERJA') {
            $unitKerjaText = $employees->first()?->unitKerja?->nama_unit_kerja
                ?? $filters['unit_kerja'];
        }

        $profile = [
            'namapdam' => 'PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR',
            'kota' => 'Kab. Bogor',
            'alamat' => 'Jl. Raya Sukahati No 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913',
            'logo' => public_path('logo/logo_pdam.png'),
        ];
        $predikatNilai = $overallValues->mapWithKeys(fn ($nilai, $questionId) => [
            $questionId => $this->hitungPredikat($nilai),
        ]);
        $tglNow = now()->format('d-m-Y H:i:s');
        $viewData = compact(
            'reportRows',
            'questions',
            'overallValues',
            'predikatNilai',
            'profile',
            'periodeText',
            'unitKerjaText',
            'tglNow'
        );

        if (($filters['action'] ?? 'preview') === 'pdf') {
            return Pdf::loadView('laporan.IndeksKompetensi.pdf', $viewData)
                ->setPaper('a4', 'landscape')
                ->stream('Laporan_Indeks_Kompetensi_' . str_replace(' ', '_', $periodeText) . '.pdf');
        }

        if (($filters['action'] ?? 'preview') === 'excel') {
            return Excel::download(
                new LaporanIndeksKompetensiExport($viewData),
                'Laporan_Indeks_Kompetensi_' . str_replace(' ', '_', $periodeText) . '.xlsx'
            );
        }

        return view('laporan.IndeksKompetensi.preview', $viewData);
    }

    private function resolvePeriodIds(array $filters, $periods): ?array
    {
        if (($filters['jenis_filter'] ?? 'per_bulan') === 'per_periode') {
            if (empty($filters['periode_awal']) || empty($filters['periode_akhir'])) {
                return [];
            }

            $periodIds = $periods->pluck('periode_id')->all();
            $start = array_search($filters['periode_awal'], $periodIds, true);
            $end = array_search($filters['periode_akhir'], $periodIds, true);
            if ($start === false || $end === false) {
                return [];
            }

            return array_slice($periodIds, min($start, $end), abs($end - $start) + 1);
        }

        return !empty($filters['periode']) ? [$filters['periode']] : null;
    }

    private function formatPeriodeText(?array $periodIds, $periods): string
    {
        if ($periodIds === null) {
            return 'SEMUA PERIODE';
        }
        if ($periodIds === []) {
            return 'PERIODE TIDAK DIPILIH';
        }

        $selectedPeriods = $periods->whereIn('periode_id', $periodIds)->values();
        $format = fn ($period) => strtoupper(
            \Illuminate\Support\Carbon::createFromDate($period->tahun, $period->bulan, 1)->translatedFormat('F Y')
        );

        if ($selectedPeriods->count() === 1) {
            return $format($selectedPeriods->first());
        }

        return $format($selectedPeriods->first()) . ' - ' . $format($selectedPeriods->last());
    }

    private function lingkupKerja(string $namaUnit): string
    {
        $nama = strtolower($namaUnit);
        if (str_contains($nama, 'cabang')) {
            return 'CABANG';
        }
        if (str_contains($nama, 'instalasi')) {
            return 'INSTALASI';
        }
        if (str_contains($nama, 'spi') || str_contains($nama, 'renbang') || str_contains($nama, 'sekretariat')
            || str_contains($nama, 'pengawasan intern') || str_contains($nama, 'perencanaan')) {
            return 'DIVISI UTAMA';
        }
        if (str_contains($nama, 'pemasaran') || str_contains($nama, 'humas') || str_contains($nama, 'keuangan')
            || str_contains($nama, 'sdm') || str_contains($nama, 'sumber daya') || str_contains($nama, 'umum')
            || str_contains($nama, 'pengadaan') || str_contains($nama, 'mis')) {
            return 'DIVISI UMUM';
        }
        if (str_contains($nama, 'distribusi') || str_contains($nama, 'nrw') || str_contains($nama, 'produksi')
            || str_contains($nama, 'penjaminan mutu') || str_contains($nama, 'k3') || str_contains($nama, 'pelaksana')
            || str_contains($nama, 'teknik')) {
            return 'DIVISI OPERASIONAL';
        }

        return 'LAINNYA';
    }

    private function hitungPredikat(?float $nilai): string
    {
        return match (true) {
            $nilai === null => 'BELUM DINILAI',
            $nilai >= 90 => 'LUAR BIASA',
            $nilai >= 80 => 'DIATAS EKSPEKTASI',
            $nilai >= 70 => 'SESUAI EKSPEKTASI',
            $nilai >= 60 => 'DIBAWAH EKSPEKTASI',
            $nilai > 0 => 'MENGECEWAKAN',
            default => 'BELUM DINILAI',
        };
    }
}
