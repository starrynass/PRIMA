@extends('layout.app')

@section('content')

<style>
/* =========================================================
   1. Global Container & Header Banner Maroon
   ========================================================= */
.dashboard-container {
    padding: 0.5rem 0;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}

.dash-banner {
    background: linear-gradient(135deg, #7A1C38 0%, #4A0E17 100%);
    border-radius: 0.875rem;
    padding: 1.5rem 2rem;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(122, 28, 56, 0.2);
}

.dash-sub { 
    font-size: 0.7rem; 
    font-weight: 700; 
    opacity: 0.85; 
    letter-spacing: 0.05em; 
    text-transform: uppercase;
}
.dash-title { font-size: 1.5rem; font-weight: 800; margin: 0.25rem 0; }
.dash-desc { font-size: 0.8125rem; opacity: 0.9; margin: 0; }
.dash-actions { display: flex; gap: 0.75rem; align-items: center; }

.form-select-custom {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-size: 0.8125rem;
    font-weight: 600;
    outline: none;
    cursor: pointer;
}
.form-select-custom option {
    color: #0f172a;
    background: #ffffff;
}

/* =========================================================
   2. Metric Ringkasan Cards (3 Card + 4 Card Berurutan Di Atas)
   ========================================================= */
.dash-cards-row-top {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.dash-cards-row-bottom {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 5px solid #cbd5e1;
    border-radius: 0.875rem;
    padding: 1.25rem 1rem;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
    transition: all 0.25s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
}

.metric-card.card-total   { border-left-color: #0284c7; }
.metric-card.card-success { border-left-color: #16a34a; }
.metric-card.card-warning { border-left-color: #d97706; background: #fffcf5; }
.metric-card.card-undo    { border-left-color: #ea580c; }
.metric-card.card-danger  { border-left-color: #dc2626; background: #fff8f8; }
.metric-card.card-purple  { border-left-color: #9333ea; }
.metric-card.card-gray    { border-left-color: #64748b; }

.card-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.metric-value {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}

.metric-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #334155;
    margin: 0.4rem 0 0.3rem 0;
}

.metric-sub {
    font-size: 0.725rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

/* =========================================================
   3. Baris Widget Visualisasi (Di Bawah Seluruh Card Ringkasan)
   ========================================================= */
.widget-row {
    display: grid;
    grid-template-columns: 550px 1fr 350px;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.widget-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    padding: 1.25rem;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.25rem;
}

.widget-title-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.widget-icon-box {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.5rem;
    background: #fbf0f3;
    color: #7A1C38;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.widget-title {
    font-size: 0.925rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
}

.widget-subtitle {
    font-size: 0.725rem;
    color: #64748b;
    margin: 0;
}

.widget-badge-realtime {
    background: #e0f2fe;
    color: #0284c7;
    font-size: 0.6875rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 0.375rem;
}

/* Donut Progress */
.completion-body {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-link-detail {
    color: #801B2B;
    font-size: 0.8125rem; 
    font-weight: 600; 
    text-decoration: none; 
    transition: color 0.2s ease, opacity 0.2s ease;
}

.btn-link-detail:hover {
    color: #5c131f; 
    text-decoration: none;
    opacity: 0.9;
}

.circle-progress-wrapper {
    position: relative;
    width: 160px;
    height: 160px;
    flex-shrink: 0;
    position: relative;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
}

.circle-progress-svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
    overflow: visible;
}

.circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 8;
}

.circle-fill {
    fill: none;
    stroke: #16a34a;
    stroke-width: 8;
    stroke-linecap: round;
    transition: stroke-dasharray 0.6s ease;
}

.circle-text {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.circle-percent {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}

.circle-label {
    font-size: 0.625rem;
    color: #64748b;
    font-weight: 600;
}

.completion-legend {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
   
}

.legend-dot-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.dot-verified   { background: #16a34a; }
.dot-waiting    { background: #d97706; }
.dot-returned   { background: #dc2626; }
.dot-draft      { background: #0284c7; }
.dot-notrated   { background: #991b1b; }

.legend-value {
    font-weight: 700;
    color: #0f172a;
    font-family: monospace;
    padding-left: 0.5rem;
}

/* Progress per Lingkup */
.direktorat-list {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.direktorat-item {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 0.4rem;
}

.direktorat-name {
    width: 135px;
    font-size: 0.725rem;
    font-weight: 700;
    color: #334155;
    flex-shrink: 0;
}

.progress-bar-bg {
    flex-grow: 1;
    height: 10px;
    background: #1e293b;
    border-radius: 6px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: #16a34a;
    border-radius: 6px;
    transition: width 0.5s ease;
}

.direktorat-count {
    font-size: 0.8rem;
    font-weight: 700;
    color: #0f172a;
    font-family: monospace;
    width: 40px;
    text-align: right;
    flex-shrink: 0;
}

.widget-footer-info {
    margin-top: 0.85rem;
    background: #f0f9ff;
    border-radius: 0.5rem;
    padding: 0.55rem 0.75rem;
    font-size: 0.7rem;
    color: #0369a1;
    display: flex;
    gap: 0.4rem;
    align-items: flex-start;
}

.empty-alert-box {
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #94a3b8;
    font-size: 0.8rem;
    padding: 1.5rem 1rem;
}

/* Color & Badges */
.bg-blue-light   { background: #e0f2fe; color: #0284c7; }
.bg-green-light  { background: #dcfce7; color: #16a34a; }
.bg-amber-light  { background: #fef3c7; color: #d97706; }
.bg-orange-light { background: #ffedd5; color: #ea580c; }
.bg-red-light    { background: #fee2e2; color: #dc2626; }
.bg-purple-light { background: #f3e8ff; color: #9333ea; }
.bg-blue-dark-light { background: #e0e7ff; color: #3730a3; }

.badge-pill { padding: 0.15rem 0.5rem; border-radius: 0.375rem; font-size: 0.6875rem; font-weight: 700; }
.bg-green-soft  { background: #d1fae5; color: #065f46; }
.bg-red-soft    { background: #ffe4e6; color: #991b1b; }
.bg-orange-soft { background: #ffedd5; color: #9a3412; }

/* =========================================================
   4. MODAL POPUP OVERLAY
   ========================================================= */
.assessment-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
}

.assessment-modal.is-open { 
    display: flex !important;
}

.assessment-modal-panel {
    width: min(920px, 100%);
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    border-radius: 0.75rem;
    background: #ffffff;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    animation: modalPop 0.2s ease-out;
}

@keyframes modalPop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.assessment-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

.assessment-modal-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.assessment-modal-close {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    color: #64748b;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}
.assessment-modal-close:hover { background: #f1f5f9; color: #0f172a; }

.assessment-modal-body {
    padding: 1.25rem 1.5rem;
    overflow-y: auto;
}

.assessment-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    background: #f8fafc;
}

.modal-table-wrapper {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.assessment-detail-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8125rem;
    text-align: left;
}

.assessment-detail-table th {
    background-color: #f8fafc;
    padding: 0.75rem 1rem;
    font-weight: 700;
    color: #334155;
    border-bottom: 1px solid #cbd5e1;
    text-transform: uppercase;
    font-size: 0.725rem;
}

.assessment-detail-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

/* Styling Badge Status & Predikat */
.badge-status-danger,
.badge-status-success,
.badge-status-warning,
.badge-status-gray {
    white-space: nowrap;
    display: inline-block;
    max-width: 100%;
    text-overflow: ellipsis;
    overflow: hidden;
    padding: 0.35rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
}

.badge-status-gray { background-color: #F1F5F9; color: var(--text-secondary, #475569); border: 1px solid var(--border-color, #CBD5E1); }
.badge-status-success { background-color: var(--badge-emerald-bg, #D1FAE5); color: var(--badge-emerald-text, #065F46); border: 1px solid #10B981; }
.badge-status-warning { background-color: var(--badge-amber-bg, #FEF3C7); color: var(--badge-amber-text, #92400E); border: 1px solid #F59E0B; }
.badge-status-danger { background-color: var(--badge-rose-bg, #FFE4E6); color: var(--badge-rose-text, #9F1239); border: 1px solid #F43F5E; }

.badge {
    padding: 0.2rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    border-width: 1px;
    border-style: solid;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.badge-emerald { background-color: #D1FAE5; color: #065F46; border-color: #10B981; }
.badge-blue { background-color: #DBEAFE; color: #1E40AF; border-color: #3B82F6; }
.badge-amber { background-color: #FEF3C7; color: #92400E; border-color: #F59E0B; }
.badge-orange { background-color: #FFEDD5; color: #9A3412; border-color: #F97316; }
.badge-rose { background-color: #FFE4E6; color: #9F1239; border-color: #F43F5E; }

.alert-overdue-container {
    background: #FFF5F5;
    border: 1px solid #FECDD3;
    border-radius: 0.875rem;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(225, 29, 72, 0.05);
}

.alert-overdue-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 1rem;
    border-bottom: 1px solid #FFE4E6;
}

.alert-overdue-title-box {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.alert-overdue-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.625rem;
    background: #E11D48;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.alert-overdue-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #9F1239;
    margin: 0 0 0.2rem 0;
}

.alert-overdue-desc {
    font-size: 0.8125rem;
    color: #881337;
    margin: 0;
}

.btn-toggle-alert {
    background: #FFFFFF;
    border: 1px solid #FECDD3;
    color: #9F1239;
    font-size: 0.8125rem;
    font-weight: 700;
    padding: 0.4rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: all 0.2s ease;
}

.btn-toggle-alert:hover {
    background: #FFF1F2;
    border-color: #FDA4AF;
}

/* Detail Box Dalam Banner */
.alert-overdue-body {
    display: grid;
    grid-template-columns: 280px repeat(5, 1fr) 140px;
    gap: 0.75rem;
    align-items: center;
    padding-top: 1rem;
}

.overdue-info-left {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.overdue-periode-name {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0F172A;
}

.overdue-deadline-text {
    font-size: 0.75rem;
    color: #64748B;
}

.badge-terlambat-pill {
    background: #DC2626;
    color: #FFFFFF;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    width: fit-content;
}

/* Stat Box Mini di Dalam Banner Alert */
.overdue-stat-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 0.5rem;
    padding: 0.6rem;
    text-align: center;
}

.overdue-stat-val {
    font-size: 1.125rem;
    font-weight: 800;
    line-height: 1.1;
}

.overdue-stat-lbl {
    font-size: 0.6875rem;
    font-weight: 700;
    color: #64748B;
    margin-top: 0.2rem;
}

/* Mini Progress Bar Kanan */
.overdue-progress-box {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.3rem;
}

.overdue-progress-text {
    font-size: 0.75rem;
    font-weight: 800;
    color: #334155;
}

.overdue-progress-bg {
    width: 100%;
    height: 8px;
    background: #CBD5E1;
    border-radius: 4px;
    overflow: hidden;
}

.overdue-progress-fill {
    height: 100%;
    background: #E11D48;
    border-radius: 4px;
}

.overdue-progress-percent {
    font-size: 0.75rem;
    font-weight: 800;
    color: #0F172A;
}

/* Container Scrollable Pegawai Lewat Batas */
.overdue-list-container {
    max-height: 260px;
    overflow-y: auto;
    padding-right: 0.35rem;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

/* Scrollbar styling halus */
.overdue-list-container::-webkit-scrollbar {
    width: 6px;
}
.overdue-list-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.overdue-list-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.overdue-list-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Card Item Pegawai Lewat Batas */
.overdue-item-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 0.65rem 0.85rem;
    transition: all 0.2s ease;
}

.overdue-item-card:hover {
    border-color: #fca5a5;
    background-color: #fff5f5;
}

.overdue-user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    overflow: hidden;
}

.overdue-avatar {
    width: 34px;
    height: 34px;
    border-radius: 0.5rem;
    background: #ffe4e6;
    color: #9f1239;
    font-weight: 800;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.overdue-user-details {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.overdue-user-name {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.overdue-user-sub {
    font-size: 0.7rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.followup-pill {
    background: #ffe4e6;
    color: #9f1239;
    border: none;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
    transition: all 0.2s ease;
}


.empty-periode-card {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 0.875rem;
    padding: 3rem 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.02);
}

.empty-periode-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.empty-periode-title {
    font-size: 1.125rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.35rem;
}

.empty-periode-desc {
    font-size: 0.825rem;
    color: #64748b;
    margin-bottom: 0;
}

@media (max-width: 1200px) { .widget-row { grid-template-columns: 1fr; } }
@media (max-width: 992px) { .dash-cards-row-top, .dash-cards-row-bottom { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .dash-cards-row-top, .dash-cards-row-bottom { grid-template-columns: 1fr; } }
</style>

<div class="dashboard-container">

    <!-- Header Banner Maroon -->
    <div class="dash-banner">
        <div>
            <span class="dash-sub">EXECUTIVE PERFORMANCE OVERVIEW</span>
            <h1 class="dash-title">Dashboard Monitoring Penilaian Pegawai</h1>
            <p class="dash-desc">Monitor progress pengisian, verifikasi berjenjang, distribusi predikat, dan pegawai dalam satu tampilan eksekutif.</p>
        </div>
        <div class="dash-actions">
            <select id="periodeSelect" class="form-select-custom">
                @foreach($periodes as $p)
                    <option value="{{ $p->periode_id }}" {{ $selectedPeriodeId ==$p->periode_id ? 'selected' : '' }}>
                        {{ $p->nama_periode ?? $p->periode_id }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-light btn-sm fw-bold px-3" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>
    </div>

    <!-- BARIS CARD RINGKASAN TOP (3 CARD) -->
    <div class="dash-cards-row-top">
        <div class="metric-card card-total" onclick="openDetailModal('total')">
            <div class="card-icon bg-blue-light"><i class="fas fa-users text-blue"></i></div>
            <div class="metric-value">{{ $totalPegawai }}</div>
            <div class="metric-label">Total Pegawai</div>
            <div class="metric-sub"><span class="badge-pill bg-green-soft text-green">Aktif</span> Periode Berjalan</div>
        </div>

        <div class="metric-card card-success" onclick="openDetailModal('terverifikasi')">
            <div class="card-icon bg-green-light"><i class="fas fa-check text-green"></i></div>
            <div class="metric-value">{{ $sudahTerverifikasi }}</div>
            <div class="metric-label">Sudah Terverifikasi</div>
            <div class="metric-sub"><span class="text-green fw-bold">{{ $percentVerifiedTotal }}%</span> Dari Total Pegawai</div>
        </div>

        <div class="metric-card card-warning" onclick="openDetailModal('menunggu')">
            <div class="card-icon bg-amber-light"><i class="fas fa-clock text-amber"></i></div>
            <div class="metric-value">{{ $menungguVerifikasi }}</div>
            <div class="metric-label">Menunggu Verifikasi</div>
            <div class="metric-sub"><span class="text-amber fw-bold">Perlu Aksi</span> Oleh Atasan</div>
        </div>
    </div>

    <!-- BARIS CARD RINGKASAN BOTTOM (4 CARD LANGSUNG DI BAWAHNYA) -->
    <div class="dash-cards-row-bottom">
        <div class="metric-card card-undo" onclick="openDetailModal('dikembalikan')">
            <div class="card-icon bg-orange-light"><i class="fas fa-undo text-orange"></i></div>
            <div class="metric-value">{{ $dikembalikan }}</div>
            <div class="metric-label">Dikembalikan</div>
            <div class="metric-sub"><span class="badge-pill bg-red-soft text-red">Revisi</span> Menunggu Perbaikan Penilai</div>
        </div>

        <div class="metric-card card-danger" onclick="openDetailModal('at_risk')">
            <div class="card-icon bg-red-light"><i class="fas fa-exclamation-triangle text-red"></i></div>
            <div class="metric-value">{{ $atRisk }}</div>
            <div class="metric-label">At Risk / Lewat Batas</div>
            <div class="metric-sub"><span class="text-red fw-bold">Prioritas</span> Tindak Lanjut SDM</div>
        </div>

        <div class="metric-card card-purple" onclick="openDetailModal('dikoreksi')">
            <div class="card-icon bg-purple-light"><i class="fas fa-edit text-purple"></i></div>
            <div class="metric-value">{{ $dikoreksi }}</div>
            <div class="metric-label">Penilaian Dikoreksi</div>
            <div class="metric-sub"><span class="badge-pill bg-orange-soft text-orange">Tercatat</span> Dalam Audit Trail</div>
        </div>

        <div class="metric-card card-gray" onclick="openDetailModal('belum_dinilai')">
            <div class="card-icon bg-blue-dark-light"><i class="fas fa-clock text-blue-dark"></i></div>
            <div class="metric-value">{{ $belumDinilai }}</div>
            <div class="metric-label">Belum Dinilai</div>
            <div class="metric-sub"><span class="text-amber fw-bold">Follow Up</span> Penilai Terkait</div>
        </div>
    </div>

        @if(!empty($noPeriode) && $noPeriode)
    <div class="empty-periode-card">
        <div class="empty-periode-icon">
            <i class="fas fa-calendar-times"></i>
        </div>
        <h5 class="empty-periode-title">Tidak Ada Periode Penilaian Aktif</h5>
        <p class="empty-periode-desc">Saat ini tidak ada periode penilaian pegawai yang tersedia atau dibuka. Silakan buat atau buka periode penilaian baru terlebih dahulu melalui menu Kelola Periode.</p>
    </div>
@else
    <!-- TAMPILKAN BANNER OVERDUE & WIDGET JIKA PERIODE ADA -->
    @if($isOverdue)
        <div class="alert-overdue-container" id="alertOverdueContainer">
            <div class="alert-overdue-header">
                <div class="alert-overdue-title-box">
                    <div class="alert-overdue-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div>
                        <h5 class="alert-overdue-title">Periode Penilaian Melebihi Batas Waktu</h5>
                        <p class="alert-overdue-desc">Periode berikut telah melewati tanggal deadline namun masih berstatus Open. Segera lakukan tindak lanjut — tutup periode atau perpanjang deadline.</p>
                    </div>
                </div>
                <button type="button" class="btn-toggle-alert" onclick="toggleOverdueAlert()">
                    <i class="fas fa-chevron-up" id="toggleIcon"></i> Sembunyikan
                </button>
            </div>

            <div class="alert-overdue-body" id="alertOverdueBody">
                <div class="overdue-info-left">
                    <div class="overdue-periode-name">{{ $periodeAktif->nama_periode ?? 'Periode ' . $selectedPeriodeId }}</div>
                    <div class="overdue-deadline-text">Deadline: {{ $deadlineTanggal ?? '-' }} &bull; {{ $totalPegawai }} pegawai</div>
                    <div class="badge-terlambat-pill">
                        <i class="fas fa-exclamation-circle"></i> {{ $hariTerlambat }} hari terlambat
                    </div>
                </div>

                <div class="overdue-stat-box">
                    <div class="overdue-stat-val text-danger">{{ $belumDinilai }}</div>
                    <div class="overdue-stat-lbl">Belum Dinilai</div>
                </div>

                <div class="overdue-stat-box">
                    <div class="overdue-stat-val text-primary">{{ $masihDraft }}</div>
                    <div class="overdue-stat-lbl">Draft</div>
                </div>

                <div class="overdue-stat-box">
                    <div class="overdue-stat-val text-warning">{{ $menungguVerifikasi }}</div>
                    <div class="overdue-stat-lbl">Submit</div>
                </div>

                <div class="overdue-stat-box">
                    <div class="overdue-stat-val text-success">{{ $sudahTerverifikasi }}</div>
                    <div class="overdue-stat-lbl">Verified</div>
                </div>

                <div class="overdue-stat-box">
                    <div class="overdue-stat-val text-danger">{{ $dikembalikan }}</div>
                    <div class="overdue-stat-lbl">Revisi</div>
                </div>

                <div class="overdue-progress-box">
                    <div class="overdue-progress-text">{{ $sudahTerverifikasi }}/{{ $totalPegawai }} selesai</div>
                    <div class="overdue-progress-bg">
                        <div class="overdue-progress-fill" style="width: {{ $percentVerifiedTotal }}%;"></div>
                    </div>
                    <div class="overdue-progress-percent">{{ $percentVerifiedTotal }}%</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Baris Widget Visualisasi -->
    <div class="widget-row">
        <!-- Widget 1: Penyelesaian Penilaian -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title-box">
                    <div class="widget-icon-box"><i class="fas fa-chart-pie"></i></div>
                    <div>
                        <h6 class="widget-title">Penyelesaian Penilaian</h6>
                        <p class="widget-subtitle">Progres Keseluruhan Periode Aktif</p>
                    </div>
                </div>
                <a href="javascript:void(0)" class="btn-link-detail small text-decoration-none" onclick="openDetailModal('total')">Lihat Detail</a>
            </div>

            <div class="completion-body">
                <div class="circle-progress-wrapper">
                    <svg class="circle-progress-svg" viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle-fill" 
                              stroke-dasharray="{{ $percentVerifiedTotal }}, 100" 
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="circle-text">
                        <span class="circle-percent">{{ $percentVerifiedTotal }}%</span>
                        <span class="circle-label">Verified</span>
                    </div>
                </div>

                <div class="completion-legend">
                    <div class="legend-item">
                        <div class="legend-dot-label"><span class="dot dot-verified"></span> Sudah Verified</div>
                        <span class="legend-value">{{ $sudahTerverifikasi }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot-label"><span class="dot dot-waiting"></span> Menunggu Verifikasi</div>
                        <span class="legend-value">{{ $menungguVerifikasi }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot-label"><span class="dot dot-returned"></span> Dikembalikan</div>
                        <span class="legend-value">{{ $dikembalikan }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot-label"><span class="dot dot-draft"></span> Masih Draft</div>
                        <span class="legend-value">{{ $masihDraft }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot-label"><span class="dot dot-notrated"></span> Belum Dinilai</div>
                        <span class="legend-value">{{ $belumDinilai }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget 2: Status per Lingkup Kerja -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title-box">
                    <div class="widget-icon-box"><i class="fas fa-sitemap"></i></div>
                    <div>
                        <h6 class="widget-title">Status Pegawai yang Dinilai per Direktorat</h6>
                        <p class="widget-subtitle">Progres Penilaian & Verifikasi per Lingkup Kerja</p>
                    </div>
                </div>
                <span class="widget-badge-realtime">Real-Time</span>
            </div>

            <div class="direktorat-list">
                @foreach($direktoratProgress as $dir)
                    <div class="direktorat-item">
                        <div class="direktorat-name">{{ $dir->nama }}</div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ $dir->percent }}%;"></div>
                        </div>
                        <div class="direktorat-count">{{ $dir->verified }}/{{ $dir->total }}</div>
                    </div>
                @endforeach
            </div>

            <div class="widget-footer-info">
                <i class="fas fa-info-circle flex-shrink-0 mt-0.5"></i>
                <span>Lingkup kerja mengikuti pemetaan Laporan Tahunan (Divisi Utama / Umum / Operasional / Cabang / Instalasi). Angka di kanan = pegawai terverifikasi dari total pegawai pada lingkup tersebut.</span>
            </div>
        </div>

        <!-- Widget 3: Pegawai Lewat Batas -->
        <div class="widget-card">
            <div class="widget-header">
                <div class="widget-title-box">
                    <div class="widget-icon-box text-danger" style="background: #fee2e2;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h6 class="widget-title">Pegawai Lewat Batas</h6>
                        <p class="widget-subtitle">Membutuhkan Tindak Lanjut Segera</p>
                    </div>
                </div>
            </div>

            @if(!empty($atRiskEmployees) && count($atRiskEmployees) > 0)
                <!-- List Daftar Pegawai yang Bisa Di-scroll -->
                <div class="overdue-list-container">
                    @foreach($atRiskEmployees as $emp)
                        @php
                            $empName = $emp->nama ?? '-';
                            $nameParts = explode(' ', trim($empName));
                            $initials = strtoupper(substr($nameParts[0], 0, 1));
                            if (count($nameParts) > 1) {
                                $initials .= strtoupper(substr($nameParts[1], 0, 1));
                            }
                        @endphp
                        <div class="overdue-item-card">
                            <div class="overdue-user-info">
                                <div class="overdue-avatar">{{ $initials }}</div>
                                <div class="overdue-user-details">
                                    <span class="overdue-user-name" title="{{ $empName }}">{{ $empName }}</span>
                                    <span class="overdue-user-sub" title="{{ $emp->unit_kerja ?? 'Unit Kerja -' }}">
                                        {{ $emp->unit_kerja ?? 'Unit Kerja -' }} &bull; <span class="text-danger fw-semibold">Belum Dinilai</span>
                                    </span>
                                </div>
                            </div>
                            <div class="followup-pill">
                                <span>Follow Up</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-alert-box">
                    <span>Tidak ada item prioritas saat ini.</span>
                </div>
            @endif
        </div>
    </div>
@endif

  
<!-- POP-UP MODAL DETAIL DATA PEGAWAI -->
<div class="assessment-modal" id="dashDetailModal" aria-hidden="true">
    <section class="assessment-modal-panel">
        <header class="assessment-modal-header">
            <div>
                <h2 class="assessment-modal-title" id="modalTitle">Detail Data</h2>
                <div id="modalSubTitle" class="text-muted small">0 data ditemukan.</div>
            </div>
            <button type="button" class="assessment-modal-close" onclick="closeDetailModal()">&times;</button>
        </header>

        <div class="assessment-modal-body">
            <div class="modal-table-wrapper">
                <table class="assessment-detail-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>PEGAWAI</th>
                            <th>SATKER</th>
                            <th>JABATAN</th>
                            <th>STATUS</th>
                            <th>NILAI</th>
                            <th>PREDIKAT</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="assessment-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm px-4 fw-bold" onclick="closeDetailModal()">Tutup</button>
        </footer>
    </section>
</div>

<!-- JAVASCRIPT FETCH DATA AJAX (FIXED ROUTE & DATA CHECK) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('periodeSelect');
    if (select) {
        select.addEventListener('change', function() {
            const selectedPeriodeId = this.value;
            
            // Ambil URL saat ini dan perbarui parameter periode_id secara dinamis
            const url = new URL(window.location.href);
            url.searchParams.set('periode_id', selectedPeriodeId);
            
            // Redirect halaman untuk memuat data sesuai periode yang dipilih
            window.location.href = url.toString();
        });
    }

    const modal = document.getElementById('dashDetailModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    }
});

// Fungsi untuk membuka modal detail
function openDetailModal(type) {
    const periodeSelect = document.getElementById('periodeSelect');
    const periodeId = periodeSelect ? periodeSelect.value : '';
    const modal = document.getElementById('dashDetailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubTitle = document.getElementById('modalSubTitle');
    const tableBody = document.getElementById('modalTableBody');
    
    modal.classList.add('is-open');
    modalTitle.innerText = 'Memuat Data...';
    modalSubTitle.innerText = 'Mengambil data dari server...';
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Mengambil data...</td></tr>';

    const fetchUrl = `{{ route('dashboard.modal-detail') }}?type=${type}&periode_id=${periodeId}`;

    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) throw new Error('HTTP status ' + response.status);
            return response.json();
        })
        .then(response => {
            modalTitle.innerText = response.title || 'Detail Data';
            modalSubTitle.innerText = `${response.total || 0} data ditemukan.`;

            let rows = '';
            if (!response.data || response.data.length === 0) {
                rows = '<tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data ditemukan.</td></tr>';
            } else {
                response.data.forEach((item, index) => {
                    let empName = item.nama || '-';
                    let nameParts = empName.split(' ');
                    let initials = nameParts[0].charAt(0);
                    if (nameParts.length > 1) initials += nameParts[1].charAt(0);

                    let empNup = item.nup || '-';
                    let unitKerja = item.unit_kerja || '-';
                    let jabatan = item.jabatan || '-';
                    let nilai = isNaN(parseFloat(item.total_nilai)) ? 0 : parseFloat(item.total_nilai);

                    // Penentuan Badge Status
                    let statusNilaiRaw = (item.status_nilai || '').toUpperCase().trim();
                    let statusVerifRaw = (item.status_verifikator || '').toUpperCase().trim();

                    let statusClass = 'badge-status-gray';
                    let statusLabel = 'Belum Diisi';

                    if (['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'].includes(statusVerifRaw)) {
                        statusClass = 'badge-status-success';
                        statusLabel = 'Terverifikasi';
                    } else if (['DIKEMBALIKAN', 'REVISI'].includes(statusVerifRaw) || ['DIKEMBALIKAN', 'REVISI'].includes(statusNilaiRaw)) {
                        statusClass = 'badge-status-danger';
                        statusLabel = 'Dikembalikan';
                    } else if (['DRAFT', 'SIMPAN'].includes(statusNilaiRaw)) {
                        statusClass = 'badge-status-warning';
                        statusLabel = 'DRAFT';
                    } else if (
                        ['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'].includes(statusNilaiRaw) ||
                        (nilai > 0 && (!statusVerifRaw || ['BELUM VERIFIKASI', 'UNVERIFIED', ''].includes(statusVerifRaw)))
                    ) {
                        statusClass = 'badge-status-warning';
                        statusLabel = 'Menunggu Verifikasi';
                    }

                    let statusBadge = `<span class="${statusClass} text-nowrap">${statusLabel}</span>`;

                    // Penentuan Badge Predikat
                    let badgeStyle = 'badge-rose';
                    if (nilai >= 90) badgeStyle = 'badge-emerald';
                    else if (nilai >= 80) badgeStyle = 'badge-blue';
                    else if (nilai >= 70) badgeStyle = 'badge-amber';
                    else if (nilai >= 60) badgeStyle = 'badge-orange';

                    let predikatNama = item.predikat ? item.predikat : 'Belum terdapat data predikat';
                    let predikatBadge = `<span class="badge ${badgeStyle} text-nowrap">${predikatNama}</span>`;

                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center font-monospace fw-bold text-secondary" style="width: 32px; height: 32px; font-size: 0.75rem;">${initials.toUpperCase()}</div>
                                    <div>
                                        <div class="fw-bold text-dark">${empName}</div>
                                        <div class="text-muted small">${empNup}</div>
                                    </div>
                                </div>
                            </td>
                            <td>${unitKerja}</td>
                            <td>${jabatan}</td>
                            <td>${statusBadge}</td>
                            <td class="font-monospace fw-bold">${nilai > 0 ? nilai.toFixed(2) : '-'}</td>
                            <td>${predikatBadge}</td>
                        </tr>
                    `;
                });
            }
            tableBody.innerHTML = rows;
        })
        .catch(err => {
            console.error('Error:', err);
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">Gagal memuat data. Silakan coba lagi.</td></tr>';
        });
}

function closeDetailModal() {
    const modal = document.getElementById('dashDetailModal');
    if(modal) modal.classList.remove('is-open');
}

function toggleOverdueAlert() {
    const body = document.getElementById('alertOverdueBody');
    const icon = document.getElementById('toggleIcon');
    if (body.style.display === 'none') {
        body.style.display = 'grid';
        icon.className = 'fas fa-chevron-up';
    } else {
        body.style.display = 'none';
        icon.className = 'fas fa-chevron-down';
    }
}
</script>

@endsection