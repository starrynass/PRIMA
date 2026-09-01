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

    /* --- Action Buttons --- */
   /* 1. KUNCI KELUARGA TOMBOL AGAR TIDAK TURUN BARIS & TIDAK TERPOTONG */
.action-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: nowrap !important; /* Mencegah tombol turun ke bawah */
    overflow: visible !important; /* Mencegah tombol di paling kanan terpotong */
}

/* 2. HEADER ATAS & CARD HEADER PARENT */
/* Container Utam Header Struktur Template */
.card-header-structure {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 0.75rem 1.1rem;
    background: #ffffff;
    border: 2px solid #a4a8abab;
    border-radius: 8px;
    margin-top: 1rem;
    margin-bottom: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* Sisi Kiri & Sisi Kanan Sejajar Horizontal */
.header-left-group,
.header-right-group {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    flex-wrap: nowrap !important;
}

/* Judul Struktur Template */
.structure-title {
    margin: 0 !important;
    font-size: 0.925rem;
    font-weight: 700;
    color: #800020; /* Color Maroon */
    display: flex;
    align-items: center;
    gap: 0.4rem;
    white-space: nowrap;
}

/* BADGE KATEGORI MODERN & MENARIK */
.category-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
    color: #9d174d;
    border: 1px solid #fbcfe8;
    padding: 0.25rem 0.65rem;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(157, 23, 77, 0.05);
    white-space: nowrap;
}

.category-badge-pill .badge-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    background-color: #be185d;
    color: #ffffff;
    border-radius: 50%;
    font-size: 0.65rem;
}

.category-badge-pill .badge-text strong {
    font-weight: 800;
    font-size: 0.85rem;
}

/* 3. STYLE BUTTON BASE (Kombinasi style kamu yang sudah bagus) */
.btn-group { 
    display: flex; 
    align-items: center;
    gap: 0.5rem; 
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.5rem 0.85rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.825rem;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid transparent;
    white-space: nowrap !important; /* Supaya teks tombol tidak patah dua baris */
}

/* --- TAMPILAN KEUANGAN & STYLING WARNA DARI KODE KAMU --- */

.btn-refresh {
    background-color: #f1f5f9 !important; 
    color: #1e293b !important;          
    border: 1.5px solid #919dad !important; 
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.08); 
    transition: all 0.2s ease-in-out;
}

.btn-refresh:hover {
    background-color: #e2e8f0 !important;
    border-color: #64748b !important;
    color: #0f172a !important;
}

