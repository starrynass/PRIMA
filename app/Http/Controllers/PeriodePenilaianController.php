<?php

namespace App\Http\Controllers;

use App\Models\Dp3TransPeriodePenilaian;
use App\Exports\PeriodePenilaianExport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\View\View;

class PeriodePenilaianController extends Controller
{
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
                    $query->where('status_verifikator', 'VERIFIED');
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
            'dibuka_oleh_admin' => 'Ya',
            'created_by' => auth()->user()?->email ?? 'System',
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
            // 4. Ambil seluruh pegawai untuk snapshot periode.
            // Semua pegawai tetap masuk ke periode agar data penilaian konsisten dengan total pegawai yang dikelola.
            $pegawaiAktif = DB::table('employee')
                ->where('is_hapus', 0)
                ->get();

            if ($pegawaiAktif->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data pegawai untuk di-generate.');
            }

            DB::beginTransaction();

            // 5. Insert
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
                        'pgw_kode_unit_kerja' => $pgw->kode_unit_kerja,
                        'pgw_kode_satker' => $pgw->off_id ?? $pgw->dept_id,
                        'status_nilai' => 'Belum Diisi',
                        'total_nilai' => 0.00,
                        'predikat' => 'Mengecewakan',
                        'created_by' => auth()->user()?->name ?? 'System',
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

    private function periodMutationGuard(string $id): ?RedirectResponse
    {
        $periode = Dp3TransPeriodePenilaian::where('periode_id', $id)->first();

        if (! $periode) {
            return redirect()->route('periode-penilaian.index')
                ->with('error', 'Periode tidak ditemukan.');
        }

        if (strtoupper($periode->status ?? '') === 'LOCKED') {
            return redirect()->route('periode.detail', $id)
                ->with('error', 'Periode sedang terkunci. Buka kunci periode terlebih dahulu.');
        }

        if (! $this->isPeriodGenerated($id)) {
            return redirect()->route('periode-penilaian.index')
                ->with('error', 'Periode belum di-generate.');
        }

        return null;
    }

