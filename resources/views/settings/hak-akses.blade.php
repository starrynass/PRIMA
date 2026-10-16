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

    <div id="deleteUserModal" style="position: fixed; inset: 0; background: rgba(17, 24, 39, 0.5); display: none; align-items: center; justify-content: center; z-index: 1000;">
        <div style="width: min(92vw, 420px); background: #fff; border-radius: 18px; box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2); padding: 0; overflow: hidden; border: 1px solid #f0d9df;">
            <div style="padding: 18px 22px 12px; background: linear-gradient(135deg, #fff 0%, #fff3f6 100%); border-bottom: 1px solid #f2d9e2;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(122, 28, 56, 0.1); display: flex; align-items: center; justify-content: center; color: #7A1C38; font-size: 1.2rem;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div style="font-size: 1rem; font-weight: 800; color: #7A1C38;">Hapus Akun</div>
                        <div style="font-size: 0.82rem; color: #6b7280;">Tindakan ini tidak dapat dibatalkan.</div>
                    </div>
                </div>
            </div>
            <div style="padding: 20px 22px; color: #374151; font-size: 0.96rem; line-height: 1.6;">
                Yakin ingin menghapus akun <strong id="deleteUserName">pegawai</strong>?
            </div>
            <div style="padding: 0 22px 22px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" id="cancelDeleteBtn" style="border: 1px solid #d1d5db; background: #fff; color: #374151; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="button" id="confirmDeleteBtn" style="border: none; background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); color: #fff; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer;">Hapus</button>
            </div>
        </div>
    </div>

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
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
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
                    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                        <thead>
                            <tr style="background: #f9fafb; color: #374151; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb;">Akun</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb;">Role</th>
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
                                    <select name="role" style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; background: #fff; min-width: 180px;">
                                        @foreach($roleOptions as $value => $label)
                                            <option value="{{ $value }}" {{ ($selectedUser->role ?? 'staff') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 14px 12px; text-align: center;">
                                    <button type="submit" style="background: #7A1C38; color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer;">Simpan</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>

            @if(!$selectedUser->isSuperAdmin() && $selectedUser->id !== auth()->id())
                <div style="margin-top: 16px; text-align: right;">
                    <form id="delete-user-form-{{ $selectedUser->id }}" method="POST" action="{{ route('setting.hak-akses.destroy', $selectedUser->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button"
                        class="delete-user-trigger"
                        data-form-id="delete-user-form-{{ $selectedUser->id }}"
                        data-user-name="{{ $selectedUser->name }}"
                        style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); color: #fff; border: none; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px rgba(185, 28, 28, 0.18);">
                        Hapus Akun
                    </button>
                </div>
            @endif
        @else
            <div style="padding: 20px; background: #f9fafb; border: 1px dashed #d1d5db; border-radius: 12px; color: #6b7280; text-align: center;">
                Pilih akun terlebih dahulu untuk mengatur hak akses.
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('deleteUserModal');
        const userNameText = document.getElementById('deleteUserName');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        let targetFormId = null;

        document.querySelectorAll('.delete-user-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                targetFormId = button.dataset.formId;
                userNameText.textContent = button.dataset.userName || 'pegawai';
                modal.style.display = 'flex';
            });
        });

        cancelDeleteBtn.addEventListener('click', function () {
            modal.style.display = 'none';
            targetFormId = null;
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                targetFormId = null;
            }
        });

        confirmDeleteBtn.addEventListener('click', function () {
            if (targetFormId) {
                const form = document.getElementById(targetFormId);
                if (form) {
                    form.submit();
                }
            }
        });
    });
</script>
@endsection
