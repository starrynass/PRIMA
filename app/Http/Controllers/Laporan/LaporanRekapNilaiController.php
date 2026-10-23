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
        $periodeId = $request->input('periode');
        $jabatanId = $request->input('jabatan');
        $templateId = $request->input('template_penilaian');
        $unitKerja = $request->input('unit_kerja');
        $action = $request->input('action');
        $selectedTemplate = $templateId
            ? MasterTemplate::with('kategoris.pertanyaans')->find($templateId)
            : null;

        $queryPegawai = Employee::with(['occupation', 'office', 'unitKerja']);

        if ($selectedTemplate) {
            $queryPegawai->whereIn('occ_id', $selectedTemplate->occ_id_array);
        }

        if ($unitKerja && $unitKerja !== 'SEMUA UNIT KERJA') {
            $queryPegawai->where('kode_unit_kerja', $unitKerja);
        }

        if ($jabatanId && $jabatanId !== 'SEMUA JABATAN') {
            $queryPegawai->where('occ_id', $jabatanId);
        }

        $pegawaiList = $queryPegawai->get();
        $pegawaiIds = $pegawaiList->pluck('pgw_id');

        $queryPenilaian = Dp3TransPenilaian::with('details')
            ->whereIn('pegawai_id', $pegawaiIds);

        if ($periodeId) {
            $queryPenilaian->where('periode_id', $periodeId);
        }

        $penilaianCollection = $queryPenilaian
            ->orderByDesc('penilaian_id')
            ->get();
        $penilaianMap = $penilaianCollection->keyBy(
            fn ($penilaian) => (string) $penilaian->pegawai_id
        );

        if ($selectedTemplate) {
            $templates = collect([$selectedTemplate]);
        } else {
            $templates = MasterTemplate::with('kategoris.pertanyaans')->get();
        }

        $questions = $templates
            ->flatMap(fn ($template) => $template->kategoris->flatMap(
                fn ($kategori) => $kategori->pertanyaans->map(fn ($pertanyaan) => [
                    'id' => $pertanyaan->pertanyaan_id,
                    'group' => preg_match('/special|spesial|khusus/i', $kategori->nama . ' ' . $kategori->kode)
                        ? 'SPECIALITY'
                        : 'GENERAL',
                ])
            ))
            ->unique('id')
            ->values();

        $questionCounters = ['GENERAL' => 0, 'SPECIALITY' => 0];
        $questions = $questions->map(function ($question) use (&$questionCounters) {
            $group = $question['group'];
            $questionCounters[$group]++;
            $prefix = $group === 'SPECIALITY' ? 'S' : 'G';
            $question['code'] = $prefix . $questionCounters[$group];

            return $question;
        });

        $periodeData = Dp3TransPeriodePenilaian::find($periodeId);
        $periodeText = $periodeData ? strtoupper($periodeData->nama_periode) : 'SEMUA PERIODE';

        $mappedData = $pegawaiList->map(function ($pgw) use ($penilaianMap, $questions) {
            $penilaian = $penilaianMap->get((string) $pgw->pgw_id);
            $detailByQuestion = $penilaian
                ? $penilaian->details->keyBy('pertanyaan_id')
                : collect();
            $nilaiPerPertanyaan = $questions->mapWithKeys(function ($question) use ($detailByQuestion) {
                $detail = $detailByQuestion->get($question['id']);
                $nilai = $detail
                    ? ($detail->verif_kode_nilai ?: $detail->kode_nilai)
                    : null;

                return [$question['id'] => $nilai ?: '-'];
            })->all();

            $totalNilai = $penilaian
                ? ($penilaian->total_nilai_verifikator ?? $penilaian->total_nilai ?? 0)
                : 0;
            $predikat = $penilaian
                ? ($penilaian->predikat_verifikator ?: ($penilaian->predikat ?: '-'))
                : '-';

            return [
                'nama' => $pgw->nama ?: ($penilaian->pgw_nama ?? '-'),
                'jabatan' => $pgw->occupation->occ_name ?? $penilaian->pgw_jabatan ?? '-',
                'unit_kerja' => $pgw->unitKerja->nama_unit_kerja
                    ?? $pgw->kode_unit_kerja
                    ?? $pgw->office->off_name
                    ?? $penilaian->pgw_off_name
                    ?? 'UNIT KERJA LAINNYA',
                'nilai_pertanyaan' => $nilaiPerPertanyaan,
                'total_nilai' => $totalNilai,
                'predikat' => strtoupper($predikat),
            ];
        });

        $groupedData = $mappedData->groupBy('unit_kerja');

        $profile = [
            'namapdam' => 'PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR',
            'kota' => 'Kab. Bogor',
            'alamat' => 'Jl. Raya Sukahati No 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913',
            'logo' => public_path('logo/logo_pdam.png')
        ];

        $tglNow = now()->format('d-m-Y H:i:s');

        if ($action === 'pdf') {
            $pdf = Pdf::loadView('laporan.RekapNilai.pdf', [
                'groupedData' => $groupedData,
                'profile' => $profile,
                'periodeText' => $periodeText,
                'questions' => $questions,
                'tglNow' => $tglNow
            ])->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan_Rekap_Nilai_' . str_replace(' ', '_', $periodeText) . '.pdf');
        }

        if ($action === 'excel') {
            return Excel::download(new LaporanRekapNilaiExport($groupedData, $periodeText, $questions), 'Laporan_Rekap_Nilai_' . str_replace(' ', '_', $periodeText) . '.xlsx');
        }

        return view('laporan.RekapNilai.preview', compact('groupedData', 'profile', 'periodeText', 'questions'));
    }
}