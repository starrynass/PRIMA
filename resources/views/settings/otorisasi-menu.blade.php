@extends('layout.app')

@section('content')
<div class="page-shell">
    <div class="page-header-box">
        <div>
            <h2>Otorisasi Menu</h2>
            <p>Pengaturan akses menu dan sub menu berdasarkan role.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert-success">
            {{ session('info') }}
        </div>
    @endif

    <div class="panel-box">
        <div class="toolbar-row">
            <div class="field-group">
                <label for="roleSelect">Pilih Role</label>
                <select id="roleSelect" onchange="if(this.value){ window.location.href = '{{ route('setting.otorisasi-menu') }}?role=' + this.value; }">
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}" {{ $selectedRole === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <form method="POST" action="{{ route('setting.otorisasi-menu.store') }}" class="menu-permission-form">
            @csrf
            <input type="hidden" name="role" value="{{ $selectedRole }}">

            <div class="table-wrap">
                <table class="menu-access-table">
                    <thead>
                        <tr>
                            <th style="width: 52%;">Menu</th>
                            <th style="width: 28%;">URL</th>
                            <th style="width: 20%; text-align: center;">Dapat Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menuTree as $menuKey => $menu)
                            <tr class="menu-row">
                                <td class="menu-cell">
                                    <div class="menu-label">
                                        <span class="menu-icon"><i class="fa-solid {{ $menu['icon'] }}"></i></span>
                                        <span>{{ $menu['label'] }}</span>
                                    </div>
                                </td>
                                <td class="url-cell">{{ $menu['url'] }}</td>
                                <td class="check-cell">
                                    <label class="switch">
                                        <input type="checkbox" name="permissions[{{ $menuKey }}][view]" value="1" data-parent-toggle="{{ $menuKey }}" {{ !empty($rolePermissions[$menuKey]['view']) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>

                            @foreach($menu['children'] as $child)
                                <tr class="submenu-row" data-parent-row="{{ $menuKey }}" {{ empty($rolePermissions[$menuKey]['view']) ? 'hidden' : '' }}>
                                    <td class="submenu-cell">
                                        <span class="submenu-bullet">›</span>
                                        {{ $child['label'] }}
                                    </td>
                                    <td class="url-cell">{{ $child['url'] }}</td>
                                    <td class="check-cell">
                                        <label class="switch">
                                            <input type="checkbox" name="permissions[{{ $child['key'] }}][view]" value="1" data-parent-child="{{ $menuKey }}" {{ !empty($rolePermissions[$child['key']]['view']) ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="footer-action">
                <button type="submit" class="save-btn">Simpan Otorisasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('[data-parent-toggle]').forEach(function (parentToggle) {
        const parentKey = parentToggle.dataset.parentToggle;
        const childRows = document.querySelectorAll('[data-parent-row="' + parentKey + '"]');

        parentToggle.addEventListener('change', function () {
            childRows.forEach(function (row) {
                row.hidden = !parentToggle.checked;
            });
        });
    });
</script>

<style>
    .page-shell {
        max-width: 1180px;
        margin: 0 auto;
        padding: 28px 18px 36px;
    }

    .page-header-box {
        background: linear-gradient(135deg, #ffffff 0%, #f9eef2 100%);
        border: 1px solid #efd3dc;
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 20px;
        box-shadow: 0 12px 26px rgba(122, 28, 56, 0.08);
    }

    .page-header-box h2 {
        margin: 0;
        color: #7A1C38;
        font-size: 1.7rem;
        font-weight: 800;
    }

    .page-header-box p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        border-radius: 12px;
        padding: 12px 14px;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .panel-box {
        background: #fff;
        border: 1px solid #ecdfdf;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(122, 28, 56, 0.08);
        padding: 20px;
    }

    .toolbar-row {
        margin-bottom: 20px;
    }

    .field-group {
        max-width: 340px;
    }

    .field-group label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .field-group select {
        width: 100%;
        border: 1px solid #d9dfe8;
        border-radius: 12px;
        padding: 11px 12px;
        font-size: 0.96rem;
        background: #fff;
        color: #1f2937;
        outline: none;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
    }

    .permission-help {
        margin: 10px 0 0;
        color: #64748b;
        font-size: 0.84rem;
        line-height: 1.5;
    }

    .table-wrap {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e9edf3;
    }

    .menu-access-table {
        width: 100%;
        border-collapse: collapse;
        background: #f8fafc;
    }

    .menu-access-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .menu-access-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #edf2f7;
        color: #1f2937;
        vertical-align: middle;
        background: rgba(255,255,255,0.55);
    }

    .menu-row td {
        background: #f4f6f8;
        font-weight: 700;
        color: #111827;
    }

    .submenu-row td {
        background: rgba(255,255,255,0.7);
    }

    .menu-cell, .submenu-cell {
        padding-left: 18px;
    }

    .menu-label {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu-icon {
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #7A1C38;
        font-size: 0.9rem;
    }

    .submenu-bullet {
        color: #7A1C38;
        font-size: 1.2rem;
        margin-right: 8px;
    }

    .url-cell {
        color: #475569;
        font-size: 0.92rem;
    }

    .check-cell {
        text-align: center;
    }

    .switch {
        position: relative;
        display: inline-flex;
        width: 52px;
        height: 28px;
        align-items: center;
        justify-content: center;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .slider {
        position: absolute;
        inset: 0;
        background-color: #d1d5db;
        border-radius: 999px;
        transition: 0.2s ease;
        box-shadow: inset 0 2px 6px rgba(15, 23, 42, 0.1);
    }

    .slider::before {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        left: 4px;
        top: 4px;
        border-radius: 50%;
        background: white;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
        transition: 0.2s ease;
    }

    .switch input:checked + .slider {
        background: linear-gradient(135deg, #7A1C38 0%, #a63d59 100%);
    }

    .switch input:checked + .slider::before {
        transform: translateX(24px);
    }

    .footer-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .save-btn {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #7A1C38 0%, #9c2b4d 100%);
        color: #fff;
        font-weight: 700;
        font-size: 0.96rem;
        padding: 12px 22px;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(122, 28, 56, 0.18);
    }

    @media (max-width: 768px) {
        .page-shell {
            padding-left: 12px;
            padding-right: 12px;
        }

        .menu-access-table th,
        .menu-access-table td { 
            padding: 10px 10px;
        }

        .url-cell {
            display: none;
        }

        .menu-access-table th:nth-child(2),
        .menu-access-table td:nth-child(2) {
            display: none;
        }
    }
</style>
@endsection
