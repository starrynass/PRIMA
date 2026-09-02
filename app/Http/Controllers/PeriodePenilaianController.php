<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
            'status' => 'required|in:OPEN,LOCKED',
        ], [
            'tanggal_deadline.after_or_equal' => 'Tanggal deadline tidak boleh sebelum tanggal mulai.',
        ]);

        // 2. Format Periode ID (Contoh: "2026-08")
        $periodeId = sprintf('%d-%02d', $request->tahun, $request->bulan);

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
            'status' => $request->status,
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
}