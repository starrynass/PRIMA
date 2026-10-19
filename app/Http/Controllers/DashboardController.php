<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Dp3TransPeriodePenilaian::orderBy('created_at', 'desc')->get();
        $selectedPeriodeId = $request->get('periode_id', $periodes->first()?->periode_id);
        $periodeAktif = $periodes->where('periode_id', $selectedPeriodeId)->first();

        // 1. Cek Apakah Ada Periode Penilaian
        if ($periodes->isEmpty() || !$periodeAktif) {
            return view('dashboard', [
                'periodes'             => collect(),
                'periodeAktif'         => null,
                'selectedPeriodeId'    => null,
                'totalPegawai'         => 0,
                'sudahTerverifikasi'   => 0,
                'menungguVerifikasi'   => 0,
                'dikembalikan'         => 0,
                'masihDraft'           => 0,
                'dikoreksi'            => 0,
                'atRisk'               => 0,
                'belumDinilai'         => 0,
                'percentVerifiedTotal' => 0,
                'direktoratProgress'   => [],
                'isOverdue'            => false,
                'hariTerlambat'        => 0,
                'deadlineTanggal'      => null,
                'noPeriode'            => true // Flag penanda tidak ada periode
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
        $dikoreksi = (clone $activeEntries)
            ->whereIn(DB::raw($statusVerifikatorSql), ['KOREKSI', 'PERLU DITINJAU'])
            ->count();

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

        return view('dashboard', compact(
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
            'atRiskEmployees'
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
                $query->whereIn(DB::raw($statusVerifikatorSql), ['KOREKSI', 'PERLU DITINJAU']);
                $title = "Penilaian Perlu Koreksi / Ditinjau";
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
}