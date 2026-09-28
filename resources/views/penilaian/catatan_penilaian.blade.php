@extends('layout.app')

@push('styles')
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;

    /* Crimson Maroon Palette */
    --maroon-primary: #7A1C38;
    --maroon-hover: #5C1329;
    --maroon-soft: #FBF0F3;
    --maroon-border: #F3D5DD;
    --maroon-gradient: linear-gradient(135deg, #7A1C38 0%, #9E2A4B 100%);
    --maroon-glow: rgba(122, 28, 56, 0.15);

    /* Neutral System Shades */
    --bg-page: #F8FAFC;
    --surface: #FFFFFF;
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #94A3B8;
    --border-color: #CBD5E1;
    --border-light: #E2E8F0;

    /* Stat Card Icon & Badge Colors */
    --stat-pink-bg: #FDF2F8;
    --stat-pink-icon: #DB2777;
    --stat-green-bg: #ECFDF5;
    --stat-green-icon: #059669;
    --stat-red-bg: #FEF2F2;
    --stat-red-icon: #DC2626;
    --stat-yellow-bg: #FEFCE8;
    --stat-yellow-icon: #D97706;
    --stat-slate-bg: #F1F5F9;
    --stat-slate-icon: #475569;

    --radius-lg: 0.75rem;
    --radius-md: 0.5rem;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    background-color: var(--bg-page);
    font-family: var(--font-main);
    color: var(--text-primary);
    -webkit-font-smoothing: antialiased;
}

.main-wrapper {
    padding: 1.5rem;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
}

/* --- Breadcrumb --- */
.breadcrumb-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--maroon-primary);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.breadcrumb-title span {
    color: var(--text-muted);
    font-weight: 400;
}

/* --- Card Base --- */
.card-box {
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    background: var(--surface);
    margin-bottom: 1.25rem;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.card-header-soft {
    background: var(--maroon-primary);
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 1.25rem;
}

/* --- Custom Select & Inputs --- */
.select-wrapper {
    width: 100%;
    max-width: 480px;
}

.custom-select, .custom-input {
    width: 100%;
    padding: 0.6rem 0.85rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-main);
    outline: none;
    background-color: var(--surface);
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.custom-select:focus, .custom-input:focus {
    border-color: var(--maroon-primary);
    box-shadow: 0 0 0 3px var(--maroon-glow);
}

/* --- Toolbar / Header Section --- */
.toolbar-card {
    background: var(--surface);
    padding: 1.25rem 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    position: relative;
    margin-bottom: 1.25rem;
}

.toolbar-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0; width: 5px;
    background: var(--maroon-gradient);
    border-top-left-radius: var(--radius-lg);
    border-bottom-left-radius: var(--radius-lg);
}

.toolbar-icon-wrapper {
    padding: 0.75rem;
    background: var(--maroon-gradient);
    color: #ffffff;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.toolbar-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--text-primary);
}

.toolbar-subtitle {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 0.2rem;
}

.toolbar-subtitle-highlight {
    font-weight: 700;
    color: var(--maroon-primary);
}

/* --- Stat Cards Grid --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: var(--shadow-sm);
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-top: 0.25rem;
}

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

/* Stat Icon Color Variants */
.stat-icon.pink { background: var(--stat-pink-bg); color: var(--stat-pink-icon); }
.stat-icon.green { background: var(--stat-green-bg); color: var(--stat-green-icon); }
.stat-icon.red { background: var(--stat-red-bg); color: var(--stat-red-icon); }
.stat-icon.yellow { background: var(--stat-yellow-bg); color: var(--stat-yellow-icon); }
.stat-icon.slate { background: var(--stat-slate-bg); color: var(--stat-slate-icon); }

/* --- Filter Bar --- */
.filter-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin-bottom: 1.25rem;
}

.filter-title {
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto;
    gap: 0.75rem;
    align-items: center;
}

.btn-primary {
    background: var(--maroon-primary);
    color: #ffffff;
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.2s ease;
    white-space: nowrap;
}

.btn-primary:hover {
    background: var(--maroon-hover);
}

