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
            $totalUnitKerja = DB::table('dp3_trans_penilaian')
                ->where('periode_id', $periode->periode_id) 
                ->whereNotNull('pgw_kode_unit_kerja')
                ->where('pgw_kode_unit_kerja', '!=', '')
                ->distinct()
                ->count('pgw_kode_unit_kerja');

            $periode->total_unit_kerja = $totalUnitKerja;

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
                $auto = self::getAutoAssignees((int) $pgw->occ_id, $pgw->kode_unit_kerja);
                
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
                        'penilai_id' => $auto['penilai']?->pgw_id,
                        'verifikator_id' => $auto['verifikator']?->pgw_id,
                        'penilai_manual' => false,
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

    public static function getAutoAssignees(?int $occId, ?string $unitCode): array
    {
        if (! $occId || ! $unitCode) {
            return ['penilai' => null, 'verifikator' => null];
        }

        $currentLevel = DB::table('occupation')->where('occ_id', $occId)->value('level');
        if (! $currentLevel) {
            return ['penilai' => null, 'verifikator' => null];
        }

        // Penilai: Pegawai di unit kerja yang sama dengan jabatan lebih tinggi (level lebih kecil, paling dekat dengan currentLevel)
        $penilai = DB::table('employee as e')
            ->join('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->where('e.kode_unit_kerja', $unitCode)
            ->where('occ.level', '<', $currentLevel)
            ->orderBy('occ.level', 'desc')
            ->orderBy('e.pgw_id', 'asc')
            ->select('e.pgw_id', 'e.nama', 'occ.level', 'occ.occ_name')
            ->first();

        $penilaiLevel = $penilai ? $penilai->level : $currentLevel;

        // Verifikator: Pegawai di unit kerja yang sama dengan jabatan di atas penilai
        $verifikator = DB::table('employee as e')
            ->join('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->where('e.kode_unit_kerja', $unitCode)
            ->where('occ.level', '<', $penilaiLevel)
            ->orderBy('occ.level', 'desc')
            ->orderBy('e.pgw_id', 'asc')
            ->select('e.pgw_id', 'e.nama', 'occ.level', 'occ.occ_name')
            ->first();

        if (! $verifikator && $penilai) {
            $verifikator = $penilai;
        }

        return ['penilai' => $penilai, 'verifikator' => $verifikator];
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
            ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->leftJoin('unit_kerja as uk', 'e.kode_unit_kerja', '=', 'uk.kode_unit_kerja')
            ->leftJoin('office as o', 'e.off_id', '=', 'o.off_id')
            ->leftJoin('department as d', 'e.dept_id', '=', 'd.dept_id')
            ->leftJoin('department_sub as sub', 'e.subdept_id', '=', 'sub.subdept_id')
            ->whereNotExists(function ($query) use ($id) {
                $query->selectRaw('1')
                    ->from('dp3_trans_penilaian as tp')
                    ->whereColumn('tp.pegawai_id', 'e.pgw_id')
                    ->where('tp.periode_id', $id)
                    ->where('tp.is_active', true);
            })
            ->where(function ($query) use ($search) {
                $query->where('e.nama', 'like', "%{$search}%")
                    ->orWhere('e.nup', 'like', "%{$search}%");
            })
            ->orderBy('e.nama')
            ->limit(20)
            ->get([
                'e.pgw_id',
                'e.nup',
                'e.nama',
                'e.occ_id',
                'e.dept_id',
                'e.subdept_id',
                'e.off_id',
                'e.kode_satker',
                'e.kode_unit_kerja',
                'occ.occ_name as jabatan',
                'uk.nama_unit_kerja as unit_kerja',
                'o.off_name as penempatan',
                'd.dept_name as departemen',
                'sub.subdept_name as subdepartemen',
            ])
            ->map(function ($employee) {
                $roleNames = [1 => 'Direktur', 5 => 'Manager', 6 => 'Asmen', 9 => 'Staff'];
                if (isset($roleNames[$employee->occ_id])) {
                    $employee->jabatan = $roleNames[$employee->occ_id];
                }
                $employee->has_template = $this->matchingTemplate($employee->occ_id) !== null;
                $auto = self::getAutoAssignees((int) $employee->occ_id, $employee->kode_unit_kerja);
                $employee->penilai_nama = $auto['penilai'] ? $auto['penilai']->nama : 'Tidak Ada (Tingkat Tertinggi)';
                $employee->verifikator_nama = $auto['verifikator'] ? $auto['verifikator']->nama : 'Tidak Ada (Tingkat Tertinggi)';
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

        $employee = DB::table('employee')
            ->where('pgw_id', $validated['pegawai_id'])
            ->first();
        if (!$employee) {
            return back()->withErrors(['pegawai_id' => 'Pegawai tidak aktif atau tidak ditemukan.']);
        }

        $existing = DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->where('pegawai_id', $employee->pgw_id)
            ->first();

        if ($existing && $existing->is_active) {
            return back()->withErrors(['pegawai_id' => 'Pegawai ini sudah aktif terdaftar pada periode.']);
        }

        $template = $this->matchingTemplate($employee->occ_id);
        $auto = self::getAutoAssignees((int) $employee->occ_id, $employee->kode_unit_kerja);
        $roleNames = [1 => 'Direktur', 5 => 'Manager', 6 => 'Asmen', 9 => 'Staff'];
        $jabatanName = $roleNames[$employee->occ_id] ?? DB::table('occupation')->where('occ_id', $employee->occ_id)->value('occ_name');

        $snapshot = [
            'kode' => 'PEN-' . $id . '-' . $employee->pgw_id,
            'periode_id' => $id,
            'pegawai_id' => $employee->pgw_id,
            'pgw_nup' => $employee->nup,
            'pgw_nama' => $employee->nama,
            'pgw_sex' => $employee->sex,
            'pgw_id_jabatan' => $employee->occ_id,
            'pgw_jabatan' => $jabatanName,
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
            'penilai_id' => $auto['penilai']?->pgw_id,
            'verifikator_id' => $auto['verifikator']?->pgw_id,
            'penilai_manual' => false,
            'status_nilai' => $existing ? $existing->status_nilai : 'Belum Diisi',
            'total_nilai' => $existing ? $existing->total_nilai : 0,
            'predikat' => $existing ? $existing->predikat : 'Mengecewakan',
            'is_active' => true,
            'inactive_reason' => null,
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('dp3_trans_penilaian')
                ->where('penilaian_id', $existing->penilaian_id)
                ->update($snapshot);
        } else {
            $snapshot['created_by'] = auth()->user()?->name ?? 'System';
            $snapshot['created_at'] = now();
            DB::table('dp3_trans_penilaian')->insert($snapshot);
        }

        return back()->with('success', 'Pegawai berhasil ditambahkan ke periode dengan data database terbaru.');
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

        $roleJabatanNames = [
            1 => 'Direktur',
            5 => 'Manager',
            6 => 'Asmen',
            9 => 'Staff',
        ];

        if (! empty($validated['occ_id'])) {
            $updates['pgw_id_jabatan'] = $validated['occ_id'];
            $updates['pgw_jabatan'] = $roleJabatanNames[$validated['occ_id']] ?? DB::table('occupation')->where('occ_id', $validated['occ_id'])->value('occ_name');
            $template = $this->matchingTemplate($validated['occ_id']);
            $updates['template_id'] = $template?->template_id;
            $updates['template_nama'] = $template?->nama_template;
        }

        if ($updates === []) {
            return back()->withErrors(['pegawai_ids' => 'Pilih setidaknya satu data pegawai yang akan diperbarui.']);
        }

        DB::table('dp3_trans_penilaian')
            ->where('periode_id', $id)
            ->whereIn('penilaian_id', $entries->pluck('penilaian_id'))
            ->update($updates + ['updated_at' => now()]);

        if (! empty($validated['occ_id']) || ! empty($validated['kode_unit_kerja'])) {
            foreach ($entries as $entry) {
                if (! $entry->penilai_manual) {
                    $newOccId = (int) ($validated['occ_id'] ?? $entry->pgw_id_jabatan);
                    $newUnitCode = $validated['kode_unit_kerja'] ?? $entry->pgw_kode_unit_kerja;
                    $auto = self::getAutoAssignees($newOccId, $newUnitCode);
                    DB::table('dp3_trans_penilaian')
                        ->where('penilaian_id', $entry->penilaian_id)
                        ->update([
                            'penilai_id' => $auto['penilai']?->pgw_id,
                            'verifikator_id' => $auto['verifikator']?->pgw_id,
                        ]);
                }
            }
        }

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
        $resetCount = 0;

        foreach ($entries as $entry) {
            $targetOccId = (int) ($entry->pgw_id_jabatan ?? 0);
            $targetUnitCode = $entry->pgw_kode_unit_kerja;

            $auto = self::getAutoAssignees($targetOccId, $targetUnitCode);

            DB::table('dp3_trans_penilaian')
                ->where('penilaian_id', $entry->penilaian_id)
                ->update([
                    'penilai_id' => $auto['penilai']?->pgw_id,
                    'verifikator_id' => $auto['verifikator']?->pgw_id,
                    'penilai_manual' => false,
                    'updated_at' => now(),
                ]);
            $resetCount++;
        }

        return back()->with('success', "Penilai dan verifikator untuk {$resetCount} pegawai berhasil dikembalikan ke otomatis sesuai hierarki jabatan dan unit kerja.");
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
            ->leftJoin('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
            ->leftJoin('office as o', function ($join) {
                $join->on('o.off_id', '=', DB::raw('COALESCE(e.off_id, tp.pgw_off_id)'));
            })
            ->leftJoin('occupation as occ', function ($join) {
                $join->on('occ.occ_id', '=', DB::raw('COALESCE(e.occ_id, tp.pgw_id_jabatan)'));
            })
            ->leftJoin('department as d', function ($join) {
                $join->on('d.dept_id', '=', DB::raw('COALESCE(e.dept_id, tp.pgw_id_dept)'));
            })
            ->leftJoin('department_sub as sd', function ($join) {
                $join->on('sd.subdept_id', '=', DB::raw('COALESCE(e.subdept_id, tp.pgw_id_subdept)'));
            })
            ->leftJoin('unit_kerja as uk', function ($join) {
                $join->on('uk.kode_unit_kerja', '=', DB::raw('COALESCE(e.kode_unit_kerja, tp.pgw_kode_unit_kerja)'));
            })
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
            ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
            ->leftJoin('dp3_master_predikat_nilai as mp', function ($join) {
                $join->on(DB::raw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0)'), '>=', 'mp.nilai_min')
                     ->on(DB::raw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0)'), '<=', 'mp.nilai_max');
            })
            ->where('tp.periode_id', $id)
            ->where('tp.is_active', true)
            ->select(
                'tp.*',
                DB::raw('COALESCE(e.nup, tp.pgw_nup, "-") as nup'),
                DB::raw('COALESCE(e.nama, tp.pgw_nama, "-") as nama'),
                DB::raw('COALESCE(o.off_name, tp.pgw_off_name, "-") as penempatan'),
                DB::raw('COALESCE(occ.occ_name, tp.pgw_jabatan, "-") as jabatan'),
                DB::raw('COALESCE(d.dept_name, tp.pgw_dept_name, "-") as departemen'),
                DB::raw('COALESCE(sd.subdept_name, tp.pgw_id_subdept_name, "-") as sub_departemen'),
                DB::raw("COALESCE(uk.nama_unit_kerja, e.kode_unit_kerja, tp.pgw_kode_unit_kerja, '-') as unit_kerja"),
                'tp.status_nilai as status_penilaian',
                DB::raw("COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, '') as status_verifikator"),
                DB::raw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) as nilai_akhir'),
                DB::raw('COALESCE(mp.predikat, tp.predikat, "Mengecewakan") as predikat'),
                'penilai.nama as nama_penilai',
                'verifikator.nama as nama_verifikator'
            );

        // Filter Search Text (Nama / NUP)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('e.nama', 'like', "%{$search}%")
                  ->orWhere('e.nup', 'like', "%{$search}%")
                  ->orWhere('tp.pgw_nama', 'like', "%{$search}%")
                  ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $status = strtoupper((string) $request->status);
            if ($status === 'SUBMIT' || $status === 'DIAJUKAN') {
                $query->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['SUBMIT', 'SUBMITTED', 'DIAJUKAN']);
            } elseif ($status === 'VERIFIKASI' || $status === 'VERIFIED') {
                $query->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['VERIFIKASI', 'VERIFIED', 'SELESAI', 'APPROVED'])
                      ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['VERIFIKASI', 'VERIFIED', 'SELESAI', 'APPROVED']);
                });
            } elseif ($status === 'DIKEMBALIKAN' || $status === 'REVISI') {
                $query->where(function ($q) {
                    $q->whereIn(DB::raw('UPPER(tp.status_nilai)'), ['DIKEMBALIKAN', 'REVISI', 'PERLU DITINJAU'])
                      ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(tp.status_verifikator, ''), tp.status_verifitor, ''))"), ['DIKEMBALIKAN', 'REVISI', 'KOREKSI', 'PERLU DITINJAU']);
                });
            } elseif ($status === 'DRAFT') {
                $query->whereRaw('UPPER(tp.status_nilai) = ?', ['DRAFT']);
            } elseif ($status === 'BELUM DIISI') {
                $query->where(function ($q) {
                    $q->whereNull('tp.status_nilai')
                      ->orWhereRaw('UPPER(tp.status_nilai) IN (?, ?)', ['BELUM DIISI', '']);
                });
            }
        }

        // Filter Departemen
        if ($request->filled('dept_id')) {
            $deptId = (string) $request->dept_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('d.dept_id', $deptId)
                  ->orWhere('e.dept_id', $deptId)
                  ->orWhere('tp.pgw_id_dept', $deptId);
            });
        }

        // Filter Sub Departemen
        if ($request->filled('subdept_id')) {
            $subDeptId = (string) $request->subdept_id;
            $query->where(function ($q) use ($subDeptId) {
                $q->where('sd.subdept_id', $subDeptId)
                  ->orWhere('e.subdept_id', $subDeptId)
                  ->orWhere('tp.pgw_id_subdept', $subDeptId);
            });
        }

        // Filter Unit Kerja
        if ($request->filled('kode_unit_kerja')) {
            $kodeUnit = (string) $request->kode_unit_kerja;
            $query->where(function ($q) use ($kodeUnit) {
                $q->where('uk.kode_unit_kerja', $kodeUnit)
                  ->orWhere('e.kode_unit_kerja', $kodeUnit)
                  ->orWhere('tp.pgw_kode_unit_kerja', $kodeUnit);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'nama_asc');
        switch ($sort) {
            case 'nama_desc':
                $query->orderBy(DB::raw('COALESCE(e.nama, tp.pgw_nama)'), 'desc');
                break;
            case 'nilai_desc':
                $query->orderByRaw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) DESC');
                break;
            case 'nilai_asc':
                $query->orderByRaw('COALESCE(tp.total_nilai_verifikator, tp.total_nilai, 0) ASC');
                break;
            case 'status':
                $query->orderBy('tp.status_nilai', 'asc');
                break;
            case 'nama_asc':
            default:
                $query->orderBy(DB::raw('COALESCE(e.nama, tp.pgw_nama)'), 'asc');
                break;
        }

        $penilaianList = $query->paginate(25)->appends($request->query());

        $penilaianList->getCollection()->transform(function ($row) {
            $data = is_object($row) ? (array) $row : $row;
            $data['status_penilaian'] = strtoupper((string) ($data['status_penilaian'] ?? $data['status_nilai'] ?? 'BELUM DIISI'));
            $data['status_verifikator'] = strtoupper((string) ($data['status_verifikator'] ?? ''));
            $data['nilai_akhir'] = is_numeric($data['nilai_akhir'] ?? null) ? (float) $data['nilai_akhir'] : 0.0;
            $data['predikat'] = ! empty($data['predikat']) ? $data['predikat'] : 'Mengecewakan';

            return (object) $data;
        });

        $departments = DB::table('department')->select('dept_id', 'dept_name')->orderBy('dept_name')->get();
        $subDepartments = DB::table('department_sub')->select('subdept_id', 'subdept_name')->orderBy('subdept_name')->get();
        $occupations = DB::table('occupation')->select('occ_id', 'occ_name')->orderBy('occ_name')->get();
        $offices = DB::table('office')->select('off_id', 'off_name')->orderBy('off_name')->get();
        $unitKerjaList = DB::table('unit_kerja')->select('kode_unit_kerja', 'nama_unit_kerja')->orderBy('nama_unit_kerja')->get();
        $satkers = DB::table('dp3_trans_penilaian')
            ->whereNotNull('pgw_kode_satker')
            ->where('pgw_kode_satker', '!=', '')
            ->select('pgw_kode_satker')
            ->distinct()
            ->orderBy('pgw_kode_satker')
            ->pluck('pgw_kode_satker');
        $employees = DB::table('employee as e')
            ->leftJoin('occupation as occ', 'e.occ_id', '=', 'occ.occ_id')
            ->leftJoin('unit_kerja as uk', 'e.kode_unit_kerja', '=', 'uk.kode_unit_kerja')
            ->select('e.pgw_id', 'e.nup', 'e.nama', 'e.occ_id', 'e.kode_unit_kerja', 'occ.occ_name', 'uk.nama_unit_kerja')
            ->orderBy('e.nama')
            ->get();

        $stats = [
            'total_pegawai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)->count(),
            'belum_diisi'    => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('status_nilai')->orWhereRaw('UPPER(status_nilai) IN (?, ?)', ['BELUM DIISI', '']);
                })->count(),
            'draft'          => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->whereRaw('UPPER(status_nilai) = ?', ['DRAFT'])->count(),
            'submit'         => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->whereRaw('UPPER(status_nilai) IN (?, ?, ?)', ['SUBMIT', 'SUBMITTED', 'DIAJUKAN'])->count(),
            'verifikasi'     => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->where(function ($q) {
                    $q->whereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'])
                      ->orWhereIn(DB::raw('UPPER(status_nilai)'), ['VERIFIED', 'VERIFIKASI']);
                })->count(),
            'tanpa_penilai'  => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('penilai_id')->orWhere('penilai_id', 0);
                })->count(),
            'penilai_manual' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->where('penilai_manual', true)->count(),
            'perlu_ditinjau' => DB::table('dp3_trans_penilaian')->where('periode_id', $id)->where('is_active', true)
                ->where(function ($query) {
                    $query->whereIn(DB::raw('UPPER(status_nilai)'), ['DIKEMBALIKAN', 'REVISI', 'PERLU DITINJAU'])
                          ->orWhereIn(DB::raw("UPPER(COALESCE(NULLIF(status_verifikator, ''), status_verifitor, ''))"), ['DIKEMBALIKAN', 'REVISI', 'KOREKSI', 'PERLU DITINJAU']);
                })->count(),
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
            'employees',
            'satkers'
        ));
    }
}
