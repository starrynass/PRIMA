<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\RoleMenuPermission;
use App\Models\User;
use App\Support\EmployeeAccountSync;
use App\Support\MenuCatalog;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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

    public function index(Request $request)
    {
        $users = User::with('employee')->orderBy('name')->get();
        foreach ($users as $user) {
            if ($user->employee) {
                $syncedUser = EmployeeAccountSync::sync($user->employee);
                if ($syncedUser) {
                    $user->email = $syncedUser->email;
                    $user->name = $syncedUser->name;
                }
            }
        }

        $existingEmails = User::query()
            ->pluck('email')
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->toArray();

        $employees = Employee::with('user')->orderBy('nama')->get()->filter(function ($employee) use ($existingEmails) {
            $email = EmployeeAccountSync::emailFor($employee);

            return ! $employee->user && ! in_array($email, $existingEmails, true);
        });

        $selectedUserId = $request->query('user_id');
        $selectedUser = $selectedUserId ? $users->firstWhere('id', (int) $selectedUserId) : $users->first();
        $selectedUserPassword = null;
        if ($selectedUser?->password_reveal) {
            try {
                $selectedUserPassword = Crypt::decryptString($selectedUser->password_reveal);
            } catch (DecryptException $exception) {
                Log::warning('Unable to decrypt stored account password reveal.', [
                    'user_id' => $selectedUser->id,
                ]);
            }
        }

        return view('settings.hak-akses', [
            'users' => $users,
            'employees' => $employees,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUser?->id,
            'selectedUserPassword' => $selectedUserPassword,
            'moduleDefinitions' => $this->moduleDefinitions,
            'roleOptions' => User::roleCatalog(),
        ]);
    }

    public function otorisasiMenu(Request $request)
    {
        $roleOptions = User::roleCatalog();
        unset($roleOptions['superadmin']);

        $defaultRole = array_key_exists('direktur', $roleOptions)
            ? 'direktur'
            : array_key_first($roleOptions);
        $selectedRole = (new User())->normalizeRole($request->query('role', $defaultRole));

        if (!array_key_exists($selectedRole, $roleOptions)) {
            return redirect()
                ->route('setting.otorisasi-menu', ['role' => $defaultRole])
                ->with('info', 'Role Super Admin tidak memerlukan pengaturan otorisasi menu karena selalu memiliki akses penuh.');
        }

        return view('settings.otorisasi-menu', [
            'roleOptions' => $roleOptions,
            'selectedRole' => $selectedRole,
            'rolePermissions' => RoleMenuPermission::where('role', $selectedRole)->first()?->permissions ?? [],
            'menuTree' => MenuCatalog::all(),
        ]);
    }

    public function saveOtorisasiMenu(Request $request)
    {
        $request->validate([
            'role' => ['required', 'exists:roles,slug'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = (new User())->normalizeRole($request->input('role'));
        if ($role === 'superadmin') {
            return back()->withErrors([
                'role' => 'Role Super Admin selalu memiliki akses penuh dan tidak dapat diatur melalui otorisasi menu.',
            ]);
        }

        $permissions = $request->input('permissions', []);
        $normalized = [];

        $menuKeys = array_keys(MenuCatalog::all());
        foreach (MenuCatalog::all() as $menu) {
            foreach ($menu['children'] as $child) {
                $menuKeys[] = $child['key'];
            }
        }

        foreach ($menuKeys as $key) {
            if (isset($permissions[$key]) && is_array($permissions[$key]) && !empty($permissions[$key]['view'])) {
                $normalized[$key] = ['view' => true];
            }
        }

        RoleMenuPermission::updateOrCreate(
            ['role' => $role],
            ['permissions' => $normalized]
        );

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->normalizeRole($currentUser->role) === $role) {
            $currentUser->forgetRoleMenuPermissions();
        }

        return redirect()->route('setting.otorisasi-menu', ['role' => $role])->with('success', 'Otorisasi menu berhasil diperbarui untuk role ' . (User::roleCatalog()[$role] ?? ucfirst($role)) . '.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employee,pgw_id'],
            'role' => ['required', 'exists:roles,slug'],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $role = (new User())->normalizeRole($request->input('role', 'staff'));
        $existingAccount = EmployeeAccountSync::sync($employee);
        if ($existingAccount) {
            return redirect()->route('setting.hak-akses')
                ->withErrors(['employee_id' => 'Pegawai tersebut sudah terhubung dengan akun.']);
        }

        EmployeeAccountSync::createForEmployee(
            $employee,
            $request->input('password', '1234'),
            $role
        );

        return redirect()->route('setting.hak-akses')->with('success', 'Akun baru berhasil dibuat untuk ' . $employee->nama . '.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'exists:roles,slug'],
        ]);

        $permissions = $request->input('permissions', []);
        $normalized = [];

        foreach ($this->moduleDefinitions as $module => $actions) {
            foreach ($actions as $action => $label) {
                $normalized[$module][$action] = isset($permissions[$user->id][$module][$action]);
            }
        }

        $normalizedRole = (new User())->normalizeRole($request->input('role', $user->role ?? 'staff'));
        $user->role = $normalizedRole;

        $user->permissions = $normalized;
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

    public function roles()
    {
        return view('settings.roles', [
            'roles' => Role::withCount('users')->orderBy('name')->get(),
        ]);
    }

    public function storeRole(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['name']);
        if ($slug === '' || Role::where('slug', $slug)->exists()) {
            return back()->withErrors(['name' => 'Nama role ini sudah digunakan atau tidak dapat dijadikan kode role.'])->withInput();
        }

        Role::create([
            'slug' => $slug,
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('setting.roles')->with('success', 'Role berhasil ditambahkan.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,' . $role->id],
        ]);

        $role->name = trim($validated['name']);
        $role->save();

        return redirect()->route('setting.roles')->with('success', 'Nama role berhasil diperbarui.');
    }

    public function destroyRole(Role $role)
    {
        if ($role->slug === 'superadmin') {
            return redirect()->route('setting.roles')->withErrors(['role' => 'Role Super Admin tidak dapat dihapus.']);
        }

        if ($role->users()->exists()) {
            return redirect()->route('setting.roles')->withErrors(['role' => 'Pindahkan semua akun dari role ini sebelum menghapusnya.']);
        }

        $role->delete();
        RoleMenuPermission::where('role', $role->slug)->delete();

        return redirect()->route('setting.roles')->with('success', 'Role berhasil dihapus.');
    }
}