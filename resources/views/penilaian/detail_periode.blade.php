@extends('layout.app')

@section('content')
<style>
    :root {
        --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        --bg-page: #f8fafc;
        --surface: #ffffff;
        --maroon-primary: #7A1C38;
        --maroon-hover: #5C1329;
        --maroon-soft: #FBF0F3;
        --maroon-border: #F3D5DD;
        --maroon-gradient: linear-gradient(135deg, #7A1C38 0%, #9E2A4B 100%);
        --maroon-glow: rgba(122, 28, 56, 0.2);
        --text-primary: #0F172A;
        --text-secondary: #475569;
        --text-muted: #94A3B8;
        --border-color: #E2E8F0;
        --badge-emerald-bg: #D1FAE5;
        --badge-emerald-text: #065F46;
        --badge-amber-bg: #FEF3C7;
        --badge-amber-text: #92400E;
        --badge-rose-bg: #FFE4E6;
        --badge-rose-text: #9F1239;
        --badge-slate-bg: #F1F5F9;
        --badge-slate-text: #475569;
        --radius-xl: 1rem;
        --radius-lg: 0.75rem;
        --radius-md: 0.5rem;
        --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
    }

    .detail-page {
        font-family: var(--font-main);
        background: #ffffff;
        min-height: 100vh;
        color: var(--text-primary);
        padding: 1.5rem;
        letter-spacing: -0.01em;
    }

    .breadcrumb-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--maroon-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .breadcrumb-title span {
        color: var(--text-muted);
        font-weight: 500;
    }

    .breadcrumb-badge {
        background-color: var(--maroon-soft);
        color: var(--maroon-primary);
        border: 1px solid var(--maroon-border);
        font-size: 0.725rem;
        font-weight: 800;
        padding: 0.15rem 0.6rem;
        border-radius: 9999px;
        letter-spacing: 0.03em;
    }

    .detail-toolbar {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: var(--shadow-card);
        padding: 0.9rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .detail-toolbar::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--maroon-gradient);
    }

    .detail-toolbar-left {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .detail-toolbar-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.7rem 1.1rem;
        border: none;
        border-radius: 0.75rem;
        background: var(--maroon-gradient);
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(122, 28, 56, 0.18);
        cursor: pointer;
    }

    .detail-toolbar-action:hover {
        color: #fff;
        opacity: 0.96;
    }

    .detail-toolbar-actions,
    .detail-bulk-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-toolbar-actions .detail-toolbar-action,
    .detail-bulk-actions .detail-toolbar-action {
        padding: 0.6rem 0.8rem;
        font-size: 0.76rem;
    }

    .detail-toolbar-action.secondary {
        color: var(--maroon-primary);
        background: #fff;
        border: 1px solid var(--maroon-border);
        box-shadow: none;
    }

    .detail-toolbar-action.danger {
        background: #b4233c;
    }

    .detail-card-header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        width: 100%;
    }

    .detail-notice {
        margin: 0 0 1rem;
        padding: 0.85rem 1rem;
        border-radius: 0.65rem;
        border: 1px solid var(--border-color);
        background: #f8fafc;
    }

    .detail-notice.success { color: #065f46; background: #ecfdf5; border-color: #a7f3d0; }
    .detail-notice.error { color: #9f1239; background: #fff1f2; border-color: #fecdd3; }

    .period-dialog {
        width: min(640px, calc(100% - 2rem));
        max-height: 90vh;
        padding: 0;
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.25);
    }

    .period-dialog::backdrop { background: rgba(15, 23, 42, 0.5); }
    .period-dialog-content { padding: 1.4rem; }
    .period-dialog h2 { margin: 0 0 0.45rem; font-size: 1.2rem; }
    .period-dialog p { color: var(--text-secondary); }
    .period-dialog label { display: block; margin: 0.8rem 0 0.35rem; font-weight: 700; }
    .period-dialog input,
    .period-dialog select,
    .period-dialog textarea {
        width: 100%;
        padding: 0.65rem 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
    }
    .period-dialog-actions { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1rem; }
    .employee-search-results { margin-top: 0.5rem; max-height: 220px; overflow-y: auto; }
    .employee-search-result {
        display: block;
        width: 100%;
        padding: 0.65rem;
        text-align: left;
        border: 0;
        border-bottom: 1px solid var(--border-color);
        background: #fff;
        cursor: pointer;
    }
    .employee-search-result:hover,
    .employee-search-result.selected { background: var(--maroon-soft); }
    .selection-checkbox { width: 1rem; height: 1rem; cursor: pointer; }

    .detail-toolbar-meta {
        font-size: 0.7rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .toolbar-icon-wrapper {
        width: 42px;
        height: 42px;
        background: var(--maroon-gradient);
        color: white;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px var(--maroon-glow);
    }

    .detail-toolbar-title {
        font-size: 1.15rem;
        font-weight: 800;
        margin: 0;
        color: var(--text-primary);
    }

    .detail-toolbar-subtitle {
        margin: 0.15rem 0 0;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .btn-maroon-custom {
        background: var(--maroon-gradient);
        color: #ffffff;
        border: none;
        padding: 0.72rem 1.25rem;
        font-size: 0.8125rem;
        font-weight: 700;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: opacity 0.15s ease-in-out;
        box-shadow: 0 8px 18px rgba(122, 28, 56, 0.18);
        height: 44px;
    }

    .btn-maroon-custom:hover {
        opacity: 0.96;
        color: #ffffff;
    }

    .detail-action-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.1rem;
        height: 2.1rem;
        border: 1px solid var(--maroon-border);
        background: var(--maroon-soft);
        color: var(--maroon-primary);
        border-radius: 0.65rem;
        font-size: 0.8rem;
        transition: all 0.18s ease;
        padding: 0;
    }

    .btn-action-icon svg {
        width: 0.95rem;
        height: 0.95rem;
        stroke: currentColor;
        fill: none;
    }

    .btn-action-icon:hover {
        background: var(--maroon-primary);
        color: #ffffff;
        border-color: var(--maroon-primary);
        transform: translateY(-1px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .card-stat {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1rem 1.125rem;
        box-shadow: var(--shadow-card);
        height: 100%;
    }

    .stat-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .text-primary-custom { color: var(--maroon-primary) !important; }
    .bg-primary-light { background-color: var(--maroon-soft); color: var(--maroon-primary); border: 1px solid var(--maroon-border); }
    .bg-success-light { background-color: var(--badge-emerald-bg); color: var(--badge-emerald-text); border: 1px solid #10B981; }
    .bg-warning-light { background-color: var(--badge-amber-bg); color: var(--badge-amber-text); border: 1px solid #F59E0B; }
    .bg-danger-light { background-color: var(--badge-rose-bg); color: var(--badge-rose-text); border: 1px solid #F43F5E; }
    .bg-secondary-light { background-color: var(--badge-slate-bg); color: var(--badge-slate-text); border: 1px solid var(--border-color); }

    .card-box {
        border: 1px solid #dfe4ea;
        border-radius: 12px;
        background: #ffffff;
        overflow: hidden;
    }

    .card-header-daftarpegawai {
        background: #ffffff;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #dfe4ea;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title-daftarpegawai {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-container {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }

    .table-responsive { overflow-x: auto; }

    .table-custom {
        width: 100%;
        min-width: 1200px;
        font-size: 0.8125rem;
        text-align: left;
        border-collapse: collapse;
        margin: 0;
        border-spacing: 0;
        table-layout: fixed;
    }

    .table-custom thead {
        background-color: var(--maroon-primary);
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.725rem;
        line-height: 1.2;
    }

    .table-custom th, .table-custom td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
        line-height: 1.45;
        letter-spacing: 0.01em;
    }

    .table-custom thead th + th,
    .table-custom tbody td + td {
        border-left: 1px solid rgba(148, 163, 184, 0.28);
    }

    .table-custom tbody tr {
        background: #ffffff;
        transition: background-color 0.15s ease-in-out;
    }

    .table-custom tbody tr:nth-child(even) {
        background: #fff;
    }

    .table-custom tbody tr:hover {
        background-color: #FDF7F9 !important;
    }

    .table-action-cell {
        width: 110px;
        min-width: 110px;
    }

    .null-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.3rem;
        padding: 0.22rem 0.55rem;
        border-radius: 999px;
        background: #f1f5f9;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .badge-status-gray {
        background-color: #F1F5F9;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .badge-status-success {
        background-color: var(--badge-emerald-bg);
        color: var(--badge-emerald-text);
        border: 1px solid #10B981;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .badge-status-warning {
        background-color: var(--badge-amber-bg);
        color: var(--badge-amber-text);
        border: 1px solid #F59E0B;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .badge-status-danger {
        background-color: var(--badge-rose-bg);
        color: var(--badge-rose-text);
        border: 1px solid #F43F5E;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .btn-outline-maroon {
        color: var(--maroon-primary);
        border-color: var(--maroon-border);
        background-color: var(--maroon-soft);
    }

    .btn-outline-maroon:hover {
        color: #ffffff;
        background-color: var(--maroon-primary);
        border-color: var(--maroon-primary);
    }

    .filter-card {
        background: transparent;
        border: none;
        border-radius: 0;
        box-shadow: none;
        padding: 1rem 1rem 1.1rem;
        margin: 0 0 0;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(140px, 1fr));
        gap: 0.9rem;
        align-items: center;
    }

    .form-control-custom,
    .form-select-custom {   
        width: 100%;
        border: 1px solid #d8dee8;
        border-radius: 8px;
        background: #ffffff;
        color: var(--text-primary);
        padding: 0.72rem 0.9rem;
        font-size: 0.8125rem;
        outline: none;
        height: 44px;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: var(--maroon-primary);
        box-shadow: 0 0 0 3px rgba(122, 28, 56, 0.08);
    }

    /* PAGINATION: dibuat agar sama dengan contoh yang diberikan */
    .detail-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-top: 1px solid #dfe4ea;
        padding: 0.8rem 0 0;
        margin-top: 0.75rem;
        background: transparent;
    }

    .detail-pagination-label {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-secondary);
        white-space: nowrap;                                                            
    }

    .detail-pagination-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.35rem;
        margin-left: auto;
    }

    .page-arrow,
    .page-number,
    .page-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        user-select: none;
        text-decoration: none;
    }

    .page-arrow {
        width: 2.6rem;
        height: 2.5rem;
        border: 1px solid #dfe4ea;
        border-radius: 0.35rem;
        background: #ffffff;
        color: #7a838f;
        font-size: 2rem;
        font-weight: 400;
        padding: 0;
    }

    .page-arrow.disabled {
        color: #a9b3c0;
        background: #f8fafc;
        pointer-events: none;
    }

    .page-number {
        width: 2.45rem;
        height: 2.5rem;
        border: 1px solid #dfe4ea;
        border-radius: 0.35rem;
        background: #ffffff;
        color: #2f3746;
        font-size: 1rem;
        font-weight: 700;
        text-decoration: none;
        padding: 0;
    }

    .page-number.active {
        background: #edf2ff;
        border-color: #2d6cdf;
        color: #1d4ed8;
    }

    .page-number:hover,
    .page-arrow:hover {
        text-decoration: none;
    }

    .page-ellipsis {
        width: 1.3rem;
        height: 2.5rem;
        color: #67748a;
        font-size: 1.5rem;
        font-weight: 700;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .filter-grid { grid-template-columns: repeat(3, minmax(160px, 1fr)); }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .detail-toolbar { flex-direction: column; align-items: flex-start; }
        .filter-grid { grid-template-columns: 1fr; }
        .detail-pagination { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="detail-page">
    @if(session('success'))
        <div class="detail-notice success" role="status">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="detail-notice error" role="alert">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="detail-notice error" role="alert">
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="detail-toolbar">
        <div class="detail-toolbar-left">
            <div class="toolbar-icon-wrapper">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="detail-toolbar-title">Detail Periode</h1>
                <div class="detail-toolbar-meta">Periode: <strong>{{ $periode->nama_periode ?? $periode->periode_id }}</strong> <span class="breadcrumb-badge" style="margin-left:0.4rem;">PRIMA</span></div>
            </div>
        </div>

        <div class="detail-toolbar-actions">
            <button type="button" class="detail-toolbar-action secondary" data-open-dialog="evaluatorDialog">Set Penilai</button>
            <form method="POST" action="{{ route('periode.evaluators.automatic', $periode->periode_id) }}" id="automaticEvaluatorForm">
                @csrf
                <div class="selected-entries"></div>
                <button type="submit" class="detail-toolbar-action secondary" data-require-selection>Kembalikan ke Otomatis</button>
            </form>
            <button type="button" class="detail-toolbar-action secondary" data-open-dialog="updateEmployeesDialog">Set Pegawai</button>
            <button type="button" class="detail-toolbar-action" data-open-dialog="addEmployeeDialog">Tambah Pegawai</button>
            <button type="button" class="detail-toolbar-action danger" data-open-dialog="deactivateEmployeesDialog">Nonaktif</button>
            <a href="{{ route('periode.export', array_merge(['id' => $periode->periode_id], request()->query())) }}" class="detail-toolbar-action secondary">Export Excel</a>
            <a href="{{ route('periode-penilaian.index') }}" class="detail-toolbar-action">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Total Pegawai</div>
                    <div class="stat-value text-primary-custom">{{ $stats['total_pegawai'] }}</div>
                </div>
                <div class="stat-icon bg-primary-light">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Belum Diisi</div>
                    <div class="stat-value text-secondary">{{ $stats['belum_diisi'] }}</div>
                </div>
                <div class="stat-icon bg-secondary-light">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Tanpa Penilai</div>
                    <div class="stat-value text-danger">{{ $stats['tanpa_penilai'] }}</div>
                </div>
                <div class="stat-icon bg-danger-light"><i class="fas fa-user-slash"></i></div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Penilai Manual</div>
                    <div class="stat-value" style="color:#7A1C38;">{{ $stats['penilai_manual'] }}</div>
                </div>
                <div class="stat-icon bg-primary-light"><i class="fas fa-user-edit"></i></div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Perlu Ditinjau</div>
                    <div class="stat-value" style="color:#d97706;">{{ $stats['perlu_ditinjau'] }}</div>
                </div>
                <div class="stat-icon bg-warning-light"><i class="fas fa-eye"></i></div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Draft</div>
                    <div class="stat-value" style="color: #d97706;">{{ $stats['draft'] }}</div>
                </div>
                <div class="stat-icon bg-warning-light">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Submit</div>
                    <div class="stat-value text-success">{{ $stats['submit'] }}</div>
                </div>
                <div class="stat-icon bg-success-light">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="card-stat">
            <div class="stat-body">
                <div>
                    <div class="stat-label">Verifikasi</div>
                    <div class="stat-value text-danger">{{ $stats['verifikasi'] }}</div>
                </div>
                <div class="stat-icon bg-danger-light">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-box">
        <div class="card-header-daftarpegawai">
            <div class="detail-card-header-actions">
                <div class="card-title-daftarpegawai">
                    <i class="fas fa-list me-2"></i> Daftar Pegawai Penilaian
                </div>
                <div class="detail-bulk-actions">
                    <span id="selectionCount" aria-live="polite">0 dipilih</span>
                </div>
            </div>
        </div>

        <div class="p-3">
            <div class="filter-card" style="margin-bottom: 0;">
                <form method="GET" action="{{ route('periode.detail', $periode->periode_id) }}" class="filter-grid">
                    <div>
                        <input type="text" name="search" class="form-control-custom" placeholder="Cari nama / NUP..." value="{{ request('search') }}">
                    </div>

                    <div>
                        <select name="kode_unit_kerja" class="form-select-custom">
                            <option value="">- Semua Unit Kerja -</option>
                            @foreach($unitKerjaList as $uk)
                                <option value="{{ $uk->kode_unit_kerja }}" {{ request('kode_unit_kerja') == $uk->kode_unit_kerja ? 'selected' : '' }}>
                                    {{ $uk->nama_unit_kerja }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="status" class="form-select-custom">
                            <option value="">- Semua Status -</option>
                            <option value="BELUM DIISI" {{ request('status') === 'BELUM DIISI' ? 'selected' : '' }}>Belum Diisi</option>
                            <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                            <option value="SUBMIT" {{ request('status') === 'SUBMIT' ? 'selected' : '' }}>Submit</option>
                            <option value="DIAJUKAN" {{ request('status') === 'DIAJUKAN' ? 'selected' : '' }}>Diajukan</option>
                            <option value="VERIFIKASI" {{ request('status') === 'VERIFIKASI' ? 'selected' : '' }}>Verifikasi</option>
                        </select>
                    </div>

                    <div>
                        <select name="sort" class="form-select-custom">
                            <option value="nama_asc" {{ request('sort', 'nama_asc') === 'nama_asc' ? 'selected' : '' }}>Urut Nama A-Z</option>
                            <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Urut Nama Z-A</option>
                            <option value="nilai_desc" {{ request('sort') === 'nilai_desc' ? 'selected' : '' }}>Nilai Tertinggi</option>
                            <option value="nilai_asc" {{ request('sort') === 'nilai_asc' ? 'selected' : '' }}>Nilai Terendah</option>
                            <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn-maroon-custom" style="width: 100%; justify-content: center;">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAllRows" class="selection-checkbox" aria-label="Pilih semua pegawai di halaman"></th>
                            <th width="40">NO</th>
                            <th>NUP</th>
                            <th>NAMA</th>
                            <th>UNIT KERJA</th> <!-- Kolom Baru -->
                            <th>PENEMPATAN</th>
                            <th>JABATAN</th>
                            <th>PENILAI</th>
                            <th>VERIFIKATOR</th>
                            <th>STATUS</th>
                            <th>NILAI</th>
                            <th>PREDIKAT</th>
                            <th width="90" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penilaianList as $index => $row)
                            @php
                                $statusPenilaian = strtoupper((string) data_get($row, 'status_penilaian', data_get($row, 'status_nilai', 'BELUM DIISI')));
                                $nilaiAkhir = is_numeric(data_get($row, 'nilai_akhir')) ? (float) data_get($row, 'nilai_akhir') : 0.00;
                                $predikat = data_get($row, 'predikat', 'Mengecewakan');
                            @endphp
                            <tr>
                                <td><input type="checkbox" class="selection-checkbox period-row-checkbox" value="{{ data_get($row, 'penilaian_id') }}" aria-label="Pilih {{ data_get($row, 'nama', 'pegawai') }}"></td>
                                <td>{{ (($penilaianList->currentPage() - 1) * $penilaianList->perPage()) + $loop->iteration }}</td>
                                <td><code>{{ data_get($row, 'nup', '-') }}</code></td>
                                <td><strong>{{ data_get($row, 'nama', '-') }}</strong></td>
                                <td>{{ data_get($row, 'unit_kerja', '-') }}</td> <!-- Tampilkan Unit Kerja -->
                                <td>{{ data_get($row, 'penempatan', '-') }}</td>
                                <td>{{ data_get($row, 'jabatan', '-') }}</td>
                                <td>
                                    @if(data_get($row, 'nama_penilai'))
                                        {{ data_get($row, 'nama_penilai') }}
                                    @else
                                        <span class="null-pill">NULL</span>
                                    @endif
                                </td>
                                <td>
                                    @if(data_get($row, 'nama_verifikator'))
                                        {{ data_get($row, 'nama_verifikator') }}
                                    @else
                                        <span class="null-pill">NULL</span>
                                    @endif
                                </td>
                                <td>
                                    @if($statusPenilaian === 'DIAJUKAN')
                                        <span class="badge-status-success">Diajukan</span>
                                    @elseif($statusPenilaian === 'DIKEMBALIKAN')
                                        <span class="badge-status-danger">Dikembalikan</span>
                                    @elseif($statusPenilaian === 'DRAFT')
                                        <span class="badge-status-warning">Draft</span>
                                    @else
                                        <span class="badge-status-gray">Belum Diisi</span>
                                    @endif
                                </td>
                                <td>{{ number_format($nilaiAkhir, 2) }}</td>
                                <td>
                                    @if(!empty($predikat))
                                        <span class="badge-status-gray">{{ $predikat }}</span>
                                    @else
                                        <span class="badge-status-danger">Mengecewakan</span>
                                    @endif
                                </td>
                                <td class="text-center table-action-cell">
                                    <div class="detail-action-group" style="justify-content:center;">
                                        <button class="btn-action-icon" type="button" title="Lihat Detail" aria-label="Lihat Detail">
                                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button class="btn-action-icon" type="button" title="Verifikasi Penilaian" aria-label="Verifikasi Penilaian">
                                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 3l7 3v5c0 4.2-2.6 8.1-7 10-4.4-1.9-7-5.8-7-10V6l7-3z"></path>
                                                <path d="M9.5 12.5l1.6 1.6 3.4-3.9"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center py-4 text-muted">Belum ada data pegawai aktif pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($penilaianList instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $penilaianList->hasPages())
            @php
                $currentPage = $penilaianList->currentPage();
                $lastPage = $penilaianList->lastPage();
                $firstItem = $penilaianList->firstItem() ?? 0;
                $lastItem = $penilaianList->lastItem() ?? 0;
                $total = $penilaianList->total();

                $pageNumbers = [];
                if ($lastPage <= 5) {
                    $pageNumbers = range(1, $lastPage);
                } elseif ($currentPage <= 3) {
                    $pageNumbers = [1, 2, 3, 4, 5];
                } elseif ($currentPage >= $lastPage - 2) {
                    $pageNumbers = [$lastPage - 4, $lastPage - 3, $lastPage - 2, $lastPage - 1, $lastPage];
                } else {
                    $pageNumbers = [$currentPage - 1, $currentPage, $currentPage + 1, $currentPage + 2, $currentPage + 3];
                }
            @endphp

            <div class="detail-pagination">
                <p class="detail-pagination-label">Menampilkan {{ $firstItem }} sampai {{ $lastItem }} dari {{ $total }} data</p>

                <div class="detail-pagination-nav">
                    @if ($currentPage > 1)
                        <a href="{{ $penilaianList->url($currentPage - 1) }}" class="page-arrow" aria-label="Previous page">&lsaquo;</a>
                    @else
                        <span class="page-arrow disabled" aria-hidden="true">&lsaquo;</span>
                    @endif

                    @foreach ($pageNumbers as $page)
                        @if ($page == $currentPage)
                            <span class="page-number active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $penilaianList->url($page) }}" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($lastPage > 5 && $currentPage < $lastPage - 2)
                        <span class="page-ellipsis">…</span>
                    @endif

                    @if ($currentPage < $lastPage)
                        <a href="{{ $penilaianList->url($currentPage + 1) }}" class="page-arrow" aria-label="Next page">&rsaquo;</a>
                    @else
                        <span class="page-arrow disabled" aria-hidden="true">&rsaquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<dialog id="addEmployeeDialog" class="period-dialog">
    <div class="period-dialog-content">
        <h2>Tambah Pegawai ke Periode</h2>
        <p>Cari pegawai menggunakan nama atau NUP, minimal 3 karakter. Pegawai tanpa template tetap dapat ditambahkan, tetapi daftar pertanyaannya akan kosong.</p>
        <form method="POST" action="{{ route('periode.employees.add', $periode->periode_id) }}" id="addEmployeeForm">
            @csrf
            <label for="employeeSearch">Cari Nama atau NUP</label>
            <input type="search" id="employeeSearch" autocomplete="off" placeholder="Ketik minimal 3 karakter">
            <div id="employeeSearchResults" class="employee-search-results" aria-live="polite"></div>
            <input type="hidden" name="pegawai_id" id="selectedEmployeeId">
            <div id="selectedEmployeeLabel" class="detail-notice" hidden></div>
            <div class="period-dialog-actions">
                <button type="button" class="detail-toolbar-action secondary" data-close-dialog>Batalkan</button>
                <button type="submit" class="detail-toolbar-action">Tambahkan</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="updateEmployeesDialog" class="period-dialog">
    <div class="period-dialog-content">
        <h2>Set Data Pegawai</h2>
        <p>Perubahan hanya berlaku pada data pegawai di periode ini. Kosongkan isian yang tidak ingin diubah.</p>
        <form method="POST" action="{{ route('periode.employees.update', $periode->periode_id) }}" data-selected-form>
            @csrf
            <div class="selected-entries"></div>
            <label for="bulkOccupation">Jabatan</label>
            <select name="occ_id" id="bulkOccupation">
                <option value="">- Tidak diubah -</option>
                @foreach($occupations as $occupation)
                    <option value="{{ $occupation->occ_id }}">{{ $occupation->occ_name }}</option>
                @endforeach
            </select>
            <label for="bulkDepartment">Departemen</label>
            <select name="dept_id" id="bulkDepartment">
                <option value="">- Tidak diubah -</option>
                @foreach($departments as $department)
                    <option value="{{ $department->dept_id }}">{{ $department->dept_name }}</option>
                @endforeach
            </select>
            <label for="bulkSubDepartment">Sub Departemen</label>
            <select name="subdept_id" id="bulkSubDepartment">
                <option value="">- Tidak diubah -</option>
                @foreach($subDepartments as $subDepartment)
                    <option value="{{ $subDepartment->subdept_id }}">{{ $subDepartment->subdept_name }}</option>
                @endforeach
            </select>
            <label for="bulkOffice">Penempatan</label>
            <select name="off_id" id="bulkOffice">
                <option value="">- Tidak diubah -</option>
                @foreach($offices as $office)
                    <option value="{{ $office->off_id }}">{{ $office->off_name }}</option>
                @endforeach
            </select>
            <label for="bulkSatker">Satker</label>
            <input type="text" name="kode_satker" id="bulkSatker" maxlength="255" placeholder="Kode satuan kerja">
            <label for="bulkUnitKerja">Unit Kerja</label>
            <select name="kode_unit_kerja" id="bulkUnitKerja">
                <option value="">- Tidak diubah -</option>
                @foreach($unitKerjaList as $unitKerja)
                    <option value="{{ $unitKerja->kode_unit_kerja }}">{{ $unitKerja->nama_unit_kerja }}</option>
                @endforeach
            </select>
            <div class="period-dialog-actions">
                <button type="button" class="detail-toolbar-action secondary" data-close-dialog>Batalkan</button>
                <button type="submit" class="detail-toolbar-action">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="evaluatorDialog" class="period-dialog">
    <div class="period-dialog-content">
        <h2>Set Penilai</h2>
        <p>Penilai yang dipilih akan ditetapkan sebagai penilai manual untuk semua pegawai terpilih.</p>
        <form method="POST" action="{{ route('periode.evaluators.assign', $periode->periode_id) }}" data-selected-form>
            @csrf
            <div class="selected-entries"></div>
            <label for="bulkEvaluator">Penilai</label>
            <select name="penilai_id" id="bulkEvaluator" required>
                <option value="">- Pilih Penilai -</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->pgw_id }}">{{ $employee->nama }}{{ $employee->nup ? ' ('.$employee->nup.')' : '' }}</option>
                @endforeach
            </select>
            <div class="period-dialog-actions">
                <button type="button" class="detail-toolbar-action secondary" data-close-dialog>Batalkan</button>
                <button type="submit" class="detail-toolbar-action">Tetapkan Penilai</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="deactivateEmployeesDialog" class="period-dialog">
    <div class="period-dialog-content">
        <h2>Nonaktifkan Pegawai</h2>
        <p>Pegawai terpilih tidak lagi ditampilkan sebagai peserta aktif pada periode ini. Data penilaian tetap tersimpan.</p>
        <form method="POST" action="{{ route('periode.employees.deactivate', $periode->periode_id) }}" data-selected-form>
            @csrf
            <div class="selected-entries"></div>
            <label for="deactivationReason">Alasan nonaktif <span aria-hidden="true">*</span></label>
            <textarea name="reason" id="deactivationReason" rows="3" maxlength="255" required placeholder="Masukkan alasan (maksimal 255 karakter)"></textarea>
            <div class="period-dialog-actions">
                <button type="button" class="detail-toolbar-action secondary" data-close-dialog>Batalkan</button>
                <button type="submit" class="detail-toolbar-action danger">Nonaktifkan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
(() => {
    const checkboxes = Array.from(document.querySelectorAll('.period-row-checkbox'));
    const selectAll = document.getElementById('selectAllRows');
    const selectionCount = document.getElementById('selectionCount');

    function selectedIds() {
        return checkboxes.filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
    }

    function syncSelection() {
        const selected = selectedIds();
        selectionCount.textContent = `${selected.length} dipilih`;
        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && selected.length === checkboxes.length;
            selectAll.indeterminate = selected.length > 0 && selected.length < checkboxes.length;
        }
    }

    function addSelectedIds(form) {
        const container = form.querySelector('.selected-entries');
        if (!container) return;
        container.replaceChildren();
        selectedIds().forEach((id) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'penilaian_ids[]';
            input.value = id;
            container.appendChild(input);
        });
    }

    document.querySelectorAll('[data-open-dialog]').forEach((button) => {
        button.addEventListener('click', () => {
            const dialog = document.getElementById(button.dataset.openDialog);
            if (dialog.id !== 'addEmployeeDialog' && selectedIds().length === 0) {
                window.alert('Pilih setidaknya satu pegawai terlebih dahulu.');
                return;
            }
            if (dialog.querySelector('[data-selected-form]')) {
                addSelectedIds(dialog.querySelector('[data-selected-form]'));
            }
            dialog.showModal();
        });
    });

    document.querySelectorAll('[data-close-dialog]').forEach((button) => {
        button.addEventListener('click', () => button.closest('dialog').close());
    });

    document.querySelectorAll('[data-selected-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            addSelectedIds(form);
            if (selectedIds().length === 0) {
                event.preventDefault();
                window.alert('Pilih setidaknya satu pegawai terlebih dahulu.');
            }
        });
    });

    const automaticForm = document.getElementById('automaticEvaluatorForm');
    if (automaticForm) {
        automaticForm.addEventListener('submit', (event) => {
            addSelectedIds(automaticForm);
            if (selectedIds().length === 0) {
                event.preventDefault();
                window.alert('Pilih setidaknya satu pegawai terlebih dahulu.');
            }
        });
    }

    checkboxes.forEach((checkbox) => checkbox.addEventListener('change', syncSelection));
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach((checkbox) => { checkbox.checked = selectAll.checked; });
            syncSelection();
        });
    }
    syncSelection();

    const searchInput = document.getElementById('employeeSearch');
    if (!searchInput) return;
    const results = document.getElementById('employeeSearchResults');
    const selectedEmployeeId = document.getElementById('selectedEmployeeId');
    const selectedEmployeeLabel = document.getElementById('selectedEmployeeLabel');
    let searchTimer;

    searchInput.addEventListener('input', () => {
        window.clearTimeout(searchTimer);
        selectedEmployeeId.value = '';
        selectedEmployeeLabel.hidden = true;
        const term = searchInput.value.trim();
        results.replaceChildren();
        if (term.length < 3) {
            results.textContent = term.length ? 'Masukkan minimal 3 karakter.' : '';
            return;
        }

        results.textContent = 'Mencari pegawai...';
        searchTimer = window.setTimeout(async () => {
            try {
                const url = new URL(@json(route('periode.employees.search', $periode->periode_id)), window.location.origin);
                url.searchParams.set('q', term);
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const employees = await response.json();
                if (!response.ok) throw new Error(employees.message || 'Pencarian pegawai gagal.');
                results.replaceChildren();
                if (!employees.length) {
                    results.textContent = 'Pegawai tidak ditemukan atau sudah terdaftar pada periode.';
                    return;
                }
                employees.forEach((employee) => {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'employee-search-result';
                    const templateStatus = employee.has_template
                        ? 'Template tersedia'
                        : 'Tanpa template (pertanyaan kosong)';
                    option.textContent = `${employee.nama} — NUP: ${employee.nup || '-'} — ${templateStatus}`;
                    option.addEventListener('click', () => {
                        selectedEmployeeId.value = employee.pgw_id;
                        selectedEmployeeLabel.textContent = `Terpilih: ${employee.nama} (${employee.nup || 'tanpa NUP'})`;
                        selectedEmployeeLabel.hidden = false;
                        results.querySelectorAll('.employee-search-result').forEach((item) => item.classList.remove('selected'));
                        option.classList.add('selected');
                    });
                    results.appendChild(option);
                });
            } catch (error) {
                results.textContent = error.message || 'Pencarian pegawai gagal.';
            }
        }, 250);
    });

    document.getElementById('addEmployeeForm').addEventListener('submit', (event) => {
        if (!selectedEmployeeId.value) {
            event.preventDefault();
            window.alert('Pilih pegawai dari hasil pencarian terlebih dahulu.');
        }
    });
})();
</script>
@endsection