@extends('layout.app')

@push('styles')
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

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

.bg-rev-light { 
    background-color: #eee0fc; 
    color: #6b21a8; 
    border:  1px solid #6b21a8;
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
    border: 1px solid gray;
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
    background-color: #10B981;
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
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.15s ease-in-out;
}

.btn-success-custom:hover:not(:disabled) {
    background-color: #059669;
    color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
}

.btn-success-custom:disabled {
    background-color: #A7F3D0;
    cursor: not-allowed;
    color: #ffffff;
    opacity: 0.7;
    box-shadow: none;
}

.btn-warning-custom {
    background-color: #F59E0B;
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
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.15s ease-in-out;
}

.btn-warning-custom:hover:not(:disabled) {
    background-color: #D97706;
    color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);
}

.btn-warning-custom:disabled {
    background-color: #FDE68A;
    cursor: not-allowed;
    color: #ffffff;
    opacity: 0.7;
    box-shadow: none;
}

/* Modal Konfirmasi Massal (Verifikasi & Unverifikasi) */
.custom-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(2px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1rem;
    animation: fadeInModal 0.15s ease-out;
}

.custom-modal-dialog {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 780px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid #E2E8F0;
}

.custom-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
    background: #ffffff;
}

.custom-modal-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1E293B;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin: 0;
}

.custom-modal-close {
    background: none;
    border: none;
    font-size: 1.35rem;
    line-height: 1;
    color: #94A3B8;
    cursor: pointer;
    transition: color 0.15s;
    padding: 0.2rem 0.4rem;
}

.custom-modal-close:hover {
    color: #334155;
}

.custom-modal-body {
    padding: 1.25rem;
    overflow-y: auto;
    font-size: 0.875rem;
    color: #334155;
}

.modal-notice-banner {
    border-radius: 6px;
    padding: 0.85rem 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    line-height: 1.45;
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
}

.modal-notice-warning {
    background-color: #FFFBEB;
    border: 1px solid #FDE68A;
    color: #92400E;
}

.modal-notice-success {
    background-color: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
}

.modal-table-container {
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    overflow: hidden;
    max-height: 280px;
    overflow-y: auto;
    margin-top: 0.75rem;
}

.modal-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8125rem;
}

.modal-table th {
    background: #F8FAFC;
    color: #475569;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.04em;
    padding: 0.6rem 0.85rem;
    border-bottom: 1px solid #E2E8F0;
    text-align: left;
}

.modal-table td {
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid #F1F5F9;
    color: #1E293B;
}

.modal-table tr:last-child td {
    border-bottom: none;
}

.custom-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 0.85rem 1.25rem;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
}

.btn-modal-cancel {
    background: #475569;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: background 0.15s;
}

.btn-modal-cancel:hover {
    background: #334155;
    color: #ffffff;
}

.btn-modal-unverify {
    background: #F59E0B;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.15rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: background 0.15s;
}

.btn-modal-unverify:hover {
    background: #D97706;
    color: #ffffff;
}

.btn-modal-verify {
    background: #10B981;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1.15rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: background 0.15s;
}

.btn-modal-verify:hover {
    background: #059669;
    color: #ffffff;
}

.modal-form-textarea {
    width: 100%;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 0.55rem 0.75rem;
    font-size: 0.8125rem;
    font-family: var(--font-main);
    color: #1E293B;
    resize: vertical;
    outline: none;
    transition: border-color 0.15s;
}

.modal-form-textarea:focus {
    border-color: var(--maroon-primary);
    box-shadow: 0 0 0 2px var(--maroon-glow);
}

@keyframes fadeInModal {
    from { opacity: 0; }
    to { opacity: 1; }
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
    min-width: 200px;
    font-size: 0.8125rem;
    text-align: left;
    border-collapse: collapse;
    margin: 0;  
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
    padding: 0.75rem 0.55rem;
    vertical-align: middle;
    word-break: normal;
    overflow-wrap: break-word;
    line-height: 1.4;
}

.table-custom th {
    white-space: nowrap !important;
}

