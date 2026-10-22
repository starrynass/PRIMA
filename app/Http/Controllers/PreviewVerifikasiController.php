<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dp3TransPenilaian;
use App\Models\MasterSkalaNilai;
use App\Models\MasterTemplate;
use App\Models\MasterKategori;

class PreviewVerifikasiController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data transaksi penilaian beserta relasi
        $penilaian = Dp3TransPenilaian::with([
            'periode', 
            'pegawai', 
            'penilai', 
            'verifikator', 
            'office', 
            'department', 
            'occupation', 
            'unitkerja',
            'details'
        ])->where('penilaian_id', $id)->firstOrFail();

        // 2. Ambil Template terkait
        $templateId = $penilaian->template_id;
        $template = null;
        if ($templateId) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->find($templateId);
        }

        if (!$template && $penilaian->pgw_id_jabatan) {
            $template = MasterTemplate::with('kategoris.pertanyaans')
                ->get()
                ->first(function ($t) use ($penilaian) {
                    return in_array($penilaian->pgw_id_jabatan, $t->occ_id_array);
                });
        }

        if (!$template) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->where('status_aktif', 1)->first();
        }

        // 3. Mapping data detail transaksi (Nilai Penilai vs Nilai Verifikator)
        $transDetailsMap = $penilaian->details->keyBy('pertanyaan_id');
        $skalaNilaiMap = MasterSkalaNilai::all()->keyBy(function ($skala) {
            return strtoupper(trim($skala->kode_nilai));
        });

        $kategoriList = collect();
        if ($template && $template->kategoris) {
            $kategoriList = $template->kategoris->map(function ($kat) use ($transDetailsMap, $skalaNilaiMap) {
                
                $mappedPertanyaan = $kat->pertanyaans->map(function ($q) use ($transDetailsMap, $skalaNilaiMap) {
                    $filledDetail = $transDetailsMap->get($q->pertanyaan_id);
                    $kodeNilaiPenilai = $filledDetail->kode_nilai ?? null;
                    $hasNilaiVerifikator = !empty($filledDetail->verif_kode_nilai)
                        || $filledDetail?->verif_nilai_angka !== null;
                    $kodeNilaiVerifikator = $hasNilaiVerifikator
                        ? ($filledDetail->verif_kode_nilai ?? null)
                        : $kodeNilaiPenilai;
                    $nilaiSkala = $skalaNilaiMap->get(strtoupper(trim((string) $kodeNilaiVerifikator)))->nilai_angka
                        ?? null;
                    $nilaiAkhir = ($hasNilaiVerifikator
                        ? ($filledDetail->verif_nilai_angka ?? $nilaiSkala ?? $filledDetail->nilai_angka)
                        : ($filledDetail->nilai_angka ?? $nilaiSkala)) ?? 0;

                    return (object)[
                        'pertanyaan_id'           => $q->pertanyaan_id,
                        'pertanyaan'              => $q->pertanyaan,
                        'deskripsi'               => $q->deskripsi,
                        'bobot_pertanyaan_persen' => $q->bobot_persen ?? 0,
                        
                        // Nilai Penilai Pertama
                        'kode_nilai_penilai'      => $kodeNilaiPenilai,
                        'catatan_penilai'         => $filledDetail->catatan ?? null,
                        
                        // Nilai Koreksi Verifikator
                        'kode_nilai_verifikator'  => $kodeNilaiVerifikator,
                        'catatan_verifikator'     => $filledDetail->verif_catatan ?? $filledDetail->catatan_verifikasi ?? null,
                        
                        // Hasil Akhir
                        'nilai_akhir'             => (float) $nilaiAkhir,
                    ];
                });

                $bobotKategori = (float) ($kat->bobot_persen ?? 0);
                $jumlahPertanyaan = $mappedPertanyaan->count();
                $rataRataKategori = $jumlahPertanyaan > 0
                    ? $mappedPertanyaan->sum('nilai_akhir') / $jumlahPertanyaan
                    : 0;
                $subtotalNilai = $rataRataKategori * ($bobotKategori / 100);

                return (object)[
                    'kategori_id'   => $kat->kategori_id,
                    'nama_kategori' => $kat->nama ?? $kat->nama_kategori,
                    'bobot_persen'  => $bobotKategori,
                    'pertanyaans'   => $mappedPertanyaan,
                    'subtotal'      => $subtotalNilai
                ];
            });
        }

        $totalNilaiPreview = $kategoriList->sum('subtotal');

        return view('penilaian.preview_verifikasi', compact(
            'penilaian',
            'template',
            'kategoriList',
            'totalNilaiPreview'
        ));
    }

