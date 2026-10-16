@extends('layout.app')

@section('content')
<div class="roles-page">
    <div class="page-heading">
        <h2>Kelola Role</h2>
        <p>Tambahkan role baru atau ubah nama role. Role yang masih digunakan akun tidak dapat dihapus.</p>
    </div>

    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="notice error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="panel">
        <h3>Tambah Role</h3>
        <form method="POST" action="{{ route('setting.roles.store') }}" class="add-role-form">
            @csrf
            <label for="roleName">Nama role</label>
            <input id="roleName" name="name" type="text" maxlength="100" value="{{ old('name') }}" required>
            <button type="submit">Tambah Role</button>
        </form>
    </section>

    <section class="panel">
        <h3>Daftar Role</h3>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Nama Role</th>
                        <th>Kode</th>
                        <th>Jumlah Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <form id="role-form-{{ $role->id }}" method="POST" action="{{ route('setting.roles.update', $role) }}" class="edit-role-form">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" type="text" maxlength="100" value="{{ $role->name }}" required>
                                </form>
                            </td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ $role->users_count }}</td>
                            <td class="actions">
                                <button type="submit" form="role-form-{{ $role->id }}">Simpan Nama</button>
                                @if($role->slug !== 'superadmin')
                                    <form method="POST" action="{{ route('setting.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="delete-button" type="submit" {{ $role->users_count > 0 ? 'disabled title=Pindahkan akun terlebih dahulu' : '' }}>Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">Belum ada role.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
    .roles-page { max-width: 1100px; margin: 0 auto; padding: 24px 18px 36px; color: #1f2937; }
    .page-heading, .panel { background: #fff; border: 1px solid #eadfe2; border-radius: 16px; box-shadow: 0 8px 22px rgba(122, 28, 56, .07); }
    .page-heading { padding: 22px 24px; margin-bottom: 18px; background: linear-gradient(135deg, #fff 0%, #f9eef2 100%); }
    .page-heading h2 { margin: 0; color: #7a1c38; font-size: 1.6rem; }
    .page-heading p { margin: 7px 0 0; color: #64748b; }
    .panel { padding: 20px; margin-bottom: 18px; }
    .panel h3 { margin: 0 0 15px; font-size: 1rem; }
    .add-role-form { display: grid; grid-template-columns: auto minmax(180px, 1fr) auto; align-items: center; gap: 12px; }
    input { min-width: 0; border: 1px solid #d1d5db; border-radius: 9px; padding: 10px 12px; font: inherit; }
    button { border: 0; border-radius: 9px; background: #7a1c38; color: #fff; padding: 10px 14px; font-weight: 700; cursor: pointer; }
    button:disabled { background: #9ca3af; cursor: not-allowed; }
    .table-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #edf0f4; }
    th { color: #475569; background: #f8fafc; font-size: .8rem; text-transform: uppercase; }
    td input { width: 100%; }
    .actions { display: flex; align-items: center; gap: 8px; }
    .actions form { margin: 0; }
    .delete-button { background: #b91c1c; }
    .notice { border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; font-weight: 600; }
    .success { background: #ecfdf5; color: #065f46; }
    .error { background: #fef2f2; color: #991b1b; }
    .empty-state { text-align: center; color: #64748b; }
    @media (max-width: 600px) { .add-role-form { grid-template-columns: 1fr; } }
</style>
@endsection