    private function selectedActiveEntries(Request $request, string $id)
    {
        $validated = $request->validate([
            'penilaian_ids' => ['required', 'array', 'min:1'],
            'penilaian_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $entries = DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->where('is_active', true)
            ->whereIn('penilaian_id', $validated['penilaian_ids'])
            ->get();

        if ($entries->count() !== count($validated['penilaian_ids'])) {
            abort(422, 'Pilihan pegawai tidak valid untuk periode ini.');
        }

        return $entries;
    }

    private function matchingTemplate($occupationId): ?object
    {
        if (! $occupationId) {
            return null;
        }

        return DB::table('dp3_master_template_penilaian')
            ->where(function ($query) {
                $query->where('status_aktif', 'Aktif')
                    ->orWhere('status_aktif', 1);
            })
            ->orderBy('template_id')
            ->get(['template_id', 'nama_template', 'occ_id'])
            ->first(function ($template) use ($occupationId) {
                $occupationIds = json_decode((string) $template->occ_id, true);
                if (! is_array($occupationIds)) {
                    $occupationIds = explode(',', (string) $template->occ_id);
                }

                return in_array(
                    (string) $occupationId,
                    array_map(static fn ($value) => trim((string) $value, " \t\n\r\0\x0B\"'"), $occupationIds),
                    true
                );
            });
    }

    public function searchEmployees(string $id, Request $request)
    {
        $periode = Dp3TransPeriodePenilaian::where('periode_id', $id)->first();
        if (! $periode) {
            return response()->json(['message' => 'Periode tidak ditemukan.'], 404);
        }
        if (strtoupper($periode->status ?? '') === 'LOCKED' || ! $this->isPeriodGenerated($id)) {
            return response()->json(['message' => 'Periode tidak dapat diubah.'], 422);
        }

        $validated = $request->validate([
            'q' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        $search = trim($validated['q']);

        $employees = DB::table('employee as e')
            ->where(function ($query) {
                $query->whereNull('e.is_hapus')
                    ->orWhere('e.is_hapus', 0);
            });
            
        $employees = DB::table('employee as e') 
            ->whereNotExists(function ($query) use ($id) {
                $query->selectRaw('1')
                    ->from('dp3_trans_penilaian as tp')
                    ->whereColumn('tp.pegawai_id', 'e.pgw_id')
                    ->where('tp.periode_id', $id);
            })
            ->where(function ($query) use ($search) {
                $query->where('e.nama', 'like', "%{$search}%")
                    ->orWhere('e.nup', 'like', "%{$search}%");
            })
            ->orderBy('e.nama')
            ->limit(20)
            ->get(['e.pgw_id', 'e.nup', 'e.nama', 'e.occ_id'])
            ->map(function ($employee) {
                $employee->has_template = $this->matchingTemplate($employee->occ_id) !== null;
                return $employee;
            });

        return response()->json($employees);
    }

    public function addEmployee(string $id, Request $request): RedirectResponse
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        $validated = $request->validate([
            'pegawai_id' => ['required', 'integer', 'exists:employee,pgw_id'],
        ]);

        $employee = DB::table('employee')->where('pgw_id', $validated['pegawai_id'])->first();

        if (DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->where('pegawai_id', $employee->pgw_id)
            ->exists()) {
            return back()->withErrors(['pegawai_id' => 'Pegawai ini sudah terdaftar pada periode.']);
        }

        $template = $this->matchingTemplate($employee->occ_id);
        $snapshot = [
            'kode' => 'PEN-' . $id . '-' . $employee->pgw_id,
            'periode_id' => $id,
            'pegawai_id' => $employee->pgw_id,
            'pgw_nup' => $employee->nup,
            'pgw_nama' => $employee->nama,
            'pgw_sex' => $employee->sex,
            'pgw_id_jabatan' => $employee->occ_id,
            'pgw_jabatan' => DB::table('occupation')->where('occ_id', $employee->occ_id)->value('occ_name'),
            'pgw_id_dept' => $employee->dept_id,
            'pgw_dept_name' => DB::table('department')->where('dept_id', $employee->dept_id)->value('dept_name'),
            'pgw_id_subdept' => $employee->subdept_id,
            'pgw_id_subdept_name' => DB::table('department_sub')->where('subdept_id', $employee->subdept_id)->value('subdept_name'),
            'pgw_off_id' => $employee->off_id,
            'pgw_off_name' => DB::table('office')->where('off_id', $employee->off_id)->value('off_name'),
            'pgw_kode_satker' => $employee->kode_satker ?? $employee->off_id ?? $employee->dept_id,
            'pgw_kode_unit_kerja' => $employee->kode_unit_kerja ?? null,
            'template_id' => $template?->template_id,
            'template_nama' => $template?->nama_template,
            'status_nilai' => 'Belum Diisi',
            'total_nilai' => 0,
            'predikat' => 'Mengecewakan',
            'is_active' => true,
            'created_by' => auth()->user()?->name ?? 'System',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('dp3_trans_penilaian')->insert($snapshot);

        return back()->with('success', 'Pegawai berhasil ditambahkan ke periode.');
    }

    public function updateEmployees(string $id, Request $request): RedirectResponse
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        $entries = $this->selectedActiveEntries($request, $id);
        $validated = $request->validate([
            'occ_id' => ['nullable', 'integer', 'exists:occupation,occ_id'],
            'dept_id' => ['nullable', 'integer', 'exists:department,dept_id'],
            'subdept_id' => ['nullable', 'integer', 'exists:department_sub,subdept_id'],
            'off_id' => ['nullable', 'integer', 'exists:office,off_id'],
            'kode_satker' => ['nullable', 'string', 'max:255'],
            'kode_unit_kerja' => ['nullable', 'string', 'max:20', 'exists:unit_kerja,kode_unit_kerja'],
        ]);

        $updates = [];
        foreach ([
            'occ_id' => ['pgw_id_jabatan', 'pgw_jabatan', 'occupation', 'occ_id', 'occ_name'],
            'dept_id' => ['pgw_id_dept', 'pgw_dept_name', 'department', 'dept_id', 'dept_name'],
            'subdept_id' => ['pgw_id_subdept', 'pgw_id_subdept_name', 'department_sub', 'subdept_id', 'subdept_name'],
            'off_id' => ['pgw_off_id', 'pgw_off_name', 'office', 'off_id', 'off_name'],
        ] as $input => [$idColumn, $nameColumn, $table, $keyColumn, $labelColumn]) {
            if (! empty($validated[$input])) {
                $updates[$idColumn] = $validated[$input];
                $updates[$nameColumn] = DB::table($table)
                    ->where($keyColumn, $validated[$input])
                    ->value($labelColumn);
            }
        }

        if (! empty($validated['kode_satker'])) {
            $updates['pgw_kode_satker'] = $validated['kode_satker'];
        }

        if (! empty($validated['kode_unit_kerja'])) {
            $updates['pgw_kode_unit_kerja'] = $validated['kode_unit_kerja'];
        }

        if ($updates === []) {
            return back()->withErrors(['pegawai_ids' => 'Pilih setidaknya satu data pegawai yang akan diperbarui.']);
        }

        DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->whereIn('penilaian_id', $entries->pluck('penilaian_id'))
            ->update($updates + ['updated_at' => now()]);

        return back()->with('success', 'Data pegawai pada periode berhasil diperbarui.');
    }

    public function deactivateEmployees(string $id, Request $request): RedirectResponse
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        $entries = $this->selectedActiveEntries($request, $id);
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->whereIn('penilaian_id', $entries->pluck('penilaian_id'))
            ->update([
                'is_active' => false,
                'inactive_reason' => trim($validated['reason']),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Pegawai berhasil dinonaktifkan dari periode.');
    }

    public function assignEvaluator(string $id, Request $request): RedirectResponse
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        $entries = $this->selectedActiveEntries($request, $id);
        $validated = $request->validate([
            'penilai_id' => ['required', 'integer', 'exists:employee,pgw_id'],
        ]);

        DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->whereIn('penilaian_id', $entries->pluck('penilaian_id'))
            ->update([
                'penilai_id' => $validated['penilai_id'],
                'penilai_manual' => true,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Penilai berhasil ditetapkan secara manual.');
    }

    public function resetEvaluator(string $id, Request $request): RedirectResponse
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        $entries = $this->selectedActiveEntries($request, $id);

        DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->whereIn('penilaian_id', $entries->pluck('penilaian_id'))
            ->update([
                'penilai_id' => null,
                'penilai_manual' => false,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Penugasan manual dihapus. Penilai dapat ditetapkan kembali melalui mekanisme otomatis.');
    }

    public function export(string $id, Request $request)
    {
        if ($response = $this->periodMutationGuard($id)) {
            return $response;
        }

        return Excel::download(
            new PeriodePenilaianExport($id, $request->query()),
            'detail-periode-' . $id . '.xlsx'
        );
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
            ->leftJoin('office as o', 'tp.pgw_off_id', '=', 'o.off_id')
            ->leftJoin('occupation as occ', 'tp.pgw_id_jabatan', '=', 'occ.occ_id')
            ->leftJoin('department as d', 'tp.pgw_id_dept', '=', 'd.dept_id')
            ->leftJoin('department_sub as sd', 'tp.pgw_id_subdept', '=', 'sd.subdept_id')
            ->leftJoin('unit_kerja as uk', 'tp.pgw_kode_unit_kerja', '=', 'uk.kode_unit_kerja')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
            ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
            ->where('tp.periode_id', $id)
            ->where('tp.is_active', true)
            ->select(
                'tp.*',
                'tp.pgw_nup as nup',
                'tp.pgw_nama as nama',
                DB::raw('COALESCE(tp.pgw_off_name, o.off_name) as penempatan'),
                DB::raw('COALESCE(tp.pgw_jabatan, occ.occ_name) as jabatan'),
                DB::raw('COALESCE(tp.pgw_dept_name, d.dept_name) as departemen'),
                DB::raw('COALESCE(tp.pgw_id_subdept_name, sd.subdept_name) as sub_departemen'),
                'uk.nama_unit_kerja as unit_kerja',
                'tp.status_nilai as status_penilaian',
                'tp.total_nilai as nilai_akhir',
                'penilai.nama as nama_penilai',
                'verifikator.nama as nama_verifikator'
            );

        // Filter Search Text
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('tp.pgw_nama', 'like', "%{$search}%")
                ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $status = strtoupper((string) $request->status);
            $query->whereRaw('UPPER(COALESCE(tp.status_nilai, "BELUM DIISI")) = ?', [$status]);
        }

        // Filter Departemen
        if ($request->filled('dept_id')) {
            $deptId = (string) $request->dept_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('d.dept_id', $deptId)
                ->orWhere('tp.pgw_id_dept', $deptId);
            });
        }

        // Filter Sub Departemen
        if ($request->filled('subdept_id')) {
            $subDeptId = (string) $request->subdept_id;
            $query->where(function ($q) use ($subDeptId) {
                $q->where('sd.subdept_id', $subDeptId)
                ->orWhere('tp.pgw_id_subdept', $subDeptId);
            });
        }

        // FILTER BARU: Unit Kerja
        if ($request->filled('kode_unit_kerja')) {
            $kodeUnit = (string) $request->kode_unit_kerja;
            $query->where(function ($q) use ($kodeUnit) {
                $q->where('uk.kode_unit_kerja', $kodeUnit)
                ->orWhere('tp.pgw_kode_unit_kerja', $kodeUnit);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'nama_asc');
        switch ($sort) {
            case 'nama_desc':
                $query->orderBy('tp.pgw_nama', 'desc');
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
                $query->orderBy('tp.pgw_nama', 'asc');
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
        $occupations = DB::table('occupation')->select('occ_id', 'occ_name')->orderBy('occ_name')->get();
        $offices = DB::table('office')->select('off_id', 'off_name')->orderBy('off_name')->get();
        $unitKerjaList = DB::table('unit_kerja')->select('kode_unit_kerja', 'nama_unit_kerja')->orderBy('nama_unit_kerja')->get();
        $employees = DB::table('employee')->select('pgw_id', 'nup', 'nama')->orderBy('nama')->get();

        $stats = [
            'total_pegawai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->count(),
            'belum_diisi'    => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('status_nilai', 'Belum Diisi')->count(),
            'draft'          => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('status_nilai', 'Draft')->count(),
            'submit'         => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('status_nilai', 'Submit')->count(),
            'verifikasi'     => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('status_nilai', 'Verifikasi')->count(),
            'tanpa_penilai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->whereNull('penilai_id')->count(),
            'penilai_manual' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('penilai_manual', 1)->count(),
            'perlu_ditinjau' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->where('status_nilai', 'Perlu Ditinjau')->count(),
        ];

        return view('penilaian.detail_periode', compact(
            'periode',
            'penilaianList',
            'stats',
            'departments',
            'subDepartments',
            'occupations',
            'offices',
            'unitKerjaList',
            'employees'
        ));
    }
}