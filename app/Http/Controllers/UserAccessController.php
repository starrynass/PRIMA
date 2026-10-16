<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAccessController extends Controller
{
    private array $moduleDefinitions = [
        'dashboard' => [
            'view' => 'Dashboard',
        ],
        'master' => [
            'view' => 'Lihat Master',
            'create' => 'Tambah Data',
            'update' => 'Edit Data',
            'delete' => 'Hapus Data',
        ],
        'penilaian' => [
            'view' => 'Lihat Penilaian',
            'create' => 'Buat Penilaian',
            'update' => 'Edit Penilaian',
            'delete' => 'Hapus Penilaian',
            'verify' => 'Verifikasi',
        ],
        'laporan' => [
            'view' => 'Lihat Laporan',
        ],
        'settings' => [
            'view' => 'Lihat Setting',
            'manage' => 'Kelola Hak Akses',
        ],
    ];

    private array $roleOptions = [
        'superadmin' => 'Super Admin',
        'direktur' => 'Direktur',
        'manager' => 'Manager',
        'staff' => 'Staff',
    ];

    private function menuTree(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'url' => 'Dashboard',
                'children' => [],
            ],
            'master' => [
                'label' => 'Master',
                'url' => 'Master',
                'children' => [
                    ['key' => 'master.skala-predikat', 'label' => 'Skala & Predikat Nilai', 'url' => 'Master/SkalaPredikatNilai'],
                    ['key' => 'master.template-penilaian', 'label' => 'Template, Fase dan Pertanyaan', 'url' => 'Master/TemplateFaseDanPertanyaan'],
                ],
            ],
            'penilaian' => [
                'label' => 'Penilaian',
                'url' => 'Penilaian',
                'children' => [
                    ['key' => 'penilaian.periode', 'label' => 'Periode Penilaian', 'url' => 'Penilaian/PeriodePenilaian'],
                    ['key' => 'penilaian.kelola', 'label' => 'Kelola Penilaian', 'url' => 'Penilaian/KelolaPenilaian'],
                    ['key' => 'penilaian.verifikasi', 'label' => 'Verifikasi Penilaian', 'url' => 'Penilaian/VerifikasiPenilaian'],
                    ['key' => 'penilaian.catatan', 'label' => 'Catatan Penilaian', 'url' => 'Penilaian/CatatanPenilaian'],
                ],
            ],
            'laporan' => [
                'label' => 'Laporan',
                'url' => 'Laporan',
                'children' => [
                    ['key' => 'laporan.tahunan', 'label' => 'Laporan Tahunan', 'url' => 'Laporan/Tahunan'],
                    ['key' => 'laporan.rekap-nilai', 'label' => 'Laporan Rekap Nilai', 'url' => 'Laporan/RekapNilai'],
                    ['key' => 'laporan.indeks-unit-kerja', 'label' => 'Laporan Index Unit Kerja', 'url' => 'Laporan/IndeksUnitKerja'],
                    ['key' => 'laporan.indeks-kompetensi', 'label' => 'Laporan Index Kompetensi', 'url' => 'Laporan/IndexKompetensi'],
                ],
            ],
            'settings' => [
                'label' => 'Setting',
                'url' => 'Setting',
                'children' => [
                    ['key' => 'settings.hak-akses', 'label' => 'Hak Akses', 'url' => 'Setting/HakAkses'],
                    ['key' => 'settings.otorisasi-menu', 'label' => 'Otorisasi Menu', 'url' => 'Setting/OtorisasiMenu'],
                ],
            ],
        ];
    }

    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $existingEmails = User::query()
            ->pluck('email')
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->toArray();

        $employees = Employee::orderBy('nama')->get()->filter(function ($employee) use ($existingEmails) {
            $email = strtolower(trim((string) $employee->nup)) . '@dp3.local';

            return !in_array($email, $existingEmails, true);
        });

        $selectedUserId = $request->query('user_id');
        $selectedUser = $selectedUserId ? $users->firstWhere('id', (int) $selectedUserId) : $users->first();

        return view('settings.hak-akses', [
            'users' => $users,
            'employees' => $employees,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUser?->id,
            'moduleDefinitions' => $this->moduleDefinitions,
            'roleOptions' => $this->roleOptions,
        ]);
    }

    public function otorisasiMenu(Request $request)
    {
        $selectedRole = (new User())->normalizeRole($request->query('role', 'superadmin'));

        return view('settings.otorisasi-menu', [
            'roleOptions' => $this->roleOptions,
            'selectedRole' => $selectedRole,
            'rolePermissions' => User::where('role', $selectedRole)->first()?->permissions ?? (new User())->defaultRolePermissions($selectedRole),
            'roleUsers' => User::where('role', $selectedRole)->orderBy('name')->get(),
            'menuTree' => $this->menuTree(),
            'moduleDefinitions' => $this->moduleDefinitions,
        ]);
    }

    public function saveOtorisasiMenu(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:superadmin,direktur,manager,staff'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = (new User())->normalizeRole($request->input('role'));
        $permissions = $request->input('permissions', []);
        $normalized = [];

        foreach ($permissions as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $module = $key;
            if (str_contains($key, '.')) {
                $module = explode('.', $key, 2)[0];
            }

            if (!empty($value['view'])) {
                $normalized[$module]['view'] = true;
            }
        }

        foreach ($this->menuTree() as $menuKey => $menu) {
            if (!isset($normalized[$menuKey]['view']) && !empty($permissions[$menuKey]['view'])) {
                $normalized[$menuKey]['view'] = true;
            }

            foreach ($menu['children'] as $child) {
                $childKey = $child['key'];
                if (!isset($normalized[$childKey]['view']) && !empty($permissions[$childKey]['view'])) {
                    $normalized[$childKey]['view'] = true;
                }

                $parentKey = explode('.', $childKey, 2)[0];
                if (!empty($permissions[$childKey]['view']) || !empty($normalized[$childKey]['view'])) {
                    $normalized[$parentKey]['view'] = true;
                }
            }
        }

        foreach ($this->moduleDefinitions as $module => $actions) {
            $moduleEnabled = !empty($normalized[$module]['view']);

            foreach ($actions as $action => $label) {
                $normalized[$module][$action] = $moduleEnabled;
            }
        }

        User::where('role', $role)->each(function ($user) use ($normalized) {
            $user->permissions = $normalized;
            $user->save();
        });

        return redirect()->route('setting.otorisasi-menu', ['role' => $role])->with('success', 'Otorisasi menu berhasil diperbarui untuk role ' . ($this->roleOptions[$role] ?? ucfirst($role)) . '.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employee,pgw_id'],
            'role' => ['required', 'in:superadmin,direktur,manager,staff'],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $username = trim((string) $employee->nup);
        $email = $username . '@dp3.local';

        $role = (new User())->normalizeRole($request->input('role', 'staff'));

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $employee->nama,
                'password' => Hash::make($request->input('password', '1234')),
                'role' => $role,
            ]
        );

        $user->name = $employee->nama;
        $user->role = $role;
        $user->permissions = $user->defaultRolePermissions($role);
        $user->password = Hash::make($request->input('password', '1234'));
        $user->save();

        return redirect()->route('setting.hak-akses')->with('success', 'Akun baru berhasil dibuat untuk ' . $employee->nama . '.');
    }

    public function update(Request $request, User $user)
    {
        $permissions = $request->input('permissions', []);
        $normalized = [];

        foreach ($this->moduleDefinitions as $module => $actions) {
            foreach ($actions as $action => $label) {
                $normalized[$module][$action] = isset($permissions[$user->id][$module][$action]);
            }
        }

        $normalizedRole = (new User())->normalizeRole($request->input('role', $user->role ?? 'staff'));
        $user->role = $normalizedRole;

        // Ambil default permissions berdasarkan role baru, lalu timpa dengan nilai dari form ($normalized)
        $userPermissions = $user->defaultRolePermissions($normalizedRole);

        foreach ($normalized as $module => $actions) {
            foreach ($actions as $action => $value) {
                $userPermissions[$module][$action] = (bool) $value;
            }
        }

        // Simpan array lokal ke properti model
        $user->permissions = $userPermissions;
        $user->save();

        return redirect()->route('setting.hak-akses')->with('success', 'Hak akses akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('setting.hak-akses')->withErrors(['user' => 'Super Admin tidak dapat dihapus dari sistem.']);
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('setting.hak-akses')->withErrors(['user' => 'Anda tidak dapat menghapus akun yang sedang aktif dipakai.']);
        }

        $user->delete();

        return redirect()->route('setting.hak-akses')->with('success', 'Akun berhasil dihapus.');
    }
}