@extends('layout.app')

@section('content')

<style>
    :root {
        --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        --font-code: 'JetBrains Mono', monospace;

        --bg-page: #f8fafc;
        --surface: #ffffff;
        
        /* Crimson Maroon Palette */
        --maroon-primary: #7A1C38;
        --maroon-hover: #5C1329;
        --maroon-soft: #FBF0F3;
        --maroon-border: #F3D5DD;
        --maroon-gradient: linear-gradient(135deg, #7A1C38 0%, #9E2A4B 100%);
        --maroon-glow: rgba(122, 28, 56, 0.2);

        /* Neutral System Shades */
        --text-primary: #0F172A;
        --text-secondary: #475569;
        --text-muted: #94A3B8;
        --border-color: #E2E8F0;

        /* High-Contrast Badge Colors */
        --badge-emerald-bg: #D1FAE5;
        --badge-emerald-text: #065F46;
        --badge-emerald-border: #10B981;

        --badge-blue-bg: #DBEAFE;
        --badge-blue-text: #1E40AF;
        --badge-blue-border: #3B82F6;

        --badge-amber-bg: #FEF3C7;
        --badge-amber-text: #92400E;
        --badge-amber-border: #F59E0B;

        --badge-orange-bg: #FFEDD5;
        --badge-orange-text: #9A3412;
        --badge-orange-border: #F97316;

        --badge-rose-bg: #FFE4E6;
        --badge-rose-text: #9F1239;
        --badge-rose-border: #F43F5E;

        --radius-xl: 1rem;
        --radius-lg: 0.75rem;
        --radius-md: 0.5rem;

        --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
    }

    * { box-sizing: border-box; }

    .main-wrapper {
        font-family: var(--font-main);
        -webkit-font-smoothing: antialiased;
        padding: 1.5rem;
        background-color: #ffffff;
        min-height: 100vh;
        color: var(--text-dark);
        width: 100%;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    /* --- Unik Breadcrumb Header --- */
    .breadcrumb-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--maroon-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

    .layout-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.25rem;
        align-items: start;
    }

    .main-content-column {
        min-width: 0;
        width: 100%;
    }

    .card-box {
        border: 2px solid #a4a8abab;
        border-radius: 8px;
        background: #ffffff;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .card-header-soft {
        background: var(--maroon-primary);
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #a4a8abab;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .card-header-daftarpegawai {
        background: white;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #a4a8abab;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title-daftarpegawai {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* --- Custom Select Dropdown --- */
    .select-wrapper { position: relative; width: 100%; max-width: 480px; }
    .custom-select {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border: 1px solid #a4a8abab;
        border-radius: 6px;
        font-size: 0.85rem;
        font-family: var(--font-main);
        outline: none;
        background-color: #ffffff;
        color: var(--text-primary);
        cursor: pointer;
    }
    .custom-select:focus {
        border-color: var(--maroon-primary);
        box-shadow: 0 0 0 3px var(--maroon-glow);
    }

    /* --- Empty State --- */
    .empty-state {
        padding: 3.5rem 1.5rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-state-icon {
        width: 48px; height: 48px;
        color: #94A3B8;
        margin-bottom: 0.5rem;
    }

    .toolbar-card {
        background: var(--surface);
        padding: 1.25rem 1.5rem;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        position: relative;
    }

    .toolbar-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0; width: 5px;
        background: var(--maroon-gradient);
        border-top-left-radius: var(--radius-xl);
        border-bottom-left-radius: var(--radius-xl);
    }

    .toolbar-header {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .toolbar-icon-wrapper {
        padding: 0.625rem;
        background: var(--maroon-gradient);
        color: #ffffff;
        border-radius: var(--radius-md);
        display: inline-flex;
        box-shadow: 0 4px 12px var(--maroon-glow);
    }

    .toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .toolbar-subtitle {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .toolbar-subtitle-count {
        font-weight: 800;
        color: var(--maroon-primary);
        background-color: var(--maroon-soft);
        padding: 0.1rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid var(--maroon-border);
        font-size: 0.75rem;
    }

    /* ==========================================================================
   2. CARD RINGKASAN STATISTIK (STAT CARDS)
   ========================================================================== */
.card-stat {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1rem 1.125rem;
    box-shadow: var(--shadow-card);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.card-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -5px rgba(15, 23, 42, 0.08);
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Color Themes for Stat Cards */
.text-primary-custom { color: var(--maroon-primary) !important; }
.bg-primary-light { 
    background-color: var(--maroon-soft); 
    color: var(--maroon-primary); 
    border: 1px solid var(--maroon-border);
}

.bg-success-light { 
    background-color: var(--badge-emerald-bg); 
    color: var(--badge-emerald-text); 
    border: 1px solid var(--badge-emerald-border);
}

.bg-danger-light { 
    background-color: var(--badge-rose-bg); 
    color: var(--badge-rose-text); 
    border: 1px solid var(--badge-rose-border);
}

.bg-warning-light { 
    background-color: var(--badge-amber-bg); 
    color: var(--badge-amber-text); 
    border: 1px solid var(--badge-amber-border);
}

.bg-secondary-light { 
    background-color: #F1F5F9; 
    color: var(--text-secondary); 
    border: 1px solid var(--border-color);
}

/* ==========================================================================
   FILTER SECTION STYLING
   ========================================================================== */
.filter-card {
    background-color: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 1rem;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: var(--shadow-card);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.75rem;
    align-items: center;
}

.filter-item {
    flex: 1;
    min-width: 160px;
}

.filter-item-search {
    flex: 1.5;
    min-width: 200px;
}

.filter-item-btn {
    flex: 0 0 auto;
}

/* Custom Input & Select Styling */
.form-control-custom,
.form-select-custom {
    width: 100%;
    padding: 0.5rem 0.85rem;
    font-size: 0.8125rem;
    font-family: var(--font-main);
    color: var(--text-primary);
    background-color: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    outline: none;
    transition: all 0.15s ease-in-out;
}

.form-control-custom:focus,
.form-select-custom:focus {
    border-color: var(--maroon-primary);
    box-shadow: 0 0 0 3px var(--maroon-glow);
}

.btn-maroon-custom {
    background: var(--maroon-gradient);
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    cursor: pointer;
    transition: opacity 0.15s ease-in-out;
}

.btn-maroon-custom:hover {
    opacity: 0.92;
    color: #ffffff;
}

/* Custom Buttons untuk Verifikasi & Unverifikasi Massal (Dibuat kontras & Rapi) */
.filter-action-row {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.85rem;
}

.btn-success-custom {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-success-custom:hover:not(:disabled) {
    background-color: #218838;
    color: #ffffff;
}

.btn-success-custom:disabled {
    background-color: #8ce19d;
    cursor: not-allowed;
    color: #ffffff;
    opacity: 0.8;
}

.btn-warning-custom {
    background-color: #f0ad4e;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-warning-custom:hover:not(:disabled) {
    background-color: #ec971f;
    color: #ffffff;
}

.btn-warning-custom:disabled {
    background-color: #f7d4a0;
    cursor: not-allowed;
    color: #ffffff;
    opacity: 0.8;
}

/* ==========================================================================
   1. DATA TABLE STYLING (KELOLA PENILAIAN)
   ========================================================================== */
.table-container {
    background-color: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-card);
}

.table-responsive {
    overflow-x: auto;
}

.table-custom {
    width: 100%;
    min-width: 1550px;
    font-size: 0.8125rem;
    text-align: left;
    border-collapse: collapse;
    margin: 0;
    table-layout: fixed;
}

.table-custom thead {
    background: var(--maroon-gradient);
    color: #ffffff;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-size: 0.725rem;
}

.table-custom thead th + th,
.table-custom tbody td + td {
    border-left: 1px solid rgba(148, 163, 184, 0.28);
}

.table-custom th,
.table-custom td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    word-break: break-word;
    overflow-wrap: anywhere;
    line-height: 1.4;
}

.table-custom th {
    white-space: nowrap !important;
}

.th-verifikasi {
    width: 170px;
    min-width: 170px;
}

.table-custom td {
    color: var(--text-primary);
}
 
.table-custom thead th:nth-child(8),
.table-custom tbody td:nth-child(8) {
    min-width: 180px;
    width: 180px;
}

.table-custom tbody tr {
    background: #ffffff;
    border-bottom: 1px solid var(--border-color);
    transition: background-color 0.15s ease-in-out;
}

.table-custom tbody tr:nth-child(even) {
    background: #fff;
}

.table-custom tbody tr:hover {
    background-color: #FDF7F9 !important;
}

.table-custom tbody tr.selected-row {
    background-color: #F1F5F9 !important;
}

.table-action-cell {
    width: 110px;
    min-width: 110px;
}

.employee-filter-item {
    width: 100%;
}

.employee-filter-button {
    background: var(--maroon-gradient);
    color: #ffffff;
    border: none;
    border-radius: 0.75rem;
    min-height: 44px;
    font-size: 0.8125rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(122, 28, 56, 0.18);
}

.employee-filter-button:hover {
    color: #ffffff;
    opacity: 0.96;
}

.detail-action-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
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

    .page-ellipsis {
        width: 1.3rem;
        height: 2.5rem;
        color: #67748a;
        font-size: 1.5rem;
        font-weight: 700;
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

@media (max-width: 1200px) {
    .filter-grid {
        grid-template-columns: repeat(3, minmax(160px, 1fr));
    }
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>

    <div class="main-wrapper">
        <!-- BREADCRUMB HEADER -->
        <div class="header-bar d-flex justify-content-between align-items-center mb-3">
            <div class="breadcrumb-title">
                Verifikasi Penilaian <span>» Penilaian Kinerja Pegawai</span>
            </div>
        </div>
        
        <div class="main-content-column">
            <!-- SELECTOR PERIODE PENILAIAN -->
            <div class="card-box mb-3">
                <div class="card-header-soft">
                    <div class="card-title">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="me-1">
                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm1 2h6v1H7V4zM4 8h12v8H4V8z"></path>
                        </svg>
                        Pilih Periode Penilaian
                    </div>
                </div>
                <div style="padding: 0.85rem 1rem;">
                    <div class="select-wrapper">
                        <form method="GET" action="{{ route('verifikasi-penilaian.index') }}" id="formSelectPeriode">
                            <select id="periodeSelector" name="periode_id" class="custom-select" onchange="document.getElementById('formSelectPeriode').submit();">
                                <option value="" @selected(!request('periode_id'))>-- Pilih Periode --</option>
                                @forelse($periodes as $periode)
                                    <option value="{{ $periode->periode_id }}" @selected(request('periode_id') == $periode->periode_id)>
                                        {{ $periode->nama_periode ?? ($periode->tahun . ' - ' . $periode->bulan) }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada periode tersimpan</option>
                                @endforelse
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TOOLBAR HEADER -->
            <div class="toolbar-card mb-3">
                <div class="toolbar-header">
                    <div class="toolbar-icon-wrapper">
                        <svg class="icon-svg" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="toolbar-title">Verifikasi Penilaian</h1>
                        <p class="toolbar-subtitle">
                            @if($hasPeriodeSelected && $selectedPeriode)
                                Periode Penilaian: <strong>{{ $selectedPeriode->nama_periode ?? $selectedPeriode->periode_id }}</strong>
                            @else
                                Silakan pilih periode penilaian untuk mengelola data penilaian.
                            @endif
                        </p>
                    </div>
                </div>
            </div><br>

            @if($hasPeriodeSelected)
                <!-- CARD RINGKASAN STATISTIK (KE SAMPING) -->
                <div class="stats-grid">
                    <div class="card-stat">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Menunggu Verifikasi</div>
                                <div class="stat-value text-primary-custom">{{ $menungguVerifikasi }}</div>
                            </div>
                            <div class="stat-icon bg-primary-light">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Sudah Verified</div>
                                <div class="stat-value text-success">{{ $sudahVerified }}</div>
                            </div>
                            <div class="stat-icon bg-success-light">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Ada Koreksi</div>
                                <div class="stat-value text-danger">{{ $adaKoreksi }}</div>
                            </div>
                            <div class="stat-icon bg-danger-light">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat">
                        <div class="stat-body">
                            <div>
                                <div class="stat-label">Dikembalikan Revisi</div>
                                <div class="stat-value text-warning">{{ $dikembalikan }}</div>
                            </div>
                            <div class="stat-icon bg-warning-light">
                                <i class="fas fa-undo"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-header-daftarpegawai">
                        <div class="card-title-daftarpegawai">
                            <i class="fas fa-list me-2"></i> Daftar Penilaian Menunggu Verifikasi
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="filter-card" style="margin-bottom: 0;">
                            <form method="GET" action="{{ route('verifikasi-penilaian.index') }}" id="formFilterPenilaian">
                                <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">

                                <!-- BARIS 1: INPUT FILTER (5 KOLOM KONSISTEN) -->
                                <div class="filter-grid">
                                    <div>
                                        <input type="text" name="search" class="form-control-custom" placeholder="Cari nama/NUP/penilai..." value="{{ request('search') }}">
                                    </div>

                                    <div>
                                        <select name="penempatan" class="form-select-custom">
                                            <option value="">- Semua Penempatan -</option>
                                        </select>
                                    </div>

                                    <div>
                                        <select name="departemen" class="form-select-custom">
                                            <option value="">- Semua Departemen -</option>
                                        </select>
                                    </div>

                                    <div>
                                        <select name="status_verifikasi" class="form-select-custom">
                                            <option value="">- Semua Status Verifikasi -</option>
                                            <option value="VERIFIED" @selected(request('status_verifikasi') == 'VERIFIED')>Verified</option>
                                            <option value="UNVERIFIED" @selected(request('status_verifikasi') == 'UNVERIFIED')>Belum Verifikasi</option>
                                        </select>
                                    </div>

                                    <div>
                                        <select name="koreksi" class="form-select-custom">
                                            <option value="">- Semua Koreksi -</option>
                                            <option value="ADA_KOREKSI" @selected(request('koreksi') == 'ADA_KOREKSI')>Ada Koreksi</option>
                                            <option value="TANPA_KOREKSI" @selected(request('koreksi') == 'TANPA_KOREKSI')>Tanpa Koreksi</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- BARIS 2: TOMBOL AKSI DISUSUN PAS RATA KANAN -->
                                <div class="filter-action-row">
                                    <button type="submit" class="btn-maroon-custom">
                                        <i class="fas fa-search"></i> Tampilkan
                                    </button>
                                    <button type="button" id="btnBulkVerify" class="btn-success-custom" disabled>
                                        <i class="fas fa-check-circle"></i> Verifikasi Terpilih (<span id="countVerify">0</span>)
                                    </button>
                                    <button type="button" id="btnBulkUnverify" class="btn-warning-custom" disabled>
                                        <i class="fas fa-undo"></i> Unverifikasi Terpilih (<span id="countUnverify">0</span>)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-header-daftarpegawai">
                        <div class="card-title-daftarpegawai">
                            <i class="fas fa-table me-2"></i> Data Pegawai
                        </div>
                    </div>

                    <div class="table-container" style="border-radius: 0; border-left: 0; border-right: 0; border-bottom: 0; box-shadow: none;">
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" id="checkAll" style="cursor: pointer;">
                                        </th>
                                        <th width="50">NO</th>
                                        <th>NUP</th>
                                        <th>NAMA</th>
                                        <th>PENEMPATAN</th>
                                        <th>JABATAN</th>
                                        <th>DEPARTEMEN</th>
                                        <th>SUB DEPARTEMEN</th>
                                        <th>PENILAI</th>
                                        <th>VERIFIKATOR</th>
                                        <th>STATUS</th>
                                        <th width="60">NILAI</th>
                                        <th width="140">PREDIKAT</th>
                                        <th class="th-verifikasi">STATUS VERIFIKASI</th>
                                        <th width="110" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penilaians as $index => $row)
                                        @php
                                            $statusPenilaian = strtoupper((string) data_get($row, 'status_nilai', 'BELUM DIISI'));
                                            $statusVerifikator = strtoupper((string) data_get($row, 'status_verifikator', ''));
                                            $nilaiAkhir = is_numeric(data_get($row, 'nilai_akhir')) ? (float) data_get($row, 'nilai_akhir') : 0.00;
                                            $predikat = data_get($row, 'predikat', 'Mengecewakan');
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="row-checkbox" value="{{ data_get($row, 'penilaian_id', $row->id ?? '') }}" style="cursor: pointer;">
                                            </td>
                                            <td>{{ (($penilaians->currentPage() - 1) * $penilaians->perPage()) + $loop->iteration }}</td>
                                            <td><code>{{ data_get($row, 'nup', '-') }}</code></td>
                                            <td><strong>{{ data_get($row, 'nama', '-') }}</strong></td>
                                            <td>{{ data_get($row, 'penempatan', '-') }}</td>
                                            <td>{{ data_get($row, 'jabatan', '-') }}</td>
                                            <td>{{ data_get($row, 'departemen', '-') }}</td>
                                            <td>{{ data_get($row, 'departement_sub', data_get($row, 'sub_departemen', '-')) }}</td>
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
                                                @if(in_array($statusPenilaian, ['DIAJUKAN', 'SUBMIT']))
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
                                                @if(!empty($predikat) && $predikat !== 'Mengecewakan')
                                                    <span class="badge-status-gray">{{ $predikat }}</span>
                                                @else
                                                    <span class="badge-status-danger">Mengecewakan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(in_array($statusVerifikator, ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']))
                                                    <span class="badge-status-success">Verified</span>
                                                @elseif(in_array($statusVerifikator, ['KOREKSI', 'PERLU DITINJAU']))
                                                    <span class="badge-status-danger">Ada Koreksi</span>
                                                @elseif(in_array($statusVerifikator, ['REVISI', 'DIKEMBALIKAN']))
                                                    <span class="badge-status-warning">Revisi</span>
                                                @else
                                                    <span class="badge-status-gray">{{ $statusVerifikator ?: '-' }}</span>
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
                                            <td colspan="15" class="text-center py-4 text-muted">Belum ada data penilaian pada periode ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($penilaians instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $penilaians->hasPages())
                        @php
                            $currentPage = $penilaians->currentPage();
                            $lastPage = $penilaians->lastPage();
                            $firstItem = $penilaians->firstItem() ?? 0;
                            $lastItem = $penilaians->lastItem() ?? 0;
                            $total = $penilaians->total();

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
                                    <a href="{{ $penilaians->url($currentPage - 1) }}" class="page-arrow" aria-label="Previous page">&lsaquo;</a>
                                @else
                                    <span class="page-arrow disabled" aria-hidden="true">&lsaquo;</span>
                                @endif

                                @foreach ($pageNumbers as $page)
                                    @if ($page == $currentPage)
                                        <span class="page-number active" aria-current="page">{{ $page }}</span>
                                    @else
                                        <a href="{{ $penilaians->url($page) }}" class="page-number">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($lastPage > 5 && $currentPage < $lastPage - 2)
                                    <span class="page-ellipsis">…</span>
                                @endif

                                @if ($currentPage < $lastPage)
                                    <a href="{{ $penilaians->url($currentPage + 1) }}" class="page-arrow" aria-label="Next page">&rsaquo;</a>
                                @else
                                    <span class="page-arrow disabled" aria-hidden="true">&rsaquo;</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <script>
        window.selectedPeriodeData = null;

        document.addEventListener('DOMContentLoaded', function() {
            const selectPeriode = document.getElementById('periodeSelector');
            if (selectPeriode) {
                selectPeriode.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Script Checkbox & Mass Actions Counter
            const checkAll = document.getElementById('checkAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const btnVerify = document.getElementById('btnBulkVerify');
            const btnUnverify = document.getElementById('btnBulkUnverify');
            const countVerify = document.getElementById('countVerify');
            const countUnverify = document.getElementById('countUnverify');

            function updateCounters() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                if (countVerify) countVerify.textContent = checkedCount;
                if (countUnverify) countUnverify.textContent = checkedCount;

                if (checkedCount > 0) {
                    if (btnVerify) btnVerify.removeAttribute('disabled');
                    if (btnUnverify) btnUnverify.removeAttribute('disabled');
                } else {
                    if (btnVerify) btnVerify.setAttribute('disabled', 'true');
                    if (btnUnverify) btnUnverify.setAttribute('disabled', 'true');
                }
            }

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    rowCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateCounters();
                });
            }

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked && checkAll) {
                        checkAll.checked = false;
                    }
                    updateCounters();
                });
            });
        });
    </script>

    @endsection