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

    /* --- Action Buttons --- */
   /* 1. KUNCI KELUARGA TOMBOL AGAR TIDAK TURUN BARIS & TIDAK TERPOTONG */
.action-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: nowrap !important; /* Mencegah tombol turun ke bawah */
    overflow: visible !important; /* Mencegah tombol di paling kanan terpotong */
}

/* CARD HEADER STRUKTUR TEMPLATE */
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

.header-left-group,
.header-right-group {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    flex-wrap: nowrap !important;
}

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

/* ==========================================
   CARD KATEGORI & PERTANYAAN (TEMA MAROON)
   ========================================== */
.kategori-card-wrapper {
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #ffffff;
    overflow: hidden;
    /* Hanya ubah box-shadow untuk cegah blink/flicker */
    transition: box-shadow 0.2s ease-in-out, border-color 0.2s ease-in-out;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.kategori-card-wrapper:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(128, 0, 32, 0.08);
}

.kategori-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #FAF5F6; /* Soft Maroon background */
    border-bottom: 1px solid #F3E8EA;
}

.kategori-header-left, .kategori-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.badge-urutan-kategori {
    background: #800020; /* Primary Maroon */
    color: #fff;
    font-weight: 700;
    font-size: 0.75rem;
    padding: 3px 9px;
    border-radius: 6px;
}

.kategori-title-text {
    font-size: 0.9rem;
    color: #1E293B;
    font-weight: 700;
}

.badge-info-pill {
    background: #F1F5F9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
}