.table-custom td {
    color: var(--text-primary);
}
 
.table-custom th:nth-child(1), .table-custom td:nth-child(1) { width: 42px; }
.table-custom th:nth-child(2), .table-custom td:nth-child(2) { width: 55px; }
.table-custom th:nth-child(3), .table-custom td:nth-child(3) { width: 105px; }
.table-custom th:nth-child(4), .table-custom td:nth-child(4) { width: 180px; }
.table-custom th:nth-child(5), .table-custom td:nth-child(5) { width: 160px; }
.table-custom th:nth-child(6), .table-custom td:nth-child(6) { width: 155px; }
.table-custom th:nth-child(7), .table-custom td:nth-child(7) { width: 170px; }
.table-custom th:nth-child(8), .table-custom td:nth-child(8) { width: 185px; }
.table-custom th:nth-child(9), .table-custom td:nth-child(9) { width: 165px; }
.table-custom th:nth-child(10), .table-custom td:nth-child(10) { width: 130px; }
.table-custom th:nth-child(11), .table-custom td:nth-child(11) { width: 140px; }
.table-custom th:nth-child(12), .table-custom td:nth-child(12) { width: 160px; }
.table-custom th:nth-child(13), .table-custom td:nth-child(13) { width: 150px; text-align: center; }

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
    flex-direction: row;
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

    .badge-status-rev {
        background-color: #eee0fc;
        color: #6b21a8;
        border: 1px solid #6b21a8;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-block;
    }

    .badge {
    padding: 0.2rem 0.5rem; /* Padding diperkecil */
    border-radius: 0.375rem; /* Sudut sedikit lebih tegas/pas */
    font-size: 0.7rem;       /* Ukuran font diperkecil */
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    letter-spacing: 0.01em;
    border-width: 1px;       /* Ketebalan garis tepi disesuaikan */
    border-style: solid;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    white-space: nowrap;
}

.badge-emerald { 
    background-color: var(--badge-emerald-bg); 
    color: var(--badge-emerald-text); 
    border-color: var(--badge-emerald-border); 
}

.badge-blue { 
    background-color: var(--badge-blue-bg); 
    color: var(--badge-blue-text); 
    border-color: var(--badge-blue-border); 
}

.badge-amber { 
    background-color: var(--badge-amber-bg); 
    color: var(--badge-amber-text); 
    border-color: var(--badge-amber-border); 
}

.badge-orange { 
    background-color: var(--badge-orange-bg); 
    color: var(--badge-orange-text); 
    border-color: var(--badge-orange-border); 
}

