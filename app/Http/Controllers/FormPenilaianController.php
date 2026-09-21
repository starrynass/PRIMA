<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPenilaianDetail;
use App\Models\MasterTemplate;
use App\Models\MasterPertanyaan;
use App\Models\MasterKategori;
use App\Models\MasterSkalaNilai;
use App\Models\MasterPredikatNilai;
use App\Models\Employee;

class FormPenilaianController extends Controller
{
    public function show($id)
    {
        // 1. Ambil Header Penilaian beserta data Pegawai & Relasinya
        $penilaian = Dp3TransPenilaian::with([
            'pegawai.office',
            'pegawai.department',
            'pegawai.subDepartment',
            'pegawai.occupation'
        ])->findOrFail($id);

        // Ambil object employee dari relasi, atau fallback cari via pegawai_id
        $employee = $penilaian->pegawai ?? Employee::with(['office', 'department', 'subDepartment', 'occupation'])
            ->where('pgw_id', $penilaian->pegawai_id)
            ->first();

        // Dapatkan ID Jabatan pegawai
        $jabatanId = (string) ($penilaian->pgw_id_jabatan ?? $employee?->occ_id ?? '');

        // 2. Cari Template Aktif berdasarkan Jabatan (Mendukung format string koma "6,14,17" & JSON)
        $template = MasterTemplate::where(function($q) {
                $q->where('status_aktif', 'Aktif')
                  ->orWhere('status_aktif', 1);
            })
            ->where(function ($t) use ($jabatanId) {
                if (!empty($jabatanId)) {
                    $t->whereRaw("FIND_IN_SET(?, occ_id)", [$jabatanId])
                      ->orWhere('occ_id', 'LIKE', '%' . $jabatanId . '%')
                      ->orWhereJsonContains('occ_id', $jabatanId)
                      ->orWhereJsonContains('occ_id', (int) $jabatanId);
                }
            })
            ->first();

        // Fallback ke template aktif pertama jika tidak ditemukan template spesifik jabatan
        if (!$template) {
            $template = MasterTemplate::where(function($q) {
                $q->where('status_aktif', 'Aktif')
                  ->orWhere('status_aktif', 1);
            })->first();
        }

        // 3. Ambil Kategori & Pertanyaan berdasarkan template
        $kategoriRaw = MasterKategori::where('template_id', $template->template_id ?? null)
            ->with(['pertanyaans'])
            ->orderBy('urutan', 'asc')
            ->get();

        // Mapping $kategoriList agar kompatibel dengan View Blade
        $kategoriList = [];
        foreach ($kategoriRaw as $kat) {
            $pertanyaanList = [];
            foreach ($kat->pertanyaans as $q) {
                $pertanyaanList[] = [
                    'id'        => $q->pertanyaan_id ?? $q->id,
                    'judul'     => $q->pertanyaan ?? $q->judul_pertanyaan ?? $q->nama ?? '-',
                    'deskripsi' => $q->deskripsi ?? $q->keterangan ?? '-',
                    'bobot'     => $q->bobot_pertanyaan_persen ?? $q->bobot_persen ?? $q->bobot ?? 0,
                ];
            }

            $kategoriList[] = [
                'id'         => $kat->kategori_id ?? $kat->id,
                'nama'       => $kat->nama_kategori ?? $kat->nama ?? 'Kategori',
                'bobot'      => $kat->bobot_kategori_persen ?? $kat->bobot_persen ?? $kat->bobot ?? 0,
                'pertanyaan' => $pertanyaanList,
            ];
        }

        // 4. Ambil data Skala Nilai & Mapping untuk View
        $skalaNilaiRaw = MasterSkalaNilai::orderBy('nilai_angka', 'desc')->get();
        $skalaNilaiList = $skalaNilaiRaw->map(function ($s) {
            return [
                'kode'       => $s->kode_nilai ?? $s->kode_skala ?? $s->kode ?? '-',
                'nilai'      => $s->nilai_angka ?? $s->nilai ?? 0,
                'keterangan' => $s->nama_nilai ?? $s->keterangan ?? '-',
            ];
        })->toArray();

        // 5. Ambil Predikat Nilai untuk kalkulasi JavaScript di Blade
        $predikatList = MasterPredikatNilai::all()->map(function ($p) {
            return [
                'nama'  => $p->predikat ?? $p->nama_predikat ?? '-',
                'min'   => $p->nilai_min ?? $p->min_nilai ?? 0,
                'max'   => $p->nilai_max ?? $p->max_nilai ?? 100,
                'warna' => $p->warna ?? '#10B981',
            ];
        })->toArray();

        // 6. Ambil detail penilaian yang sudah tersimpan
        $existingDetails = Dp3TransPenilaianDetail::where('penilaian_id', $id)
            ->get()
            ->keyBy('pertanyaan_id');

        return view('penilaian.form_penilaian', compact(
            'penilaian', 
            'template', 
            'kategoriList', 
            'existingDetails', 
            'skalaNilaiRaw',
            'skalaNilaiList',
            'predikatList',
            'employee'
        ));
    }