.badge-bobot-pill {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}
.badge-bobot-pill.valid { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-bobot-pill.warning { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

/* Action Buttons Header Kategori */
.btn-action-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1px solid #CBD5E1;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-add-q { color: #800020; border-color: #F3E8EA; background: #FFF5F7; }
.btn-add-q:hover { background: #800020; color: #fff; border-color: #800020; }

.btn-edit-k { color: #D97706; border-color: #FEF3C7; background: #FFFBEB; border: 1px solid #b7babd; }
.btn-edit-k:hover { background: #D97706; color: #fff; border-color: #D97706; }

.btn-delete-k { color: #DC2626; border-color: #FEE2E2; background: #FEF2F2; border: 1px solid #b7babd }
.btn-delete-k:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

/* Accordion Pertanyaan */
.question-accordion-item {
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.question-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background-color: #800020; /* Color fallback */
    background-image: linear-gradient(135deg, #800020 0%, #a00028 100%);
    color: #ffffff;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    user-select: none;
    transition: background-color 0.15s ease-in-out, opacity 0.15s ease-in-out;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.question-summary:hover {
    background-image: none;
    background-color: #660019;
}

.q-left { display: flex; align-items: center; gap: 10px; }
.q-badge-urutan { 
    background: #ffffff; 
    color: #800020; 
    border-radius: 50%; 
    width: 22px; 
    height: 22px; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 0.75rem; 
    font-weight: 800;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.q-badge-type { 
    background: rgba(255, 255, 255, 0.2); 
    padding: 2px 8px; 
    border-radius: 4px; 
    font-size: 0.68rem; 
    letter-spacing: 0.5px;
    font-weight: 700;
}
.q-badge-bobot { 
    background: rgba(255, 255, 255, 0.95); 
    color: #800020; 
    padding: 3px 10px; 
    border-radius: 12px; 
    font-size: 0.75rem; 
    font-weight: 700;
}

/* Detail Box Inside Accordion */
.question-detail-box { 
    padding: 14px 16px; 
    background: #FAFAFA; 
    border-top: 1px solid #E2E8F0; 
    font-size: 0.825rem; 
}

/* Teks Jenis tanpa background di detail pertanyaan */
.q-jenis-text {
    font-weight: 600;
    color: #334155;
    background: none !important;
    padding: 0 !important;
}

/* Kerapihan tabel detail agar teks tidak menumpuk */
.question-detail-table {
    width: 100%;
    font-size: 0.85rem;
    border-collapse: collapse;
}

.question-detail-table td {
    padding: 6px 4px; /* Memberi jarak vertikal antar baris */
    line-height: 1.5;   /* Memberi spasi antar baris teks */
    vertical-align: top;
}

.empty-question-notice { 
    padding: 16px; 
    text-align: center; 
    color: #64748B; 
    font-size: 0.825rem; 
    background: #F8FAFC; 
    border: 1px dashed #CBD5E1;
    border-radius: 8px; 
}

/* Custom Tombol Edit / Hapus Pertanyaan */
.btn-q-edit {
    background-color: #D97706;
    color: #fff;
    border: none;
    padding: 3px 8px;       /* Padding dikecilkan */
    font-size: 0.725rem;     /* Ukuran teks lebih kecil */
    line-height: 1.2;
    border-radius: 4px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background-color 0.2s ease;
}
.btn-q-edit:hover { background-color: #B45309; color: #fff; }

.btn-q-delete {
    background-color: #DC2626;
    color: #fff;
    border: none;
    padding: 3px 8px;       /* Padding dikecilkan */
    font-size: 0.725rem;     /* Ukuran teks lebih kecil */
    line-height: 1.2;
    border-radius: 4px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background-color 0.2s ease;
}
.btn-q-delete:hover { background-color: #B91C1C; color: #fff; }

/* CARD SKALA NILAI */
.card-skala {
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    background: #ffffff;
}

.card-skala-header {
    background-color: var(--maroon-primary);
    border-bottom: 1px solid #FEE2E2;
    padding: 10px 16px;
}

.card-skala-title {
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.skala-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.2s ease;
}

.skala-item:last-child { border-bottom: none; }
.skala-item:hover { background-color: #FAFAFA; }

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

.skala-item:nth-child(1) .badge-skala-code { background-color: #10B981; }
.skala-item:nth-child(2) .badge-skala-code { background-color: #2563EB; }
.skala-item:nth-child(3) .badge-skala-code { background-color: #0891B2; }
.skala-item:nth-child(4) .badge-skala-code { background-color: #F59E0B; }
.skala-item:nth-child(5) .badge-skala-code { background-color: #EF4444; }

.skala-name { color: #334155; font-size: 0.85rem; font-weight: 500; }
.skala-score { color: #0284C7; font-size: 0.875rem; font-weight: 700; }
.skala-empty { padding: 16px; text-align: center; color: #94A3B8; font-size: 0.8125rem; }

/* CARD VALIDASI BOBOT SIDEBAR */
.card-validasi-bobot {
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}

/* Header */
.card-validasi-header {
    background-color: #7A1C30; /* maroon-primary */
    padding: 10px 14px;
    border-bottom: 1px solid #FEE2E2;
}

.card-validasi-title { 
    color: #ffffff; 
    font-size: 0.875rem; 
    font-weight: 700; 
    margin: 0; 
    display: flex; 
    align-items: center; 
    gap: 8px; 
}

/* Body Container (Memberi ruang napas/padding di dalam card) */
.card-validasi-body {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.val-section {
    width: 100%;
}

.val-label { 
    font-size: 0.78rem; 
    font-weight: 600; 
    color: #475569; 
    margin-bottom: 6px; 
    display: block; 
}

/* Progress Bar */
.progress-bar-container { 
    width: 100%; 
    height: 18px; 
    background: #E2E8F0; 
    border-radius: 10px; 
    overflow: hidden; 
}

.progress-bar-fill { 
    height: 100%; 
    color: #fff; 
    font-size: 0.7rem; 
    font-weight: 700; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    transition: width 0.3s ease; 
}

.bg-success-custom { background-color: #10B981; }
.bg-danger-custom { background-color: #EF4444; }

.val-status-text { 
    font-size: 0.75rem; 
    font-weight: 700; 
    margin-top: 6px; 
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Garis Pemisah (Divider) */
.divider-line { 
    height: 0;
    border: none;
    border-top: 1px dashed #CBD5E1; 
    margin: 2px 0; 
    width: 100%;
}

/* List Item Kategori */
.val-kategori-list { 
    display: flex; 
    flex-direction: column; 
    gap: 6px; 
}

.val-kategori-item { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 8px 10px; 
    background: #F8FAFC; 
    border: 1px solid #E2E8F0; 
    border-radius: 6px; 
    gap: 8px;
    box-sizing: border-box;
}

.val-kat-name { 
    display: flex; 
    align-items: center; 
    gap: 6px; 
    font-size: 0.78rem; 
    font-weight: 500; 
    color: #334155; 
    min-width: 0;
    flex: 1;
}

.val-kat-name span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.val-kat-name i {
    flex-shrink: 0;
}

.badge-val-percent { 
    font-size: 0.725rem; 
    font-weight: 700; 
    padding: 3px 8px; 
    border-radius: 4px; 
    flex-shrink: 0; 
    line-height: 1;
}

.badge-val-percent.pass { background: #DCFCE7; color: #15803D; }
.badge-val-percent.fail { background: #FEE2E2; color: #B91C1C; }

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

.main-content-column {
    min-width: 0; /* PENTING: Mencegah tabel/accordion di dalamnya membuat layout jebol */
    width: 100%;
}

.sidebar-column {
    width: 300px;
    display: flex;
    flex-direction: column;
    gap: 1rem; /* Memberi jarak otomatis antar card di sidebar */
}

/* Penanganan breakpoint layar kecil agar tidak overflow */
@media (max-width: 768px) {
    .layout-grid {
        grid-template-columns: 1fr;
    }
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

  <div class="layout-grid">

    <div class="main-content-column">
        
        <!-- CARD SELECT TEMPLATE -->
        <div class="card-box mb-3">
            <div class="card-header-soft">
                <div class="card-title">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                    </svg>
                    Pilih Template
                </div>
            </div>
            <div style="padding: 0.85rem 1rem;">
                <div class="select-wrapper">
                    <form method="GET" action="{{ route('template-penilaian.index') }}" id="formSelectTemplate">
                        <!-- Attributes onchange dihapus agar di-handle penuh oleh JS event listener -->
                        <select id="templateSelector" name="template_id" class="custom-select">
                            <option value="" @selected(!request('template_id'))>-- Pilih Template --</option>
                            @forelse($templates as $template)
                                <option value="{{ $template->template_id }}" @selected(request('template_id') == $template->template_id)>
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

        @php
            // Validasi ketat: Kategori HANYA dimuat jika URL memiliki parameter template_id yang valid
            $hasTemplateSelected = request()->filled('template_id') && isset($selectedTemplate) && $selectedTemplate;
            $kategoriList = $hasTemplateSelected ? ($kategories ?? $selectedTemplate->kategoris) : collect();
        @endphp

        <!-- CARD HEADER STRUKTUR TEMPLATE -->
        <div class="card-header-structure">
            <div class="header-left-group">
                <h6 class="structure-title">
                    <i class="fas fa-sitemap"></i> Struktur Template
                </h6>
                <div class="category-badge-pill" id="badge-total-kategori" style="{{ $hasTemplateSelected ? '' : 'display: none;' }}">
                    <span class="badge-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="badge-text"><strong id="text-total-kategori-num">{{ $kategoriList->count() }}</strong> Kategori</span>
                </div>
            </div>

            <div class="header-right-group">
                <button type="button" class="btn btn-add-template btn-sm {{ $hasTemplateSelected ? '' : 'disabled opacity-50' }}" id="btnTambahKategori" onclick="openModalTambahKategori()" {{ $hasTemplateSelected ? '' : 'disabled' }}>
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Kategori
                </button>
                <button type="button" class="btn btn-edit btn-sm {{ $hasTemplateSelected ? '' : 'disabled opacity-50' }}" id="btnEditTemplate" onclick="openModalEditTemplate()" {{ $hasTemplateSelected ? '' : 'disabled' }}>
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Ubah Template
                </button>
                <button type="button" class="btn btn-delete btn-sm {{ $hasTemplateSelected ? '' : 'disabled opacity-50' }}" id="btnDeleteTemplate" onclick="deleteTemplate()" {{ $hasTemplateSelected ? '' : 'disabled' }}>
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Template
                </button>
            </div>
        </div>

        {{-- JIKA BELUM MEMILIH TEMPLATE, TAMPILKAN EMPTY STATE --}}
        @if(!$hasTemplateSelected)
            <div id="emptyStateBox" class="card-box empty-state mb-3">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                <p style="margin: 0; font-size: 0.85rem;">Pilih template di atas untuk melihat struktur kategori & pertanyaan</p>
            </div>
        @else
            @forelse($kategoriList as $kategori)
                @php
                    $totalPertanyaan = $kategori->pertanyans ? $kategori->pertanyans->count() : 0;
                    $sumBobotPertanyaan = $kategori->pertanyans ? $kategori->pertanyans->sum('bobot_persen') : 0;
                    $isValidBobotPertanyaan = ($sumBobotPertanyaan == 100);
                @endphp
                <div class="kategori-card-wrapper mb-3">
                    <div class="kategori-card-header">
                        <div class="kategori-header-left">
                            <i class="fas fa-chevron-down text-muted icon-toggle me-1"></i>
                            <span class="badge-urutan-kategori">{{ $kategori->urutan }}</span>
                            <strong class="kategori-title-text">{{ $kategori->nama }}</strong>
                            <small class="text-muted">({{ $kategori->kode }})</small>
                        </div>
                        <div class="kategori-header-right">
                            <span class="badge-info-pill">
                                <i class="fas fa-question-circle text-secondary me-1"></i> {{ $totalPertanyaan }} Pertanyaan
                            </span>
                            <span class="badge-bobot-pill {{ $isValidBobotPertanyaan ? 'valid' : 'warning' }}">
                                <i class="fas {{ $isValidBobotPertanyaan ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-1"></i> {{ $kategori->bobot_persen }}%
                            </span>
                            <button type="button" class="btn-action-icon btn-add-q" onclick="openModalTambahPertanyaan('{{ $kategori->kategori_id }}')" title="Tambah Pertanyaan">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn-action-icon btn-edit-k" onclick="openModalEditKategori('{{ $kategori->kategori_id }}')" title="Edit Kategori">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn-action-icon btn-delete-k" onclick="openModalDeleteKategori('{{ $kategori->kategori_id }}')" title="Hapus Kategori">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="kategori-card-body p-3">
                        @if($kategori->pertanyaans && $kategori->pertanyaans->count() > 0)
                            @foreach($kategori->pertanyaans as $pertanyaan)
                                <details class="question-accordion-item mb-2">
                                    <summary class="question-summary">
                                        <div class="q-left">
                                            <span class="q-badge-urutan">{{ $pertanyaan->urutan }}</span>
                                            <span class="q-name me-2">{{ $pertanyaan->pertanyaan }}</span>
                                            <span class="q-badge-type">{{ strtoupper(str_replace('_', ' ', $pertanyaan->jenis ?? 'NILAI')) }}</span>
                                        </div>
                                        <div class="q-right">
                                            <span class="q-badge-bobot">{{ $pertanyaan->bobot_persen }}%</span>
                                        </div>
                                    </summary>
                                    <div class="question-detail-box">
                                        <table class="table-borderless text-secondary mb-3 question-detail-table">
                                            <tr>
                                                <td style="width: 110px;" class="fw-bold">Pertanyaan</td>
                                                <td>: {{ $pertanyaan->pertanyaan }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Deskripsi</td>
                                                <td>: {{ $pertanyaan->deskripsi ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Jenis</td>
                                                <td>: <span class="q-jenis-text">{{ strtoupper(str_replace('_', ' ', $pertanyaan->jenis ?? 'NILAI')) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Bobot</td>
                                                <td>: {{ $pertanyaan->bobot_persen }}%</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Status</td>
                                                <td>: 
                                                    @if($pertanyaan->status_aktif == 1 || $pertanyaan->status_aktif == 'Aktif')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Non-Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="d-flex gap-2 pt-1">
                                            <button class="btn btn-edit-k" onclick="openModalEditPertanyaan('{{ $pertanyaan->pertanyaan_id }}')">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-delete-k" onclick="openModalHapusPertanyaan('{{ $pertanyaan->pertanyaan_id }}')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        @else
                            <div class="empty-question-notice">
                                <i class="fas fa-folder-open me-1"></i> Belum ada pertanyaan. Klik tombol <i class="fas fa-plus text-danger fw-bold"></i> di kanan atas kategori untuk menambah pertanyaan.
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card-box empty-state mb-3">
                    <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Belum ada kategori pada template ini.</p>
                </div>
            @endforelse
        @endif

    </div>

        <!-- ================= KOLOM SIDEBAR (KANAN) ================= -->
        <div class="sidebar-column">
            
            <!-- CARD SKALA NILAI -->
            <div class="card card-skala mb-3">
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
            </div> <!-- Tag penutup card-skala disisipkan di sini -->

            <!-- CARD VALIDASI BOBOT -->
            <div class="card card-validasi-bobot">
                <div class="card-validasi-header">
                    <h6 class="card-validasi-title">
                        <i class="fas fa-balance-scale"></i> Validasi Bobot
                    </h6>
                </div>

                <!-- Gunakan class khusus ini agar padding tidak hilang/reset -->
                <div class="card-validasi-body">
                    
                    <!-- TOTAL BOBOT KATEGORI -->
                    @php 
                        $totalBobotKat = isset($totalBobotKategori) ? $totalBobotKategori : (isset($selectedTemplate) && $selectedTemplate->kategoris ? $selectedTemplate->kategoris->sum('bobot_persen') : 0);
                    @endphp
                    <div class="val-section">
                        <label class="val-label">Total Bobot Kategori</label>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill {{ $totalBobotKat == 100 ? 'bg-success-custom' : 'bg-danger-custom' }}" 
                                style="width: {{ min($totalBobotKat, 100) }}%;">
                                {{ $totalBobotKat }}%
                            </div>
                        </div>
                        <div class="val-status-text {{ $totalBobotKat == 100 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $totalBobotKat == 100 ? 'fa-check' : 'fa-times' }}"></i>
                            {{ $totalBobotKat == 100 ? 'Sudah 100%' : 'Total bobot harus 100% (Saat ini: '.$totalBobotKat.'%)' }}
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <!-- VALIDASI PERTANYAAN PER KATEGORI -->
                    <div class="val-section">
                        <label class="val-label mb-2">Validasi Pertanyaan per Kategori</label>
                        @if($selectedTemplate && $selectedTemplate->kategoris->count() > 0)
                            <div class="val-kategori-list">
                                @foreach($selectedTemplate->kategoris as $kat)
                                    @php
                                        $sumBobotQ = $kat->pertanyans ? $kat->pertanyans->sum('bobot_persen') : 0;
                                        $isPass = ($sumBobotQ == 100);
                                    @endphp
                                    <div class="val-kategori-item">
                                        <div class="val-kat-name" title="{{ $kat->nama }}">
                                            <i class="fas {{ $isPass ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-warning' }}"></i>
                                            <span>{{ $kat->nama }}</span>
                                        </div>
                                        <span class="badge-val-percent {{ $isPass ? 'pass' : 'fail' }}">
                                            {{ $sumBobotQ }}%
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted" style="font-size: 0.78rem; margin: 0;">Tidak ada data kategori.</p>
                        @endif
                    </div>

                </div>
            </div> 
        </div> 
    </div>
</div> 

<!-- MODAL FORM TEMPLATE -->
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

<!-- MODAL FORM KATEGORI -->
<div class="modal-backdrop" id="modalKategori" onclick="closeModalKategoriOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge" id="modalIconBadgeKategori">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="modalIconPathKategori" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitleKategori">Tambah Kategori Penilaian</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalKategori()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formKategori" method="POST" action="{{ route('kategori-penilaian.store') }}">
            @csrf
            <input type="hidden" id="formMethodKategori" name="_method" value="POST">
            <input type="hidden" id="kategori_id" name="kategori_id">
            
            <!-- Mengikat Kategori ke Template yang sedang aktif -->
            <input type="hidden" id="template_id_kategori" name="template_id" value="{{ request('template_id') }}">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="kode">Kode Kategori</label>
                    <div class="input-addon-group">
                        <span class="input-addon">Kode</span>
                        <input type="text" id="kode" name="kode" class="form-input" placeholder="contoh: KAT001">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama">Nama Kategori <span class="text-danger">*</span></label>
                    <div class="input-addon-group">
                        <span class="input-addon">Nama</span>
                        <input type="text" id="nama" name="nama" class="form-input" placeholder="contoh: Sikap & Etika" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="bobot_persen">Bobot (%) <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Bobot</span>
                            <input type="number" step="0.01" id="bobot_persen" name="bobot_persen" class="form-input" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="urutan">Urutan <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Urutan</span>
                            <input type="number" step="1" id="urutan" name="urutan" class="form-input" placeholder="1" required>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info py-2 px-3 mt-3 mb-0" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; font-size: 0.8rem; border-radius: 6px;">
                    <i class="fas fa-info-circle me-1"></i> Total bobot semua kategori dalam template harus = 100%
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-refresh" onclick="closeModalKategori()">Batal</button>
                <button type="submit" class="btn btn-add-template" id="btnSubmitModalKategori">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="btnSubmitTextKategori">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalDeleteKategori" class="modal-backdrop-delete" onclick="closeModalDeleteKategoriOnBackdrop(event)" style="display: none;">
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
                Anda yakin akan menghapus kategori <strong id="deleteKategoriName"></strong>?
            </p>
            <div class="delete-actions">
                <button type="button" class="btn-delete-confirm" onclick="confirmDeleteKategori()">HAPUS</button>
                <button type="button" class="btn-delete-cancel" onclick="closeModalDeleteKategori()">BATAL</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM PERTANYAAN -->
<div class="modal-backdrop" id="modalPertanyaan" onclick="closeModalPertanyaanOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge" id="modalIconBadgePertanyaan">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="modalIconPathPertanyaan" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitlePertanyaan">Tambah Pertanyaan</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalPertanyaan()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formPertanyaan" method="POST" action="{{ route('pertanyaan-penilaian.store') }}">
            @csrf
            <input type="hidden" id="formMethodPertanyaan" name="_method" value="POST">
            <input type="hidden" id="pertanyaan_id" name="pertanyaan_id">
            
            <!-- Mengikat Kategori ke Template yang sedang aktif -->
            <input type="hidden" id="kategori_id_pertanyaan" name="kategori_id" value="">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="pertanyaan">Pertanyaan </label>
                    <div class="input-addon-group">
                        <span class="input-addon">Pertanyaan</span>
                        <input type="text" id="pertanyaan" name="pertanyaan" class="form-input" placeholder="contoh: Hasil Kinerja">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                    <div class="input-addon-group">
                        <span class="input-addon">Deskripsi</span>
                        <input type="text" id="deskripsi" name="deskripsi" class="form-input" placeholder="contoh: Deskripsi pertanyaan" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="bobot_persen">Bobot (%) <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Bobot</span>
                            <input type="number" step="0.01" id="bobot_persen_pertanyaan" name="bobot_persen" class="form-input" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="urutan">Urutan <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Urutan</span>
                            <input type="number" step="1" id="urutan_pertanyaan" name="urutan" class="form-input" placeholder="1" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="jenis">Jenis <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Jenis</span>
                            <select id="jenis_pertanyaan" name="jenis" class="form-input" required>
                                <option value="Nilai">NILAI</option>
                                <option value="Nilai Catatan">NILAI CATATAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="status_aktif">Status Aktif <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Status</span>
                            <select id="status_aktif_pertanyaan" name="status_aktif" class="form-input" required>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info py-2 px-3 mt-3 mb-0" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; font-size: 0.8rem; border-radius: 6px;">
                    <i class="fas fa-info-circle me-1"></i> Total bobot semua pertanyaan dalam template harus = 100%
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-refresh" onclick="closeModalPertanyaan()">Batal</button>
                <button type="submit" class="btn btn-add-template" id="btnSubmitModalPertanyaan">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="btnSubmitTextPertanyaan">Simpan Data</span>
                </button>
            </div>
        </form>
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
            // Cukup jalankan sekali berdasarkan state value saat ini (dari request/URL)
            updateButtonState(selectTemplate.value);

            selectTemplate.addEventListener('change', function() {
                if (this.value) {
                    // Submit form ke server saat dropdown berubah
                    this.form.submit();
                } else {
                    // Jika user memilih kembali ke "-- Pilih Template --"
                    updateButtonState('');
                    this.form.submit();
                }
            });
        }
    });

    function updateButtonState(templateId) {
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

        // 1. Tampilkan modal terlebih dahulu
        const modal = document.getElementById('modalTemplate');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }

        // 2. Isi input text & status
        document.getElementById('template_id').value = data.template_id;
        document.getElementById('nama_template').value = data.nama_template || '';
        document.getElementById('status_aktif').value = data.status_aktif || 'Aktif';

        // 3. PARSE DATA JABATAN (occ_id)
        let occSelected = [];

        if (data.occ_id) {
            try {
                // Jika data dari DB berupa string JSON '["4"]' atau '["4","5"]'
                let parsed = typeof data.occ_id === 'string' ? JSON.parse(data.occ_id) : data.occ_id;
                occSelected = Array.isArray(parsed) ? parsed : [parsed];
            } catch (e) {
                // fallback jika bukan format JSON
                occSelected = data.occ_id.toString().split(',');
            }
        }

        // Bersihkan karakter petik/spasi dan konversi ke String ("4")
        occSelected = occSelected.map(id => id.toString().replace(/[^0-9a-zA-Z_-]/g, '').trim());

        // Inject ke Select2
        $('.select2-jabatan').val(occSelected).trigger('change');

        // 4. Ubah Action Form & Tampilan Modal
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

    function closeModalDeleteTemplateOnBackdrop(event) {
        if (event.target.id === 'modalDeleteTemplate') {
            closeModalDeleteTemplate();
        }
    }
</script>

 
<script>
    
    window.kategoriList = @json($kategoris ?? $kategoriList ?? []);

    // Function untuk Tambah Kategori Baru
    function openModalTambahKategori() {
        const form = document.getElementById('formKategori');
        if (form) form.reset();

        const inputId = document.getElementById('kategori_id');
        const inputMethod = document.getElementById('formMethodKategori'); 

        if (inputId) inputId.value = '';
        if (inputMethod) inputMethod.value = 'POST';
        if (form) form.action = "{{ route('kategori-penilaian.store') }}";
        
        const urlParams = new URLSearchParams(window.location.search);
        const selectedTemplateId = urlParams.get('template_id') || document.getElementById('templateSelector')?.value || '';

        const inputTemplateId = document.getElementById('template_id_kategori');
        if (inputTemplateId) inputTemplateId.value = selectedTemplateId;

        if (document.getElementById('modalTitleKategori')) {
            document.getElementById('modalTitleKategori').textContent = 'Tambah Kategori';
        }
        if (document.getElementById('btnSubmitTextKategori')) {
            document.getElementById('btnSubmitTextKategori').textContent = 'Simpan Data';
        }

        const iconPath = document.getElementById('modalIconPathKategori');
        const iconBadge = document.getElementById('modalIconBadgeKategori');
        if (iconPath) iconPath.setAttribute('d', 'M12 4v16m8-8H4');
        if (iconBadge) {
            iconBadge.style.background = '';
            iconBadge.style.color = '';
        }

        const btnSubmit = document.getElementById('btnSubmitModalKategori');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-amber-template');
            btnSubmit.classList.add('btn-add-template');
        }

        const modal = document.getElementById('modalKategori');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }


    function openModalEditKategori(kategoriId) {
        // 1. Cari data kategori di window.kategoriList berdasarkan ID
        let data = null;
        if (window.kategoriList && Array.isArray(window.kategoriList)) {
            data = window.kategoriList.find(k => k.kategori_id == kategoriId);
        }

        if (!data) {
            alert('Data Kategori tidak ditemukan!');
            return;
        }

        const form = document.getElementById('formKategori');
        
        // Set Input Values
        const inputId = document.getElementById('kategori_id');
        if (inputId) inputId.value = data.kategori_id;

        if (document.getElementById('kode')) document.getElementById('kode').value = data.kode || '';
        if (document.getElementById('nama')) document.getElementById('nama').value = data.nama || '';
        if (document.getElementById('bobot_persen')) document.getElementById('bobot_persen').value = data.bobot_persen || '';
        if (document.getElementById('urutan')) document.getElementById('urutan').value = data.urutan || '';

        // Set Form Method & Action
        if (document.getElementById('formMethodKategori')) {
            document.getElementById('formMethodKategori').value = 'PUT';
        }
        if (form) form.action = `{{ url('/master/kategori-penilaian') }}/${data.kategori_id}`;

        // Set UI Modal
        if (document.getElementById('modalTitleKategori')) {
            document.getElementById('modalTitleKategori').textContent = 'Ubah Kategori';
        }
        if (document.getElementById('btnSubmitTextKategori')) {
            document.getElementById('btnSubmitTextKategori').textContent = 'Perbarui Data';
        }

        const btnSubmit = document.getElementById('btnSubmitModalKategori');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-add-template');
            btnSubmit.classList.add('btn-amber-template');
        }

        const iconPath = document.getElementById('modalIconPathKategori');
        const iconBadge = document.getElementById('modalIconBadgeKategori');
        if (iconPath) iconPath.setAttribute('d', 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z');
        if (iconBadge) {
            iconBadge.style.background = '#FEF3C7';
            iconBadge.style.color = '#D97706';
        }

        // Tampilkan Modal
        const modal = document.getElementById('modalKategori');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

    function closeModalKategori() {
        const modal = document.getElementById('modalKategori');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
    }

    function closeModalKategoriOnBackdrop(event) {
        if (event.target.id === 'modalKategori') {
            closeModalKategori();
        }
    }

    window.selectedDeleteKategoriId = null;

    function openModalDeleteKategori(kategoriId) {
        window.selectedDeleteKategoriId = kategoriId;
        
        let data = null;
        if (window.kategoriList && Array.isArray(window.kategoriList)) {
            data = window.kategoriList.find(k => k.kategori_id == kategoriId);
        }

        if (!data) {
            alert('Data Kategori tidak ditemukan!');
            return;
        }

        const nameEl = document.getElementById('deleteKategoriName');
        if (nameEl) {
            nameEl.textContent = `"${data.nama}"`;
        }

        const modal = document.getElementById('modalDeleteKategori');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function confirmDeleteKategori() {
        const kategoriId = window.selectedDeleteKategoriId;

        if (!kategoriId) {
            alert('Data Kategori tidak ditemukan!');
            return;
        }

        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `{{ url('/master/kategori-penilaian') }}/${kategoriId}`;

        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        deleteForm.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }


    function closeModalDeleteKategori() {
        window.selectedDeleteKategoriId = null;
        const modal = document.getElementById('modalDeleteKategori');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function closeModalDeleteKategoriOnBackdrop(event) {
        if (event.target.id === 'modalDeleteKategori') {
            closeModalDeleteKategori();
        }
    }
</script>

<script>
    function openModalTambahPertanyaan(kategoriId) {
        document.getElementById('kategori_id_pertanyaan').value = kategoriId; 

        const form = document.getElementById('formPertanyaan');
        if (form) form.reset();

        const inputId = document.getElementById('pertanyaan_id');
        const inputMethod = document.getElementById('formMethodPertanyaan'); 

        if (inputId) inputId.value = '';
        if (inputMethod) inputMethod.value = 'POST';
        
        if (form) form.action = "{{ route('pertanyaan-penilaian.store') }}";

        const title = document.getElementById('modalTitlePertanyaan');
        const btnText = document.getElementById('btnSubmitTextPertanyaan');
        if (title) title.textContent = 'Tambah Pertanyaan Penilaian';
        if (btnText) btnText.textContent = 'Simpan Data';

        // Set Icon Modal ke Plus (+)
        const iconPath = document.getElementById('modalIconPathPertanyaan');
        const iconBadge = document.getElementById('modalIconBadgePertanyaan');
        if (iconPath) iconPath.setAttribute('d', 'M12 4v16m8-8H4');
        if (iconBadge) {
            iconBadge.style.background = '';
            iconBadge.style.color = '';
        }

        // Reset Warna Tombol Submit
        const btnSubmit = document.getElementById('btnSubmitModalPertanyaan');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-amber-template');
            btnSubmit.classList.add('btn-add-template');
        }

        // Tampilkan Modal
        const modal = document.getElementById('modalPertanyaan');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }
</script>

@endsection