/* --- Table Styling --- */
.table-container {
    background-color: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-card);
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-custom {
    width: 100%;
    /* Naikkan min-width agar horizontal scroll aktif secara ideal jika layar sempit */
    min-width: 1500px; 
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
    padding: 0.75rem 0.65rem;
    vertical-align: middle;
    word-break: normal; /* Diubah dari break-word agar teks tidak terpotong acak */
    overflow-wrap: normal;
    line-height: 1.4;
}

.table-custom th {
    white-space: nowrap;
}

.table-custom td {
    color: var(--text-primary);
}

/* Badge Predikat & Status agar tidak pernah ter-wrap ke bawah */
.badge-status-danger,
.badge-status-success,
.badge-status-warning,
.badge-status-gray {
    white-space: nowrap;
    display: inline-block;
    max-width: 100%;
    text-overflow: ellipsis;
    overflow: hidden;
}

.table-action-cell {
    width: 90px;
    min-width: 90px;
}

/* Badges */
.badge {
    padding: 0.25rem 0.6rem;
    border-radius: 9999px;
    font-size: 0.725rem;
    font-weight: 700;
    display: inline-block;
}

.badge-draft {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #F59E0B;
}

.badge-espektasi {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #F59E0B;
}

/* Action Button Table */
.btn-icon-action {
    background: #FEF2F2;
    color: var(--maroon-primary);
    border: 1px solid var(--maroon-border);
    padding: 0.35rem 0.5rem;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-icon-action:hover {
    background: var(--maroon-primary);
    color: #ffffff;
}

.main-wrapper {
    max-width: none;
    background: #ffffff;
    color: var(--text-primary);
}

.main-content-column {
    width: 100%;
    min-width: 0;
}

.card-box {
    border: 2px solid #a4a8ab;
    border-radius: 8px;
    margin-bottom: 1rem;
    box-shadow: none;
}

.card-header-soft {
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #a4a8ab;
}

.card-header-daftarpegawai {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: #ffffff;
    border-bottom: 2px solid #a4a8ab;
}

.card-title-daftarpegawai {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: var(--text-primary);
    font-size: 0.85rem;
    font-weight: 700;
}

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

.toolbar-card {
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
}

.toolbar-header {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.toolbar-icon-wrapper {
    padding: 0.625rem;
    box-shadow: 0 4px 12px var(--maroon-glow);
}

.toolbar-title {
    font-size: 1.25rem;
    line-height: 1.2;
}

.toolbar-subtitle {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8125rem;
}

.stats-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.card-stat {
    height: 100%;
    padding: 1rem 1.125rem;
    border: 1px solid var(--border-light);
    border-radius: 0.75rem;
    background: var(--surface);
    box-shadow: var(--shadow-card);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
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
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-value {
    margin-top: 0;
    font-size: 1.5rem;
    line-height: 1;
}

.stat-icon {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border: 1px solid transparent;
    border-radius: 0.5rem;
}

.text-primary-custom { color: var(--maroon-primary) !important; }
.bg-primary-light { background: var(--maroon-soft); color: var(--maroon-primary); border-color: var(--maroon-border); }
.bg-success-light { background: #D1FAE5; color: #065F46; border-color: #10B981; }
.bg-warning-light { background: #FEF3C7; color: #92400E; border-color: #F59E0B; }

.filter-card {
    margin-bottom: 0;
    padding: 1rem;
    border-color: var(--border-light);
    border-radius: 0.75rem;
    box-shadow: var(--shadow-sm);
}

.filter-grid {
    grid-template-columns: minmax(180px, 1.4fr) repeat(2, minmax(160px, 1fr)) minmax(130px, 0.7fr);
    gap: 0.75rem;
}

.btn-maroon-custom {
    min-height: 40px;
    padding: 0.55rem 1.25rem;
    border: 0;
    border-radius: 0.5rem;
    background: var(--maroon-gradient);
    color: #ffffff;
    font-family: var(--font-main);
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: opacity 0.15s ease;
}

.btn-maroon-custom:hover,
.btn-export:hover {
    color: #ffffff;
    opacity: 0.92;
}

.btn-export {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    border: 1px solid #10B981;
    border-radius: 0.5rem;
    padding: 0.5rem 0.8rem;
    background: #059669;
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
}

.table-container,
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.custom-table {
    min-width: 1500px;
    margin: 0;
    table-layout: fixed;
    border-collapse: collapse;
    font-size: 0.8125rem;
}

.custom-table th {
    padding: 0.75rem 0.65rem;
    background: var(--maroon-gradient);
    color: #ffffff;
    font-size: 0.725rem;
    letter-spacing: 0.03em;
    white-space: nowrap;
    border: 0;
    border-right: 1px solid rgba(255, 255, 255, 0.2);
}

.custom-table td {
    padding: 0.75rem 0.65rem;
    color: var(--text-primary);
    line-height: 1.4;
    vertical-align: middle;
    overflow-wrap: anywhere;
    border: 0;
    border-bottom: 1px solid var(--border-light);
    border-right: 1px solid rgba(148, 163, 184, 0.28);
}

.custom-table tbody tr:hover {
    background: var(--maroon-soft);
}

.custom-table tbody tr:last-child td {
    border-bottom: 0;
}

.badge {
    padding: 0.25rem 0.55rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}

.badge-emerald { background: #D1FAE5; color: #065F46; border-color: #10B981; }
.badge-blue { background: #DBEAFE; color: #1E40AF; border-color: #3B82F6; }
.badge-amber { background: #FEF3C7; color: #92400E; border-color: #F59E0B; }
.badge-orange { background: #FFEDD5; color: #9A3412; border-color: #F97316; }
.badge-rose { background: #FFE4E6; color: #9F1239; border-color: #F43F5E; }

.badge-status {
    display: inline-block;
    max-width: 100%;
    padding: 0.35rem 0.65rem;
    border: 1px solid var(--border-color);
    border-radius: 999px;
    background: #F1F5F9;
    color: var(--text-secondary);
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}

.badge-status.diajukan,
.badge-status.disetujui,
.badge-status.selesai,
.badge-status.submit,
.badge-status.submitted,
.badge-status.verified { background: #D1FAE5; color: #065F46; border-color: #10B981; }
.badge-status.dikembalikan { background: #FFE4E6; color: #9F1239; border-color: #F43F5E; }
.badge-status.draft { background: #FEF3C7; color: #92400E; border-color: #F59E0B; }

.btn-action-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.1rem;
    height: 2.1rem;
    border: 1px solid var(--maroon-border);
    border-radius: 0.5rem;
    background: var(--maroon-soft);
    color: var(--maroon-primary);
    text-decoration: none;
    transition: all 0.18s ease;
}

.btn-action-view:hover {
    transform: translateY(-1px);
    border-color: var(--maroon-primary);
    background: var(--maroon-primary);
    color: #ffffff;
}

/* Modal Overlay */
.assessment-modal {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
}

.assessment-modal.is-open { 
    display: flex; 
}

/* Modal Box Container */
.assessment-modal-panel {
    width: min(920px, 100%);
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    border-radius: 0.75rem;
    background: #ffffff;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden; /* Mencegah modal luar ter-scroll */
}

/* Header & Footer Sticky */
.assessment-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    background: #ffffff;
}

.assessment-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color, #e2e8f0);
    background: #f8fafc;
}

.assessment-modal-title {
    margin: 0;
    color: var(--text-primary, #0f172a);
    font-size: 1.125rem;
    font-weight: 700;
}

.assessment-modal-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 0.375rem;
    background: #ffffff;
    color: #64748b;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.2s;
}

.assessment-modal-close:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* Body (Scrollable) */
.assessment-modal-body { 
    padding: 1.5rem; 
    overflow-y: auto;
}

/* Summary Grid Cards */
.assessment-modal-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.summary-card {
    padding: 0.875rem 1rem;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 0.5rem;
    background: #f8fafc;
}

.assessment-modal-label {
    display: block;
    margin-bottom: 0.25rem;
    color: #64748b;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.summary-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-primary, #0f172a);
}

.text-maroon {
    color: var(--maroon-primary, #800020);
}

/* General Note Box */
.assessment-general-note { 
    margin-bottom: 1.5rem; 
    padding: 0.875rem 1rem;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 0.5rem;
    background: #f8fafc;
}

.note-content {
    font-size: 0.875rem;
    color: var(--text-primary, #334155);
    margin-top: 0.25rem;
}

/* Table Styling (Rapi & Bersih) */
.modal-table-wrapper {
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 0.5rem;
    overflow: hidden;
}

.assessment-detail-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8125rem;
    text-align: left;
}

.assessment-detail-table thead tr {
    background-color: var(--maroon-border);
    border-bottom: 1px solid var(--border-color, #cbd5e1);
}

.assessment-detail-table th {
    padding: 0.75rem 1rem;
    font-weight: 700;
    color: #334155;
    white-space: nowrap;
}

.assessment-detail-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    color: #475569;
    vertical-align: top;
}

.assessment-detail-table tbody tr:last-child td {
    border-bottom: none;
}

.assessment-detail-table tbody tr:hover {
    background-color: #f8fafc;
}

.btn-secondary-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
    font-family: inherit;
    color: #475569;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
    cursor: pointer;
    outline: none;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Efek saat kursor diarahkan ke tombol (Hover) */
.btn-secondary-custom:hover {
    background-color: #f8fafc;
    color: #1e293b;
    border-color: #94a3b8;
}

/* Responsif untuk Mobile */
@media (max-width: 768px) {
    .assessment-modal-summary { 
        grid-template-columns: 1fr; 
    }
    .assessment-modal-body { 
        padding: 1rem; 
    }
}

.pagination {
    gap: 0.25rem;
    margin-bottom: 0;
}

.pagination .page-link {
    border-radius: 0.35rem;
    color: var(--maroon-primary);
}

.pagination .active .page-link {
    border-color: var(--maroon-primary);
    background: var(--maroon-primary);
    color: #ffffff;
}

@media (max-width: 1200px) {
    .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .main-wrapper {
        padding: 1rem;
    }

    .breadcrumb-title {
        flex-wrap: wrap;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .toolbar-card {
        padding: 1rem 1.1rem;
    }

    .toolbar-title {
        font-size: 1.1rem;
    }

    .stats-grid,
    .filter-grid {
        grid-template-columns: 1fr;
    }

    .card-header-daftarpegawai {
        align-items: flex-start;
        flex-direction: column;
    }

    .detail-pagination-label,
    .mt-3.d-flex.justify-content-between.align-items-center > div:first-child {
        font-size: 0.8rem;
    }
}

.catatan-table-wrap {
    width: 100%;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.custom-table.catatan-table {
    width: 100%;
    min-width: 1800px;
    table-layout: fixed;
    border-collapse: collapse;
    background: var(--surface);
}

.custom-table.catatan-table th {
    background: var(--maroon-primary);
}


.custom-table.catatan-table td {
    padding: 0.75rem 0.65rem;
    color: var(--text-primary);
    line-height: 1.4;
    vertical-align: middle;
    overflow-wrap: break-word;
    border: 0;
    border-bottom: 1px solid var(--border-light);
    border-right: 1px solid rgba(148, 163, 184, 0.28);
}

.custom-table.catatan-table tbody tr:hover {
    background: var(--maroon-soft);
}

.custom-table.catatan-table tbody tr:last-child td {
    border-bottom: 0;
}

.custom-table.catatan-table th:nth-child(1),
.custom-table.catatan-table td:nth-child(1) { width: 52px; }
.custom-table.catatan-table th:nth-child(2),
.custom-table.catatan-table td:nth-child(2) { width: 120px; }
.custom-table.catatan-table th:nth-child(3),
.custom-table.catatan-table td:nth-child(3) { width: 170px; }
.custom-table.catatan-table th:nth-child(4),
.custom-table.catatan-table td:nth-child(4) { width: 155px; }
.custom-table.catatan-table th:nth-child(5),
.custom-table.catatan-table td:nth-child(5) { width: 145px; }
.custom-table.catatan-table th:nth-child(6),
.custom-table.catatan-table td:nth-child(6) { width: 260px; }
.custom-table.catatan-table th:nth-child(7),
.custom-table.catatan-table td:nth-child(7) { width: 145px; }
.custom-table.catatan-table th:nth-child(8),
.custom-table.catatan-table td:nth-child(8) { width: 260px; }
.custom-table.catatan-table th:nth-child(9),
.custom-table.catatan-table td:nth-child(9) { width: 110px; }
.custom-table.catatan-table th:nth-child(10),
.custom-table.catatan-table td:nth-child(10) { width: 180px; }
.custom-table.catatan-table th:nth-child(11),
.custom-table.catatan-table td:nth-child(11) { width: 125px; }
.custom-table.catatan-table th:nth-child(12),
.custom-table.catatan-table td:nth-child(12) { width: 75px; }

.catatan-note {
    display: block;
    white-space: normal;
    overflow-wrap: anywhere;
    line-height: 1.45;
}

.catatan-table .badge {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}

.catatan-pagination {
    gap: 1rem;
    padding-top: 0.75rem;
    color: var(--text-secondary);
    font-size: 0.8125rem;
}

.catatan-pagination > div:last-child {
    max-width: 100%;
    overflow-x: auto;
}

@media (max-width: 768px) {
    .catatan-pagination {
        align-items: flex-start !important;
        flex-direction: column;
    }

    .card-header-daftarpegawai {
        flex-direction: row;
        align-items: center;
    }
}
</style>
<div class="main-wrapper">
    <!-- BREADCRUMB HEADER -->
    <div class="header-bar d-flex justify-content-between align-items-center mb-3">
        <div class="breadcrumb-title">
            Catatan Penilaian <span>» Catatan Penilai & Verifikator per Periode</span>
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
                    <form method="GET" action="{{ route('catatan-penilaian.index') }}" id="formSelectPeriode">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="toolbar-title">Catatan Penilaian Pegawai</h1>
                    <p class="toolbar-subtitle">
                        @if($hasPeriodeSelected && $selectedPeriode)
                            Periode Penilaian: <strong>{{ $selectedPeriode->nama_periode }}</strong>
                        @else
                            Silakan pilih periode penilaian untuk melihat catatan penilai dan verifikator.
                        @endif
                    </p>
                </div>
            </div>
        </div><br>

        @if($hasPeriodeSelected)
            <!-- CARD RINGKASAN STATISTIK -->
            <div class="stats-grid mb-3">
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Total Catatan</div>
                            <div class="stat-value text-primary-custom">{{ $totalCatatan }}</div>
                        </div>
                        <div class="stat-icon bg-primary-light">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Verifikasi</div>
                            <div class="stat-value text-success">{{ $verifikasi }}</div>
                        </div>
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Belum</div>
                            <div class="stat-value text-warning">{{ $belum }}</div>
                        </div>
                        <div class="stat-icon bg-warning-light">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR CATATAN TABLE CARD -->
            <div class="card-box">
                <div class="card-header-daftarpegawai d-flex justify-content-between align-items-center">
                    <div class="card-title-daftarpegawai">
                        <i class="fas fa-list me-2"></i> Daftar Catatan Penilaian Pegawai
                    </div>
                    <a href="{{ route('catatan-penilaian.export', request()->all()) }}" class="btn-export">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                </div>

                <div class="p-3">
                    <!-- FILTER FORM -->
                    <div class="filter-card mb-3">
                        <form method="GET" action="{{ route('catatan-penilaian.index') }}" class="filter-grid">
                            <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">

                            <div>
                                <input type="text" name="search" class="form-control-custom" placeholder="Cari nama / NUP..." value="{{ request('search') }}">
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
                                <select name="dept_id" class="select2">
                                    <option value="">- Semua Departemen -</option>
                                    @foreach($listDepartemen as $dept)
                                        <option value="{{ $dept->dept_id }}" {{ request('dept_id') == $dept->dept_id ? 'selected' : '' }}>
                                            {{ $dept->dept_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn-maroon-custom" style="width: 100%; justify-content: center;">
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TABEL DATA -->
                    <div class="table-responsive catatan-table-wrap">
                        <table class="table table-bordered table-hover align-middle custom-table catatan-table">
                            <thead>
                                <tr class="text-center">
                                    <th width="40">NO</th>
                                    <th>NUP</th>
                                    <th>NAMA</th>
                                    <th>DEPARTEMEN</th>
                                    <th>PENILAI</th>
                                    <th>CATATAN PENILAI</th>
                                    <th>VERIFIKATOR</th>
                                    <th>CATATAN VERIFIKATOR</th>
                                    <th>TOTAL NILAI</th>
                                    <th>PREDIKAT</th>
                                    <th>STATUS</th>
                                    <th width="60">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penilaians as $index => $row)
                                    @php
                                        $nilai = (float) ($row->total_nilai ?? 0);
                                        if ($nilai >= 90) $badgeStyle = 'badge-emerald';
                                        elseif ($nilai >= 80) $badgeStyle = 'badge-blue';
                                        elseif ($nilai >= 70) $badgeStyle = 'badge-amber';
                                        elseif ($nilai >= 60) $badgeStyle = 'badge-orange';
                                        else $badgeStyle = 'badge-rose';

                                        $catatanPenilai = $row->catatan;
                                        $catatanVerifikator = filled($row->catatan_verifikator)
                                            ? $row->catatan_verifikator
                                            : $row->details->pluck('verif_catatan')->filter(fn ($catatan) => filled($catatan))->implode(' | ');
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $penilaians->firstItem() + $index }}</td>
                                        <td>{{ $row->pgw_nup ?? '-' }}</td>
                                        <td class="fw-bold">{{ $row->pgw_nama ?? '-' }}</td>
                                        <td>{{ $row->pgw_dept_name ?? '-' }}</td>
                                        <td>{{ $row->penilai_manual ?? ($row->penilai->nama ?? '-') }}</td>
                                        <td><span class="catatan-note" title="{{ $catatanPenilai ?: '-' }}">{{ $catatanPenilai ?: '-' }}</span></td>
                                        <td>{{ $row->verifikator->nama ?? '-' }}</td>
                                        <td><span class="catatan-note" title="{{ $catatanVerifikator ?: '-' }}">{{ $catatanVerifikator ?: '-' }}</span></td>
                                        <td class="text-center fw-bold">{{ number_format($nilai, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeStyle }}" title="{{ $row->predikat ?? 'Belum ada' }}">
                                                <span class="badge-dot"></span>
                                                {{ $row->predikat ?? 'Belum ada' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-status {{ strtolower($row->status_nilai ?? 'draft') }}">
                                                {{ $row->status_nilai ?? 'Draft' }}
                                            </span>
                                        </td>
                                        @php
                                            $detailPenilaian = $row->details->map(function ($detail) {
                                                return [
                                                    'question' => $detail->pertanyaan,
                                                    'description' => $detail->deskripsi,
                                                    'weight' => $detail->bobot_pertanyaan_persen,
                                                    'score_code' => $detail->kode_nilai,
                                                    'score_name' => $detail->nama_nilai,
                                                    'score' => $detail->nilai_angka,
                                                    'note' => $detail->catatan,
                                                    'verifier_note' => $detail->verif_catatan,
                                                ];
                                            })->values();
                                        @endphp
                                        <td class="text-center">
                                            <button type="button" class="btn-action-view js-assessment-detail"
                                                title="Detail Penilaian"
                                                aria-label="Detail penilaian {{ $row->pgw_nama }}"
                                                data-employee="{{ $row->pgw_nama }}"
                                                data-total="{{ number_format($nilai, 2) }}"
                                                data-predikat="{{ $row->predikat ?? 'Belum ada' }}"
                                                data-general-note="{{ $row->catatan ?? '' }}"
                                                data-details="{{ json_encode($detailPenilaian, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4 text-muted">
                                            Data tidak ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-3 d-flex justify-content-between align-items-center catatan-pagination">
                        <div>
                            Menampilkan {{ $penilaians->firstItem() ?? 0 }} sampai {{ $penilaians->lastItem() ?? 0 }} dari {{ $penilaians->total() }} data
                        </div>
                        <div>
                            {{ $penilaians->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="assessment-modal" id="assessmentDetailModal" aria-hidden="true">
    <section class="assessment-modal-panel" role="dialog" aria-modal="true" aria-labelledby="assessmentModalTitle">
        
        <!-- Header -->
        <header class="assessment-modal-header">
            <div>
                <h2 class="assessment-modal-title" id="assessmentModalTitle">Detail Penilaian</h2>
                <div id="assessmentModalEmployee" class="text-muted small fw-semibold"></div>
            </div>
            <button type="button" class="assessment-modal-close" data-modal-close aria-label="Tutup">&times;</button>
        </header>
        
        <!-- Body (Akan di-scroll jika konten panjang) -->
        <div class="assessment-modal-body">
            
            <!-- Summary Metric Cards -->
            <div class="assessment-modal-summary">
                <div class="summary-card">
                    <span class="assessment-modal-label">Total Nilai</span>
                    <strong id="assessmentModalTotal" class="summary-value text-maroon"></strong>
                </div>
                <div class="summary-card">
                    <span class="assessment-modal-label">Predikat</span>
                    <strong id="assessmentModalPredikat" class="summary-value"></strong>
                </div>
                <div class="summary-card">
                    <span class="assessment-modal-label">Jumlah Pertanyaan</span>
                    <strong id="assessmentModalCount" class="summary-value"></strong>
                </div>
            </div>

            <!-- Catatan Umum -->
            <div class="assessment-general-note">
                <span class="assessment-modal-label">Catatan Umum Penilai</span>
                <div id="assessmentModalGeneralNote" class="note-content"></div>
            </div>

            <!-- Tabel Detail -->
            <div class="table-responsive modal-table-wrapper">
                <table class="assessment-detail-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Pertanyaan</th>
                            <th style="width: 10%; text-align: center;">Bobot</th>
                            <th style="width: 20%;">Nilai</th>
                            <th style="width: 20%;">Catatan Penilai</th>
                            <th style="width: 20%;">Catatan Verifikator</th>
                        </tr>
                    </thead>
                    <tbody id="assessmentModalDetails">
                        <!-- Content via JS -->
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Footer -->
        <footer class="assessment-modal-footer">
            <button type="button" class="btn-secondary-custom" data-modal-close>Tutup</button>
        </footer>

    </section>
</div>

@push('scripts')
    <!-- Library jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Inisialisasi Select2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('assessmentDetailModal');
            const detailBody = document.getElementById('assessmentModalDetails');

            function closeModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('.js-assessment-detail').forEach(function (button) {
                button.addEventListener('click', function () {
                    const details = JSON.parse(button.dataset.details || '[]');
                    document.getElementById('assessmentModalEmployee').textContent = button.dataset.employee || '-';
                    document.getElementById('assessmentModalTotal').textContent = button.dataset.total || '0.00';
                    document.getElementById('assessmentModalPredikat').textContent = button.dataset.predikat || '-';
                    document.getElementById('assessmentModalCount').textContent = details.length;
                    document.getElementById('assessmentModalGeneralNote').textContent = button.dataset.generalNote || '-';
                    detailBody.replaceChildren();

                    details.forEach(function (detail) {
                        const row = document.createElement('tr');
                        const questionCell = document.createElement('td');
                        questionCell.textContent = detail.question || '-';
                        if (detail.description) {
                            const description = document.createElement('small');
                            description.className = 'd-block text-muted';
                            description.textContent = detail.description;
                            questionCell.appendChild(description);
                        }
                        row.appendChild(questionCell);
                        [
                            detail.weight ? detail.weight + '%' : '-',
                            [detail.score_code, detail.score_name, detail.score].filter(Boolean).join(' / ') || '-',
                            detail.note || '-',
                            detail.verifier_note || '-'
                        ].forEach(function (value) {
                            const cell = document.createElement('td');
                            cell.textContent = value;
                            row.appendChild(cell);
                        });
                        detailBody.appendChild(row);
                    });

                    if (details.length === 0) {
                        const row = document.createElement('tr');
                        const cell = document.createElement('td');
                        cell.colSpan = 5;
                        cell.className = 'text-center text-muted';
                        cell.textContent = 'Tidak ada rincian pertanyaan.';
                        row.appendChild(cell);
                        detailBody.appendChild(row);
                    }

                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            modal.querySelectorAll('[data-modal-close]').forEach(function (button) {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
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