    public function store(Request $request, $penilaian_id)
{
    $request->validate([
        'nilai' => 'required|array',
    ]);

    DB::beginTransaction();
    try {
        $penilaian = Dp3TransPenilaian::findOrFail($penilaian_id);
        $templateId = $request->template_id ?? $penilaian->template_id;

        // 1. Ambil data Master Kategori beserta Pertanyaannya
        $kategoriList = MasterKategori::where('template_id', $templateId)
            ->with(['pertanyaans'])
            ->get();

        $masterSkala = MasterSkalaNilai::all()->keyBy('skala_id');
        
        // Ambil nilai skala maksimum untuk pembagi (default 4 jika tidak ada)
        $maxScale = $masterSkala->max('nilai_angka') ?: 4;

        $totalNilaiPenilaian = 0;

        foreach ($kategoriList as $kategori) {
            // Bobot Kategori (misal 60% -> 0.60)
            $bobotKategoriPersen = $kategori->bobot_kategori_persen ?? $kategori->bobot_persen ?? 100;
            $bobotKategoriDesimal = $bobotKategoriPersen / 100;

            $sumNilaiTerbobotPertanyaan = 0;
            $sumBobotPertanyaanTerisi = 0;

            foreach ($kategori->pertanyaans as $pertanyaanObj) {
                $pertanyaanId = $pertanyaanObj->pertanyaan_id ?? $pertanyaanObj->id;

                // Cek apakah pertanyaan ini diisi/dipilih oleh user
                if (isset($request->nilai[$pertanyaanId])) {
                    $skalaId = $request->nilai[$pertanyaanId];
                    $skalaObj = $masterSkala->get($skalaId);

                    if (!$skalaObj) {
                        continue;
                    }

                    $bobotPertanyaanPersen = $pertanyaanObj->bobot_pertanyaan_persen ?? $pertanyaanObj->bobot_persen ?? 0;
                    $bobotPertanyaanDesimal = $bobotPertanyaanPersen / 100;

                    $kodeNilai  = $skalaObj->kode_nilai ?? $skalaObj->kode_skala ?? '-';
                    $namaNilai  = $skalaObj->nama_nilai ?? $skalaObj->keterangan ?? '-';
                    $nilaiAngka = $skalaObj->nilai_angka ?? 0;

                    // Kalkulasi Nilai Terbobot Pertanyaan
                    // Jika nilaiAngka berupa skala (misal 1-4), konversi ke persen (nilaiAngka / maxScale) * bobot
                    if ($nilaiAngka <= $maxScale && $maxScale > 0) {
                        $nilaiAkhirPertanyaan = ($nilaiAngka / $maxScale) * $bobotPertanyaanPersen;
                    } else {
                        $nilaiAkhirPertanyaan = $bobotPertanyaanDesimal > 0 
                            ? ($nilaiAngka * $bobotPertanyaanDesimal) 
                            : $nilaiAngka;
                    }

                    // Akumulasi per kategori
                    $sumNilaiTerbobotPertanyaan += $nilaiAkhirPertanyaan;
                    $sumBobotPertanyaanTerisi += $bobotPertanyaanDesimal;

                    // Simpan / Update Detail Penilaian
                    Dp3TransPenilaianDetail::updateOrCreate(
                        [
                            'penilaian_id'  => $penilaian_id,
                            'pertanyaan_id' => $pertanyaanId,
                        ],
                        [
                            'pertanyaan'              => $pertanyaanObj->pertanyaan ?? $pertanyaanObj->judul_pertanyaan ?? '',
                            'deskripsi'               => $pertanyaanObj->deskripsi ?? '',
                            'bobot_pertanyaan_persen' => $bobotPertanyaanPersen,
                            'skala_id'                => $skalaId,
                            'kode_nilai'              => $kodeNilai,
                            'nama_nilai'              => $namaNilai,
                            'nilai_angka'             => $nilaiAngka,
                            'nilai_akhir'             => $nilaiAkhirPertanyaan,
                            'catatan'                 => $request->catatan[$pertanyaanId] ?? null,
                            'created_by'              => auth()->user()?->name ?? 'System',
                            'updated_by'              => auth()->user()?->name ?? 'System',
                        ]
                    );
                }
            }

            // 2. Hitung Rata-Rata / Total Kategori Terbobot
            $nilaiKategoriTerbobot = $sumNilaiTerbobotPertanyaan * $bobotKategoriDesimal;

            // 3. Tambahkan ke Total Nilai Keseluruhan
            $totalNilaiPenilaian += $nilaiKategoriTerbobot;
        }

        // Pembulatan Total Nilai 2 digit desimal
        $totalNilaiAkhir = round($totalNilaiPenilaian, 2);

        // 4. Tentukan Predikat Nilai
        $predikatObj = MasterPredikatNilai::where(function($q) use ($totalNilaiAkhir) {
            $q->where('nilai_min', '<=', $totalNilaiAkhir)
              ->orWhere('min_nilai', '<=', $totalNilaiAkhir);
        })->where(function($q) use ($totalNilaiAkhir) {
            $q->where('nilai_max', '>=', $totalNilaiAkhir)
              ->orWhere('max_nilai', '>=', $totalNilaiAkhir);
        })->first();

        $predikatId   = $predikatObj->predikat_id ?? $predikatObj->id ?? null;
        $predikatText = $predikatObj->predikat ?? $predikatObj->nama_predikat ?? 'Belum Ditentukan';

        // 5. Cek Aksi Submit atau Draft (Mendukung 'status_aksi' dari JS dan 'action')
        $statusAksi = $request->input('status_aksi', $request->input('action', 'draft'));
        $isSubmit   = in_array(strtolower($statusAksi), ['submitted', 'submit']);

        // 6. Update Header Penilaian
        $penilaian->update([
            'template_id'    => $templateId,
            'total_nilai'    => $totalNilaiAkhir,
            'predikat_id'    => $predikatId,
            'predikat'       => $predikatText,
            'status_nilai'   => $isSubmit ? 'Disubmit' : 'Draft',
            'tanggal_submit' => $isSubmit ? now() : null,
            'updated_by'     => auth()->user()?->name ?? 'System',
        ]);

        DB::commit();

        return redirect()->back()->with('success', $isSubmit ? 'Penilaian berhasil diajukan!' : 'Draft penilaian berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
    }
}
}