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
     * Catatan: helper ini mencegah 404 yang muncul saat ID periode tidak lagi ada di database,
     * misalnya karena data sudah dihapus atau request berasal dari state stale di UI.
     * Alih-alih memunculkan error 404, sistem akan redirect ke daftar periode dengan pesan aman.
     */
    protected function findPeriodeOrRedirect(string $id): ?Dp3TransPeriodePenilaian
    {
        $periode = Dp3TransPeriodePenilaian::where('periode_id', $id)->first();

        if (! $periode) {
            redirect()->route('periode-penilaian.index')
                ->with('error', 'Periode tidak ditemukan atau sudah dihapus.')
                ->send();

            return null;
        }

        return $periode;
    }

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
        $periode = $this->findPeriodeOrRedirect($id);

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')->with('error', 'Periode tidak ditemukan atau sudah dihapus.');
        }

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
        $periode = $this->findPeriodeOrRedirect($id);

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')->with('error', 'Periode tidak ditemukan atau sudah dihapus.');
        }
        
        // Penghapusan diperbolehkan tanpa pengecualian apapun.
        $periode->delete();

        return redirect()->back()->with('success', 'Periode penilaian berhasil dihapus!');
    }

    /**
     * Mengubah status Buka/Kunci (OPEN / LOCKED).
     */
    public function toggleLock($id)
    {
        $periode = $this->findPeriodeOrRedirect($id);

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')->with('error', 'Periode tidak ditemukan atau sudah dihapus.');
        }
        
        $newStatus = ($periode->status === 'OPEN') ? 'LOCKED' : 'OPEN';
        $periode->update(['status' => $newStatus]);

        $message = ($newStatus === 'LOCKED') ? 'Periode berhasil dikunci!' : 'Periode berhasil dibuka!';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Catatan: fungsi ini dipakai untuk memastikan periode belum pernah digenerate.
     * Logika ini memeriksa data snapshot pegawai di tabel dp3_trans_penilaian
     * atau kolom generated flag bila kolom tersebut sudah ada di DB.
     */
    protected function isPeriodGenerated(string $periodeId): bool
    {
        if (DB::table('dp3_trans_penilaian')->where('periode_id', $periodeId)->exists()) {
            return true;
        }

        $periode = DB::table('dp3_trans_periode_penilaian')->where('periode_id', $periodeId)->first();

        if (! $periode) {
            return false;
        }

        $columns = DB::getSchemaBuilder()->getColumnListing('dp3_trans_periode_penilaian');

        if (in_array('is_generated', $columns, true) && (int) ($periode->is_generated ?? 0) === 1) {
            return true;
        }

        if (in_array('keterangan', $columns, true) && is_string($periode->keterangan ?? null)) {
            return stripos($periode->keterangan, 'generate') !== false;
        }

        return false;
    }

    /**
     * Catatan: fungsi ini menandai periode bahwa data pegawai sudah dibuat snapshot-nya.
     * Karena struktur DB bisa ada atau tidak ada kolom is_generated/keterangan,
     * maka update dilakukan hanya pada kolom yang benar-benar tersedia.
     */
    protected function markPeriodGenerated(string $periodeId): void
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('dp3_trans_periode_penilaian');
        $payload = ['updated_at' => now()];

        if (in_array('is_generated', $columns, true)) {
            $payload['is_generated'] = 1;
        }

        if (in_array('keterangan', $columns, true)) {
            $payload['keterangan'] = 'Sudah generate';
        }

        if (! empty($payload)) {
            DB::table('dp3_trans_periode_penilaian')->where('periode_id', $periodeId)->update($payload);
        }
    }

    public function generate($id)
    {
        // 1. Ambil data periode
        $periode = Dp3TransPeriodePenilaian::where('periode_id', $id)->first();

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')
                ->with('error', 'Periode tidak ditemukan atau sudah dihapus, sehingga generate tidak bisa dijalankan.');
        }

        // 2. Hindari generate ganda: periode yang sudah punya data pegawai tidak boleh generate ulang.
        if ($this->isPeriodGenerated($periode->periode_id)) {
            return redirect()->route('periode-penilaian.index')
                ->with('warning', 'Periode ini sudah di-generate. Anda dapat membuka detail dari daftar periode.');
        }

        // 3. Notifikasi & Penolakan jika periode berstatus LOCKED
        if (strtoupper($periode->status) === 'LOCKED') {
            return redirect()->back()->with('error', 'Gagal Generate: Periode sedang di-LOCKED/dikunci. Buka kunci periode terlebih dahulu!');
        }

        try {
            DB::beginTransaction();

            // 4. Ambil seluruh pegawai untuk snapshot periode.
            // Semua pegawai tetap masuk ke periode agar data penilaian konsisten dengan total pegawai yang dikelola.
            $pegawaiAktif = DB::table('employee')->get();

            if ($pegawaiAktif->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data pegawai untuk di-generate.');
            }

            // 5. Insert / Snapshot pegawai ke tabel dp3_trans_penilaian.
            // Catatan: kolom yang benar-benar ada di migrasi adalah nama-nama yang dideklarasikan
            // pada file migration, jadi kita hanya menulis pada field yang valid agar data tersimpan.
            foreach ($pegawaiAktif as $pgw) {
                $kode = 'PEN-' . $periode->periode_id . '-' . $pgw->pgw_id;

                $namaJabatan = DB::table('occupation')->where('occ_id', $pgw->occ_id)->value('occ_name');
                $namaDepartemen = DB::table('department')->where('dept_id', $pgw->dept_id)->value('dept_name');
                $namaSubDepartemen = DB::table('department_sub')->where('subdept_id', $pgw->subdept_id)->value('subdept_name');
                $namaOffice = DB::table('office')->where('off_id', $pgw->off_id)->value('off_name');

                DB::table('dp3_trans_penilaian')->updateOrInsert(
                    [
                        'periode_id' => $periode->periode_id,
                        'pegawai_id' => $pgw->pgw_id,
                    ],
                    [
                        'kode' => $kode,
                        'pgw_nup' => $pgw->nup,
                        'pgw_nama' => $pgw->nama,
                        'pgw_id_jabatan' => $pgw->occ_id,
                        'pgw_jabatan' => $namaJabatan,
                        'pgw_id_dept' => $pgw->dept_id,
                        'pgw_dept_name' => $namaDepartemen,
                        'pgw_id_subdept' => $pgw->subdept_id,
                        'pgw_id_subdept_name' => $namaSubDepartemen,
                        'pgw_off_id' => $pgw->off_id,
                        'pgw_off_name' => $namaOffice,
                        'pgw_kode_satker' => $pgw->off_id ?? $pgw->dept_id,
                        'status_nilai' => 'Belum Diisi',
                        'total_nilai' => 0.00,
                        'predikat' => 'Mengecewakan',
                        'created_by' => auth()->user()->name ?? 'System',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // 6. Tandai bahwa periode sudah di-generate sesuai struktur kolom yang tersedia.
            $this->markPeriodGenerated($periode->periode_id);

            DB::commit();

            // 7. Setelah generate sukses, tetap di halaman periode penilaian.
            return redirect()->route('periode-penilaian.index')
                         ->with('success', 'Berhasil meng-generate data penilaian untuk ' . $pegawaiAktif->count() . ' pegawai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal meng-generate data: ' . $e->getMessage());
        }
    }

    public function detail($id, Request $request)
    {
        $periode = Dp3TransPeriodePenilaian::where('periode_id', $id)->first();

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')
                ->with('error', 'Periode tidak ditemukan atau sudah dihapus.');
        }

        if (strtoupper($periode->status ?? '') === 'LOCKED' || ! $this->isPeriodGenerated($periode->periode_id)) {
            return redirect()->route('periode-penilaian.index')
                ->with('error', 'Detail periode tidak bisa dibuka karena periode sedang terkunci atau belum di-generate.');
        }

        $query = DB::table('dp3_trans_penilaian as tp')
            ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
            ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
            ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
            ->leftJoin('department_sub as sd', 'e.subdept_id', '=', 'sd.subdept_id')
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
                'tp.status_nilai as status_penilaian',
                'tp.total_nilai as nilai_akhir',
                'penilai.nama as nama_penilai',
                'verifikator.nama as nama_verifikator'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('e.nama', 'like', "%{$search}%")
                    ->orWhere('e.nup', 'like', "%{$search}%")
                    ->orWhere('tp.pgw_nama', 'like', "%{$search}%")
                    ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = strtoupper((string) $request->status);
            $query->whereRaw('UPPER(COALESCE(tp.status_nilai, "BELUM DIISI")) = ?', [$status]);
        }

        if ($request->filled('dept_id')) {
            $deptId = (string) $request->dept_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('d.dept_id', $deptId)
                    ->orWhere('e.dept_id', $deptId)
                    ->orWhere('tp.pgw_id_dept', $deptId);
            });
        }

        if ($request->filled('subdept_id')) {
            $subDeptId = (string) $request->subdept_id;
            $query->where(function ($q) use ($subDeptId) {
                $q->where('sd.subdept_id', $subDeptId)
                    ->orWhere('e.subdept_id', $subDeptId)
                    ->orWhere('tp.pgw_id_subdept', $subDeptId);
            });
        }

        $sort = $request->get('sort', 'nama_asc');
        switch ($sort) {
            case 'nama_desc':
                $query->orderBy('e.nama', 'desc');
                break;
            case 'nilai_desc':
                $query->orderBy('tp.total_nilai', 'desc');
                break;
            case 'nilai_asc':
                $query->orderBy('tp.total_nilai', 'asc');
                break;
            case 'status':
                $query->orderBy('tp.status_nilai', 'asc');
                break;
            case 'nama_asc':
            default:
                $query->orderBy('e.nama', 'asc');
                break;
        }

        $penilaianList = $query->paginate(25)->appends($request->query());

        $penilaianList->getCollection()->transform(function ($row) {
            $data = is_object($row) ? (array) $row : $row;
            $data['status_penilaian'] = strtoupper((string) ($data['status_penilaian'] ?? $data['status_nilai'] ?? 'BELUM DIISI'));
            $data['nilai_akhir'] = is_numeric($data['nilai_akhir'] ?? null) ? (float) $data['nilai_akhir'] : 0.0;
            $data['predikat'] = ! empty($data['predikat']) ? $data['predikat'] : 'Mengecewakan';
            return (object) $data;
        });

        $departments = DB::table('department')->select('dept_id', 'dept_name')->orderBy('dept_name')->get();
        $subDepartments = DB::table('department_sub')->select('subdept_id', 'subdept_name')->orderBy('subdept_name')->get();

        $stats = [
            'total_pegawai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->count(),
            'belum_diisi'    => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_nilai', 'Belum Diisi')->count(),
            'draft'          => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_nilai', 'Draft')->count(),
            'submit'         => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_nilai', 'Submit')->count(),
            'verifikasi'     => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_nilai', 'Verifikasi')->count(),
            'tanpa_penilai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->whereNull('penilai_id')->count(),
            'penilai_manual' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('penilai_manual', 1)->count(),
            'perlu_ditinjau' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('status_nilai', 'Perlu Ditinjau')->count(),
        ];

        return view('penilaian.detail_periode', compact('periode', 'penilaianList', 'stats', 'departments', 'subDepartments'));
    }
}