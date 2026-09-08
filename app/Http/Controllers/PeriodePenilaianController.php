<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Employee;

class PeriodePenilaianController extends Controller
{
    /**
     * Menampilkan daftar periode penilaian beserta statistik agregasinya.
     */
    public function index(): View
    {
        $periodeList = Dp3TransPeriodePenilaian::query()
            ->withCount([
                'transPenilaian as total_pegawai',
                
                // Menghitung penilai kosong (NULL atau string kosong)
                'transPenilaian as penilai_kosong_count' => function ($query) {
                    $query->where(function ($q) {
                        $q->whereNull('penilai_id')
                          ->orWhere('penilai_id', '');
                    });
                },

                // Menghitung verifikator kosong (NULL atau string kosong)
                'transPenilaian as verifikator_kosong_count' => function ($query) {
                    $query->where(function ($q) {
                        $q->whereNull('verifikator_id')
                          ->orWhere('verifikator_id', '');
                    });
                },

                // Menghitung pegawai yang status verifikasinya sudah terverifikasi
                'transPenilaian as verifikasi_selesai_count' => function ($query) {
                    $query->where('status_verifitor', 'VERIFIED');
                },
            ])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        // Kalkulasi dinamis per baris data
        $periodeList->getCollection()->transform(function (Dp3TransPeriodePenilaian $periode) {
            // 1. Hitung Satker Unik yang terlibat dalam periode ini
            $totalSatker = DB::table('dp3_trans_penilaian')
                ->where('periode_id', $periode->periode_id) 
                ->whereNotNull('pgw_kode_satker')
                ->where('pgw_kode_satker', '!=', '')
                ->distinct()
                ->count('pgw_kode_satker');

            $periode->total_satker = $totalSatker;

            // 2. Hitung persentase progress verifikasi (mencegah Divide by Zero)
            $totalPegawai = (int) $periode->total_pegawai;
            $verifikasiSelesai = (int) $periode->verifikasi_selesai_count;

            $periode->progress_verifikasi = $totalPegawai > 0 
                ? round(($verifikasiSelesai / $totalPegawai) * 100, 1) 
                : 0;

            return $periode;
        });

        return view('penilaian.periode_penilaian', compact('periodeList'));
    }