public function cetakPdf($id)
    {
        // 1. Ambil data transaksi penilaian beserta relasi
        $penilaian = Dp3TransPenilaian::with([
            'periode', 
            'pegawai', 
            'penilai', 
            'verifikator', 
            'office', 
            'department', 
            'occupation', 
            'unitkerja',
            'details'
        ])->where('penilaian_id', $id)->firstOrFail();

        // 2. Ambil Template terkait
        $templateId = $penilaian->template_id;
        $template = null;
        if ($templateId) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->find($templateId);
        }

        if (!$template && $penilaian->pgw_id_jabatan) {
            $template = MasterTemplate::with('kategoris.pertanyaans')
                ->get()
                ->first(function ($t) use ($penilaian) {
                    return in_array($penilaian->pgw_id_jabatan, $t->occ_id_array);
                });
        }

        if (!$template) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->where('status_aktif', 1)->first();
        }

        // 3. Mapping data detail transaksi (Nilai Penilai vs Nilai Verifikator)
        $transDetailsMap = $penilaian->details->keyBy('pertanyaan_id');
        $skalaNilaiMap = MasterSkalaNilai::all()->keyBy(function ($skala) {
            return strtoupper(trim($skala->kode_nilai));
        });

        $kategoriList = collect();
        if ($template && $template->kategoris) {
            $kategoriList = $template->kategoris->map(function ($kat) use ($transDetailsMap, $skalaNilaiMap) {
                
                $mappedPertanyaan = $kat->pertanyaans->map(function ($q) use ($transDetailsMap, $skalaNilaiMap) {
                    $filledDetail = $transDetailsMap->get($q->pertanyaan_id);
                    $kodeNilaiPenilai = $filledDetail->kode_nilai ?? null;
                    $hasNilaiVerifikator = !empty($filledDetail->verif_kode_nilai)
                        || $filledDetail?->verif_nilai_angka !== null;
                    $kodeNilaiVerifikator = $hasNilaiVerifikator
                        ? ($filledDetail->verif_kode_nilai ?? null)
                        : $kodeNilaiPenilai;
                    $nilaiSkala = $skalaNilaiMap->get(strtoupper(trim((string) $kodeNilaiVerifikator)))->nilai_angka ?? null;
                    $nilaiAkhir = ($hasNilaiVerifikator
                        ? ($filledDetail->verif_nilai_angka ?? $nilaiSkala ?? $filledDetail->nilai_angka)
                        : ($filledDetail->nilai_angka ?? $nilaiSkala)) ?? 0;

                    return (object)[
                        'pertanyaan_id'           => $q->pertanyaan_id,
                        'pertanyaan'              => $q->pertanyaan,
                        'deskripsi'               => $q->deskripsi,
                        'bobot_pertanyaan_persen' => $q->bobot_persen ?? 0,
                        
                        // Nilai Penilai
                        'kode_nilai_penilai'      => $kodeNilaiPenilai,
                        'catatan_penilai'         => $filledDetail->catatan ?? null,
                        
                        // Nilai Verifikator
                        'kode_nilai_verifikator'  => $kodeNilaiVerifikator,
                        'catatan_verifikator'     => $filledDetail->verif_catatan ?? $filledDetail->catatan_verifikasi ?? null,
                        
                        // Hasil Akhir
                        'nilai_akhir'             => (float) $nilaiAkhir,
                    ];
                });

                $bobotKategori = (float) ($kat->bobot_persen ?? 0);
                $jumlahPertanyaan = $mappedPertanyaan->count();
                $rataRataKategori = $jumlahPertanyaan > 0
                    ? $mappedPertanyaan->sum('nilai_akhir') / $jumlahPertanyaan
                    : 0;
                $subtotalNilai = $rataRataKategori * ($bobotKategori / 100);

                return (object)[
                    'kategori_id'   => $kat->kategori_id,
                    'nama_kategori' => $kat->nama ?? $kat->nama_kategori,
                    'bobot_persen'  => $bobotKategori,
                    'pertanyaans'   => $mappedPertanyaan,
                    'subtotal'      => $subtotalNilai
                ];
            });
        }

        $totalNilai = $kategoriList->sum('subtotal');

        $predikatNama = $penilaian->predikat_verifikator ?: ($penilaian->predikat ?: 'Belum Diisi');

        $pdf = Pdf::loadView('penilaian.preview_verif_pdf', compact(
            'penilaian',
            'template',
            'kategoriList',
            'totalNilai',
            'predikatNama'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('FORM_HASIL_VERIFIKASI_' . ($penilaian->pgw_nup ?? $id) . '.pdf');
    }
}
