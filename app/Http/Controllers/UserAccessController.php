<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $employees = Employee::orderBy('nama')->get();
        $selectedUserId = $request->query('user_id');
        $selectedUser = $selectedUserId ? $users->firstWhere('id', (int) $selectedUserId) : $users->first();

        return view('settings.hak-akses', [
            'users' => $users,
            'employees' => $employees,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUser?->id,
            'moduleDefinitions' => $this->moduleDefinitions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employee,pgw_id'],
            'role' => ['required', 'in:superadmin,employee'],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $username = trim((string) $employee->nup);
        $email = $username . '@dp3.local';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $employee->nama,
                'password' => Hash::make($request->input('password', '1234')),
                'role' => $request->input('role', 'employee'),
            ]
        );

        $user->name = $employee->nama;
        $user->role = $request->role;
        $user->permissions = $user->role === 'superadmin' ? $user->defaultPermissions() : $user->defaultEmployeePermissions();
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
                $normalized[$module][$action] = isset($permissions[$user->id][$module][$action]) ? true : false;
            }
        }

        $user->permissions = $normalized;
        $user->role = $request->input('role', $user->role ?? 'employee');
        $user->save();

        return redirect()->route('setting.hak-akses')->with('success', 'Hak akses akun berhasil diperbarui.');
    }
}

