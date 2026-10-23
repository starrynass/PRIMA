<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPenilaianDetail;
use App\Models\MasterTemplate;
use App\Models\MasterSkalaNilai;
use App\Models\MasterPredikatNilai;
use App\Models\Office;
use App\Models\UnitKerja;
use App\Support\EmployeeAssessmentPolicy;

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

        $listPenempatan = Office::orderBy('off_name', 'asc')->get();
        $listUnitKerja = UnitKerja::orderBy('nama_unit_kerja', 'asc')->get();

        if ($selectedPeriodeId) {
            $selectedPeriode = Dp3TransPeriodePenilaian::where('periode_id', $selectedPeriodeId)->first();

            if ($selectedPeriode) {
                $hasPeriodeSelected = true;

                // 1. Query Utama Tabel Pegawai
                $queryPenilaian = DB::table('dp3_trans_penilaian as tp')
                    ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                    ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
                    ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
                    ->leftJoin('unit_kerja as uk', function ($join) {
                        $join->on('uk.kode_unit_kerja', '=', DB::raw('COALESCE(e.kode_unit_kerja, tp.pgw_kode_unit_kerja)'));
                    })
                    ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
                    ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    ->where('tp.is_active', true)
                    // Hanya tampilkan status yang sudah diajukan/diisi
                    ->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'MENUNGGU VERIFIKASI', 'DIKEMBALIKAN', 'VERIFIED'])
                    ->select(
                        'tp.*',
                        'e.nup',
                        'e.nama',
                        'o.off_name as penempatan',
                        'occ.occ_name as jabatan',
                        DB::raw("COALESCE(uk.nama_unit_kerja, e.kode_unit_kerja, tp.pgw_kode_unit_kerja, '-') as unit_kerja"),
                        'tp.status_nilai',
                        DB::raw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor) as status_verifikator_display"),
                        'tp.status_nilai as status_penilaian',
                        DB::raw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) as nilai_akhir'),
                        'tp.predikat',
                        'penilai.nama as nama_penilai',
                        'verifikator.nama as nama_verifikator'
                    );
                EmployeeAssessmentPolicy::applyVerificationListScope($queryPenilaian, $request->user());
                $scopedStatistics = DB::table('dp3_trans_penilaian as tp')
                    ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                    ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    ->where('tp.is_active', true);
                EmployeeAssessmentPolicy::applyVerificationListScope($scopedStatistics, $request->user());

                // Filter Pencarian (Nama / NUP)
                if ($request->filled('search')) {
                    $search = trim($request->search);
                    $queryPenilaian->where(function ($q) use ($search) {
                        $q->where('e.nama', 'like', "%{$search}%")
                          ->orWhere('e.nup', 'like', "%{$search}%");
                    });
                }

                // Filter Penempatan (Office)
                if ($request->filled('off_id')) {
                    $offId = (string) $request->off_id;
                    $queryPenilaian->where(function ($q) use ($offId) {
                        $q->where('o.off_id', $offId)
                        ->orWhere('e.off_id', $offId)
                        ->orWhere('tp.pgw_off_id', $offId);
                    });
                }

                // Filter Unit Kerja
                if ($request->filled('kode_unit_kerja')) {
                    $kodeUnitKerja = (string) $request->kode_unit_kerja;
                    $queryPenilaian->where(function ($q) use ($kodeUnitKerja) {
                        $q->where('uk.kode_unit_kerja', $kodeUnitKerja)
                            ->orWhere('e.kode_unit_kerja', $kodeUnitKerja)
                            ->orWhere('tp.pgw_kode_unit_kerja', $kodeUnitKerja);
                    });
                }

                // Filter Status Verifikasi
                if ($request->filled('status_verifikasi')) {
                    $statusVerifikasi = strtoupper((string) $request->status_verifikasi);
                    if ($statusVerifikasi === 'VERIFIED') {
                        $queryPenilaian->whereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']);
                    } elseif ($statusVerifikasi === 'UNVERIFIED') {
                        $queryPenilaian->where(function($q) {
                            $q->whereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['BELUM VERIFIKASI', 'UNVERIFIED'])
                                ->orWhereRaw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, '') = ''");
                        });
                    }
                }

                // Filter Status Penilaian / Koreksi
                if ($request->filled('koreksi')) {
                    if ($request->koreksi === 'ADA_KOREKSI') {
                        $queryPenilaian->where(function ($query) {
                            $query->whereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['KOREKSI', 'PERLU DITINJAU'])
                                ->orWhereExists(function ($details) {
                                    $details->selectRaw('1')
                                        ->from('dp3_trans_penilaian_detail as tpd')
                                        ->whereColumn('tpd.penilaian_id', 'tp.penilaian_id')
                                        ->whereNotNull('tpd.verif_nilai_angka')
                                        ->whereNotNull('tpd.nilai_angka')
                                        ->whereColumn('tpd.verif_nilai_angka', '<>', 'tpd.nilai_angka');
                                });
                        });
                    } elseif ($request->koreksi === 'TANPA_KOREKSI') {
                        $queryPenilaian->whereNotIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['KOREKSI', 'PERLU DITINJAU'])
                            ->whereNotExists(function ($details) {
                                $details->selectRaw('1')
                                    ->from('dp3_trans_penilaian_detail as tpd')
                                    ->whereColumn('tpd.penilaian_id', 'tp.penilaian_id')
                                    ->whereNotNull('tpd.verif_nilai_angka')
                                    ->whereNotNull('tpd.nilai_angka')
                                    ->whereColumn('tpd.verif_nilai_angka', '<>', 'tpd.nilai_angka');
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
                $menungguVerifikasi = (clone $scopedStatistics)
                    ->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI'])
                    ->where(function ($query) {
                        $query->whereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['BELUM VERIFIKASI', 'UNVERIFIED'])
                            ->orWhereRaw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, '') = ''");
                    })
                    ->count();

                $sudahVerified = (clone $scopedStatistics)
                    ->whereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
                    ->count();

                $adaKoreksi = DB::table('dp3_trans_penilaian as tp')
                    ->join('dp3_trans_penilaian_detail as tpd', 'tp.penilaian_id', '=', 'tpd.penilaian_id')
                    ->where('tp.periode_id', $selectedPeriodeId)
                    ->where(function ($q) {
                        $q->whereIn(DB::raw('UPPER(TRIM(COALESCE(tpd.verif_status, "")))'), [
                            'KOREKSI', 'DIKOREKSI', 'PERLU DITINJAU'
                        ]);
                    })
                    ->distinct('tp.pegawai_id')
                    ->count('tp.pegawai_id');

                $dikembalikan = (clone $scopedStatistics)
                    ->where(function ($query) {
                        $query->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['DIKEMBALIKAN', 'REVISI'])
                            ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['DIKEMBALIKAN', 'REVISI']);
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
            'penilaians',
            'listPenempatan',
            'listUnitKerja'
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
        abort_unless(EmployeeAssessmentPolicy::canVerify(auth()->user(), $penilaian)
            && $this->isVerifiable($penilaian), 403);

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
        abort_unless(EmployeeAssessmentPolicy::canVerify($request->user(), $penilaian)
            && $this->isVerifiable($penilaian), 403);
        $actorEmployeeId = (int) ($request->user()->employee_id ?? 0);
        if (!$request->user()->isSuperAdmin()
            && !empty($penilaian->verifikator_id)
            && (int) $penilaian->verifikator_id !== $actorEmployeeId
            && (int) $penilaian->verifikator_id !== (int) $request->user()->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status_aksi' => ['nullable', 'in:koreksi,verified,revisi'],
            'jawaban' => ['nullable', 'array'],
            'jawaban.*' => ['nullable', 'numeric'],
            'catatan_verifikator' => ['nullable', 'array'],
            'catatan_umum_verifikator' => ['nullable', 'string'],
            'alasan_revisi' => ['nullable', 'string'],
            'grand_total' => ['nullable', 'numeric'],
            'predikat_nama' => ['nullable', 'string', 'max:100'],
        ]);
        $statusAksi = $validated['status_aksi'] ?? 'koreksi';
        $jawaban = $request->input('jawaban', []);
        $catatanVerifikator = $request->input('catatan_verifikator', []);
        $catatanUmum = $request->input('catatan_umum_verifikator');
        $alasanRevisi = trim((string) $request->input('alasan_revisi', ''));

        if ($statusAksi === 'revisi' && empty($alasanRevisi)) {
            return back()->withInput()->with('error', 'Alasan pengembalian revisi wajib diisi.');
        }

        // Validasi koreksi nilai: jika ada nilai yang diubah, verifikator wajib mengisi catatan/alasan koreksi
        foreach ($jawaban as $pertanyaanId => $skalaVal) {
            $detail = Dp3TransPenilaianDetail::where('penilaian_id', $penilaian->penilaian_id)
                ->where('pertanyaan_id', $pertanyaanId)
                ->first();
            if ($detail && (float) $detail->nilai_angka !== (float) $skalaVal) {
                $reason = trim((string) ($catatanVerifikator[$pertanyaanId] ?? ''));
                if (empty($reason)) {
                    return back()->withInput()->with('error', 'Setiap butir nilai yang dikoreksi wajib diberikan alasan / catatan verifikator.');
                }
            }
        }

        DB::transaction(function () use ($penilaian, $statusAksi, $jawaban, $catatanVerifikator, $catatanUmum, $request, $actorEmployeeId) {
            
            // 1. Simpan Detail Koreksi Per Pertanyaan ke Tabel Detail
            foreach ($jawaban as $pertanyaanId => $skalaVal) {
                $masterSkala = MasterSkalaNilai::where('nilai_angka', $skalaVal)->first();
                $detail = Dp3TransPenilaianDetail::where('penilaian_id', $penilaian->penilaian_id)
                    ->where('pertanyaan_id', $pertanyaanId)
                    ->first();

                if (!$detail) {
                    abort(422, 'Pertanyaan tidak ditemukan pada penilaian ini.');
                }

                $isCorrected = (float) $detail->nilai_angka !== (float) $skalaVal;
                $detail->fill([
                        'verif_nilai_angka' => $skalaVal,
                        'verif_kode_nilai'  => $masterSkala->kode_nilai ?? null,
                        'verif_nama_nilai'  => $masterSkala->nama_nilai ?? null,
                        'verif_catatan'     => $catatanVerifikator[$pertanyaanId] ?? null,
                        'verif_nilai_sebelumnya' => $isCorrected ? (string) $detail->nilai_angka : null,
                        'verif_alasan_koreksi' => $isCorrected ? ($catatanVerifikator[$pertanyaanId] ?? null) : null,
                        'verif_status'      => $isCorrected ? 'KOREKSI' : 'VERIFIED',
                        'verifikator_id'    => $actorEmployeeId ?: $penilaian->verifikator_id,
                        'verif_tanggal'     => now(),
                    ]);
                $detail->save();
            }

            // 1. Ambil total nilai baru dari form verifikator
            $totalNilai = (float) $request->input('grand_total', 0);

            // 2. Cari master predikat berdasarkan rentang nilai verifikator
            $masterPredikatVerif = MasterPredikatNilai::where('nilai_min', '<=', $totalNilai)
                ->where('nilai_max', '>=', $totalNilai)
                ->first();

            $predikatNamaVerif = $masterPredikatVerif?->predikat ?? $request->input('predikat_nama', '-');

            // 3. Simpan KHUSUS ke field verifikator tanpa mengganggu field penilai awal
            $penilaian->total_nilai_verifikator = $totalNilai;
            $penilaian->predikat_verifikator    = $predikatNamaVerif;
            $penilaian->catatan_verifikator     = $catatanUmum;
            $penilaian->verifikator_id          = $actorEmployeeId ?: $penilaian->verifikator_id;

            if ($statusAksi === 'verified') {
                $penilaian->status_verifikator = 'VERIFIED';
                $penilaian->status_nilai       = 'VERIFIED';
                $penilaian->tanggal_verifikasi = now();
            } elseif ($statusAksi === 'revisi') {
                $penilaian->status_verifikator = 'REVISI';
                $penilaian->status_nilai       = 'DIKEMBALIKAN';
                $penilaian->tanggal_revisi     = now();
                if (!empty($alasanRevisi)) {
                    $prefix = "[Revisi: " . now()->format('d/m/Y H:i') . "] " . $alasanRevisi;
                    $penilaian->catatan_verifikator = $penilaian->catatan_verifikator
                        ? $prefix . "\n" . $penilaian->catatan_verifikator
                        : $prefix;
                }
            } else {
                $hasCorrections = Dp3TransPenilaianDetail::where('penilaian_id', $penilaian->penilaian_id)
                    ->whereNotNull('verif_nilai_angka')
                    ->whereNotNull('nilai_angka')
                    ->whereColumn('verif_nilai_angka', '<>', 'nilai_angka')
                    ->exists();
                $penilaian->status_verifikator = $hasCorrections ? 'KOREKSI' : 'BELUM VERIFIKASI';
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

    /**
     * Verifikasi Banyak Penilaian Sekaligus (Verifikasi Terpilih)
     */
    public function bulkVerify(Request $request)
    {
        $validated = $request->validate([
            'penilaian_ids'   => ['required', 'array', 'min:1'],
            'penilaian_ids.*' => ['required', 'integer'],
            'periode_id'      => ['nullable', 'string'],
        ]);

        $actorEmployeeId = (int) ($request->user()->employee_id ?? 0);
        $user = $request->user();

        $query = Dp3TransPenilaian::with('details')->whereIn('penilaian_id', $validated['penilaian_ids'])
            ->where('is_active', true)
            ->whereIn(DB::raw('UPPER(status_nilai)'), ['SUBMIT', 'SUBMITTED', 'DIAJUKAN', 'MENUNGGU VERIFIKASI']);

        $penilaians = $query->get();
        $verifiedCount = 0;

        DB::transaction(function () use ($penilaians, $user, $actorEmployeeId, &$verifiedCount) {
            foreach ($penilaians as $penilaian) {
                if (!EmployeeAssessmentPolicy::canVerify($user, $penilaian)) {
                    continue;
                }

                if (!$user->isSuperAdmin()
                    && !empty($penilaian->verifikator_id)
                    && (int) $penilaian->verifikator_id !== $actorEmployeeId
                    && (int) $penilaian->verifikator_id !== (int) $user->id) {
                    continue;
                }

                // Pada verifikasi terpilih, tidak mengubah nilai atau koreksi butir jika sudah ada.
                // Jika verifikator belum mengisi verif_nilai_angka pada detail, salin dari nilai_angka awal
                foreach ($penilaian->details as $detail) {
                    if (is_null($detail->verif_nilai_angka)) {
                        $detail->update([
                            'verif_nilai_angka' => $detail->nilai_angka,
                            'verif_kode_nilai'  => $detail->kode_nilai,
                            'verif_nama_nilai'  => $detail->nama_nilai,
                            'verif_status'      => 'VERIFIED',
                            'verifikator_id'    => $actorEmployeeId ?: $penilaian->verifikator_id,
                            'verif_tanggal'     => now(),
                        ]);
                    }
                }

                // Tentukan total nilai verifikator jika belum ada
                if (empty($penilaian->total_nilai_verifikator) || (float) $penilaian->total_nilai_verifikator <= 0) {
                    $penilaian->total_nilai_verifikator = $penilaian->total_nilai;
                }

                if (empty($penilaian->predikat_verifikator)) {
                    $penilaian->predikat_verifikator = $penilaian->predikat;
                }

                $penilaian->status_verifikator = 'VERIFIED';
                $penilaian->status_nilai       = 'VERIFIED';
                $penilaian->tanggal_verifikasi = now();
                $penilaian->verifikator_id     = $actorEmployeeId ?: $penilaian->verifikator_id;
                $penilaian->save();

                $verifiedCount++;
            }
        });

        $periodeId = $validated['periode_id'] ?? $request->input('periode_id');

        return redirect()->route('verifikasi-penilaian.index', array_filter(['periode_id' => $periodeId]))
            ->with('success', "Berhasil memverifikasi {$verifiedCount} penilaian terpilih.");
    }

    /**
     * Batalkan Verifikasi (Unverifikasi Terpilih)
     */
    public function bulkUnverify(Request $request)
    {
        $validated = $request->validate([
            'penilaian_ids'   => ['required', 'array', 'min:1'],
            'penilaian_ids.*' => ['required', 'integer'],
            'periode_id'      => ['nullable', 'string'],
            'catatan'         => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $actorEmployeeId = (int) ($user->employee_id ?? 0);

        $query = Dp3TransPenilaian::whereIn('penilaian_id', $validated['penilaian_ids'])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI'])
                  ->orWhere(DB::raw('UPPER(status_nilai)'), 'VERIFIED');
            });

        $penilaians = $query->get();
        $unverifiedCount = 0;

        DB::transaction(function () use ($penilaians, $user, $actorEmployeeId, $validated, &$unverifiedCount) {
            foreach ($penilaians as $penilaian) {
                if (!EmployeeAssessmentPolicy::canVerify($user, $penilaian)) {
                    continue;
                }

                if (!$user->isSuperAdmin()
                    && !empty($penilaian->verifikator_id)
                    && (int) $penilaian->verifikator_id !== $actorEmployeeId
                    && (int) $penilaian->verifikator_id !== (int) $user->id) {
                    continue;
                }

                // Reset seluruh data koreksi butir penilaian pada detail transaksi
                Dp3TransPenilaianDetail::where('penilaian_id', $penilaian->penilaian_id)
                    ->update([
                        'verif_nilai_angka'      => null,
                        'verif_kode_nilai'       => null,
                        'verif_nama_nilai'       => null,
                        'verif_catatan'          => null,
                        'verif_nilai_sebelumnya' => null,
                        'verif_alasan_koreksi'   => null,
                        'verif_status'           => null,
                        'verifikator_id'         => null,
                        'verif_tanggal'          => null,
                    ]);

                // Reset seluruh status dan kalkulasi verifikator pada header penilaian
                $penilaian->total_nilai_verifikator = null;
                $penilaian->predikat_verifikator    = null;
                $penilaian->status_nilai            = 'SUBMIT';
                $penilaian->status_verifikator      = 'BELUM VERIFIKASI';
                $penilaian->status_verifitor        = 'BELUM VERIFIKASI';
                $penilaian->tanggal_verifikasi      = null;
                $penilaian->tanggal_revisi          = null;

                if (!empty($validated['catatan'])) {
                    $prefix = "[Unverifikasi: " . now()->format('d/m/Y H:i') . "] " . $validated['catatan'];
                    $penilaian->catatan_verifikator = $penilaian->catatan_verifikator
                        ? $prefix . "\n" . $penilaian->catatan_verifikator
                        : $prefix;
                } else {
                    $penilaian->catatan_verifikator = null;
                }

                $penilaian->save();
                $unverifiedCount++;
            }
        });

        $periodeId = $validated['periode_id'] ?? $request->input('periode_id');

        return redirect()->route('verifikasi-penilaian.index', array_filter(['periode_id' => $periodeId]))
            ->with('success', "Berhasil membatalkan verifikasi ({$unverifiedCount} penilaian). Status kembali ke Menunggu Verifikasi.");
    }

    private function isVerifiable(Dp3TransPenilaian $penilaian): bool
    {
        return (bool) $penilaian->is_active
            && in_array(strtoupper(trim((string) $penilaian->status_nilai)), [
                'SUBMIT',
                'SUBMITTED',
                'DIAJUKAN',
                'MENUNGGU VERIFIKASI',
            ], true);
    }
}