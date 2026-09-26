<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPenilaianDetail;
use App\Models\MasterTemplate;
use App\Models\MasterSkalaNilai;
use App\Models\MasterPredikatNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormPenilaianController extends Controller
{
    public function index($penilaian_id)
    {
        // 1. Ambil Data Penilaian Transaksi
        $penilaian = Dp3TransPenilaian::with(['periode', 'pegawai', 'details'])->where('penilaian_id', $penilaian_id)->firstOrFail();

        $existingJawaban = $penilaian->details->pluck('nilai_angka', 'pertanyaan_id')->toArray();
        $existingCatatan = $penilaian->details->pluck('catatan', 'pertanyaan_id')->toArray();

        // 2. Ambil Master Skala Nilai & Master Predikat Nilai
        $skalaNilai = MasterSkalaNilai::orderBy('nilai_angka', 'desc')->get();
        $predikatNilai = MasterPredikatNilai::orderBy('nilai_min', 'desc')->get();

        // 3. Ambil ID Jabatan Pegawai
        $jabatanId = (string) $penilaian->pgw_id_jabatan;

        // 4. Cari Template Penilaian Berdasarkan Jabatan Pegawai
        // PENTING: status_aktif bernilai 'Aktif' di DB, dan pencarian occ_id mengover JSON Array serta String LIKE
        $template = MasterTemplate::where(function($query) {
                $query->where('status_aktif', 'Aktif')
                      ->orWhere('status_aktif', 1);
            })
            ->where(function ($query) use ($jabatanId) {
                $query->whereJsonContains('occ_id', $jabatanId)
                      ->orWhereJsonContains('occ_id', (int) $jabatanId)
                      ->orWhere('occ_id', 'LIKE', '%"' . $jabatanId . '"%')
                      ->orWhere('occ_id', 'LIKE', '%' . $jabatanId . '%');
            })
            ->with(['kategoris.pertanyaans'])
            ->first();

        return view('penilaian.form_penilaian', compact('penilaian', 'template', 'skalaNilai', 'predikatNilai', 'existingJawaban', 'existingCatatan',));
    }

  public function store(Request $request, $penilaian_id)
    {
        $penilaian = Dp3TransPenilaian::where('penilaian_id', $penilaian_id)->firstOrFail();
        
        $statusAksi = $request->input('status_aksi', 'draft'); // 'draft' atau 'submitted'
        $jawaban = $request->input('jawaban', []); // Array [pertanyaan_id => nilai_angka]
        $catatan = $request->input('catatan', []); // Array [pertanyaan_id => teks_catatan]

        // Jalankan Transaction untuk menyimpan detail dan update header
        DB::transaction(function () use ($penilaian, $statusAksi, $jawaban, $catatan, $request) {
            
            foreach ($jawaban as $pertanyaanId => $skalaVal) {
                
                // 1. Ambil snapshot data Master Pertanyaan
                // (Sesuaikan \App\Models\MasterPertanyaan dengan nama model master pertanyaan kamu)
                $masterPertanyaan = \App\Models\MasterPertanyaan::where('pertanyaan_id', $pertanyaanId)->first();

                // 2. Ambil snapshot data Master Skala Nilai berdasarkan nilai_angka yang dipilih
                // (Sesuaikan \App\Models\MasterSkalaNilai dengan nama model skala nilai kamu)
                $masterSkala = \App\Models\MasterSkalaNilai::where('nilai_angka', $skalaVal)->first();
                
                \App\Models\Dp3TransPenilaianDetail::updateOrCreate(
                    [
                        'penilaian_id'  => $penilaian->penilaian_id,
                        'pertanyaan_id' => $pertanyaanId,
                    ],
                    [
                        // Snapshot informasi pertanyaan
                        'pertanyaan'              => $masterPertanyaan->pertanyaan ?? null,
                        'deskripsi'               => $masterPertanyaan->deskripsi ?? null,
                        'bobot_pertanyaan_persen' => $masterPertanyaan->bobot_persen ?? null,

                        // Snapshot informasi skala nilai
                        'skala_id'                => $masterSkala->skala_id ?? null,
                        'kode_nilai'              => $masterSkala->kode_nilai ?? null,
                        'nama_nilai'              => $masterSkala->nama_nilai ?? null,
                        'nilai_angka'             => $skalaVal,

                        'catatan'                 => $catatan[$pertanyaanId] ?? null,
                        'updated_at'              => now(),
                    ]
                );
            }

            // Hitung Grand Total Nilai
            $totalNilai   = $request->input('grand_total', 0);
            $predikatNama = $request->input('predikat_nama', '-');

            // Update Header Penilaian
            $penilaian->total_nilai  = $totalNilai;
            $penilaian->predikat     = $predikatNama;
            $penilaian->status_nilai = ($statusAksi === 'submitted') ? 'SUBMITTED' : 'DRAFT';
            
            if ($statusAksi === 'submitted') {
                $penilaian->tanggal_submit = now();
            }
            
            $penilaian->save();
        });

        $msg = ($statusAksi === 'submitted') ? 'Penilaian berhasil diajukan!' : 'Draft penilaian berhasil disimpan!';
        return redirect()->route('kelola-penilaian.index', ['periode_id' => $penilaian->periode_id])
            ->with('success', $msg);
    }
}