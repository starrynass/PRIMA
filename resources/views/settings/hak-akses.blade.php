@extends('layout.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; box-shadow: 0 8px 22px rgba(122, 28, 56, 0.08); padding: 24px; margin-bottom: 22px;">
        <h2 style="margin: 0 0 6px; color: #7A1C38; font-size: 1.5rem; font-weight: 800;">Hak Akses & Aksi</h2>
        <p style="margin: 0; color: #667085; font-size: 0.9rem;">Hanya Super Admin yang dapat mengubah pengaturan ini.</p>
    </div>

    @if(session('success'))
        <div style="margin-bottom: 18px; padding: 12px 14px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; color: #065f46; font-size: 0.9rem; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; box-shadow: 0 8px 22px rgba(122, 28, 56, 0.08); padding: 24px; margin-bottom: 22px;">
        <h3 style="margin: 0 0 14px; color: #374151; font-size: 1rem; font-weight: 700;">Tambah Akun Baru</h3>
        <form method="POST" action="{{ route('setting.hak-akses.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; font-weight: 700; color: #374151;">Pilih Pegawai</label>
                <select name="employee_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff;">
                    <option value="">-- Pilih pegawai --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->pgw_id }}">{{ $employee->nama }} (NUP: {{ $employee->nup }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; font-weight: 700; color: #374151;">Role</label>
                <select name="role" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff;">
                    <option value="employee">Employee</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; font-weight: 700; color: #374151;">Password Default</label>
                <input type="text" name="password" value="1234" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff;">
            </div>
            <div>
                <button type="submit" style="width: 100%; background: #7A1C38; color: #fff; border: none; border-radius: 10px; padding: 11px 16px; font-weight: 700; cursor: pointer;">Buat Akun</button>
            </div>
        </form>
    </div>

    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; box-shadow: 0 8px 22px rgba(122, 28, 56, 0.08); padding: 24px;">
        <div style="margin-bottom: 18px;">
            <label style="display: block; margin-bottom: 8px; font-size: 0.8rem; font-weight: 700; color: #374151;">Pilih Akun yang Ingin Diatur</label>
            <select
                onchange="if(this.value){ window.location.href = '{{ route('setting.hak-akses') }}?user_id=' + this.value; }"
                style="width: min(100%, 400px); padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff;"
            >
                <option value="">-- Pilih akun --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        @if($selectedUser)
            <form method="POST" action="{{ route('setting.hak-akses.update', $selectedUser->id) }}">
                @csrf
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 980px;">
                        <thead>
                            <tr style="background: #f9fafb; color: #374151; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb;">Akun</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb;">Role</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb;">Hak Akses</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f2f5; vertical-align: top;">
                                <td style="padding: 14px 12px;">
                                    <div style="font-weight: 700; color: #1f2937;">{{ $selectedUser->name }}</div>
                                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 4px;">{{ $selectedUser->email }}</div>
                                </td>
                                <td style="padding: 14px 12px;">
                                    <select name="role" style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff; min-width: 150px;">
                                        <option value="employee" {{ ($selectedUser->role ?? 'employee') === 'employee' ? 'selected' : '' }}>Employee</option>
                                        <option value="superadmin" {{ ($selectedUser->role ?? 'employee') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                    </select>
                                </td>
                                <td style="padding: 14px 12px;">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                                        @foreach($moduleDefinitions as $module => $actions)
                                            <div style="padding: 10px 12px; border: 1px solid #f1f5f9; background: #fafafa; border-radius: 10px;">
                                                <div style="font-size: 0.74rem; color: #7A1C38; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.04em;">
                                                    {{ ucfirst($module) }}
                                                </div>
                                                @foreach($actions as $action => $label)
                                                    @php
                                                        $checked = !empty($selectedUser->permissions[$module][$action]) || ($selectedUser->isSuperAdmin() && $module === 'dashboard' && $action === 'view');
                                                    @endphp
                                                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 0.82rem; color: #374151;">
                                                        <input type="checkbox" name="permissions[{{ $selectedUser->id }}][{{ $module }}][{{ $action }}]" value="1" {{ $checked ? 'checked' : '' }}>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding: 14px 12px; text-align: center;">
                                    <button type="submit" style="background: #7A1C38; color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer;">Simpan</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        @else
            <div style="padding: 20px; background: #f9fafb; border: 1px dashed #d1d5db; border-radius: 12px; color: #6b7280; text-align: center;">
                Pilih akun terlebih dahulu untuk mengatur hak akses.
            </div>
        @endif
    </div>
</div>
@endsection