.badge-rose { 
    background-color: var(--badge-rose-bg); 
    color: var(--badge-rose-text); 
    border-color: var(--badge-rose-border); 
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
                                    {{ $periode->nama_periode ?? ($periode->tahun . ' - ' . $periode->bulan) }} · {{ strtoupper($periode->status ?? 'LOCKED') }}
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

        @if(session('success'))
            <div style="background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fas fa-check-circle" style="color: #10B981; font-size: 1.1rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065F46; cursor: pointer; font-size: 1rem;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #FFF1F2; border: 1px solid #F43F5E; color: #9F1239; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #F43F5E; font-size: 1.1rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #9F1239; cursor: pointer; font-size: 1rem;">&times;</button>
            </div>
        @endif

        @if($hasPeriodeSelected)
            <!-- CARD RINGKASAN STATISTIK -->
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
                        <div class="stat-icon bg-rev-light">
                            <i class="fas fa-undo"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTER CARD -->
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

                            <div class="filter-grid">
                                <div>
                                    <input type="text" name="search" class="form-control-custom" placeholder="Cari nama/NUP/penilai..." value="{{ request('search') }}">
                                </div>
                                <div>
                                    <select name="off_id" class="select2">
                                        <option value="">- Semua Penempatan -</option>
                                        @foreach($listPenempatan as $off)
                                            <option value="{{ $off->off_id }}" {{ request('off_id') == $off->off_id ? 'selected' : '' }}>
                                                {{ $off->off_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <select name="kode_unit_kerja" class="select2">
                                        <option value="">- Semua Unit Kerja -</option>
                                        @foreach($listUnitKerja as $unitKerja)
                                            <option value="{{ $unitKerja->kode_unit_kerja }}" {{ request('kode_unit_kerja') == $unitKerja->kode_unit_kerja ? 'selected' : '' }}>
                                                {{ $unitKerja->nama_unit_kerja }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="status_verifikasi" class="select2">
                                        <option value="">- Semua Status Verifikasi -</option>
                                        <option value="VERIFIED" @selected(request('status_verifikasi') == 'VERIFIED')>Verified</option>
                                        <option value="UNVERIFIED" @selected(request('status_verifikasi') == 'UNVERIFIED')>Belum Verifikasi</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="koreksi" class="select2">
                                        <option value="">- Semua Koreksi -</option>
                                        <option value="ADA_KOREKSI" @selected(request('koreksi') == 'ADA_KOREKSI')>Ada Koreksi</option>
                                        <option value="TANPA_KOREKSI" @selected(request('koreksi') == 'TANPA_KOREKSI')>Tanpa Koreksi</option>
                                    </select>
                                </div>
                            </div>

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

            <!-- TABEL DATA PEGAWAI -->
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
                                    <th>NO</th>
                                    <th>NUP</th>
                                    <th>NAMA</th>
                                    <th>PENEMPATAN</th>
                                    <th>JABATAN</th>
                                    <th>UNIT KERJA</th>
                                    <th>PENILAI</th>
                                    <th>VERIFIKATOR</th>
                                    <th width="40">NILAI</th>
                                    <th width="160">PREDIKAT</th>
                                    <th width="140" class="text-center">STATUS VERIFIKASI</th>
                                    <th width="110" class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penilaians as $index => $row)
                                    @php
                                        $penilaianId = data_get($row, 'penilaian_id', $row->id ?? '');
                                        $statusPenilaian = strtoupper((string) data_get($row, 'status_nilai', 'BELUM DIISI'));
                                        $statusVerifikator = strtoupper((string) data_get($row, 'status_verifikator_display', data_get($row, 'status_verifikator', '')));
                                        $nilaiAkhir = is_numeric(data_get($row, 'nilai_akhir')) ? (float) data_get($row, 'nilai_akhir') : 0.00;
                                        $predikat = data_get($row, 'predikat', 'Mengecewakan');

                                        // Prioritaskan nilai & predikat verifikator
                                        $nilaiTampil = ($row->total_nilai_verifikator > 0) 
                                            ? $row->total_nilai_verifikator 
                                            : ($row->nilai_akhir ?? $row->total_nilai ?? 0);

                                        $predikatTampil = $row->predikat_verifikator 
                                            ?: $row->predikat 
                                            ?: 'Belum Diisi';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="row-checkbox" value="{{ $penilaianId }}" 
                                                   data-id="{{ $penilaianId }}"
                                                   data-nup="{{ data_get($row, 'nup', '-') }}"
                                                   data-nama="{{ data_get($row, 'nama', '-') }}"
                                                   data-jabatan="{{ data_get($row, 'jabatan', '-') }}"
                                                   data-nilai="{{ number_format($nilaiTampil, 2) }}"
                                                   data-predikat="{{ $predikatTampil }}"
                                                   data-status-badge="{{ in_array($statusVerifikator, ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']) ? 'Terverifikasi' : 'Menunggu Verifikasi' }}"
                                                   data-is-verified="{{ in_array($statusVerifikator, ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']) ? '1' : '0' }}"
                                                   style="cursor: pointer;">
                                        </td>
                                        <td>{{ (($penilaians->currentPage() - 1) * $penilaians->perPage()) + $loop->iteration }}</td>
                                        <td><code>{{ data_get($row, 'nup', '-') }}</code></td>
                                        <td><strong>{{ data_get($row, 'nama', '-') }}</strong></td>
                                        <td>{{ data_get($row, 'penempatan', '-') }}</td>
                                        <td>{{ data_get($row, 'jabatan', '-') }}</td>
                                        <td>{{ data_get($row, 'unit_kerja', '-') }}</td>
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
                                        <td>{{ number_format($nilaiAkhir, 2) }}</td>
                                        <td>
                                            @php
                                            // Penentuan warna badge berdasarkan nilai yang ditampilkan
                                            if ($nilaiTampil >= 90) {
                                                $badgeStyle = 'badge-emerald';
                                            } elseif ($nilaiTampil >= 80) {
                                                $badgeStyle = 'badge-blue';
                                            } elseif ($nilaiTampil >= 70) {
                                                $badgeStyle = 'badge-amber';
                                            } elseif ($nilaiTampil >= 60) {
                                                $badgeStyle = 'badge-orange';
                                            } elseif ($nilaiTampil > 0) {
                                                $badgeStyle = 'badge-rose';
                                            } else {
                                                $badgeStyle = 'badge-gray';
                                            }
                                        @endphp

                                        <span class="badge {{ $badgeStyle }} text-nowrap">
                                            {{ $predikatTampil }}
                                        </span>
                                        </td>
                                        <td>
                                            @if(in_array($statusVerifikator, ['VERIFIED', 'VERIFIKASI', 'APPROVED', 'SELESAI']))
                                                <span class="badge-status-success">Verified</span>
                                            @elseif(in_array($statusVerifikator, ['KOREKSI', 'PERLU DITINJAU']))
                                                <span class="badge-status-danger">Ada Koreksi</span>
                                            @elseif(in_array($statusVerifikator, ['REVISI', 'DIKEMBALIKAN']))
                                                <span class="badge-status-rev">Dikembalikan</span>
                                            @elseif(in_array($statusVerifikator, ['', 'BELUM VERIFIKASI', 'UNVERIFIED']))
                                                <span class="badge-status-gray">Menunggu Verifikasi</span>
                                            @else
                                                <span class="badge-status-gray">{{ $statusVerifikator ?: 'BELUM VERIFIKASI' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center table-action-cell">
                                            <div class="detail-action-group">

                                                @if(in_array($statusPenilaian, ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI'], true))
                                                    <a href="{{ route('verifikasi-penilaian.form', $penilaianId) }}"
                                                       class="btn-action-icon"
                                                       title="Verifikasi Penilaian"
                                                       aria-label="Verifikasi Penilaian">
                                                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 3l7 3v5c0 4.2-2.6 8.1-7 10-4.4-1.9-7-5.8-7-10V6l7-3z"></path>
                                                            <path d="M9.5 12.5l1.6 1.6 3.4-3.9"></path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                <!-- Tombol 1: Preview Form / Detail -->
                                                <a href="{{ route('verifikasi.preview', $penilaianId) }}" 
                                                   class="btn-action-icon" 
                                                   title="Lihat Detail" 
                                                   aria-label="Lihat Detail">
                                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
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

                        $pageNumbers = range(1, min(5, $lastPage));
                    @endphp

                    <div class="detail-pagination">
                        <p class="detail-pagination-label">Menampilkan {{ $firstItem }} sampai {{ $lastItem }} dari {{ $total }} data</p>

                        <div class="detail-pagination-nav">
                            @if ($currentPage > 1)
                                <a href="{{ $penilaians->url($currentPage - 1) }}" class="page-arrow">&lsaquo;</a>
                            @else
                                <span class="page-arrow disabled">&lsaquo;</span>
                            @endif

                            @foreach ($pageNumbers as $page)
                                @if ($page == $currentPage)
                                    <span class="page-number active">{{ $page }}</span>
                                @else
                                    <a href="{{ $penilaians->url($page) }}" class="page-number">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($currentPage < $lastPage)
                                <a href="{{ $penilaians->url($currentPage + 1) }}" class="page-arrow">&rsaquo;</a>
                            @else
                                <span class="page-arrow disabled">&rsaquo;</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

<!-- Modal Konfirmasi Verifikasi Multiple -->
<div id="modalBulkVerify" class="custom-modal-backdrop">
    <div class="custom-modal-dialog">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title">
                <i class="fas fa-check-circle" style="color: #10B981;"></i>
                Konfirmasi Verifikasi Multiple
            </h3>
            <button type="button" class="custom-modal-close" data-close-modal="modalBulkVerify">&times;</button>
        </div>
        <form id="formBulkVerify" action="{{ route('verifikasi-penilaian.bulk-verify') }}" method="POST">
            @csrf
            <input type="hidden" name="periode_id" value="{{ request('periode_id', $selectedPeriode->periode_id ?? '') }}">
            <div id="verifyHiddenInputs"></div>

            <div class="custom-modal-body">
                <div class="modal-notice-banner modal-notice-success">
                    <i class="fas fa-info-circle" style="font-size: 1.15rem; margin-top: 2px;"></i>
                    <div>
                        Verifikasi sekaligus untuk <strong id="verifyModalCount">0</strong> penilaian terpilih. Penilaian akan ditandai <strong>Terverifikasi</strong> menggunakan nilai &amp; predikat yang tertera tanpa mengubah butir koreksi.
                    </div>
                </div>

                <div class="modal-table-container">
                    <table class="modal-table">
                        <thead>
                            <tr>
                                <th width="40">NO</th>
                                <th>NUP</th>
                                <th>NAMA</th>
                                <th>JABATAN</th>
                                <th width="70">NILAI</th>
                                <th width="140">PREDIKAT</th>
                            </tr>
                        </thead>
                        <tbody id="verifyTableBody">
                            <!-- Diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-modal-cancel" data-close-modal="modalBulkVerify">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-modal-verify">
                    <i class="fas fa-check-circle"></i> Verifikasi Semua
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Unverifikasi Multiple -->
<div id="modalBulkUnverify" class="custom-modal-backdrop">
    <div class="custom-modal-dialog">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title">
                <i class="fas fa-undo" style="color: #F59E0B;"></i>
                Konfirmasi Unverifikasi Multiple
            </h3>
            <button type="button" class="custom-modal-close" data-close-modal="modalBulkUnverify">&times;</button>
        </div>
        <form id="formBulkUnverify" action="{{ route('verifikasi-penilaian.bulk-unverify') }}" method="POST">
            @csrf
            <input type="hidden" name="periode_id" value="{{ request('periode_id', $selectedPeriode->periode_id ?? '') }}">
            <div id="unverifyHiddenInputs"></div>

            <div class="custom-modal-body">
                <div class="modal-notice-banner modal-notice-warning">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.15rem; margin-top: 2px;"></i>
                    <div>
                        Batalkan verifikasi untuk <strong id="unverifyModalCount">0</strong> penilaian. Status kembali ke <strong>Menunggu Verifikasi</strong>. Koreksi per-butir tetap disimpan.
                    </div>
                </div>

                <div style="margin-bottom: 0.85rem;">
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem;">
                        Alasan / Catatan Unverifikasi (opsional)
                    </label>
                    <textarea name="catatan" class="modal-form-textarea" rows="2" placeholder="Contoh: salah verifikasi massal, perlu dikoreksi ulang..."></textarea>
                </div>

                <div class="modal-table-container">
                    <table class="modal-table">
                        <thead>
                            <tr>
                                <th width="40">NO</th>
                                <th>NUP</th>
                                <th>NAMA</th>
                                <th>JABATAN</th>
                                <th width="70">NILAI</th>
                                <th width="140">PREDIKAT</th>
                            </tr>
                        </thead>
                        <tbody id="unverifyTableBody">
                            <!-- Diisi via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn-modal-cancel" data-close-modal="modalBulkUnverify">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-modal-unverify">
                    <i class="fas fa-undo"></i> Unverifikasi Semua
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- Library jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Inisialisasi Script Interaksi & Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const btnVerify = document.getElementById('btnBulkVerify');
            const btnUnverify = document.getElementById('btnBulkUnverify');
            const countVerify = document.getElementById('countVerify');
            const countUnverify = document.getElementById('countUnverify');

            const modalVerify = document.getElementById('modalBulkVerify');
            const modalUnverify = document.getElementById('modalBulkUnverify');

            function getSelectedRows() {
                const checked = Array.from(document.querySelectorAll('.row-checkbox:checked'));
                return checked.map(cb => ({
                    id: cb.value,
                    nup: cb.getAttribute('data-nup') || '-',
                    nama: cb.getAttribute('data-nama') || '-',
                    jabatan: cb.getAttribute('data-jabatan') || '-',
                    nilai: cb.getAttribute('data-nilai') || '0.00',
                    predikat: cb.getAttribute('data-predikat') || '-',
                    statusBadge: cb.getAttribute('data-status-badge') || '',
                    isVerified: cb.getAttribute('data-is-verified') === '1'
                }));
            }

            function updateCounters() {
                const checkedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));
                const checkedCount = checkedBoxes.length;

                let unverifiedCount = 0;
                let verifiedCount = 0;

                checkedBoxes.forEach(cb => {
                    if (cb.getAttribute('data-is-verified') === '1') {
                        verifiedCount++;
                    } else {
                        unverifiedCount++;
                    }
                });

                if (countVerify) countVerify.textContent = unverifiedCount;
                if (countUnverify) countUnverify.textContent = verifiedCount;

                // Jika ada yang belum diverifikasi dipilih dan TIDAK ada yang sudah diverifikasi tercampur
                if (unverifiedCount > 0 && verifiedCount === 0) {
                    if (btnVerify) btnVerify.removeAttribute('disabled');
                } else {
                    if (btnVerify) btnVerify.setAttribute('disabled', 'true');
                }

                // Jika ada yang sudah diverifikasi dipilih dan TIDAK ada yang belum diverifikasi tercampur
                if (verifiedCount > 0 && unverifiedCount === 0) {
                    if (btnUnverify) btnUnverify.removeAttribute('disabled');
                } else {
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

            // Helper render baris tabel modal
            function renderModalRows(tbody, hiddenContainer, items) {
                tbody.innerHTML = '';
                hiddenContainer.innerHTML = '';

                items.forEach((item, idx) => {
                    // hidden input
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'penilaian_ids[]';
                    input.value = item.id;
                    hiddenContainer.appendChild(input);

                    // tr
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${idx + 1}</td>
                        <td><code>${item.nup}</code></td>
                        <td><strong>${item.nama}</strong></td>
                        <td>${item.jabatan}</td>
                        <td>${item.nilai}</td>
                        <td>
                            <span style="background: #2563EB; color: #fff; font-size: 0.68rem; font-weight: 700; padding: 2px 7px; border-radius: 4px; display: inline-block;">
                                ${item.predikat}
                            </span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Buka Modal Verifikasi
            if (btnVerify) {
                btnVerify.addEventListener('click', function() {
                    const selected = getSelectedRows().filter(item => !item.isVerified);
                    if (selected.length === 0) return;

                    document.getElementById('verifyModalCount').textContent = selected.length;
                    renderModalRows(
                        document.getElementById('verifyTableBody'),
                        document.getElementById('verifyHiddenInputs'),
                        selected
                    );
                    modalVerify.style.display = 'flex';
                });
            }

            // Buka Modal Unverifikasi
            if (btnUnverify) {
                btnUnverify.addEventListener('click', function() {
                    const selected = getSelectedRows().filter(item => item.isVerified);
                    if (selected.length === 0) return;

                    document.getElementById('unverifyModalCount').textContent = selected.length;
                    renderModalRows(
                        document.getElementById('unverifyTableBody'),
                        document.getElementById('unverifyHiddenInputs'),
                        selected
                    );
                    modalUnverify.style.display = 'flex';
                });
            }

            // Tutup Modal
            document.querySelectorAll('[data-close-modal]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-close-modal');
                    const targetEl = document.getElementById(targetId);
                    if (targetEl) targetEl.style.display = 'none';
                });
            });

            // Tutup jika klik di backdrop luar
            [modalVerify, modalUnverify].forEach(m => {
                if (m) {
                    m.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.style.display = 'none';
                        }
                    });
                }
            });
        });
        
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endpush

@endsection