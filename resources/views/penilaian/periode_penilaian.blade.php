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
        --overdue-bg: #Ffffff;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background-color: var(--bg-page);
        font-family: var(--font-main);
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
    }

    /* --- Container Utama --- */
    .periode-card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        margin: 1.5rem auto;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* --- Breadcrumb Header --- */
    .breadcrumb-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--maroon-primary);
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

    /* --- Header Toolbar Container --- */
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
        overflow: hidden;
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

    .toolbar-icon-wrapper .icon-svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0;
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

    /* --- Action Controls & Button Styling --- */
    .action-controls {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        flex-wrap: wrap;
    }

    .action-controls .btn, .modal-footer .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        border: 1px solid transparent;
        outline: none;
    }

    /* Base style tombol disabled */
    .action-controls .btn:disabled,
    .action-controls .btn[disabled] {
        background-color: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        opacity: 0.6 !important;
    }

    /* Variants Button Controls */
    .btn-add {
        background-color: var(--maroon-primary) !important;
        color: #ffffff !important;
    }
    .btn-add:hover {
        background-color: var(--maroon-hover) !important;
        box-shadow: 0 4px 12px var(--maroon-glow);
    }

    .btn-detail:not(:disabled) {
        background-color: #0284C7 !important;
        color: #ffffff !important;
    }
    .btn-detail:not(:disabled):hover {
        background-color: #0369A1 !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    .btn-toggle-lock:not(:disabled) {
        background-color: #4F46E5 !important;
        color: #ffffff !important;
    }
    .btn-toggle-lock:not(:disabled):hover {
        background-color: #4338CA !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .btn-generate:not(:disabled) {
        background-color: #0D9488 !important;
        color: #ffffff !important;
    }
    .btn-generate:not(:disabled):hover {
        background-color: #0F766E !important;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
    }

    .btn-edit:not(:disabled) {
        background-color: #D97706 !important;
        color: #ffffff !important;
    }
    .btn-edit:not(:disabled):hover {
        background-color: #B45309 !important;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
    }

    .btn-delete:not(:disabled) {
        background-color: #E11D48 !important;
        color: #ffffff !important;
    }
    .btn-delete:not(:disabled):hover {
        background-color: #BE123C !important;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
    }

    svg {
        max-width: none;
    }

    .icon-svg {
        width: 1.25rem !important;
        height: 1.25rem !important;
    }

    .icon-svg-sm {
        width: 1rem !important;
        height: 1rem !important;
    }

    /* --- Data Table Container & Styling --- */
    .table-container {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }

    .table-responsive { overflow-x: auto; }

    .table-periode {
        width: 100%;
        font-size: 0.8125rem;
        text-align: left;
        border-collapse: collapse;
        margin: 0;
    }

    .table-periode thead {
        background-color: var(--maroon-primary);
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.725rem;
    }

    .table-periode th, .table-periode td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .table-periode tbody tr {
        border-bottom: 1px solid #F1F5F9;
        transition: background-color 0.15s;
        cursor: pointer;
    }

    .table-periode tbody tr:hover { background-color: #FDF7F9; }
    .table-periode tbody tr.selected-row { background-color: #F1F5F9 !important; }
    .tr-overdue { background-color: var(--overdue-bg) !important; }

    /* Badge & Progress Bar */
    .badge-status {
        padding: 0.375rem 0.875rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.02em;
        border: 1.5px solid transparent;
    }

    .badge-open {
        background-color: var(--badge-emerald-bg);
        color: var(--badge-emerald-text);
        border-color: var(--badge-emerald-border);
    }

    .badge-locked {
        background-color: var(--badge-rose-bg);
        color: var(--badge-rose-text);
        border-color: var(--badge-rose-border);
    }

    .badge-count {
        background-color: var(--maroon-soft);
        color: var(--maroon-primary);
        border: 1px solid var(--maroon-border);
        padding: 0.25rem 0.625rem;
        border-radius: var(--radius-md);
        font-weight: 800;
        font-size: 0.75rem;
    }

    .tag-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }

    .tag-red { background-color: var(--badge-rose-bg); color: var(--badge-rose-text); }
    .tag-orange { background-color: var(--badge-orange-bg); color: var(--badge-orange-text); }
    .tag-green { background-color: var(--badge-emerald-bg); color: var(--badge-emerald-text); }

    .progress-container {
        background: #E2E8F0;
        border-radius: 10px;
        height: 14px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #F59E0B 0%, #10B981 100%);
        transition: width 0.3s ease;
    }

    .progress-text {
        position: absolute;
        right: 6px;
        top: -1px;
        font-size: 0.68rem;
        font-weight: 800;
        color: #1A202C;
    }

    /* =========================================================
       --- MODAL BACKDROP & DIALOG STYLING (MODERN BLUR) ---
       ========================================================= */
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.25s ease-in-out, visibility 0.25s ease-in-out;
    }

    .modal-backdrop.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .modal-card {
        background: var(--surface);
        width: 100%;
        max-width: 540px;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-color);
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        transform: scale(0.95) translateY(10px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-backdrop.active .modal-card {
        transform: scale(1) translateY(0);
    }

    /* Modal Header */
    .modal-header {
        padding: 1.25rem 1.5rem;
        background: var(--surface);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-icon-badge {
        padding: 0.5rem;
        background: var(--maroon-soft);
        color: var(--maroon-primary);
        border-radius: var(--radius-md);
        border: 1px solid var(--maroon-border);
        display: inline-flex;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .btn-close-modal {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.375rem;
        display: flex;
        transition: all 0.15s ease;
    }

    .btn-close-modal:hover {
        background-color: #F1F5F9;
        color: var(--text-primary);
    }

    /* Modal Body & Custom Input Group */
    .modal-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .custom-input-group {
        display: flex;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .custom-input-group:focus-within {
        border-color: var(--maroon-primary);
        box-shadow: 0 0 0 3px var(--maroon-glow);
    }

    .custom-input-group .input-label-addon {
        background-color: #F8FAFC;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.625rem 1rem;
        border-right: 1px solid var(--border-color);
        min-width: 140px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .custom-input-group .form-control,
    .custom-input-group .form-select {
        border: none !important;
        border-radius: 0 !important;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: var(--text-primary);
        outline: none !important;
        box-shadow: none !important;
        width: 100%;
        background-color: transparent;
    }

    /* Modal Footer */
    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        background-color: #F8FAFC;
        border-top: 1px solid var(--border-color);
    }

    .btn-cancel {
        background-color: #F1F5F9;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .btn-cancel:hover {
        background-color: #E2E8F0;
        color: var(--text-primary);
    }

    /* 1. Sekat HANYA untuk isi data tabel (td) */
    .table-periode td {
        border-right: 1px solid #a4a8abab;  
    }

    /* 2. Header maroon (th) TANPA sekat garis abu-abu */
    .table-periode th {
        border-right: none !important;
    }

    /* 3. Hilangkan sekat pada kolom data terakhir */
    .table-periode td:last-child {
        border-right: none;
    }

    /* 4. Garis bawah header (Perbaiki nama variabelnya, misal: --maroon-hover atau --maroon-primary) */
    .table-periode thead {
        border-bottom: 2px solid var(--maroon-hover) !important;
    }

    /* Garis pembatas horizontal antar baris tabel */
    .data-table tbody td,
    .table-periode tbody td {
        border-bottom: 1px solid #a4a8abab !important; /* Warna garis abu-abu halus */
    }

    /* Hilangkan garis bawah pada baris terakhir agar sudut bawah tabel tetap rapi */
    .data-table tbody tr:last-child td,
    .table-periode tbody tr:last-child td {
        border-bottom: none !important;
    }

    .modal-backdrop-confirm.active {
        display: flex !important;
        transform: scale(1) translateY(0);
    }

    /* Kartu Konfirmasi Dialog */
    .confirm-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        width: 100%;
        max-width: 440px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: modalPop 0.2s ease-out forwards;
    }

    @keyframes modalPop {
        from { opacity: 0; transform: scale(0.92); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Lingkaran Ikon */
    .confirm-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
    }

    .icon-warning-circle {
        border: 3px solid #38bdf8;
        color: #0284c7;
    }

    .icon-danger-circle {
        border: 3px solid #fca5a5;
        color: #dc2626;
    }

    .confirm-icon-text {
        font-size: 2rem;
        font-weight: bold;
        line-height: 1;
    }

    .confirm-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .confirm-message {
        font-size: 0.95rem;
        color: #475569;
        margin-bottom: 0.25rem;
    }

    .confirm-subtext {
        font-size: 0.85rem;
        color: #dc2626;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Tombol Aksi */
    .confirm-actions {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .btn-confirm-yes {
        background-color: #eab308;
        color: #ffffff;
        border: none;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-confirm-yes:hover { background-color: #ca8a04; }

    .btn-confirm-danger {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-confirm-danger:hover { background-color: #b91c1c; }

    .btn-confirm-cancel {
        background-color: #64748b;
        color: #ffffff;
        border: none;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-confirm-cancel:hover { background-color: #475569; }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Garis & warna saat baris aktif diklik */
    .table-periode tbody tr.table-active-row {
        background-color: #fce8ec !important;
        outline: 1px solid var(--maroon-primary, #800020);
    }
</style>

<div class="periode-card">
    <!-- Breadcrumb Title -->
    <div class="breadcrumb-title">
        Penilaian <span>» Periode Penilaian</span>
        <span class="breadcrumb-badge">PRIMA</span>
    </div>

    <!-- Toolbar Section -->
    <div class="toolbar-card">
        <div class="toolbar-header">
            <div class="toolbar-icon-wrapper">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="toolbar-title">Periode Penilaian</h1>
                <p class="toolbar-subtitle">
                    Total Periode: <span class="toolbar-subtitle-count">{{ $periodeList->total() }} Data</span>
                </p>
            </div>
        </div>     
        
        <div class="action-controls">
            <!-- 1. Tambah Data -->
            <button type="button" id="btnAdd" onclick="openModalTambahPeriode()" class="btn btn-add">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </button>

            <!-- 2. Detail Periode -->
            <button type="button" id="btnDetailPeriode" onclick="openDetailPeriode()" class="btn btn-detail" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Detail
            </button>

            <!-- 3. Buka/Kunci Periode -->
            <button type="button" id="btnToggleLock" onclick="toggleLockPeriode()" class="btn btn-toggle-lock" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Buka/Kunci
            </button>

            <!-- 4. Generate Alokasi -->
            <button type="button" id="btnGenerate" onclick="generateAlokasi()" class="btn btn-generate" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Generate
            </button>

            <!-- 5. Ubah -->
            <button type="button" id="btnEditPeriode" onclick="openModalEditPeriode()" class="btn btn-edit" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Ubah
            </button>

            <!-- 6. Hapus -->
            <button type="button" id="btnDeletePeriode" onclick="deletePeriodeRow()" class="btn btn-delete" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table-periode">
                <thead>
                    <tr>
                        <th class="text-center" width="4%">NO</th>
                        <th width="14%">PERIODE</th>
                        <th width="11%">TGL MULAI</th>
                        <th width="11%">TGL AKHIR</th>
                        <th class="text-center" width="8%">STATUS</th>
                        <th class="text-center" width="10%">TOTAL PEGAWAI</th>
                        <th class="text-center" width="9%">TOTAL SATKER</th>
                        <th width="16%">PROGRESS VERIFIKASI</th>
                        <th width="17%">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse($periodeList as $index => $item)
                        @php
                            $statusUpper = strtoupper($item->status ?? '');
                            $isOverdue = ($statusUpper === 'OPEN' && $item->tanggal_deadline?->isPast());
                        @endphp

                        <!-- TAG TR YANG SUDAH DILENGKAPI EVENT ONCLICK & PAYLOAD JSON -->
                        <tr class="{{ $isOverdue ? 'tr-overdue' : '' }} cursor-pointer" 
                            data-periode-id="{{ $item->periode_id }}"
                            data-nama-periode="{{ $item->nama_periode }}"
                            data-tanggal-mulai="{{ $item->tanggal_mulai?->format('Y-m-d') }}"
                            data-tanggal-deadline="{{ $item->tanggal_deadline?->format('Y-m-d') }}"
                            data-status="{{ $statusUpper }}"
                            onclick="selectRow(this)">
                            
                            <td class="text-center font-monospace">{{ $periodeList->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $item->nama_periode }}</td>
                            <td>{{ $item->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $item->tanggal_deadline?->format('d M Y') ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-status {{ $statusUpper === 'OPEN' ? 'badge-open' : 'badge-locked' }}">
                                    {{ $statusUpper ?: 'LOCKED' }}
                                </span>
                            </td>
                            <td class="text-center"><span class="badge-count">{{ $item->total_pegawai }}</span></td>
                            <td class="text-center"><span class="badge-count">{{ $item->total_satker }}</span></td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar-fill" style="width: {{ $item->progress_verifikasi }}%;"></div>
                                    <span class="progress-text">{{ $item->progress_verifikasi }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($item->total_pegawai === 0)
                                        <span class="tag-badge tag-orange">Belum generate</span>
                                    @else
                                        <span class="tag-badge tag-green">Sudah Generate</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data alokasi periode penilaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Menampilkan {{ $periodeList->firstItem() ?? 0 }} sampai {{ $periodeList->lastItem() ?? 0 }} dari {{ $periodeList->total() }} data
        </small>
        <div>
            {{ $periodeList->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Periode -->
<div class="modal-backdrop" id="modalTambahPeriode">
    <div class="modal-card">
        
        <!-- Header Modal -->
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h5 class="modal-title">Tambah Periode</h5>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalTambahPeriode()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('periode.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                
                <!-- Input Bulan -->
                <div class="custom-input-group">
                    <span class="input-label-addon">Bulan</span>
                    <select name="bulan" class="form-select" required>
                        <option value="" selected disabled>- Pilih Bulan -</option>
                        @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $key => $namaBulan)
                            <option value="{{ $key }}">{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Tahun -->
                <div class="custom-input-group">
                    <span class="input-label-addon">Tahun</span>
                    <input type="number" name="tahun" class="form-control" placeholder="Pilih Tahun (misal: {{ date('Y') }})" min="2020" max="2099" required>
                </div>

                <!-- Input Tanggal Mulai -->
                <div class="custom-input-group">
                    <span class="input-label-addon">Tanggal Mulai</span>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>

                <!-- Input Tanggal Akhir -->
                <div class="custom-input-group">
                    <span class="input-label-addon">Tanggal Akhir</span>
                    <input type="date" name="tanggal_deadline" class="form-control" required>
                </div>

                <input type="hidden" name="status" value="LOCKED">
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModalTambahPeriode()">Batal</button>
                <button type="submit" class="btn btn-add">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openModalTambahPeriode() {
        document.getElementById('modalTambahPeriode').classList.add('active');
    }

    function closeModalDeletePeriode() {
        const modal = document.getElementById('modalDeletePeriode');
        if (modal) {
            modal.classList.remove('active');
        }
    }
</script>

<!-- Modal Ubah Periode -->
<div class="modal-backdrop" id="modalEditPeriode">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <h5 class="modal-title">Ubah Periode Penilaian</h5>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalEditPeriode()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formEditPeriode" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="custom-input-group">
                    <span class="input-label-addon">Periode</span>
                    <input type="text" id="edit_nama_periode" class="form-control" readonly style="background-color: #f8fafc;">
                </div>

                <div class="custom-input-group">
                    <span class="input-label-addon">Tanggal Mulai</span>
                    <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control" required>
                </div>

                <div class="custom-input-group">
                    <span class="input-label-addon">Tanggal Akhir</span>
                    <input type="date" name="tanggal_deadline" id="edit_tanggal_deadline" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModalEditPeriode()">Batal</button>
                <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Buka/Kunci -->
<div class="modal-backdrop" id="modalToggleLock">
    <div class="confirm-card">
        <div class="confirm-icon-wrapper icon-warning-circle">
            <span class="confirm-icon-text">?</span>
        </div>
        <h3 class="confirm-title">Konfirmasi</h3>
        <p class="confirm-message">Ubah status periode <strong id="lock_nama_periode">-</strong> menjadi <strong id="lock_target_status">-</strong>?</p>
        
        <form id="formToggleLock" method="POST" class="confirm-actions">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-confirm-yes" id="btnConfirmLockAction">Ya, Ubah!</button>
            <button type="button" class="btn-confirm-cancel" onclick="closeModalToggleLock()">Batal</button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus (Versi Simpel Tanpa Captcha) -->
<div class="modal-backdrop" id="modalDeletePeriode">
    <div class="confirm-card">
        <div class="confirm-icon-wrapper icon-danger-circle">
            <span class="confirm-icon-text">!</span>
        </div>
        <h3 class="confirm-title">Konfirmasi Hapus</h3>
        <p class="confirm-message">Yakin akan menghapus periode <strong id="delete_nama_periode">-</strong> beserta SEMUA data penilaian?</p>
    

        <form id="formDeletePeriode" method="POST" class="confirm-actions">
            @csrf
            @method('DELETE')
            
            <!-- Tombol Ya, Hapus -->
            <button type="submit" class="btn-confirm-danger">Ya, Hapus!</button>

            <!-- Tombol Batal (Wajib type="button" agar tidak mensubmit form) -->
            <button type="button" class="btn-confirm-cancel" onclick="closeModalDeletePeriode()">Batal</button>
        </form>
    </div>
</div>

<script>
    let selectedPeriodeData = null;

    // Fungsi Klik Baris Tabel + Fitur UNCLICK
    function selectRow(trElement) {
        const clickedPeriodeId = trElement.dataset.periodeId;

        // JIKA BARIS YANG SAMA DIKLIK KEMBALI -> LAKUKAN UNCLICK (DESELECT)
        if (selectedPeriodeData && selectedPeriodeData.periode_id === clickedPeriodeId) {
            trElement.classList.remove('table-active-row');
            selectedPeriodeData = null;
            disableAllToolbarButtons();
            console.log("Pilihan dibatalkan (Unclick).");
            return;
        }

        // BISA DIKLIK BARIS BARU
        document.querySelectorAll('.table-periode tbody tr').forEach(row => {
            row.classList.remove('table-active-row');
        });

        trElement.classList.add('table-active-row');

        selectedPeriodeData = {
            periode_id: trElement.dataset.periodeId,
            nama_periode: trElement.dataset.namaPeriode,
            tanggal_mulai: trElement.dataset.tanggalMulai,
            tanggal_deadline: trElement.dataset.tanggalDeadline,
            status: trElement.dataset.status
        };

        // Aktifkan semua tombol toolbar
        const btnIds = ['btnDetailPeriode', 'btnToggleLock', 'btnGenerate', 'btnEditPeriode', 'btnDeletePeriode'];
        btnIds.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = false;
        });
    }

    // Reset tombol toolbar ke mode disabled
    function disableAllToolbarButtons() {
        const btnIds = ['btnDetailPeriode', 'btnToggleLock', 'btnGenerate', 'btnEditPeriode', 'btnDeletePeriode'];
        btnIds.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = true;
        });
    }

    // Modal Tambah
    function openModalTambahPeriode() {
        document.getElementById('modalTambahPeriode').classList.add('active');
    }
    function closeModalTambahPeriode() {
        document.getElementById('modalTambahPeriode').classList.remove('active');
    }

 // Sesuaikan baseUrl dengan prefix route kamu: /penilaian/periode-penilaian
const baseUrl = "{{ url('/penilaian/periode-penilaian') }}";

// 1. Action Form Edit (PUT)
function openModalEditPeriode() {
    if (!selectedPeriodeData) return;

    document.getElementById('edit_nama_periode').value = selectedPeriodeData.nama_periode;
    document.getElementById('edit_tanggal_mulai').value = selectedPeriodeData.tanggal_mulai;
    document.getElementById('edit_tanggal_deadline').value = selectedPeriodeData.tanggal_deadline;

    // Menghasilkan URL: /penilaian/periode-penilaian/2026-01
    document.getElementById('formEditPeriode').action = `${baseUrl}/${selectedPeriodeData.periode_id}`;
    document.getElementById('modalEditPeriode').classList.add('active');
}

function closeModalEditPeriode() {
    document.getElementById('modalEditPeriode').classList.remove('active');
}

// 2. Action Form Toggle Lock (PATCH)
function toggleLockPeriode() {
    if (!selectedPeriodeData) return;

    const currentStatus = (selectedPeriodeData.status || '').toUpperCase();
    const targetStatus = (currentStatus === 'OPEN') ? 'DIKUNCI (LOCKED)' : 'DIBUKA (OPEN)';

    document.getElementById('lock_nama_periode').innerText = selectedPeriodeData.nama_periode;
    document.getElementById('lock_target_status').innerText = targetStatus;

    // Menghasilkan URL: /penilaian/periode-penilaian/2026-01/toggle-lock
    document.getElementById('formToggleLock').action = `${baseUrl}/${selectedPeriodeData.periode_id}/toggle-lock`;
    document.getElementById('modalToggleLock').classList.add('active');
}
function closeModalToggleLock() {
    document.getElementById('modalToggleLock').classList.remove('active');
}

// 3. Action Form Hapus (DELETE)
function deletePeriodeRow() {
    if (!selectedPeriodeData) return;

    document.getElementById('delete_nama_periode').innerText = selectedPeriodeData.nama_periode;

    // Menghasilkan URL: /penilaian/periode-penilaian/2026-01
    document.getElementById('formDeletePeriode').action = `${baseUrl}/${selectedPeriodeData.periode_id}`;
    document.getElementById('modalDeletePeriode').classList.add('active');
}
function closeModalDeletePeriode() {
    document.getElementById('modalDeletePeriode').classList.remove('active');
}
</script>

@endsection