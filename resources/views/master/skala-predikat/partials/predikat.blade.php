<style>
    /* --- Global Styles --- */
    * {
        box-sizing: border-box;
    }

    .page-container {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        -webkit-font-smoothing: antialiased;
        padding: 1.5rem;
        background-color: #f8fafc;
        min-height: 100vh;
        color: #334155;
    }

    .page-container > * + * {
        margin-top: 1.5rem;
    }

    /* --- Alerts --- */
    .alert-success {
        padding: 1rem;
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .alert-error {
        padding: 1rem;
        background-color: #fff1f2;
        border: 1px solid #fecdd3;
        color: #9f1239;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .alert-error > * + * {
        margin-top: 0.5rem;
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .alert-error-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 0.875rem;
        color: #be123c;
    }

    .alert-close-btn {
        background: transparent;
        border: none;
        color: #059669;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
    }

    .alert-close-btn:hover {
        color: #065f46;
    }

    .error-list {
        list-style-type: disc;
        list-style-position: inside;
        padding-left: 0.25rem;
        color: rgba(190, 18, 60, 0.9);
    }

    .error-list > * + * {
        margin-top: 0.25rem;
    }

    /* --- Header Toolbar --- */
    .toolbar-card {
        background-color: #ffffff;
        padding: 1.25rem;
        border-radius: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .toolbar-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .toolbar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .toolbar-icon-wrapper {
        padding: 0.5rem;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 0.5rem;
        border: 1px solid #dbeafe;
        display: inline-flex;
    }

    .toolbar-subtitle {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    .toolbar-subtitle-count {
        font-weight: 600;
        color: #334155;
    }

    .action-controls {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    /* --- Buttons --- */
    .btn {
        padding: 0.5rem 0.875rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border: 1px solid transparent;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, transform 0.1s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn:active {
        transform: scale(0.95);
    }

    .btn:disabled {
        background-color: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .btn-refresh {
        background-color: #ffffff;
        color: #475569;
        border-color: #e2e8f0;
    }
    .btn-refresh:hover { background-color: #f8fafc; }

    .btn-add {
        background-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(37, 99, 235, 0.3);
    }
    .btn-add:hover { background-color: #1d4ed8; }

    .btn-edit {
        background-color: #f59e0b;
        color: #ffffff;
        border-color: #d97706;
    }
    .btn-edit:hover { background-color: #d97706; }

    .btn-delete {
        background-color: #e11d48;
        color: #ffffff;
        border-color: #be123c;
    }
    .btn-delete:hover { background-color: #be123c; }

    .btn-secondary {
        background-color: #ffffff;
        color: #334155;
        border-color: #e2e8f0;
        padding: 0.5rem 1rem;
    }
    .btn-secondary:hover { background-color: #f1f5f9; }

    /* --- Data Table --- */
    .table-card {
        background-color: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        font-size: 0.75rem;
        text-align: left;
        color: #475569;
        border-collapse: collapse;
    }

    .data-table thead {
        background-color: rgba(241, 245, 249, 0.7);
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table th, .data-table td {
        padding: 0.875rem 1.25rem;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
        cursor: pointer;
    }

    .data-table tbody tr:hover {
        background-color: rgba(239, 246, 255, 0.4);
    }

    .data-table tbody tr.selected-row {
        background-color: rgba(239, 246, 255, 0.8);
        font-weight: 600;
    }

    .text-center { text-align: center; }
    .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }

    .id-cell { color: #2563eb; font-weight: 600; }
    .code-cell { font-weight: 600; color: #1e293b; }
    .val-cell { color: #475569; background-color: rgba(248, 250, 252, 0.5); }
    .empty-cell { text-align: center; padding: 3rem 1rem; color: #94a3b8; font-weight: 500; }

    .radio-input {
        width: 1rem;
        height: 1rem;
        color: #2563eb;
        border-color: #cbd5e1;
        cursor: pointer;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        border: 1px solid transparent;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }

    .badge-default { background-color: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .badge-emerald { background-color: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .badge-blue { background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .badge-amber { background-color: #fffbeb; color: #b45309; border-color: #fde68a; }
    .badge-orange { background-color: #fff7ed; color: #c2410c; border-color: #fed7aa; }
    .badge-rose { background-color: #fff1f2; color: #be123c; border-color: #fecdd3; }

    /* --- Modals --- */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 50;
        padding: 1rem;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal-content {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 100%;
        max-width: 36rem;
        overflow: hidden;
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.2s ease;
    }

    .modal-content.active {
        transform: scale(1);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background-color: rgba(248, 250, 252, 0.5);
    }

    .modal-title {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-weight: 700;
        font-size: 1rem;
    }

    .modal-title-add { color: #2563eb; }
    .modal-title-edit { color: #d97706; }

    .modal-icon-add { padding: 0.5rem; background-color: #dbeafe; color: #2563eb; border-radius: 0.5rem; }
    .modal-icon-edit { padding: 0.5rem; background-color: #fef3c7; color: #d97706; border-radius: 0.5rem; }

    .modal-close-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.5rem;
        font-weight: 700;
        cursor: pointer;
    }
    .modal-close-btn:hover { color: #475569; }

    .modal-body {
        padding: 1.5rem;
    }
    .modal-body > * + * {
        margin-top: 1rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .form-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.375rem;
    }

    .form-control {
        width: 100%;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.625rem;
        font-size: 0.75rem;
        color: #1e293b;
        outline: none;
        transition: border-color 0.15s, background-color 0.15s, box-shadow 0.15s;
    }

    .form-control:focus {
        background-color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    .form-control-amber:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
    }

    .form-control[readonly] {
        background-color: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    .modal-info-box {
        padding: 0.75rem;
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1d4ed8;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.625rem;
        padding: 1rem 1.5rem;
        background-color: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    .icon-svg { width: 1.25rem; height: 1.25rem; }
    .icon-svg-sm { width: 1rem; height: 1rem; }
    .hidden { display: none !important; }
</style>

<div class="page-container">

    <!-- Flash Alert Sukses -->
    @if(session('success'))
        <div class="alert-success">
            <div class="alert-content">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="alert-close-btn">&times;</button>
        </div>
    @endif

    <!-- Alert Validasi Error -->
    @if ($errors->any())
        <div class="alert-error">
            <div class="alert-error-header">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Gagal Menyimpan Data</span>
            </div>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header & Action Toolbar Bar -->
    <div class="toolbar-card">
        <div>
            <h1 class="toolbar-title">
                <span class="toolbar-icon-wrapper">
                    <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </span>
                Master Predikat Nilai
            </h1>
            <p class="toolbar-subtitle">
                Total Data: <span class="toolbar-subtitle-count">{{ count($predikatNilai) }} Master Range</span>
            </p>
        </div>

        <!-- Action Control Group -->
        <div class="action-controls">
            <button onclick="window.location.reload()" class="btn btn-refresh">
                <svg class="icon-svg-sm" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui
            </button>

            <button onclick="openModalTambah()" class="btn btn-add">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah
            </button>

            <button id="btnEdit" disabled onclick="triggerEditSelected()" class="btn btn-edit">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Ubah
            </button>

            <button id="btnDelete" disabled onclick="triggerDeleteSelected()" class="btn btn-delete">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 2.5rem;">Pilih</th>
                        <th class="text-center" style="width: 3rem;">#</th>
                        <th>ID Predikat</th>
                        <th>Kode</th>
                        <th class="text-center">Nilai Min</th>
                        <th class="text-center">Nilai Max</th>
                        <th>Predikat</th>
                        <th class="text-center">Preview Badge</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($predikatNilai as $index => $item)
                        <tr onclick="selectRow(this, '{{ $item->predikat_id }}', '{{ $item->kode }}', '{{ $item->nilai_min }}', '{{ $item->nilai_max }}', '{{ $item->predikat }}')">
                            <td class="text-center" onclick="event.stopPropagation()">
                                <input type="radio" name="row_select" value="{{ $item->predikat_id }}" 
                                       onchange="onRadioChange(this, '{{ $item->predikat_id }}', '{{ $item->kode }}', '{{ $item->nilai_min }}', '{{ $item->nilai_max }}', '{{ $item->predikat }}')"
                                       class="radio-input">
                            </td>
                            <td class="text-center" style="color: #94a3b8;">{{ $index + 1 }}</td>
                            <td class="font-mono id-cell">{{ $item->predikat_id }}</td>
                            <td class="code-cell">{{ $item->kode }}</td>
                            <td class="text-center font-mono val-cell">{{ number_format($item->nilai_min, 2) }}</td>
                            <td class="text-center font-mono val-cell">{{ number_format($item->nilai_max, 2) }}</td>
                            <td>{{ $item->predikat }}</td>
                            <td class="text-center">
                                @php
                                    $badgeStyle = 'badge-default';
                                    if ($item->nilai_min >= 90) $badgeStyle = 'badge-emerald';
                                    elseif ($item->nilai_min >= 80) $badgeStyle = 'badge-blue';
                                    elseif ($item->nilai_min >= 70) $badgeStyle = 'badge-amber';
                                    elseif ($item->nilai_min >= 60) $badgeStyle = 'badge-orange';
                                    else $badgeStyle = 'badge-rose';
                                @endphp
                                <span class="badge {{ $badgeStyle }}">
                                    {{ $item->predikat }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-cell">
                                <svg class="icon-svg" style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.5rem; color: #cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                Belum ada data predikat nilai tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="globalDeleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Popup Tambah Data -->
    <div id="modalTambah" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title modal-title-add">
                    <div class="modal-icon-add">
                        <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span>Tambah Predikat Nilai</span>
                </div>
                <button onclick="closeModalTambah()" class="modal-close-btn">&times;</button>
            </div>

            <form action="{{ route('predikat.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">ID Predikat</label>
                            <input type="text" name="predikat_id" placeholder="PRED001" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" placeholder="A / B / C" required class="form-control">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nilai Minimum</label>
                            <input type="number" step="0.01" name="nilai_min" placeholder="85.00" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nilai Maksimum</label>
                            <input type="number" step="0.01" name="nilai_max" placeholder="100.00" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Predikat Deskriptif</label>
                        <input type="text" name="predikat" placeholder="Sangat Baik" required class="form-control">
                    </div>

                    <div class="modal-info-box">
                        <svg class="icon-svg-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span>Pastikan rentang nilai minimum & maksimum tidak saling bertabrakan.</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModalTambah()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-add">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Popup Edit Data -->
    <div id="modalEdit" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title modal-title-edit">
                    <div class="modal-icon-edit">
                        <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span>Ubah Predikat Nilai</span>
                </div>
                <button onclick="closeModalEdit()" class="modal-close-btn">&times;</button>
            </div>

            <form id="formEditPredikat" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">ID Predikat (Read-Only)</label>
                            <input type="text" id="edit_predikat_id" readonly class="form-control font-mono">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode</label>
                            <input type="text" id="edit_kode" name="kode" required class="form-control form-control-amber">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nilai Minimum</label>
                            <input type="number" step="0.01" id="edit_nilai_min" name="nilai_min" required class="form-control form-control-amber">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nilai Maksimum</label>
                            <input type="number" step="0.01" id="edit_nilai_max" name="nilai_max" required class="form-control form-control-amber">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Predikat Deskriptif</label>
                        <input type="text" id="edit_predikat" name="predikat" required class="form-control form-control-amber">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModalEdit()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-edit">Update Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    let selectedData = null;

    function selectRow(tr, id, kode, min, max, predikat) {
        const radio = tr.querySelector('input[type="radio"]');
        radio.checked = true;
        onRadioChange(radio, id, kode, min, max, predikat);
    }

    function onRadioChange(radio, id, kode, min, max, predikat) {
        selectedData = { id, kode, min, max, predikat };

        document.getElementById('btnEdit').disabled = false;
        document.getElementById('btnDelete').disabled = false;

        document.querySelectorAll('tbody tr').forEach(row => row.classList.remove('selected-row'));
        radio.closest('tr').classList.add('selected-row');
    }

    function triggerEditSelected() {
        if (!selectedData) return;
        document.getElementById('edit_predikat_id').value = selectedData.id;
        document.getElementById('edit_kode').value = selectedData.kode;
        document.getElementById('edit_nilai_min').value = selectedData.min;
        document.getElementById('edit_nilai_max').value = selectedData.max;
        document.getElementById('edit_predikat').value = selectedData.predikat;

        document.getElementById('formEditPredikat').action = `/predikat/${selectedData.id}`;
        toggleModal('modalEdit', true);
    }

    function triggerDeleteSelected() {
        if (!selectedData) return;
        if (confirm(`Apakah Anda yakin ingin menghapus predikat '${selectedData.id}'?`)) {
            const form = document.getElementById('globalDeleteForm');
            form.action = `/predikat/${selectedData.id}`;
            form.submit();
        }
    }

    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        
        if (show) {
            modal.classList.add('show');
            setTimeout(() => {
                content.classList.add('active');
            }, 10);
        } else {
            content.classList.remove('active');
            setTimeout(() => {
                modal.classList.remove('show');
            }, 150);
        }
    }

    function openModalTambah() { toggleModal('modalTambah', true); }
    function closeModalTambah() { toggleModal('modalTambah', false); }
    function closeModalEdit() { toggleModal('modalEdit', false); }
</script>