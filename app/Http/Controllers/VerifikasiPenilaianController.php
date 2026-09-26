<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPenilaianDetail;
use App\Models\MasterTemplate;
use App\Models\MasterSkalaNilai;
use App\Models\MasterPredikatNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPenilaianController extends Controller
{
    /**
     * Halaman Utama / Tabel Daftar Verifikasi Penilaian
     */
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();

        $selectedPeriodeId = $request->get('periode_id');
        $selectedPeriode = null;
        $hasPeriodeSelected = false;

        // Inisialisasi variabel statistik kartu ringkasan
        $menungguVerifikasi = 0;
        $sudahVerified      = 0;
        $adaKoreksi         = 0;
        $dikembalikan       = 0;

        $penilaians = collect();

        if ($selectedPeriodeId) {
            $selectedPeriode = Dp3TransPeriodePenilaian::where('periode_id', $selectedPeriodeId)->first();

            if ($selectedPeriode) {
                $hasPeriodeSelected = true;

                // 1. Query Utama Tabel Pegawai
                $queryPenilaian = DB::table('dp3_trans_penilaian as tp')
                    ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                    ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
                    ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
                    ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
                    ->leftJoin('department_sub as ds', 'e.subdept_id', '=', 'ds.subdept_id')
                    ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
                    ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    // Hanya tampilkan status yang sudah diajukan/diisi
                    ->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'DIKEMBALIKAN', 'VERIFIED'])
                    ->select(
                        'tp.*',
                        'e.nup',
                        'e.nama',
                        'o.off_name as penempatan',
                        'occ.occ_name as jabatan',
                        'd.dept_name as departemen',
                        'ds.subdept_name as sub_departemen',
                        'tp.status_nilai',
                        'tp.status_verifikator',
                        'tp.status_nilai as status_penilaian',
                        DB::raw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) as nilai_akhir'),
                        'tp.predikat',
                        'penilai.nama as nama_penilai',
                        'verifikator.nama as nama_verifikator'
                    );

                // Filter Pencarian (Nama / NUP)
                if ($request->filled('search')) {
                    $search = trim($request->search);
                    $queryPenilaian->where(function ($q) use ($search) {
                        $q->where('e.nama', 'like', "%{$search}%")
                          ->orWhere('e.nup', 'like', "%{$search}%");
                    });
                }

                // Filter Status Verifikasi
                if ($request->filled('status_verifikasi')) {
                    $statusVerifikasi = strtoupper((string) $request->status_verifikasi);
                    if ($statusVerifikasi === 'VERIFIED') {
                        $queryPenilaian->whereIn(DB::raw('UPPER(COALESCE(tp.status_verifikator, ""))'), ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']);
                    } elseif ($statusVerifikasi === 'UNVERIFIED') {
                        $queryPenilaian->where(function($q) {
                            $q->whereNull('tp.status_verifikator')
                              ->orWhereIn(DB::raw('UPPER(tp.status_verifikator)'), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
                        });
                    }
                }

                // Filter Status Penilaian / Koreksi
                if ($request->filled('koreksi')) {
                    if ($request->koreksi === 'ADA_KOREKSI') {
                        $queryPenilaian->whereIn(DB::raw('UPPER(tp.status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU']);
                    } elseif ($request->koreksi === 'TANPA_KOREKSI') {
                        $queryPenilaian->where(function($q) {
                            $q->whereNull('tp.status_verifikator')
                              ->orWhereNotIn(DB::raw('UPPER(tp.status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU']);
                        });
                    }
                }

                // Sorting Data
                $sort = $request->get('sort', 'nama_asc');
                switch ($sort) {
                    case 'nama_desc':
                        $queryPenilaian->orderBy('e.nama', 'desc');
                        break;
                    case 'nilai_desc':
                        $queryPenilaian->orderByRaw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) DESC');
                        break;
                    case 'nilai_asc':
                        $queryPenilaian->orderByRaw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) ASC');
                        break;
                    case 'nama_asc':
                    default:
                        $queryPenilaian->orderBy('e.nama', 'asc');
                        break;
                }

                $penilaians = $queryPenilaian->paginate(25)->appends($request->query());

                // 2. Perhitungan Kartu Ringkasan Statistik
                $menungguVerifikasi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_nilai)'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI'])
                    ->where(function($q) {
                        $q->whereNull('status_verifikator')
                          ->orWhereIn(DB::raw('UPPER(status_verifikator)'), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
                    })->count();

                $sudahVerified = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_verifikator)'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
                    ->count();

                $adaKoreksi = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->whereIn(DB::raw('UPPER(status_verifikator)'), ['KOREKSI', 'PERLU DITINJAU'])
                    ->count();

                $dikembalikan = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                    ->where(function ($q) {
                        $q->whereIn(DB::raw('UPPER(status_nilai)'), ['DIKEMBALIKAN', 'REVISI'])
                          ->orWhereIn(DB::raw('UPPER(status_verifikator)'), ['DIKEMBALIKAN', 'REVISI']);
                    })->count();
            }
        }

        return view('penilaian.verifikasi_penilaian', compact(
            'periodes',
            'selectedPeriode',
            'hasPeriodeSelected',
            'menungguVerifikasi',
            'sudahVerified',
            'adaKoreksi',
            'dikembalikan',
            'penilaians'
        ));
    }

    /**
     * Halaman Form Verifikasi Penilaian (Tempat Verifikator Mengoreksi Data)
     */
    public function form($penilaian_id)
    {
        // 1. Ambil Data Penilaian beserta Detail
        $penilaian = Dp3TransPenilaian::with(['periode', 'pegawai', 'details'])
            ->where('penilaian_id', $penilaian_id)
            ->firstOrFail();

        // 2. Pemetaan Nilai dan Catatan (Gunakan Koreksi Verifikator jika ada, jika belum gunakan nilai dari Penilai Awal)
        $existingJawaban = [];
        $existingCatatan = [];
        $nilaiPenilai = [];
        $kodeNilaiPenilai = [];
        $namaNilaiPenilai = [];
        $catatanPenilai = [];
        foreach ($penilaian->details as $detail) {
            $existingJawaban[$detail->pertanyaan_id] = $detail->verif_nilai_angka;
            $existingCatatan[$detail->pertanyaan_id] = $detail->verif_catatan;
            $nilaiPenilai[$detail->pertanyaan_id] = $detail->nilai_angka;
            $kodeNilaiPenilai[$detail->pertanyaan_id] = $detail->kode_nilai;
            $namaNilaiPenilai[$detail->pertanyaan_id] = $detail->nama_nilai;
            $catatanPenilai[$detail->pertanyaan_id] = $detail->catatan;
        }

        // 3. Ambil Master Skala Nilai & Master Predikat Nilai
        $skalaNilai = MasterSkalaNilai::orderBy('nilai_angka', 'desc')->get();
        $predikatNilai = MasterPredikatNilai::orderBy('nilai_min', 'desc')->get();

        // 4. Cari Template Penilaian Berdasarkan Jabatan Pegawai
        $jabatanId = (string) $penilaian->pgw_id_jabatan;

        $template = MasterTemplate::where(function($query) {
                $query->where('status_aktif', 'Aktif')->orWhere('status_aktif', 1);
            })
            ->where(function ($query) use ($jabatanId) {
                $query->whereJsonContains('occ_id', $jabatanId)
                      ->orWhereJsonContains('occ_id', (int) $jabatanId)
                      ->orWhere('occ_id', 'LIKE', '%"' . $jabatanId . '"%')
                      ->orWhere('occ_id', 'LIKE', '%' . $jabatanId . '%');
            })
            ->with(['kategoris.pertanyaans'])
            ->first();

        return view('penilaian.form_verifikasi', compact(
            'penilaian', 
            'template', 
            'skalaNilai', 
            'predikatNilai', 
            'existingJawaban', 
            'existingCatatan',
            'nilaiPenilai',
            'kodeNilaiPenilai',
            'namaNilaiPenilai',
            'catatanPenilai'
        ));
    }

    /**
     * Menyimpan Hasil Koreksi / Verifikasi / Pengembalian Revisi
     */
    public function storeForm(Request $request, $penilaian_id)
    {
        $penilaian = Dp3TransPenilaian::where('penilaian_id', $penilaian_id)->firstOrFail();
        
        $statusAksi = $request->input('status_aksi', 'koreksi'); // 'koreksi', 'verified', atau 'revisi'
        $jawaban = $request->input('jawaban', []);
        $catatanVerifikator = $request->input('catatan_verifikator', []);
        $catatanUmum = $request->input('catatan_umum_verifikator');

        DB::transaction(function () use ($penilaian, $statusAksi, $jawaban, $catatanVerifikator, $catatanUmum, $request) {
            
            // 1. Simpan Detail Koreksi Per Pertanyaan ke Tabel Detail
            foreach ($jawaban as $pertanyaanId => $skalaVal) {
                $masterSkala = MasterSkalaNilai::where('nilai_angka', $skalaVal)->first();

                Dp3TransPenilaianDetail::where('penilaian_id', $penilaian->penilaian_id)
                    ->where('pertanyaan_id', $pertanyaanId)
                    ->update([
                        'verif_nilai_angka' => $skalaVal,
                        'verif_kode_nilai'  => $masterSkala->kode_nilai ?? null,
                        'verif_nama_nilai'  => $masterSkala->nama_nilai ?? null,
                        'verif_catatan'     => $catatanVerifikator[$pertanyaanId] ?? null,
                        'verifikator_id'    => auth()->id() ?? $penilaian->verifikator_id,
                        'verif_tanggal'     => now(),
                        'updated_at'              => now(),
                    ]);
            }

            // 2. Ambil Nilai Akhir & Predikat Verifikator
            $totalNilai   = $request->input('grand_total', 0);
            $predikatNama = $request->input('predikat_nama', '-');

            $penilaian->total_nilai_verifikator = $totalNilai;
            $penilaian->predikat_verifikator    = $predikatNama;
            $penilaian->catatan_verifikator     = $catatanUmum;
            $penilaian->verifikator_id          = auth()->id() ?? $penilaian->verifikator_id;

            // 3. Tentukan Status Akhir Berdasarkan Tombol Aksi yang Ditekan
            if ($statusAksi === 'verified') {
                $penilaian->status_verifikator = 'VERIFIED';
                $penilaian->status_nilai       = 'VERIFIED';
                $penilaian->tanggal_verifikasi = now();
            } elseif ($statusAksi === 'revisi') {
                $penilaian->status_verifikator = 'REVISI';
                $penilaian->status_nilai       = 'DIKEMBALIKAN'; // Dikembalikan ke penilai agar bisa diedit kembali
            } else {
                // 'koreksi' -> Hanya simpan draf koreksi verifikator
                $penilaian->status_verifikator = 'KOREKSI';
            }

            $penilaian->save();
        });

        $messages = [
            'verified' => 'Penilaian berhasil diverifikasi!',
            'revisi'   => 'Penilaian berhasil dikembalikan ke penilai untuk revisi.',
            'koreksi'  => 'Hasil koreksi verifikator berhasil disimpan!'
        ];

        return redirect()->route('verifikasi-penilaian.index', ['periode_id' => $penilaian->periode_id])
            ->with('success', $messages[$statusAksi] ?? 'Data berhasil diproses');
    }
}