.btn-add-template {
    background-color: var(--maroon-primary, #800020);
    color: #ffffff;
}
.btn-add-template:hover {
    background-color: #660019;
    box-shadow: 0 4px 12px rgba(128, 0, 32, 0.25);
}

.btn-amber-template {
    background-color: #D97706 !important;
    color: #FFFFFF !important;
    border: none;
    transition: all 0.2s ease-in-out;
}

.btn-amber-template:hover {
    background-color: #B45309 !important;
    box-shadow: 0 4px 6px -1px rgba(217, 119, 6, 0.3);
}

.btn-preview-template { background: #0284C7; color: #ffffff; }
.btn-preview-template:hover { background: #0369A1; }

/* STATE DISABLED (Global untuk semua tombol disabled) */
.btn:disabled,
.btn[disabled] {
    background-color: #f1f5f9 !important;
    color: #94a3b8 !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    opacity: 0.6 !important;
}

/* STATE EDIT & DELETE (AKTIF) */
.btn-edit:not(:disabled) {
    background-color: #D97706 !important;
    color: #ffffff !important;
    border-color: #D97706 !important;
    cursor: pointer !important;
    opacity: 1 !important;
}

.btn-edit:not(:disabled):hover {
    background-color: #B45309 !important;
    border-color: #B45309 !important;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
}

.btn-delete:not(:disabled) {
    background-color: #E11D48 !important;
    color: #ffffff !important;
    border-color: #E11D48 !important;
    cursor: pointer !important;
    opacity: 1 !important;
}

.btn-delete:not(:disabled):hover {
    background-color: #BE123C !important;
    border-color: #BE123C !important;
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
}

    .layout-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.25rem;
        align-items: start;
    }

    /* --- Card Components --- */
    .card-box {
        border: 2px solid #a4a8abab;
        border-radius: 8px;
        background: #ffffff;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .card-header-soft {
        background: var(--bg-light);
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #a4a8abab;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--maroon-primary);
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

       /* --- MODAL DIALOG & BACKDROP STYLING --- */
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
        pointer-events: none; /* MEMASTIKAN KLIK TEMBUS SAAT MODAL SEMBUNYI */
        transition: opacity 0.25s ease-in-out, visibility 0.25s ease-in-out;
    }

    .modal-backdrop.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto; /* MENGAKTIFKAN KLIK SAAT MODAL MUNCUL */
    }

    .modal-card {
    background: #ffffff;
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
    background: #ffffff;
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

.modal-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.125rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.form-label {
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--text-secondary);
}

.text-danger { color: #E11D48; }

.input-addon-group {
    display: flex;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.input-addon-group:focus-within {
    border-color: var(--maroon-primary);
    box-shadow: 0 0 0 3px var(--maroon-glow);
}

.input-addon {
    background-color: #F8FAFC;
    color: var(--text-secondary);
    font-size: 0.8125rem;
    font-weight: 600;
    padding: 0 0.875rem;
    display: flex;
    align-items: center;
    border-right: 1px solid var(--border-color);
    white-space: nowrap;
    min-width: 5rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: none;
    outline: none;
    font-family: inherit;
    font-size: 0.875rem;
    color: var(--text-primary);
}

.form-input::placeholder {
    color: #CBD5E1;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color); /* Tambahkan garis pemisah atas */
    background-color: #FAFAFA; /* Opsional: beri sedikit latar lembut */
}

    .icon-svg { width: 1.15rem; height: 1.15rem; }
    .icon-svg-sm { width: 14px; height: 14px; }

    /* Merapikan Select2 di dalam modal & input-addon-group */
 /* Container Input / Box Utama */
.select2-container--default .select2-selection--multiple {
    border: none !important;
    background: transparent !important;
    min-height: 38px !important;
    padding: 2px 6px !important;
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 6px !important;
}

/* Badge Opsi Yang Dipilih */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #F1F5F9 !important; /* Latar abu-abu soft */
    color: #334155 !important;            /* Warna teks abu-abu gelap */
    border: 1px solid #E2E8F0 !important;
    border-radius: 6px !important;
    padding: 3px 8px 3px 20px !important;
    font-size: 0.8125rem !important;
    margin: 0 !important;
}

/* Tombol Silang (x) */
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #64748B !important;
    border: none !important;
    left: 4px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #0F172A !important;
    background: transparent !important;
}

/* Dropdown List */
.select2-dropdown {
    border: 1px solid #E2E8F0 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    z-index: 99999 !important;
}

/* CARD SKALA KANAN */
.card-skala {
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    background: #ffffff;
}

/* Header Card dengan Soft Maroon / Cream Light */
.card-skala-header {
    background-color: var(--maroon-primary);
    border-bottom: 1px solid #FEE2E2;
    padding: 10px 16px;
}

.card-skala-title {
    color: #ffffff; /* Maroon khas header kamu */
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Item List */
.skala-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.2s ease;
}

.skala-item:last-child {
    border-bottom: none;
}

.skala-item:hover {
    background-color: #FAFAFA;
}

.badge-skala-code {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    flex-shrink: 0;
}

/* Variasi Warna Badge yang Soft & Netral */
.skala-item:nth-child(1) .badge-skala-code { background-color: #10B981; } /* Hijau */
.skala-item:nth-child(2) .badge-skala-code { background-color: #2563EB; } /* Biru */
.skala-item:nth-child(3) .badge-skala-code { background-color: #0891B2; } /* Cyan */
.skala-item:nth-child(4) .badge-skala-code { background-color: #F59E0B; } /* Amber/Oranye */
.skala-item:nth-child(5) .badge-skala-code { background-color: #EF4444; } /* Merah */

/* Teks & Angka */
.skala-name {
    color: #334155;
    font-size: 0.85rem;
    font-weight: 500;
}

.skala-score {
    color: #0284C7; /* Biru soft khas elemen nilai */
    font-size: 0.875rem;
    font-weight: 700;
}

/* Empty State */
.skala-empty {
    padding: 16px;
    text-align: center;
    color: #94A3B8;
    font-size: 0.8125rem;
}

.modal-backdrop-delete {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(4px);
    display: none; /* DEFAULT SEMBUNYI TOTAL */
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-backdrop-delete.active {
    display: flex !important;
}

.delete-card {
    background: #FFFFFF;
    width: 90%;
    max-width: 400px;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    position: relative;
    animation: deletePop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.delete-accent-bar {
    height: 5px;
    background: linear-gradient(90deg, #EF4444 0%, #DC2626 100%);
    width: 100%;
}

/* Card Body */
.delete-card-body {
    padding: 2.25rem 2rem 1.75rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.delete-icon-wrapper {
    width: 64px;
    height: 64px;
    background-color: #FEF2F2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
    border: 8px solid #FFE4E6;
}


.delete-icon-svg {
    width: 32px;
    height: 32px;
    color: #E11D48;
}

/* Typography */
.delete-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1E293B;
    margin: 0 0 0.5rem 0;
}

.delete-message {
    font-size: 0.95rem;
    color: #64748B;
    margin: 0 0 1.75rem 0;
    line-height: 1.5;
}

.delete-message strong {
    color: #0F172A;
    font-weight: 700;
}

/* Actions Group */
.delete-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.875rem;
    width: 100%;
}

/* Red Confirm Button */
.btn-delete-confirm {
    background-color: #E11D48;
    color: #FFFFFF;
    font-weight: 700;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
    padding: 0.65rem 1.75rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.25);
}

.btn-delete-confirm:hover {
    background-color: #BE123C;
    box-shadow: 0 6px 12px -2px rgba(225, 29, 72, 0.35);
    transform: translateY(-1px);
}

/* Gray Cancel Button */
.btn-delete-cancel {
    background-color: #F1F5F9;
    color: #475569;
    font-weight: 700;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
    padding: 0.65rem 1.75rem;
    border-radius: 8px;
    border: 1px solid #a9acb1;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-delete-cancel:hover {
    background-color: #E2E8F0;
    color: #1E293B;
}

/* Entry Animation */
@keyframes deletePop {
    0% {
        opacity: 0;
        transform: scale(0.9) translateY(10px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}


</style>
<div class="main-wrapper">
    <!-- HEADER BAR ATAS -->
    <div class="header-bar d-flex justify-content-between align-items-center mb-3">
        <div class="breadcrumb-title">
            Master Penilaian <span>» Template, Fase & Pertanyaan</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-refresh" onclick="window.location.reload()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui
            </button>
            <button type="button" id="btnAddTemplate" onclick="openModalTambahTemplate()" class="btn btn-add-template">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Template
            </button>
            <button class="btn btn-preview-template" id="btnPreviewTemplate" disabled>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Preview
            </button>
        </div>
    </div>

    <!-- LAYOUT GRID PARENT -->
    <div class="layout-grid">
        <!-- KOLOM UTAMA (KIRI) -->
        <div>
            <div class="card-box mb-3">
                <div class="card-header-soft">
                    <div class="card-title">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                        Pilih Template
                    </div>
                </div>
                <div style="padding: 0.85rem 1rem;">
                    <div class="select-wrapper">
                        <form method="GET" action="{{ route('template-penilaian.index') }}" id="formSelectTemplate">
                            <select id="templateSelector" name="template_id" class="custom-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Template --</option>
                                @forelse($templates as $template)
                                    @php
                                        $isSelected = request('template_id') == $template->template_id;
                                    @endphp
                                    <option value="{{ $template->template_id }}" @selected($isSelected)>
                                        {{ $template->nama_template }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada template tersimpan</option>
                                @endforelse
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CARD HEADER STRUKTUR TEMPLATE -->
            <div class="card-header-structure">
                <div class="header-left-group">
                    <h6 class="structure-title">
                        <i class="fas fa-sitemap"></i> Struktur Template
                    </h6>
                    <div class="category-badge-pill" id="badge-total-kategori" style="{{ request('template_id') ? '' : 'display: none;' }}">
                        <span class="badge-icon"><i class="fas fa-layer-group"></i></span>
                        <span class="badge-text"><strong id="text-total-kategori-num">{{ isset($kategories) ? count($kategories) : 0 }}</strong> Kategori</span>
                    </div>
                </div>

                <div class="header-right-group">
                    <button type="button" class="btn btn-add-template btn-sm" id="btnTambahKategori" onclick="openModalTambahKategori()" {{ request('template_id') ? '' : 'disabled' }}>
                        <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Kategori
                    </button>
                    <button type="button" class="btn btn-edit btn-sm" id="btnEditTemplate" onclick="openModalEditTemplate()" {{ request('template_id') ? '' : 'disabled' }}>
                        <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Ubah Template
                    </button>
                    <button type="button" class="btn btn-delete btn-sm" id="btnDeleteTemplate" onclick="deleteTemplate()" {{ request('template_id') ? '' : 'disabled' }}>
                        <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Template
                    </button>
                </div>
            </div>

            @if(!$selectedTemplate)
                <div id="emptyStateBox" class="card-box empty-state mb-3">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    <p style="margin: 0; font-size: 0.85rem;">Pilih template di atas untuk melihat struktur kategori & pertanyaan</p>
                </div>
            @endif
        </div>

        <!-- KOLOM SIDEBAR SKALA NILAI (KANAN) -->
        <div>
            <div class="card card-skala">
                <div class="card-skala-header">
                    <h6 class="card-skala-title">
                        <i class="fas fa-star text-warning"></i> Skala Nilai
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($skalaNilai as $skala)
                        <div class="skala-item">
                            <div class="d-flex align-items-center">
                                <span class="badge-skala-code">{{ $skala->kode_nilai }}</span>
                                <span class="skala-name">{{ $skala->nama_nilai }}</span>
                            </div>
                            <span class="skala-score">{{ $skala->nilai_angka }}</span>
                        </div>
                    @empty
                        <div class="skala-empty">
                            Belum ada data skala nilai.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div> <!-- Penutup layout-grid -->
</div> <!-- Penutup main-wrapper --> 

<!-- MODAL FORM PREDIKAT -->
<div class="modal-backdrop" id="modalTemplate" onclick="closeModalTemplateOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge" id="modalIconBadgeTemplate">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="modalIconPathTemplate" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitleTemplate">Tambah Template Penilaian</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalTemplate()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formTemplate" method="POST" action="">
            @csrf
            <input type="hidden" id="formMethodTemplate" name="_method" value="POST">
            <input type="hidden" id="template_id" name="template_id">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="nama_template">Nama Template</label>
                    <div class="input-addon-group">
                        <span class="input-addon">Nama</span>
                        <input type="text" id="nama_template" name="nama_template" class="form-input" placeholder="contoh: Template DP3 Staff">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="occ_id">Jabatan <span class="text-danger">*</span></label>
                    <div class="input-addon-group">
                        <span class="input-addon">Jabatan</span>
                        <!-- Hapus class form-input, ganti dengan select2-jabatan -->
                        <select id="occ_id" name="occ_id[]" class="select2-jabatan" multiple="multiple" style="width: 100%;" required>
                            @foreach($occupation as $occ)
                                <option value="{{ $occ->occ_id }}">{{ $occ->occ_id }} - {{ $occ->occ_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label class="form-label" for="status_aktif">Status Aktif <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Status</span>
                            <select id="status_aktif" name="status_aktif" class="form-input" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-refresh" onclick="closeModalTemplate()">Batal</button>
                <button type="submit" class="btn btn-add-template" id="btnSubmitModalTemplate">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="btnSubmitTextTemplate">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalDeleteTemplate" class="modal-backdrop-delete" onclick="closeModalDeleteTemplateOnBackdrop(event)" style="display: none;">
    <div class="delete-card">
        <div class="delete-accent-bar"></div>
        <div class="delete-card-body">
            <div class="delete-icon-wrapper">
                <svg class="delete-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="delete-title">Konfirmasi Hapus</h3>
            <p class="delete-message">
                Anda yakin akan menghapus template <strong id="deleteTemplateName"></strong>?
            </p>
            <div class="delete-actions">
                <button type="button" class="btn-delete-confirm" onclick="confirmDeleteTemplate()">HAPUS</button>
                <button type="button" class="btn-delete-cancel" onclick="closeModalDeleteTemplate()">BATAL</button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-jabatan').select2({
            placeholder: " Pilih Jabatan...",
            allowClear: true,
            dropdownParent: $('#modalTemplate') // Wajib ada agar dropdown melayang di atas modal
        });
    });

    window.selectedTemplateData = null;
    window.templateList = @json($templates ?? []); 

    document.addEventListener('DOMContentLoaded', function() {
        const selectTemplate = document.getElementById('templateSelector');

        if (selectTemplate) {
            // Panggil handler saat pilihan dropdown berubah (Event Listener)
            if (selectTemplate.value) {
                onSelectTemplateChange(selectTemplate.value);
            }

            selectTemplate.addEventListener('change', function() {
                onSelectTemplateChange(this.value);
            });
        }
    });

    function onSelectTemplateChange(templateId) {
        const btnEdit = document.getElementById('btnEditTemplate');
        const btnDelete = document.getElementById('btnDeleteTemplate');
        const btnTambahKategori = document.getElementById('btnTambahKategori');
        const btnPreview = document.getElementById('btnPreviewTemplate');
        const actionButtons = [btnEdit, btnDelete, btnTambahKategori, btnPreview];

        if (templateId && templateId !== '') {
            const currentData = window.templateList.find(t => t.template_id == templateId);
            window.selectedTemplateData = currentData || { template_id: templateId };

            actionButtons.forEach(btn => {
                if (btn) {
                    btn.disabled = false;
                    btn.removeAttribute('disabled');
                    btn.classList.remove('disabled', 'opacity-50');
                }
            });
        } else {
            window.selectedTemplateData = null;

            actionButtons.forEach(btn => {
                if (btn) {
                    btn.disabled = true;
                    btn.setAttribute('disabled', 'disabled');
                    btn.classList.add('disabled', 'opacity-50');
                }
            });
        }
    }

    function openModalTambahTemplate() {
        const form = document.getElementById('formTemplate');
        if (form) form.reset();

        const inputId = document.getElementById('template_id');
        const inputMethod = document.getElementById('formMethodTemplate'); 

        if (inputId) inputId.value = '';
        if (inputMethod) inputMethod.value = 'POST';
        
        if (form) form.action = "{{ route('template-penilaian.store') }}";

        const title = document.getElementById('modalTitleTemplate');
        const btnText = document.getElementById('btnSubmitTextTemplate');
        if (title) title.textContent = 'Tambah Template Penilaian';
        if (btnText) btnText.textContent = 'Simpan Data';

        // Set Icon Modal ke Plus (+)
        const iconPath = document.getElementById('modalIconPathTemplate');
        const iconBadge = document.getElementById('modalIconBadgeTemplate');
        if (iconPath) iconPath.setAttribute('d', 'M12 4v16m8-8H4');
        if (iconBadge) {
            iconBadge.style.background = '';
            iconBadge.style.color = '';
        }

        // Reset Warna Tombol Submit
        const btnSubmit = document.getElementById('btnSubmitModalTemplate');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-amber-template');
            btnSubmit.classList.add('btn-add-template');
        }

        // Tampilkan Modal
        const modal = document.getElementById('modalTemplate');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

    function openModalEditTemplate() {
        if (!window.selectedTemplateData || !window.selectedTemplateData.template_id) {
            alert('Silakan pilih data Template terlebih dahulu!');
            return;
        }

        const data = window.selectedTemplateData;
        const form = document.getElementById('formTemplate');
        
        // PERBAIKAN 2: Mapping field disesuaikan dengan atribut data template
        document.getElementById('template_id').value = data.template_id;
        document.getElementById('nama_template').value = data.nama_template || '';
        document.getElementById('status_aktif').value = data.status_aktif || 'Aktif';

        // Bind data array jabatan ke Select2 Multi-select
        if (data.occ_id) {
            let occArray = Array.isArray(data.occ_id) ? data.occ_id : data.occ_id.toString().split(',');
            $('.select2-jabatan').val(occArray).trigger('change');
        }

        document.getElementById('formMethodTemplate').value = 'PUT';
        if (form) form.action = `{{ url('/master/template-penilaian') }}/${data.template_id}`;

        document.getElementById('modalTitleTemplate').textContent = 'Ubah Template Penilaian';
        document.getElementById('btnSubmitTextTemplate').textContent = 'Perbarui Data';

        const btnSubmit = document.getElementById('btnSubmitModalTemplate');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-add-template');
            btnSubmit.classList.add('btn-amber-template');
        }

        const iconPath = document.getElementById('modalIconPathTemplate');
        const iconBadge = document.getElementById('modalIconBadgeTemplate');
        if (iconPath) iconPath.setAttribute('d', 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z');
        if (iconBadge) {
            iconBadge.style.background = '#FEF3C7';
            iconBadge.style.color = '#D97706';
        }

        const modal = document.getElementById('modalTemplate');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

     function closeModalTemplate() {
        const modal = document.getElementById('modalTemplate');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
    }

    function closeModalTemplateOnBackdrop(event) {
        if (event.target.id === 'modalTemplate') {
            closeModalTemplate();
        }
    }

    function deleteTemplate() {
        if (!window.selectedTemplateData || !window.selectedTemplateData.template_id) {
            alert('Silakan pilih data template terlebih dahulu!');
            return;
        }

        const nameEl = document.getElementById('deleteTemplateName');
        if (nameEl) {
            nameEl.textContent = `"${window.selectedTemplateData.nama_template}"`; 
        }

        const modal = document.getElementById('modalDeleteTemplate');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function confirmDeleteTemplate() {
        if (!window.selectedTemplateData || !window.selectedTemplateData.template_id) return;

        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `{{ url('/master/template-penilaian') }}/${window.selectedTemplateData.template_id}`;

        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        deleteForm.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }

    function closeModalDeleteTemplate() {
        const modal = document.getElementById('modalDeleteTemplate');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function closeModalDeleteSkalaOnBackdrop(event) {
        if (event.target.id === 'modalDeleteSkala') {
            closeModalDeleteSkala();
        }
    }
</script>

@endsection