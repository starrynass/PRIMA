<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Employee;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = $user ? $user->normalizeRole($user->role) : 'staff';
        $isAdminSdm = in_array($userRole, ['superadmin', 'admin_sdm'], true);

        // Ambil data profil employee jika user terhubung ke tabel employee
        $employeeProfile = null;
        $employeeHistory = collect();

        if ($user && $user->employee_id) {
            $employeeProfile = Employee::with(['department', 'subDepartment', 'occupation', 'office', 'unitkerja'])
                ->where('pgw_id', $user->employee_id)
                ->first();
        } elseif ($user && !$isAdminSdm) {
            // Fallback jika bukan admin dan belum ada employee_id terpasang
            $employeeProfile = Employee::with(['department', 'subDepartment', 'occupation', 'office', 'unitkerja'])
                ->where('nama', $user->name)
                ->orWhere('nup', $user->name)
                ->first();
        }

        // ---- Year-based DP3 History ----
        $selectedYear  = (int) $request->get('history_year', now()->year);
        $periodeYears = Dp3TransPeriodePenilaian::orderByDesc('tahun')
            ->distinct()
            ->pluck('tahun')
            ->map(fn($y) => (int) $y)
            ->toArray();
        $availableYears = array_values(array_unique(array_merge([now()->year, $selectedYear], $periodeYears)));
        rsort($availableYears);

        $monthlyHistory = array_fill(1, 12, null); // null for months with no data

        if ($employeeProfile) {
            // Fetch penilaian records for the selected year
            $recordsForYear = Dp3TransPenilaian::with([
                    'periode',
                    'penilai',
                    'verifikator',
                ])
                ->whereHas('periode', fn($q) => $q->where('tahun', $selectedYear))
                ->where('pegawai_id', $employeeProfile->pgw_id)
                ->where('is_active', true)
                ->get()
                ->keyBy(fn($r) => (int) ($r->periode->bulan ?? 0));

            for ($m = 1; $m <= 12; $m++) {
                $monthlyHistory[$m] = $recordsForYear->get($m); // null when no record
            }
        }

        // Keep $employeeHistory as empty collection for backward compat (unused now)
        $employeeHistory = collect();

        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();
        $selectedPeriodeId = $request->get('periode_id', $periodes->first()?->periode_id);
        $periodeAktif = $periodes->where('periode_id', $selectedPeriodeId)->first();

        // 1. Cek Apakah Ada Periode Penilaian
        if ($periodes->isEmpty() || !$periodeAktif) {
            return view('dashboard', [
                'isAdminSdm'             => $isAdminSdm,
                'userRole'               => $userRole,
                'employeeProfile'        => $employeeProfile,
                'employeeHistory'        => $employeeHistory,
                'selectedYear'           => $selectedYear,
                'availableYears'         => $availableYears,
                'monthlyHistory'         => $monthlyHistory,
                'periodes'               => collect(),
                'periodeAktif'           => null,
                'selectedPeriodeId'      => null,
                'totalPegawai'           => 0,
                'sudahTerverifikasi'     => 0,
                'menungguVerifikasi'     => 0,
                'dikembalikan'           => 0,
                'masihDraft'             => 0,
                'dikoreksi'              => 0,
                'atRisk'                 => 0,
                'belumDinilai'           => 0,
                'percentVerifiedTotal'   => 0,
                'direktoratProgress'     => [],
                'isOverdue'              => false,
                'hariTerlambat'          => 0,
                'deadlineTanggal'        => null,
                'atRiskEmployees'        => [],
                'progressPerUnitKerja'   => [],
                'totalUnitAktif'         => 0,
                'distribusiUnitList'     => [],
                'rataRataKeseluruhan'    => '0.00',
                'rankingUnits'           => [],
                'totalPegawaiBernilai'   => 0,
                'terendahBulanIni'       => [],
                'totalTerendahBulanIni'  => 0,
                'terendahTigaBulan'      => [],
                'totalTerendahTigaBulan' => 0,
                'noPeriode'              => true,
            ]);
        }

        $statusVerifikatorSql = "UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))";
        $statusNilaiSql = "UPPER(COALESCE(status_nilai, ''))";
        $activeEntries = Dp3TransPenilaian::query()
            ->where('periode_id', $selectedPeriodeId)
            ->where('is_active', true);
        $totalPegawai = (clone $activeEntries)->distinct('pegawai_id')->count('pegawai_id');

        // 2. Sudah Terverifikasi
        $sudahTerverifikasi = (clone $activeEntries)
            ->whereIn(DB::raw($statusVerifikatorSql), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
            ->count();

        // 3. Menunggu Verifikasi
        $menungguVerifikasi = (clone $activeEntries)
            ->whereIn(DB::raw($statusNilaiSql), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'])
            ->where(function ($q) {
                $q->whereNull('status_verifikator')
                    ->whereNull('status_verifitor')
                    ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
            })
            ->count();

        // 4. Dikembalikan
        $dikembalikan = (clone $activeEntries)
            ->where(function ($q) {
                $q->whereIn(DB::raw("UPPER(COALESCE(status_nilai, ''))"), ['DIKEMBALIKAN', 'REVISI'])
                    ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['DIKEMBALIKAN', 'REVISI']);
            })
            ->count();

        // 5. Masih Draft
        $masihDraft = (clone $activeEntries)
            ->whereIn(DB::raw($statusNilaiSql), ['DRAFT', 'SIMPAN', 'SAVED'])
            ->count();

        // 6. Belum Dinilai
        $pegawaiSudahDinilaiIds = (clone $activeEntries)
            ->where(function ($q) {
                $q->whereIn(DB::raw("UPPER(COALESCE(status_nilai, ''))"), ['DRAFT', 'SIMPAN', 'SAVED', 'DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU', 'DIKEMBALIKAN', 'REVISI'])
                    ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED', 'DIKEMBALIKAN', 'REVISI', 'KOREKSI']);
            })
            ->pluck('pegawai_id')
            ->toArray();

        $belumDinilai = max(0, $totalPegawai - count(array_unique($pegawaiSudahDinilaiIds)));

        // 7. Dikoreksi
        $dikoreksi = DB::table('dp3_trans_penilaian as tp')
        ->join('dp3_trans_penilaian_detail as tpd', 'tp.penilaian_id', '=', 'tpd.penilaian_id')
        ->where('tp.periode_id', $selectedPeriodeId)
        ->where(function ($q) {
            $q->whereIn(DB::raw('UPPER(TRIM(COALESCE(tpd.verif_status, "")))'), [
                'KOREKSI', 'DIKOREKSI', 'PERLU DITINJAU'
            ]);
        })
        ->distinct('tp.pegawai_id')
        ->count('tp.pegawai_id');

        // Deteksi Batas Waktu
        $isOverdue = false;
        $hariTerlambat = 0;
        $deadlineTanggal = null;

        if (!empty($periodeAktif->tanggal_deadline)) {
            $deadlineDate = Carbon::parse($periodeAktif->tanggal_deadline)->endOfDay();
            $today = Carbon::now();

            if ($today->gt($deadlineDate)) {
                $isOverdue = true;
                $hariTerlambat = (int) $deadlineDate->diffInDays($today);
            }
            $deadlineTanggal = $deadlineDate->translatedFormat('d M Y');
        }

        // At Risk = Belum Dinilai saat Overdue
        if ($isOverdue) {
            $atRisk = $belumDinilai;
        } else {
            $atRisk = (clone $activeEntries)
                ->whereIn(DB::raw($statusNilaiSql), ['AT_RISK', 'LEWAT BATAS'])
                ->count();
        }

        // Progress per Lingkup
        $lingkupOrder = ['DIVISI UTAMA', 'DIVISI UMUM', 'DIVISI OPERASIONAL', 'CABANG', 'INSTALASI'];
        $periodProgressRows = (clone $activeEntries)
            ->leftJoin('employee as e', 'e.pgw_id', '=', 'dp3_trans_penilaian.pegawai_id')
            ->leftJoin('unit_kerja as uk', function ($join) {
                $join->whereRaw('uk.kode_unit_kerja = COALESCE(dp3_trans_penilaian.pgw_kode_unit_kerja, e.kode_unit_kerja)');
            })
            ->get([
                'dp3_trans_penilaian.pegawai_id',
                'dp3_trans_penilaian.pgw_kode_satker',
                'uk.nama_unit_kerja',
                DB::raw($statusVerifikatorSql . ' as normalized_status_verifikator'),
            ]);

        $progressByLingkup = $periodProgressRows
            ->groupBy(function ($row) {
                return (new UnitKerja(['nama_unit_kerja' => $row->nama_unit_kerja ?? $row->pgw_kode_satker]))->lingkup_kerja;
            });

        $direktoratProgress = collect($lingkupOrder)->map(function ($lingkup) use ($progressByLingkup) {
            $entries = $progressByLingkup->get($lingkup, collect());
            $totalInLingkup = $entries->pluck('pegawai_id')->unique()->count();
            $verifiedInLingkup = $entries
                ->whereIn('normalized_status_verifikator', ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
                ->pluck('pegawai_id')
                ->unique()
                ->count();
            $percent = $totalInLingkup > 0 ? min(100, round(($verifiedInLingkup / $totalInLingkup) * 100)) : 0;

            return (object) [
                'nama' => $lingkup,
                'verified' => $verifiedInLingkup,
                'total' => $totalInLingkup,
                'percent' => $percent,
            ];
        })->all();

        $percentVerifiedTotal = $totalPegawai > 0 ? round(($sudahTerverifikasi / $totalPegawai) * 100) : 0;

        $atRiskEmployees = [];
        if ($atRisk > 0) {
            $atRiskEmployees = DB::table('employee as e')
                ->leftJoin('unit_kerja as uk', 'e.kode_unit_kerja', '=', 'uk.kode_unit_kerja')
                ->whereNotIn('e.pgw_id', $pegawaiSudahDinilaiIds)
                ->select(
                    'e.pgw_id',
                    'e.nama',
                    'uk.nama_unit_kerja as unit_kerja'
                )
                ->get();
        }

        // Progress Per Unit Kerja
        $allUnitKerja = UnitKerja::with(['employees.occupation'])->get();
        $progressPerUnitKerja = [];

        foreach ($allUnitKerja as $unit) {
            $pegawaiIds = $unit->employees->pluck('pgw_id')->toArray();
            if (empty($pegawaiIds)) {
                continue;
            }

            // Ambil data transaksi penilaian untuk pegawai di unit ini pada periode aktif
            $transPenilaian = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                ->whereIn('pegawai_id', $pegawaiIds)
                ->get();

            // Sesuai permintaan: Hanya tampilkan jika ada minimal 1 pegawai yang dinilai (draft, submit, verified, dll)
            if ($transPenilaian->isEmpty()) {
                continue;
            }

            $totalPegawaiUnit = count($pegawaiIds);

            // Hitung komposisi jabatan pegawai di unit ini
            $komposisiJabatan = [];
            foreach ($unit->employees as $emp) {
                $jabatan = $emp->occupation->occ_name ?? 'Staf';
                $komposisiJabatan[$jabatan] = ($komposisiJabatan[$jabatan] ?? 0) + 1;
            }

            // Hitung jumlah berdasarkan status
            $draftCount = $transPenilaian->filter(function($tp) {
                return in_array(strtoupper(trim($tp->status_nilai)), ['DRAFT', 'SIMPAN', 'SAVED']);
            })->count();

            $submitCount = $transPenilaian->filter(function($tp) {
                return in_array(strtoupper(trim($tp->status_nilai)), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU']);
            })->count();

            $verifiedCount = $transPenilaian->filter(function($tp) {
                return in_array(strtoupper(trim($tp->status_verifikator)), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED']);
            })->count();

            $koreksiCount = $transPenilaian->filter(function($tp) {
                return in_array(strtoupper(trim($tp->status_verifikator)), ['KOREKSI', 'PERLU DITINJAU', 'DIKOREKSI']);
            })->count();

            $progressCount = $transPenilaian->filter(function($tp) {
                $statusNilai = strtoupper(trim($tp->status_nilai));
                $statusVerifikator = strtoupper(trim($tp->status_verifikator));

                return in_array($statusNilai, ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'])
                    || in_array($statusVerifikator, ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED']);
            })->unique('pegawai_id')->count();

            $progressPercent = $totalPegawaiUnit > 0 ? min(100, round(($progressCount / $totalPegawaiUnit) * 100)) : 0;
            $verifiedPercent = $totalPegawaiUnit > 0 ? min(100, round(($verifiedCount / $totalPegawaiUnit) * 100)) : 0;

            // Status At Risk jika melewati deadline dan masih ada yang belum selesai
            $sudahProses = $draftCount + $submitCount + $verifiedCount;
            $isUnitAtRisk = ($verifiedCount < $totalPegawaiUnit);

            $progressPerUnitKerja[] = (object)[
                'nama_unit'      => $unit->nama_unit_kerja ?? '-',
                'total_pegawai'  => $totalPegawaiUnit,
                'komposisi'      => $komposisiJabatan,
                'progress_count' => $progressCount,
                'progress_percent' => $progressPercent,
                'verified_count' => $verifiedCount,
                'percent'        => $verifiedPercent,
                'draft_count'    => $draftCount,
                'submit_count'   => $submitCount,
                'koreksi_count'  => $koreksiCount,
                'is_at_risk'     => $isUnitAtRisk,
            ];
        }

        $totalUnitAktif = count($progressPerUnitKerja);

        // WIDGET: DISTRIBUSI PREDIKAT KINERJA & RATA-RATA NILAI PER UNIT KERJA
        $masterPredikat = DB::table('dp3_master_predikat_nilai')->get();

        // Helper closure untuk memetakan nilai ke predikat berdasarkan master_predikat
        $getPredikatName = function ($nilai) use ($masterPredikat) {
            if ($nilai === null) return 'Sesuai Ekspektasi';
            foreach ($masterPredikat as $mp) {
                if ($nilai >= $mp->nilai_min && $nilai <= $mp->nilai_max) {
                    return $mp->predikat;
                }
            }
            if ($nilai >= 90) return 'Luar Biasa';
            if ($nilai >= 80) return 'Di Atas Ekspektasi';
            if ($nilai >= 70) return 'Sesuai Ekspektasi';
            if ($nilai >= 60) return 'Di Bawah Ekspektasi';
            return 'Mengecewakan';
        };

        $allUnits = UnitKerja::with(['employees'])->get();

        $distribusiUnitList = [];
        $totalPegawaiBernilai = 0;
        $totalNilaiSemua = 0;

        foreach ($allUnits as $unit) {
            $pegawaiIds = $unit->employees->pluck('pgw_id')->toArray();
            if (empty($pegawaiIds)) continue;

            // Hanya ambil pegawai yang SUDAH DIBERIKAN NILAI & DI-SUBMIT / VERIFIED (abaikan DRAFT)
            $transValids = Dp3TransPenilaian::where('periode_id', $selectedPeriodeId)
                ->whereIn('pegawai_id', $pegawaiIds)
                ->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(COALESCE(status_nilai, ""))'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'])
                      ->orWhereIn(DB::raw('UPPER(COALESCE(status_verifikator, ""))'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED', 'KOREKSI']);
                })
                ->whereNotNull('total_nilai')
                ->get();

            $jumlahBernilai = $transValids->count();
            // Jika tidak ada pegawai bernilai (atau hanya ada draft), lewati unit ini
            if ($jumlahBernilai == 0) continue;

            $sumNilai = $transValids->sum('total_nilai');
            $rataRata = round($sumNilai / $jumlahBernilai, 1);

            $totalPegawaiBernilai += $jumlahBernilai;
            $totalNilaiSemua += $sumNilai;

            // Hitung distribusi 5 predikat
            $counts = [
                'Luar Biasa'          => 0,
                'Di Atas Ekspektasi'  => 0,
                'Sesuai Ekspektasi'   => 0,
                'Di Bawah Ekspektasi' => 0,
                'Mengecewakan'        => 0,
            ];

            foreach ($transValids as $t) {
                $pName = $getPredikatName($t->total_nilai);
                if (array_key_exists($pName, $counts)) {
                    $counts[$pName]++;
                } else {
                    $counts['Sesuai Ekspektasi']++;
                }
            }

            // Hitung persentase bar
            $pctLuarBiasa  = round(($counts['Luar Biasa'] / $jumlahBernilai) * 100, 1);
            $pctDiAtas     = round(($counts['Di Atas Ekspektasi'] / $jumlahBernilai) * 100, 1);
            $pctSesuai     = round(($counts['Sesuai Ekspektasi'] / $jumlahBernilai) * 100, 1);
            $pctDiBawah    = round(($counts['Di Bawah Ekspektasi'] / $jumlahBernilai) * 100, 1);
            $pctMengecewakan = round(($counts['Mengecewakan'] / $jumlahBernilai) * 100, 1);

            $distribusiUnitList[] = (object)[
                'nama_unit'        => $unit->nama_unit_kerja,
                'jumlah_bernilai'  => $jumlahBernilai,
                'rata_rata'        => number_format($rataRata, 1),
                'rata_rata_raw'    => $rataRata,
                'pct_luar_biasa'   => $pctLuarBiasa,
                'pct_di_atas'      => $pctDiAtas,
                'pct_sesuai'       => $pctSesuai,
                'pct_di_bawah'     => $pctDiBawah,
                'pct_mengecewakan' => $pctMengecewakan,
            ];
        }

        // Rata-rata keseluruhan sistem
        $rataRataKeseluruhan = $totalPegawaiBernilai > 0 ? number_format($totalNilaiSemua / $totalPegawaiBernilai, 2) : '0.00';

        // Peringkat unit kerja berdasarkan Rata-Rata Nilai Tertinggi
        $rankingUnits = collect($distribusiUnitList)->sortByDesc('rata_rata_raw')->values()->all();

        // WIDGET: PREDIKAT TERENDAH BULAN INI & 3 BULAN BERTURUT-TURUT
        // =========================================================================
        
        // 1. Ambil Master Predikat untuk pemetaan nilai
        $masterPredikat = DB::table('dp3_master_predikat_nilai')->get();

        $getPredikatInfo = function ($nilai) use ($masterPredikat) {
            if ($nilai === null) return ['predikat' => 'Sesuai Ekspektasi', 'is_low' => false];
            
            $predikatStr = null;
            foreach ($masterPredikat as $mp) {
                if ($nilai >= $mp->nilai_min && $nilai <= $mp->nilai_max) {
                    $predikatStr = $mp->predikat;
                    break;
                }
            }

            if (!$predikatStr) {
                if ($nilai >= 90) $predikatStr = 'Luar Biasa';
                elseif ($nilai >= 80) $predikatStr = 'Di Atas Ekspektasi';
                elseif ($nilai >= 70) $predikatStr = 'Sesuai Ekspektasi';
                elseif ($nilai >= 60) $predikatStr = 'Dibawah Ekspektasi';
                else $predikatStr = 'Mengecewakan';
            }

            $isLow = in_array(strtoupper(trim($predikatStr)), [
                'DIBAWAH EKSPEKTASI', 'DI BAWAH EKSPEKTASI', 'MENGECEWAKAN'
            ]) || $nilai < 70;

            return [
                'predikat' => $predikatStr,
                'is_low'   => $isLow
            ];
        };

        // A. Predikat Terendah Bulan Ini (Periode Aktif)
        $terendahBulanIni = [];
        if ($periodeAktif) {
            $transPenilaianBulanIni = DB::table('dp3_trans_penilaian as tp')
                ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
                ->leftJoin('unit_kerja as uk', 'e.kode_unit_kerja', '=', 'uk.kode_unit_kerja')
                ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
                ->where('tp.periode_id', $selectedPeriodeId)
                ->whereNotNull('tp.total_nilai')
                ->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(COALESCE(tp.status_nilai, ""))'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'])
                      ->orWhereIn(DB::raw('UPPER(COALESCE(tp.status_verifikator, ""))'), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED', 'KOREKSI']);
                })
                ->select(
                    'e.pgw_id',
                    'e.nup',
                    'e.nama',
                    'uk.nama_unit_kerja as unit_kerja',
                    'occ.occ_name as jabatan',
                    'tp.total_nilai'
                )
                ->orderBy('tp.total_nilai', 'asc')
                ->get();

            foreach ($transPenilaianBulanIni as $tp) {
                $info = $getPredikatInfo($tp->total_nilai);
                if ($info['is_low']) {
                    $terendahBulanIni[] = (object)[
                        'pgw_id'      => $tp->pgw_id,
                        'nup'         => $tp->nup ?? '-',
                        'nama'        => $tp->nama,
                        'foto'        => $tp->foto ?? null,
                        'unit_kerja'  => $tp->unit_kerja ?? '-',
                        'jabatan'     => $tp->jabatan ?? 'Staf',
                        'total_nilai' => number_format($tp->total_nilai, 2),
                        'predikat'    => $info['predikat'],
                    ];
                }
            }
        }

        // B. Terendah 3 Bulan Berturut-turut
        $terendahTigaBulan = [];
        
        // Ambil 3 periode berturut-turut sampai periode aktif berjalan saat ini
        $tigaPeriodeIds = Dp3TransPeriodePenilaian::where('created_at', '<=', $periodeAktif ? $periodeAktif->created_at : Carbon::now())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->pluck('periode_id')
            ->toArray();

        // Hanya jalankan deteksi 3 bulan jika memang sudah ada minimal 3 periode penilaian
        if (count($tigaPeriodeIds) === 3) {
            // Ambil semua pegawai yang ada di predikat terendah bulan ini
            $pegawaiTerendahBulanIniIds = collect($terendahBulanIni)->pluck('pgw_id')->toArray();

            foreach ($pegawaiTerendahBulanIniIds as $pgwId) {
                // Ambil nilai pegawai di 3 periode berturut-turut tersebut
                $history = DB::table('dp3_trans_penilaian')
                    ->whereIn('periode_id', $tigaPeriodeIds)
                    ->where('pegawai_id', $pgwId)
                    ->whereNotNull('total_nilai')
                    ->get();

                // Harus terisi di 3 periode tersebut dan ketiganya berpredikat rendah
                if ($history->count() === 3) {
                    $isConsistentlyLow = true;
                    $nilaiTerakhir = 0;

                    foreach ($history as $h) {
                        $info = $getPredikatInfo($h->total_nilai);
                        if (!$info['is_low']) {
                            $isConsistentlyLow = false;
                            break;
                        }
                        if ($h->periode_id == $selectedPeriodeId) {
                            $nilaiTerakhir = $h->total_nilai;
                        }
                    }

                    if ($isConsistentlyLow) {
                        $empDetail = collect($terendahBulanIni)->firstWhere('pgw_id', $pgwId);
                        if ($empDetail) {
                            $terendahTigaBulan[] = $empDetail;
                        }
                    }
                }
            }
        }

        $totalTerendahBulanIni = count($terendahBulanIni);
        $totalTerendahTigaBulan = count($terendahTigaBulan);

        return view('dashboard', compact(
            'isAdminSdm',
            'userRole',
            'employeeProfile',
            'employeeHistory',
            'selectedYear',
            'availableYears',
            'monthlyHistory',
            'periodes',
            'periodeAktif',
            'selectedPeriodeId',
            'totalPegawai',
            'sudahTerverifikasi',
            'menungguVerifikasi',
            'dikembalikan',
            'masihDraft',
            'dikoreksi',
            'atRisk',
            'belumDinilai',
            'percentVerifiedTotal',
            'direktoratProgress',
            'isOverdue',
            'hariTerlambat',
            'deadlineTanggal',
            'atRiskEmployees',
            'progressPerUnitKerja',
            'totalUnitAktif',
            'distribusiUnitList',
            'rataRataKeseluruhan',
            'rankingUnits',
            'totalPegawaiBernilai',
            'terendahBulanIni',
            'totalTerendahBulanIni',
            'terendahTigaBulan',
            'totalTerendahTigaBulan'
        ) + ['noPeriode' => false]);
    }

    public function getModalDetail(Request $request)
    {
        $type = $request->get('type');
        $periodeId = $request->get('periode_id');

        if (!$periodeId) {
            return response()->json([
                'title' => 'Detail Data',
                'total' => 0,
                'data'  => []
            ]);
        }

        $statusVerifikatorSql = "UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))";
        $query = DB::table('dp3_trans_penilaian as tp')
            ->leftJoin('employee as e', 'e.pgw_id', '=', 'tp.pegawai_id')
            ->leftJoin('unit_kerja as uk', function ($join) {
                $join->whereRaw('uk.kode_unit_kerja = COALESCE(tp.pgw_kode_unit_kerja, e.kode_unit_kerja)');
            })
            ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->leftJoin('dp3_master_predikat_nilai as mp', function ($join) {
                $join->on('tp.total_nilai', '>=', 'mp.nilai_min')
                     ->on('tp.total_nilai', '<=', 'mp.nilai_max');
            })
            ->where('tp.periode_id', $periodeId)
            ->where('tp.is_active', true)
            ->select(
                'tp.pegawai_id as pgw_id',
                'tp.pgw_nup as nup',
                'tp.pgw_nama as nama',
                'uk.nama_unit_kerja as unit_kerja',
                DB::raw("COALESCE(tp.pgw_jabatan, occ.occ_name) as jabatan"),
                'tp.status_nilai',
                DB::raw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor) as status_verifikator"),
                'tp.total_nilai',
                'mp.predikat'
            );

        switch ($type) {
            case 'terverifikasi':
                $query->whereIn(DB::raw($statusVerifikatorSql), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED']);
                $title = "Pegawai Sudah Terverifikasi";
                break;

            case 'menunggu':
                $query->whereIn(DB::raw('UPPER(COALESCE(tp.status_nilai, ""))'), ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'])
                      ->where(function ($q) {
                          $q->whereNull('tp.status_verifikator')
                            ->whereNull('tp.status_verifitor')
                            ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['BELUM VERIFIKASI', 'UNVERIFIED', '']);
                      });
                $title = "Pegawai Menunggu Verifikasi";
                break;

            case 'dikembalikan':
                $query->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(COALESCE(tp.status_nilai, ""))'), ['DIKEMBALIKAN', 'REVISI'])
                      ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['DIKEMBALIKAN', 'REVISI']);
                });
                $title = "Penilaian Dikembalikan";
                break;

            case 'dikoreksi':
            $query->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('dp3_trans_penilaian_detail as tpd')
                    ->whereColumn('tpd.penilaian_id', 'tp.penilaian_id')
                    ->whereRaw('UPPER(COALESCE(tpd.verif_status, "")) = ?', ['KOREKSI']);
            });
            $title = "Penilaian Dikoreksi"; // <-- TAMBAHKAN BARIS INI
            break;

            case 'at_risk':
            case 'belum_dinilai':
                $sudahDinilaiIds = DB::table('dp3_trans_penilaian')
                    ->where('periode_id', $periodeId)
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereIn(DB::raw("UPPER(COALESCE(status_nilai, ''))"), ['DRAFT', 'SIMPAN', 'SAVED', 'DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU', 'DIKEMBALIKAN', 'REVISI'])
                            ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED', 'DIKEMBALIKAN', 'REVISI', 'KOREKSI']);
                    })
                    ->pluck('pegawai_id')
                    ->all();

                $query->whereNotIn('tp.pegawai_id', $sudahDinilaiIds);
                $title = ($type === 'at_risk') ? "Pegawai At Risk / Lewat Batas (Belum Dinilai)" : "Pegawai Belum Dinilai";
                break;

            case 'total':
            default:
                $title = "Total Keseluruhan Pegawai";
                break;
        }

        $data = $query->get();

        return response()->json([
            'title' => $title,
            'total' => $data->count(),
            'data'  => $data
        ]);
    }

    public function getMonitoringData(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => ['required', 'string', 'exists:dp3_trans_periode_penilaian,periode_id'],
            'tab' => ['nullable', 'in:semua,belum_dinilai,draft,menunggu,returned,verified,koreksi'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $periodeId = $validated['periode_id'];
        $tab = $validated['tab'] ?? 'semua';
        $statusVerifikatorSql = "UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))";
        $submittedStatuses = ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'];
        $verifiedStatuses = ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'];
        $returnedStatuses = ['DIKEMBALIKAN', 'REVISI'];
        $draftStatuses = ['DRAFT', 'SIMPAN', 'SAVED'];

        $query = DB::table('dp3_trans_penilaian as tp')
            ->leftJoin('employee as e', 'e.pgw_id', '=', 'tp.pegawai_id')
            ->leftJoin('unit_kerja as uk', function ($join) {
                $join->whereRaw('uk.kode_unit_kerja = COALESCE(tp.pgw_kode_unit_kerja, e.kode_unit_kerja)');
            })
            ->leftJoin('occupation as occ', 'tp.pgw_id_jabatan', '=', 'occ.occ_id')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
            ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
            ->where('tp.periode_id', $periodeId)
            ->where('tp.is_active', true)
            ->select(
                'tp.pegawai_id as pgw_id',
                DB::raw('COALESCE(tp.pgw_nup, e.nup) as nup'),
                DB::raw('COALESCE(tp.pgw_nama, e.nama) as nama'),
                'uk.nama_unit_kerja as unit_kerja',
                DB::raw('COALESCE(tp.pgw_jabatan, occ.occ_name) as jabatan'),
                'tp.status_nilai',
                DB::raw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor) as status_verifikator"),
                'tp.total_nilai',
                'tp.total_nilai_verifikator',
                'tp.predikat',
                'tp.predikat_verifikator',
                'penilai.nama as penilai_nama',
                'verifikator.nama as verifikator_nama',
                'tp.tanggal_submit',
                'tp.tanggal_verifikasi',
                'tp.updated_at',
                'tp.penilaian_id',
                DB::raw("(SELECT COUNT(*) FROM dp3_trans_penilaian_detail as tpd
                    WHERE tpd.penilaian_id = tp.penilaian_id
                    AND (
                        UPPER(TRIM(COALESCE(tpd.verif_status, ''))) = 'KOREKSI'
                        OR (
                            tpd.verif_nilai_angka IS NOT NULL
                            AND tpd.nilai_angka IS NOT NULL
                            AND tpd.verif_nilai_angka <> tpd.nilai_angka
                        )
                    )
                ) as koreksi_count")
            );

        switch ($tab) {
            case 'belum_dinilai':
                $query->where(function ($q) {
                    $q->whereNull('tp.status_nilai')
                    ->orWhereIn(DB::raw('UPPER(TRIM(tp.status_nilai))'), ['', 'BELUM DIISI', 'BELUM DINILAI']);
                })->whereIn(DB::raw($statusVerifikatorSql), ['', 'BELUM VERIFIKASI', 'UNVERIFIED'])
                    ->whereNull('tp.tanggal_submit');
                break;

            case 'draft':
                $query->whereIn(DB::raw('UPPER(TRIM(COALESCE(tp.status_nilai, "")))'), $draftStatuses);
                break;

            case 'menunggu':
                $query->whereIn(DB::raw('UPPER(TRIM(COALESCE(tp.status_nilai, "")))'), $submittedStatuses)
                    ->whereIn(DB::raw($statusVerifikatorSql), ['', 'BELUM VERIFIKASI', 'UNVERIFIED']);
                break;

            case 'returned':
                $query->where(function ($q) use ($returnedStatuses, $statusVerifikatorSql) {
                    $q->whereIn(DB::raw('UPPER(TRIM(COALESCE(tp.status_nilai, "")))'), $returnedStatuses)
                    ->orWhereIn(DB::raw($statusVerifikatorSql), $returnedStatuses);
                });
                break;

            case 'verified':
                $query->where(function ($q) use ($verifiedStatuses, $statusVerifikatorSql) {
                    $q->whereIn(DB::raw($statusVerifikatorSql), $verifiedStatuses)
                    ->orWhereIn(DB::raw('UPPER(TRIM(COALESCE(tp.status_nilai, "")))'), $verifiedStatuses);
                });
                break;

            case 'koreksi':
                $query->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('dp3_trans_penilaian_detail as tpd')
                    ->whereColumn('tpd.penilaian_id', 'tp.penilaian_id')
                    ->whereRaw('UPPER(COALESCE(tpd.verif_status, "")) = ?', ['KOREKSI']);
            });
            break;

            case 'semua':
            default:
            break;
        }

        $data = $query->orderByRaw('COALESCE(tp.pgw_nama, e.nama) asc')->paginate(25);

        return response()->json($data);
    }

    public function getPegawaiDetailModal(Request $request)
    {
        $validated = $request->validate([
            'pgw_id' => ['required', 'string'],
            'periode_id' => ['required', 'string', 'exists:dp3_trans_periode_penilaian,periode_id'],
        ]);
        $pgwId = $validated['pgw_id'];
        $periodeId = $validated['periode_id'];

        $trans = DB::table('dp3_trans_penilaian as tp')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
            ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
            ->where('tp.pegawai_id', $pgwId)
            ->where('tp.periode_id', $periodeId)
            ->where('tp.is_active', true)
            ->select(
                'tp.*',
                'penilai.nama as nama_penilai',
                'verifikator.nama as nama_verifikator',
                DB::raw("(SELECT COUNT(*) FROM dp3_trans_penilaian_detail as tpd
                    WHERE tpd.penilaian_id = tp.penilaian_id
                    AND (
                        UPPER(TRIM(COALESCE(tpd.verif_status, ''))) = 'KOREKSI'
                        OR (
                            tpd.verif_nilai_angka IS NOT NULL
                            AND tpd.nilai_angka IS NOT NULL
                            AND tpd.verif_nilai_angka <> tpd.nilai_angka
                        )
                    )
                ) as koreksi_count")
            )
            ->first();

        if (!$trans) {
            return response()->json(['message' => 'Data pegawai tidak ditemukan pada periode yang dipilih.'], 404);
        }

        $emp = DB::table('dp3_trans_penilaian as tp')
            ->leftJoin('employee as e', 'e.pgw_id', '=', 'tp.pegawai_id')
            ->leftJoin('unit_kerja as uk', function ($join) {
                $join->whereRaw('uk.kode_unit_kerja = COALESCE(tp.pgw_kode_unit_kerja, e.kode_unit_kerja)');
            })
            ->leftJoin('occupation as occ', 'tp.pgw_id_jabatan', '=', 'occ.occ_id')
            ->where('tp.pegawai_id', $pgwId)
            ->where('tp.periode_id', $periodeId)
            ->where('tp.is_active', true)
            ->select(
                'tp.pegawai_id as pgw_id',
                DB::raw('COALESCE(tp.pgw_nup, e.nup) as nup'),
                DB::raw('COALESCE(tp.pgw_nama, e.nama) as nama'),
                'uk.nama_unit_kerja as unit_kerja',
                DB::raw('COALESCE(tp.pgw_jabatan, occ.occ_name) as jabatan'),
                'tp.pgw_off_name as satker',
                DB::raw('COALESCE(tp.pgw_id_subdept_name, tp.pgw_dept_name) as penempatan')
            )
            ->first();

        $pertanyaanList = DB::table('dp3_trans_penilaian_detail as tpd')
            ->leftJoin('dp3_master_pertanyaan_penilaian as q', 'tpd.pertanyaan_id', '=', 'q.pertanyaan_id')
            ->leftJoin('dp3_master_kategori_penilaian as kat', 'q.kategori_id', '=', 'kat.kategori_id')
            ->where('tpd.penilaian_id', $trans->penilaian_id)
            ->orderBy('kat.urutan')
            ->orderBy('tpd.pertanyaan_id')
            ->select(
                DB::raw('COALESCE(kat.nama, "Aspek Penilaian") as nama_kategori'),
                'kat.bobot_persen as bobot_kategori',
                DB::raw('COALESCE(NULLIF(tpd.pertanyaan, ""), q.pertanyaan, "Pertanyaan") as pertanyaan'),
                DB::raw('COALESCE(NULLIF(tpd.deskripsi, ""), q.deskripsi) as deskripsi'),
                DB::raw('COALESCE(tpd.bobot_pertanyaan_persen, q.bobot_persen, 0) as bobot_pertanyaan_persen'),
                'tpd.kode_nilai',
                'tpd.nama_nilai',
                'tpd.nilai_angka',
                'tpd.nilai_akhir',
                'tpd.catatan',
                'tpd.verif_kode_nilai',
                'tpd.verif_nama_nilai',
                'tpd.verif_nilai_angka',
                'tpd.verif_nilai_akhir',
                'tpd.verif_catatan',
                'tpd.verif_status'
            )
            ->get()
            ->groupBy('nama_kategori')
            ->map(function ($questions, $categoryName) {
                return [
                    'nama_kategori' => $categoryName,
                    'bobot_kategori' => $questions->first()->bobot_kategori,
                    'pertanyaans' => $questions->values(),
                ];
            })
            ->values();

        return response()->json([
            'employee' => $emp,
            'trans' => $trans,
            'pertanyaan' => $pertanyaanList,
        ]);
    }
}