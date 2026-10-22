<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dp3TransPenilaian;
use App\Models\MasterPredikatNilai;
use App\Models\MasterSkalaNilai;
use App\Models\MasterTemplate;
use App\Models\MasterKategori;
class PreviewPenilaianController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data transaksi penilaian beserta detail, periode, pegawai, dll.
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

        // 2. Tentukan Template ID yang digunakan (bisa dari kolom template_id di transaksi atau via jabatan/default)
        $templateId = $penilaian->template_id;

        // Jika template_id kosong di transaksi, coba cari template yang cocok dengan jabatan pegawai
        $template = null;
        if ($templateId) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->find($templateId);
        }
        
        if (!$template && $penilaian->pgw_id_jabatan) {
            // Cari template berdasarkan occ_id (karena occ_id berupa array/json/string)
            $template = MasterTemplate::with('kategoris.pertanyaans')
                ->get()
                ->first(function ($t) use ($penilaian) {
                    return in_array($penilaian->pgw_id_jabatan, $t->occ_id_array);
                });
        }

        // Fallback jika tetap tidak ketemu, ambil template aktif pertama
        if (!$template) {
            $template = MasterTemplate::with('kategoris.pertanyaans')->where('status_aktif', 1)->first();
        }

        // 3. Mapping data detail nilai yang sudah diisi (jika ada) ke dalam bentuk key-value [pertanyaan_id => detail]
        $transDetailsMap = $penilaian->details->keyBy('pertanyaan_id');
        $skalaNilaiMap = MasterSkalaNilai::all()->keyBy(function ($skala) {
            return strtoupper(trim($skala->kode_nilai));
        });

        // 4. Susun struktur kategori dan pertanyaan berdasarkan Template
        $kategoriList = collect();
        if ($template && $template->kategoris) {
            $kategoriList = $template->kategoris->map(function ($kat) use ($transDetailsMap, $skalaNilaiMap) {
                
                $mappedPertanyaan = $kat->pertanyaans->map(function ($q) use ($transDetailsMap, $skalaNilaiMap) {
                    // Cari apakah pertanyaan ini sudah dinilai di tabel transaksi detail
                    $filledDetail = $transDetailsMap->get($q->pertanyaan_id);
                    $kodeNilai = $filledDetail->kode_nilai ?? null;
                    $nilaiSkala = $skalaNilaiMap->get(strtoupper(trim((string) $kodeNilai)))->nilai_angka
                        ?? null;
                    $nilaiAkhir = $filledDetail->nilai_angka ?? $nilaiSkala ?? 0;

                    return (object)[
                        'pertanyaan_id'           => $q->pertanyaan_id,
                        'pertanyaan'              => $q->pertanyaan,
                        'deskripsi'               => $q->deskripsi,
                        'bobot_pertanyaan_persen' => $q->bobot_persen ?? 0,
                        'kode_nilai'              => $kodeNilai,
                        'catatan'                 => $filledDetail->catatan ?? null,
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

        return view('penilaian.preview_penilaian', compact(
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

        // 2. Tentukan Template ID yang digunakan
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

        // 3. Mapping data detail transaksi
        $transDetailsMap = $penilaian->details->keyBy('pertanyaan_id');
        $skalaNilaiMap = MasterSkalaNilai::all()->keyBy(function ($skala) {
            return strtoupper(trim($skala->kode_nilai));
        });

        // 4. Susun struktur kategori dan pertanyaan berdasarkan Template
        $kategoriList = collect();
        if ($template && $template->kategoris) {
            $kategoriList = $template->kategoris->map(function ($kat) use ($transDetailsMap, $skalaNilaiMap) {
                
                $mappedPertanyaan = $kat->pertanyaans->map(function ($q) use ($transDetailsMap, $skalaNilaiMap) {
                    $filledDetail = $transDetailsMap->get($q->pertanyaan_id);
                    $kodeNilai = $filledDetail->kode_nilai ?? null;
                    $nilaiSkala = $skalaNilaiMap->get(strtoupper(trim((string) $kodeNilai)))->nilai_angka ?? null;
                    $nilaiAkhir = $filledDetail->nilai_angka ?? $nilaiSkala ?? 0;

                    return (object)[
                        'pertanyaan_id'           => $q->pertanyaan_id,
                        'pertanyaan'              => $q->pertanyaan,
                        'deskripsi'               => $q->deskripsi,
                        'bobot_pertanyaan_persen' => $q->bobot_persen ?? 0,
                        'kode_nilai'              => $kodeNilai,
                        'catatan'                 => $filledDetail->catatan ?? null,
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

        $predikatNama = !empty($penilaian->predikat_verifikator)
            ? $penilaian->predikat_verifikator
            : ($penilaian->predikat ?: 'Belum Diisi');

        $pdf = Pdf::loadView('penilaian.preview_penilaian_pdf', compact(
            'penilaian',
            'template',
            'kategoriList',
            'totalNilai',
            'predikatNama'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('FORM_HASIL_PENILAIAN_' . ($penilaian->pgw_nup ?? $id) . '.pdf');
    }
}