    /**
     * Menyimpan periode penilaian baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Form Modal
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020|max:2099',
            'tanggal_mulai' => 'required|date',
            'tanggal_deadline' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:OPEN,LOCKED',
        ], [
            'tanggal_deadline.after_or_equal' => 'Tanggal deadline tidak boleh sebelum tanggal mulai.',
        ]);

        // 2. Format Periode ID (Contoh: "2026-08")
        $periodeId = sprintf('PER%d%02d', $request->tahun, $request->bulan);

        // 3. Cek apakah Periode ID sudah terdaftar
        if (Dp3TransPeriodePenilaian::where('periode_id', $periodeId)->exists()) {
            return redirect()->back()->with('error', 'Periode penilaian untuk bulan dan tahun tersebut sudah ada!');
        }

        // 4. Simpan ke Database
        Dp3TransPeriodePenilaian::create([
            'periode_id' => $periodeId,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_deadline' => $request->tanggal_deadline,
            'status' => $request->status ?? 'LOCKED' ,
            'dibuka_oleh_admin' => auth()->user()->name ?? 'Admin',
            'created_by' => auth()->user()->username ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Periode penilaian berhasil ditambahkan!');
    }

    /**
     * Mengubah data periode penilaian (Modal Edit).
     */
    public function update(Request $request, $id)
    {
        $periode = Dp3TransPeriodePenilaian::findOrFail($id);

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_deadline' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'tanggal_deadline.after_or_equal' => 'Tanggal deadline tidak boleh sebelum tanggal mulai.',
        ]);

        $periode->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_deadline' => $request->tanggal_deadline,
        ]);

        return redirect()->back()->with('success', 'Periode penilaian berhasil diperbarui!');
    }

    /**
     * Menghapus data periode penilaian.
     */
    public function destroy($id)
    {
        $periode = Dp3TransPeriodePenilaian::findOrFail($id);
        
        // Opsional: Cek jika sudah ada transaksi penilaian terkait sebelum dihapus
        if ($periode->transPenilaian()->exists()) {
            return redirect()->back()->with('error', 'Periode tidak dapat dihapus karena sudah memiliki data transaksi penilaian!');
        }

        $periode->delete();

        return redirect()->back()->with('success', 'Periode penilaian berhasil dihapus!');
    }

    /**
     * Mengubah status Buka/Kunci (OPEN / LOCKED).
     */
    public function toggleLock($id)
    {
        $periode = Dp3TransPeriodePenilaian::findOrFail($id);
        
        $newStatus = ($periode->status === 'OPEN') ? 'LOCKED' : 'OPEN';
        $periode->update(['status' => $newStatus]);

        $message = ($newStatus === 'LOCKED') ? 'Periode berhasil dikunci!' : 'Periode berhasil dibuka!';
        return redirect()->back()->with('success', $message);
    }

 public function generate($id)
{
    // 1. Ambil data periode
    $periode = Dp3TransPeriodePenilaian::findOrFail($id);

    // 2. Syarat utama: Hanya periode berstatus OPEN yang bisa di-generate
    if (strtoupper($periode->status) !== 'OPEN') {
        return redirect()->back()->with('error', 'Generate hanya dapat dilakukan pada periode berstatus OPEN.');
    }

    try {
        DB::beginTransaction();

        // 3. Ambil pegawai aktif (is_pensiun = 0)
        $pegawaiAktif = DB::table('employee')->where('is_pensiun', 0)->get();

        if ($pegawaiAktif->isEmpty()) {
            return redirect()->back()->with('warning', 'Tidak ada data pegawai aktif untuk di-generate.');
        }

        // 4. Inisialisasi/Snapshot data pegawai ke tabel transaksi penilaian
        // Sesuaikan nama tabel transaksi milikmu (misal: dp3_trans_penilaian)
        foreach ($pegawaiAktif as $pgw) {
            DB::table('dp3_trans_penilaian')->updateOrInsert(
                [
                    'periode_id' => $periode->periode_id,
                    'pgw_id'     => $pgw->pgw_id,
                ],
                [
                    'off_id'           => $pgw->off_id,
                    'dept_id'          => $pgw->dept_id,
                    'subdept_id'       => $pgw->subdept_id,
                    'occ_id'           => $pgw->occ_id,
                    'status_penilaian' => 'Belum Diisi', // Status awal sesuai gambar
                    'nilai_akhir'      => 0.00,
                    'predikat'         => 'Mengecewakan',
                    'updated_at'       => now(),
                    'created_at'       => now(),
                ]
            );
        }

        // 5. Update penanda bahwa periode sudah di-generate
        $periode->update([
            'is_generated' => 1,
            'keterangan'   => 'Sudah generate',
        ]);

        DB::commit();

        return redirect()->route('periode.detail', $periode->periode_id)
                         ->with('success', 'Berhasil meng-generate data penilaian untuk ' . $pegawaiAktif->count() . ' pegawai.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal meng-generate data: ' . $e->getMessage());
    }
}

public function detail($id)
{
    // 1. Ambil data periode
    $periode = Dp3TransPeriodePenilaian::findOrFail($id);

    // 2. Ambil data transaksi pegawai periode ini bergabung dengan master employee, office, occupation, dept, subdept
    $penilaianList = DB::table('dp3_trans_penilaian as tp')
        ->join('employee as e', 'tp.pgw_id', '=', 'e.pgw_id')
        ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
        ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
        ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
        ->leftJoin('sub_department as sd', 'e.subdept_id', '=', 'sd.subdept_id')
        ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
        ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
        ->where('tp.periode_id', $id)
        ->select(
            'tp.*',
            'e.nup',
            'e.nama',
            'o.off_name as penempatan',
            'occ.occ_name as jabatan',
            'd.dept_name as departemen',
            'sd.subdept_name as sub_departemen',
            'penilai.nama as nama_penilai',
            'verifikator.nama as nama_verifikator'
        )
        ->paginate(25);

    // 3. Hitung ringkasan statistik (Kategori Kartu Atas sesuai gambar)
    $stats = [
        'total_pegawai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->count(),
        'belum_diisi'    => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_penilaian', 'Belum Diisi')->count(),
        'draft'          => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_penilaian', 'Draft')->count(),
        'submit'         => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_penilaian', 'Submit')->count(),
        'verifikasi'     => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_penilaian', 'Verifikasi')->count(),
        'tanpa_penilai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->whereNull('penilai_id')->count(),
        'penilai_manual' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_penilai_manual', 1)->count(),
        'perlu_ditinjau' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_penilaian', 'Perlu Ditinjau')->count(),
    ];

    return view('penilaian.detail_periode', compact('periode', 'penilaianList', 'stats'));
}
}