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
.bg-purple-light { background: #eee0fc; color: #6b21a8; }
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

.assessment-modal-footer button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    min-height: 2.25rem;
    padding: 0.45rem 0.75rem;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #dbe3ee;
    border-radius: 0.55rem;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
}

.assessment-modal-footer button:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    color: #0f172a;
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

/* Styling Widget Progress per Unit Kerja */
.unit-progress-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    margin-top: 1.5rem;
    overflow: hidden;
}

.unit-card-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 1rem;
}

.unit-header-title-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.unit-table-container {
    width: 100%;
    overflow-x: auto;
}

.unit-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.8125rem;
}

.unit-table th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.05em;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}

.unit-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #334155;
}

.unit-table tbody tr:hover {
    background-color: #fafafa;
}

.unit-table-scroll-container {
    max-height: 420px; /* Batasi tinggi card agar bisa di-scroll */
    overflow-y: auto;
    width: 100%;
}

/* Kustomisasi Scrollbar agar mirip dengan widget sebelumnya */
.unit-table-scroll-container::-webkit-scrollbar {
    width: 6px;
}
.unit-table-scroll-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.unit-table-scroll-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.unit-table-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Membuat Header Tabel tetap menempel di atas saat di-scroll (Sticky Header) */
.unit-table thead th {
    position: sticky;
    top: 0;
    background: #f8fafc;
    z-index: 10;
    box-shadow: inset 0 -1px 0 #e2e8f0;
}

.badge-komposisi {
    background: #f1f5f9;
    color: #334155;
    font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.7rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    border: 1px solid #e2e8f0;
}

.badge-komposisi span {
    color: #0284c7;
    font-weight: 700;
}

.unit-progress-bar-bg {
    width: 100%;
    background: #e2e8f0;
    height: 6px;
    border-radius: 999px;
    overflow: hidden;
    margin-top: 0.35rem;
}

.unit-progress-bar-fill {
    background: #0284c7;
    height: 100%;
    border-radius: 999px;
}

.badge-status-atrisk {
    background: #fee2e2;
    color: #991b1b;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.7rem;
}

.badge-status-aman {
    background: #dcfce7;
    color: #166534;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.7rem;
}

/* Wrapper Kolom Unit Kerja */
.unit-cell-wrapper {
    display: flex !important;
    align-items: center !important;
    flex-direction: row !important;
    gap: 0.75rem;
}

/* Kotak Ikon Gedung */
.unit-icon-box {
    width: 34px;
    height: 34px;
    border-radius: 0.5rem;
    background-color: #ffe4e6; /* Merah muda lembut */
    color: #9f1239;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Mencegah ikon tertekan/gepeng */
    font-size: 0.875rem;
}

/* Teks Nama Unit Kerja */
.unit-text-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.unit-name-text {
    font-size: 0.8125rem;
    line-height: 1.25;
    color: #0f172a;
}

.unit-sub-text {
    font-size: 0.7rem;
    line-height: 1.2;
    margin-top: 0.15rem;
}

/* Container Utama Widget Distribusi Predikat */
.distribusi-predikat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    margin-top: 1.5rem;
    overflow: hidden;
}

.distribusi-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(360px, 1fr);
    gap: 0;
    padding: 0;
}

.distribusi-grid > * {
    min-width: 0;
}

@media (max-width: 992px) {
    .distribusi-grid {
        grid-template-columns: 1fr;
    }

    .ranking-panel {
        border-left: 0;
        border-top: 1px solid #e2e8f0;
    }
}

.overall-average-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-height: 84px;
    margin: 0 0 1.25rem;
    padding: 1.15rem 1.25rem;
    background: linear-gradient(110deg, #fff7f7, #ffffff 80%);
    border: 1px solid #f3d5da;
    border-radius: 0.875rem;
}

.overall-average-label {
    color: #475569;
    font-size: 0.85rem;
    font-weight: 700;
}

.overall-average-note {
    margin-top: 0.3rem;
    color: #64748b;
    font-size: 0.75rem;
}

.overall-average-value {
    flex-shrink: 0;
    color: #801b2b;
    font-size: clamp(2rem, 3vw, 2.75rem);
    font-weight: 900;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}

.distribution-panel {
    min-width: 0;
    padding: 1.25rem 1.5rem 1.5rem;
}

/* Legend Komposisi Predikat */
.predikat-legend-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
    font-size: 0.725rem;
    color: #64748b;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.predikat-legend-items {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.legend-item-dot {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 600;
}

.dot-p {
    width: 9px;
    height: 9px;
    border-radius: 2px;
}
.dot-luar-biasa   { background-color: #15803d; } /* Hijau tua */
.dot-di-atas      { background-color: #22c55e; } /* Hijau terang */
.dot-sesuai       { background-color: #2563eb; } /* Biru */
.dot-di-bawah     { background-color: #d97706; } /* Oranye */
.dot-mengecewakan { background-color: #dc2626; } /* Merah */

/* Scroll Container Sisi Kiri (Bar Chart Unit) */
.distribusi-left-scroll {
    max-height: 480px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 0.25rem;
    display: flex;
    flex-direction: column;
    gap: 1.15rem;
}

.distribusi-left-scroll::-webkit-scrollbar {
    width: 6px;
}
.distribusi-left-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.distribusi-left-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Bar Item Per Unit Kerja */
.unit-bar-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
}

.unit-bar-info {
    width: 180px;
    min-width: 0;
    flex: 0 1 180px;
}

.unit-bar-name {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.unit-bar-sub {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 0.15rem;
}

.badge-unit-aktif {
    font-size: 0.675rem;
    background: #fbf6f6;
    color: #9f1239
}

.stacked-bar-container {
    flex-grow: 1;
    min-width: 0;
    height: 12px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
    display: flex;
}

.stacked-bar-seg {
    height: 100%;
    transition: width 0.4s ease;
}

.bg-luar-biasa   { background-color: #15803d; }
.bg-di-atas      { background-color: #22c55e; }
.bg-sesuai       { background-color: #2563eb; }
.bg-di-bawah     { background-color: #d97706; }
.bg-mengecewakan { background-color: #dc2626; }

.unit-bar-avg {
    width: 42px;
    text-align: right;
    font-size: 0.825rem;
    font-weight: 800;
    color: #0f172a;
    flex-shrink: 0;
}

/* Panel Sisi Kanan (Peringkat Rata-Rata) */
.ranking-panel {
    min-width: 0;
    background: #ffffff;
    border-left: 1px solid #e2e8f0;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
}

.ranking-panel-title {
    margin-bottom: 0.75rem;
    color: #334155;
    font-size: 0.8rem;
    font-weight: 800;
}

.ranking-panel-title i {
    margin-right: 0.35rem;
    color: #801b2b;
}

@media (max-width: 576px) {
    .overall-average-card {
        padding: 1rem;
    }

    .distribution-panel,
    .ranking-panel {
        padding: 1rem;
    }

    .ranking-panel {
        border-left: 0;
        border-top: 1px solid #e2e8f0;
    }

    .predikat-legend-bar {
        align-items: flex-start;
    }

    .unit-bar-item {
        gap: 0.6rem;
    }

    .unit-bar-info {
        flex-basis: 130px;
    }

    .overall-average-value {
        font-size: 2rem;
    }
}

.ranking-list-scroll {
    max-height: 380px;
    overflow-y: auto;
    padding-right: 0.35rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.ranking-list-scroll::-webkit-scrollbar {
    width: 5px;
}
.ranking-list-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.ranking-list-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.ranking-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 0.6rem;
    padding: 0.6rem 0.75rem;
}

.ranking-left-group {
    display: flex;
    align-items: center;
    gap: 12px;
    overflow: hidden;
    flex-grow: 1;
    margin-right: 10px;
}

.ranking-badge {
    width: 28px;
    height: 28px;
    border-radius: 0.4rem;
    background: #f1f5f9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Mencegah badge ikut mengecil */
}

.ranking-unit-name {
    margin: 0;
    font-size: 0.775rem;
    font-weight: 700;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ranking-unit-sub {
    font-size: 0.675rem;
    color: #64748b;
}

.ranking-score {
    font-size: 0.8125rem;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
}

/* Container Dua Kolom Widget Predikat Terendah */
.low-performance-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.25rem;
    margin-top: 1.5rem;
}

@media (max-width: 992px) {
    .low-performance-row {
        grid-template-columns: 1fr;
    }
}

.low-card {
    min-width: 0;
    min-height: 292px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.low-card-header {
    padding: 1.15rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: nowrap;
    border-bottom: 1px solid #f1f5f9;
}

.low-card-header-main {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1 1 auto;
    min-width: 0;
}

.low-card-header-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.65rem;
}

.low-card-header-copy {
    min-width: 0;
}

.low-card-title {
    margin: 0;
    color: #0f172a;
    font-size: 0.95rem;
    font-weight: 800;
    line-height: 1.25;
}

.low-card-description {
    margin: 0.15rem 0 0;
    color: #64748b;
    font-size: 0.725rem;
    line-height: 1.35;
}

.low-card-count {
    flex: 0 0 auto;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.725rem;
    padding: 0.35rem 0.75rem;
    white-space: nowrap;
}

/* Container Scroll Vertikal List Pegawai */
.low-list-container {
    flex: 1;
    min-height: 160px;
    max-height: 380px;
    overflow-y: auto;
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.low-list-container > .text-center {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.low-list-container::-webkit-scrollbar {
    width: 6px;
}
.low-list-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}
.low-list-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Item Card Pegawai Terendah */
.low-emp-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    padding: 0.85rem 1rem;
    transition: all 0.2s ease;
}

.low-emp-card:hover {
    border-color: #fca5a5;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.05);
}

.low-emp-info {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    overflow: hidden;
    min-width: 0;
}

.low-emp-avatar {
    width: 42px;
    height: 42px;
    border-radius: 0.6rem;
    object-fit: cover;
    flex-shrink: 0;
}

.low-emp-avatar-placeholder {
    width: 42px;
    height: 42px;
    border-radius: 0.6rem;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.low-emp-details {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.low-emp-name {
    font-size: 0.85rem;
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.low-emp-sub {
    font-size: 0.725rem;
    color: #64748b;
    margin-top: 0.15rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.low-emp-score-box {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
    flex-shrink: 0;
    margin-left: 0.75rem;
}

@media (max-width: 576px) {
    .low-card-header {
        align-items: flex-start;
        padding: 1rem;
        gap: 0.6rem;
    }

    .low-card-header-main {
        gap: 0.6rem;
    }

    .low-card-title {
        font-size: 0.85rem;
    }

    .low-card-description {
        font-size: 0.68rem;
    }

    .low-card-count {
        padding: 0.3rem 0.55rem;
        font-size: 0.65rem;
    }

    .low-list-container {
        padding: 0.85rem;
    }

    .low-emp-card {
        padding: 0.75rem;
        gap: 0.5rem;
    }

    .low-emp-sub {
        max-width: 14rem;
    }
}

.low-emp-score {
    font-size: 1.1rem;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
}

.badge-predikat-dibawah {
    background: #fef3c7;
    color: #b45309;
    font-size: 0.675rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
}

.badge-predikat-mengecewakan {
    background: #ffe4e6;
    color: #9f1239;
    font-size: 0.675rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
}

/* Styling Widget Monitoring Detail Pegawai */
.mon-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    margin-top: 1.5rem;
    overflow: hidden;
}

.mon-card,
.mon-table-wrapper {
    min-width: 0;
}

.mon-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
}

.mon-header-main {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    min-width: 0;
}

.mon-header-icon {
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    background: #e0f2fe;
    color: #0369a1;
}

.mon-header-copy {
    min-width: 0;
}

.mon-header-title {
    margin: 0 0 0.2rem;
    color: #0f172a;
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.25;
}

.mon-header-description {
    margin: 0;
    color: #64748b;
    font-size: 0.78rem;
}

.mon-header-count {
    background: #def0fd;
    color: #0369a1;
    border: 1px solid #0369a1;
    padding: 0.35rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-block;
}

/* Tab Navigasi Status */
.mon-tabs-bar {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 0 1rem;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
    overflow-x: auto;
}

.mon-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    border-radius: 0;
    padding: 0.85rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748b;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.mon-tab-btn:hover {
    color: #0f172a;
}

.mon-tab-btn.active {
    color: #801B2B;
    border-bottom-color: #801B2B;
}

/* Tabel Monitoring */
.mon-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.mon-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8125rem;
    text-align: left;
    min-width: 1120px;
}

.mon-table th {
    background: #ffffff;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.05em;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}

.mon-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #334155;
}

.mon-table tbody tr:hover {
    background: #fafafa;
}

/* Badge Status pada Tabel */
.badge-status-custom {
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.7rem;
    display: inline-block;
}
.st-belum { background: #f1f5f9; color: #475569; }
.st-submit { background: #e0f2fe; color: #0369a1; }
.st-draft { background: #fef3c7; color: #b45309; }
.st-verified { background: #dcfce7; color: #166534; }
.st-returned { background: #eee0fc; color: #6b21a8; }

/* Tombol Aksi Mata */
.btn-action-eye {
    width: 32px;
    height: 32px;
    border-radius: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-action-eye:hover {
    background: #801B2B;
    color: #ffffff;
    border-color: #801B2B;
}

/* Modal Popup Detail Styling */
.modal-detail-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.2s ease;
}
.modal-detail-backdrop.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}
.modal-detail-content {
    background: #ffffff;
    width: 100%;
    max-width: 1050px;
    max-height: 92vh;
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    color: #334155;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.modal-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

.modal-detail-heading {
    min-width: 0;
}

.modal-detail-title {
    margin: 0 0 0.25rem;
    color: #1e293b;
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.3;
}

.modal-detail-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 0.78rem;
}

.modal-detail-close,
.modal-detail-footer button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    min-height: 2.25rem;
    padding: 0.45rem 0.75rem;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #dbe3ee;
    border-radius: 0.55rem;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
}

.modal-detail-close:hover,
.modal-detail-footer button:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    color: #0f172a;
}

.modal-detail-body {
    padding: 1.25rem 1.5rem;
    overflow-y: auto;
    flex-grow: 1;
    background: #ffffff;
}

.modal-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.modal-summary-card {
    min-width: 0;
    min-height: 76px;
    padding: 0.85rem 1rem;
    background: #ffffff;
    border: 1px solid #dbe3ee;
    border-radius: 0.7rem;
}

.modal-summary-label {
    display: block;
    margin-bottom: 0.4rem;
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.modal-summary-value {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.2;
}

.modal-info-table {
    width: 100%;
    margin-bottom: 1.25rem;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #e2e8f0;
    border-radius: 0.65rem;
    overflow: hidden;
    font-size: 0.78rem;
}

.modal-info-table td {
    padding: 0.65rem 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.modal-info-table tr:last-child td {
    border-bottom: 0;
}

.modal-info-label {
    width: 17%;
    color: #64748b;
    background: #f8fafc;
    font-size: 0.67rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

.modal-info-value {
    width: 33%;
    color: #334155;
}

.modal-detail-tab-pane[hidden] {
    display: none !important;
}

.modal-detail-footer {
    display: flex;
    justify-content: flex-end;
    padding: 0.75rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.modal-detail-tabs {
    display: flex;
    align-items: center;
    gap: 0;
    border-bottom: 1px solid #dbe3ee;
    margin-bottom: 1rem;
}

.modal-detail-tab {
    padding: 0.75rem 0.9rem;
    color: #64748b;
    background: transparent;
    border: 0;
    border-bottom: 2px solid transparent;
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}

.modal-detail-tab:hover {
    color: #801b2b;
}

.modal-detail-tab.active {
    color: #801b2b;
    border-bottom-color: #801b2b;
}

.modal-question-category {
    margin-bottom: 1.5rem;
}

.modal-question-category-title {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0.75rem !important;
    padding: 0.85rem 1.25rem !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

/* Grid 2 Kolom untuk Kartu Pertanyaan */
.modal-question-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

@media (max-width: 768px) {
    .modal-question-grid {
        grid-template-columns: 1fr;
    }
}

.modal-question-card {
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0.75rem !important;
    background: #ffffff;
    padding: 1.15rem !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.modal-question-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.modal-question-card.has-correction {
    border-color: #fed7aa !important;
    background: #fffdfb;
}

.modal-question-header {
    display: flex;
    align-items: flex-start; /* Mengatur agar sejajar di bagian atas */
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.modal-question-left-group {
    display: flex;
    align-items: flex-start; /* Mengatur agar sejajar di bagian atas */
    gap: 0.75rem;
    min-width: 0;
}

.modal-question-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #fee2e2;
    color: #991b1b;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
    margin-top: 0.15rem; /* Penyesuaian mikro agar sejajar dengan baris pertama teks */
}

.modal-question-title {
    font-size: 0.77rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.35;
    margin: 0;
}

.modal-question-bobot {
    font-size: 0.72rem;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    flex-shrink: 0;
    white-space: nowrap;
}

.modal-question-description {
    color: #64748b;
    font-size: 0.78rem;
    line-height: 1.45;
    margin-bottom: 0.75rem;
}

.modal-question-correction {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background-color: #fff7ed !important;
    color: #c2410c !important;
    border: 1px solid #ffedd5 !important;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.2rem 0.55rem;
    border-radius: 6px;
    margin-bottom: 0.6rem;
}

.modal-question-score-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.6rem;
    padding: 0.75rem 0.9rem;
    margin-bottom: 0.6rem;
}

.modal-score-item {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.modal-score-item + .modal-score-item {
    margin-top: 0.65rem;
    padding-top: 0.65rem;
    border-top: 1px dashed #e2e8f0;
}

.modal-score-label {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 500;
}

.modal-score-badge-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-grade-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 5px;
    background: #10b981;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
}

.modal-grade-pill.blue {
    background: #2563eb;
}

.modal-grade-pill.orange {
    background: #ea580c;
}

.modal-score-text {
    font-size: 0.82rem;
    font-weight: 700;
    color: #1e293b;
}

.modal-question-note-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.6rem;
    padding: 0.65rem 0.9rem;
    font-size: 0.75rem;
}

.modal-note-title {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 0.2rem;
}

.modal-note-desc {
    color: #94a3b8;
    font-style: italic;
    line-height: 1.35;
}

.modal-note-desc.has-content {
    color: #334155;
    font-style: normal;
}

.modal-question-note-box + .modal-question-note-box {
    margin-top: 0.45rem;
}

.modal-summary-alert {
    margin: 0;
    padding: 0.8rem 0.9rem;
    color: #365879;
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 0.4rem;
    font-size: 0.78rem;
}

@media (max-width: 767px) {
    .mon-header {
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
    }

    .mon-header-main {
        align-items: flex-start;
    }

    .mon-tabs-bar {
        padding: 0 0.5rem;
    }

    .mon-tab-btn {
        padding: 0.75rem 0.6rem;
    }

    .modal-detail-body {
        padding: 1rem;
    }

    .modal-detail-header,
    .modal-detail-footer {
        padding-right: 1rem;
        padding-left: 1rem;
    }

    .modal-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .modal-info-table,
    .modal-info-table tbody,
    .modal-info-table tr,
    .modal-info-table td {
        display: block;
        width: 100%;
    }

    .modal-info-table tr {
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-info-table tr:last-child {
        border-bottom: 0;
    }

    .modal-info-table td {
        padding: 0.55rem 0.7rem;
        border: 0;
    }

    .modal-info-label {
        padding-bottom: 0.1rem !important;
    }

    .modal-info-value {
        padding-top: 0.1rem !important;
    }

}

/* =========================================================
   STYLE BIODATA PEGAWAI & RIWAYAT NILAI (SITAMPAN STYLE)
   ========================================================= */
.bio-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #edf2f7;
    padding: 2rem;
    margin-bottom: 2rem;
}
.bio-header {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}
.bio-avatar-wrap {
    width: 120px;
    height: 120px;
    margin: 0 auto 1rem;
    position: relative;
}
.bio-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 14px rgba(122, 28, 56, 0.2);
}
.bio-avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #7A1C38 0%, #4A0E17 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.8rem;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(122, 28, 56, 0.2);
}
.bio-name {
    font-size: 1.45rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.2rem;
}
.bio-nipp {
    font-size: 0.95rem;
    color: #64748b;
    font-weight: 600;
}
.bio-sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    border-top: 1px solid #f1f5f9;
    padding-top: 2rem;
}
.bio-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.bio-section-title i {
    color: #7A1C38;
}
.bio-table {
    width: 100%;
    font-size: 0.85rem;
}
.bio-table tr td {
    padding: 0.45rem 0;
    vertical-align: top;
}
.bio-table td.bio-label {
    width: 40%;
    color: #64748b;
    font-weight: 500;
}
.bio-table td.bio-val {
    width: 60%;
    color: #1e293b;
    font-weight: 600;
}

/* Tabel Riwayat Nilai DP3 dengan Garis Grid Rapi */
.history-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    padding: 1.8rem;
    margin-bottom: 2rem;
}
.history-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}
.history-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.history-title i {
    color: #7A1C38;
}
.history-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #e2e8f0;
}
.history-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.85rem 1rem;
    border: 1px solid #e2e8f0;
    text-align: left;
}
.history-table td {
    padding: 0.85rem 1rem;
    font-size: 0.875rem;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
}
.history-table tr:hover td {
    background-color: #f8fafc;
}
.score-badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.775rem;
    font-weight: 700;
}
.score-badge-verified { background: #dcfce7; color: #15803d; }
.score-badge-submit { background: #e0f2fe; color: #0369a1; }
.score-badge-draft { background: #fef3c7; color: #b45309; }
.score-badge-unfilled { background: #f1f5f9; color: #475569; }
.score-badge-revisi { background: #fee2e2; color: #b91c1c; }

/* Aksi button for history table — clean, no blue */
.hist-aksi-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.3rem 0.8rem;
    background: #f1f5f9;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 9999px;
    font-size: 0.775rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
    white-space: nowrap;
}
.hist-aksi-btn:hover {
    background: #7A1C38;
    color: #ffffff;
    border-color: #7A1C38;
    text-decoration: none;
}

/* Dashboard Tabs Navigation (Admin SDM Only) */
.dash-role-tabs {
    display: flex;
    gap: 0.5rem;
    background: #f1f5f9;
    padding: 0.35rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    width: fit-content;
}
.dash-role-tab {
    padding: 0.6rem 1.25rem;
    border-radius: 0.5rem;
    border: none;
    background: transparent;
    font-size: 0.875rem;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.45rem;
}
.dash-role-tab.active {
    background: #ffffff;
    color: #7A1C38;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}
.dash-role-tab:hover:not(.active) {
    color: #1e293b;
}

/* =========================================================
   STYLE PAGINATION SESUAI KELOLA PENILAIAN
   ========================================================= */
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
    font-size: 0.95rem;
    font-weight: 500;
    color: #64748b;
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
    width: 2.5rem;
    height: 2.35rem;
    border: 1px solid #dfe4ea;
    border-radius: 0.35rem;
    background: #ffffff;
    color: #7a838f;
    font-size: 1.8rem;
    font-weight: 400;
    padding: 0;
}
.page-arrow.disabled {
    color: #a9b3c0;
    background: #f8fafc;
    pointer-events: none;
}
.page-number {
    width: 2.35rem;
    height: 2.35rem;
    border: 1px solid #dfe4ea;
    border-radius: 0.35rem;
    background: #ffffff;
    color: #2f3746;
    font-size: 0.925rem;
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
    height: 2.35rem;
    color: #67748a;
    font-size: 1.3rem;
    font-weight: 700;
}

/* Monitoring table pagination — centered below table, not competing with AI chat */
.mon-pagination-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    border-top: 1px solid #dfe4ea;
    padding: 0.85rem 1rem;
    background: transparent;
}
</style>

<div class="dashboard-container">

    @if($isAdminSdm)
        <!-- Switcher Tab untuk Admin SDM -->
        <div class="dash-role-tabs">
            <button type="button" class="dash-role-tab active" onclick="switchDashboardTab('monitoring')">
                <i class="fas fa-chart-line"></i> Monitoring Penilaian
            </button>
            <button type="button" class="dash-role-tab" onclick="switchDashboardTab('profile')">
                <i class="fas fa-id-card"></i> Profil & Riwayat Kinerja
            </button>
        </div>
    @endif

    <!-- ==============================================================
         PANE 1: BIODATA & RIWAYAT KINERJA (UNTUK PEGAWAI / DIREKSI / MANAJER)
         ============================================================== -->
    <div id="pane-profile-dashboard" style="{{ $isAdminSdm ? 'display: none;' : 'display: block;' }}">
        <!-- Banner Sapaan Personal -->
        <div class="dash-banner" style="margin-bottom: 1.5rem;">
            <div>
                <span class="dash-sub">PORTAL KEPEGAWAIAN & KINERJA PRIBADI</span>
                <h1 class="dash-title">Halo, {{ $employeeProfile ? $employeeProfile->nama : Auth::user()->name }}!</h1>
                <p class="dash-desc">Berikut adalah informasi profil data diri dan riwayat penilaian prestasi kerja (DP3) Anda.</p>
            </div>
            <div class="dash-actions">
                <span class="badge" style="background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 9999px; font-weight: 700; font-size: 0.85rem;">
                    <i class="fas fa-user-tag me-1"></i> {{ Auth::user()->roleDisplayName() }}
                </span>
            </div>
        </div>

        @if($employeeProfile)
            <!-- KARTU BIODATA LENGKAP -->
            <div class="bio-card">
                <div class="bio-header">
                    <div class="bio-avatar-wrap">
                        @if(!empty($employeeProfile->foto_pegawai) && $employeeProfile->foto_pegawai !== 'default.jpg' && file_exists(public_path('storage/' . $employeeProfile->foto_pegawai)))
                            <img src="{{ asset('storage/' . $employeeProfile->foto_pegawai) }}" class="bio-avatar" alt="{{ $employeeProfile->nama }}">
                        @else
                            <div class="bio-avatar-placeholder">
                                {{ strtoupper(substr($employeeProfile->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="bio-name">{{ $employeeProfile->nama }}</div>
                    <div class="bio-nipp">NUP: {{ $employeeProfile->nup ?? '-' }}</div>
                </div>

                <div class="bio-sections-grid">
                    <!-- Kolom 1: Informasi Personal -->
                    <div>
                        <div class="bio-section-title">
                            <i class="fas fa-user"></i> Informasi Personal
                        </div>
                        <table class="bio-table">
                            <tr>
                                <td class="bio-label">Tempat Lahir</td>
                                <td class="bio-val">{{ $employeeProfile->tempat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Tanggal Lahir</td>
                                <td class="bio-val">{{ $employeeProfile->tgl_lahir ? date('d F Y', strtotime($employeeProfile->tgl_lahir)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Jenis Kelamin</td>
                                <td class="bio-val">{{ $employeeProfile->sex === 'L' || $employeeProfile->sex === '1' ? 'Laki-laki' : ($employeeProfile->sex === 'P' || $employeeProfile->sex === '0' ? 'Perempuan' : '-') }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">No. HP</td>
                                <td class="bio-val">{{ $employeeProfile->nohp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Alamat</td>
                                <td class="bio-val">{{ $employeeProfile->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Gol. Darah</td>
                                <td class="bio-val">{{ $employeeProfile->gol_darah ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Kolom 2: Informasi Kepegawaian -->
                    <div>
                        <div class="bio-section-title">
                            <i class="fas fa-briefcase"></i> Informasi Kepegawaian
                        </div>
                        <table class="bio-table">
                            <tr>
                                <td class="bio-label">NUP</td>
                                <td class="bio-val">{{ $employeeProfile->nup ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Tanggal Masuk</td>
                                <td class="bio-val">{{ $employeeProfile->tgl_masuk ? date('d F Y', strtotime($employeeProfile->tgl_masuk)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Penempatan / Kantor</td>
                                <td class="bio-val">{{ $employeeProfile->office?->off_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Departemen</td>
                                <td class="bio-val">{{ $employeeProfile->department?->dept_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Sub Departemen</td>
                                <td class="bio-val">{{ $employeeProfile->subDepartment?->subdept_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Jabatan</td>
                                <td class="bio-val">{{ $employeeProfile->occupation?->occ_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Unit Kerja</td>
                                <td class="bio-val">{{ $employeeProfile->unitkerja?->nama_unit_kerja ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Kolom 3: Administrasi & Rekening -->
                    <div>
                        <div class="bio-section-title">
                            <i class="fas fa-wallet"></i> Administrasi & Rekening
                        </div>
                        <table class="bio-table">
                            <tr>
                                <td class="bio-label">Bank Rekening</td>
                                <td class="bio-val">{{ $employeeProfile->bank_rek ?? 'Bank Mandiri' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">No. Rekening</td>
                                <td class="bio-val">{{ $employeeProfile->no_rek ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">No. NPWP</td>
                                <td class="bio-val">{{ $employeeProfile->no_npwp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">BPJS Kesehatan</td>
                                <td class="bio-val">{{ $employeeProfile->is_bpjskes ? 'Terdaftar' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Pendidikan Terakhir</td>
                                <td class="bio-val">{{ $employeeProfile->jurusan_pendidikan ?? '-' }} ({{ $employeeProfile->thn_lulus ?? '-' }})</td>
                            </tr>
                            <tr>
                                <td class="bio-label">Status Pegawai</td>
                                <td class="bio-val">
                                    <span class="score-badge score-badge-verified">Aktif</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Peringatan jika akun belum terhubung dengan data employee -->
            <div class="bio-card text-center py-4">
                <i class="fas fa-user-shield text-muted" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h5 class="fw-bold text-dark">Data Pegawai Mandiri Belum Terhubung</h5>
                <p class="text-muted small">Akun Anda belum ditautkan dengan NUP pegawai spesifik di database sistem.</p>
            </div>
        @endif

        <!-- TABEL RIWAYAT PENILAIAN DP3 PRIBADI — 12-BULAN PER TAHUN -->
        <div class="history-card">
            <div class="history-card-header">
                <h3 class="history-title">
                    <i class="fas fa-file-signature"></i> Riwayat Nilai Prestasi Kerja (DP3)
                </h3>
                {{-- Year selector --}}
                <form method="GET" action="{{ url()->current() }}" id="historyYearForm" style="display:flex;align-items:center;gap:0.5rem;">
                    {{-- preserve periode_id for admin sdm tab --}}
                    @if(request('periode_id'))
                        <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">
                    @endif
                    <label for="historyYearSelect" style="font-size:0.8125rem;font-weight:700;color:#475569;margin:0;">Tahun:</label>
                    <select id="historyYearSelect" name="history_year"
                        onchange="document.getElementById('historyYearForm').submit()"
                        style="padding:0.4rem 0.85rem;border-radius:0.5rem;border:1.5px solid #7A1C38;background:#fff;color:#7A1C38;font-size:0.8125rem;font-weight:700;outline:none;cursor:pointer;">
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ (int)$selectedYear === (int)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @php
                $namaBulanIndoHist = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp

            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th style="width:3.5rem;">No</th>
                            <th>Bulan</th>
                            <th>Penilai</th>
                            <th>Verifikator</th>
                            <th style="text-align:center;">Nilai Penilai</th>
                            <th style="text-align:center;">Nilai Verifikator</th>
                            <th style="text-align:center;">Predikat</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $history = $monthlyHistory[$m] ?? null;
                                $namabulan = $namaBulanIndoHist[$m];

                                if ($history) {
                                    $rawVerif = $history->status_verifikator ?? $history->status_verifitor ?? '';
                                    $statusVerif = strtoupper(trim((string) $rawVerif));
                                    $statusNilai = strtoupper(trim((string) ($history->status_nilai ?? '')));

                                    if (in_array($statusVerif, ['VERIFIED','VERIFIKASI','SELESAI','APPROVED'])) {
                                        $statusLabel = 'Terverifikasi';
                                        $statusBadgeClass = 'score-badge-verified';
                                    } elseif (in_array($statusNilai, ['DIAJUKAN','SUBMIT','SUBMITTED','MENUNGGU VERIFIKASI','MENUNGGU'])) {
                                        $statusLabel = 'Menunggu';
                                        $statusBadgeClass = 'score-badge-submit';
                                    } elseif (in_array($statusNilai, ['DIKEMBALIKAN','REVISI'])) {
                                        $statusLabel = 'Dikembalikan';
                                        $statusBadgeClass = 'score-badge-revisi';
                                    } elseif (in_array($statusNilai, ['DRAFT','SIMPAN','SAVED'])) {
                                        $statusLabel = 'Draft';
                                        $statusBadgeClass = 'score-badge-draft';
                                    } else {
                                        $statusLabel = 'Belum Diisi';
                                        $statusBadgeClass = 'score-badge-unfilled';
                                    }

                                    $nilaiPenilai = $history->total_nilai !== null && $history->total_nilai > 0
                                        ? number_format($history->total_nilai, 2) : '-';
                                    $nilaiVerifikator = $history->total_nilai_verifikator !== null && $history->total_nilai_verifikator > 0
                                        ? number_format($history->total_nilai_verifikator, 2)
                                        : ($history->total_nilai > 0 ? number_format($history->total_nilai, 2) : '-');
                                    $predikat = $history->predikat_verifikator ?: ($history->predikat ?: '-');
                                    $penilaiNama = $history->penilai ? $history->penilai->nama : ($history->penilai_id ? $history->penilai_id : '-');
                                    $verifikatorNama = $history->verifikator ? $history->verifikator->nama : ($history->verifikator_id ? $history->verifikator_id : '-');
                                } else {
                                    $statusLabel = '';
                                    $statusBadgeClass = '';
                                    $nilaiPenilai = '';
                                    $nilaiVerifikator = '';
                                    $predikat = '';
                                    $penilaiNama = '';
                                    $verifikatorNama = '';
                                }

                                // Row styling: highlight current month
                                $isCurrentMonth = ($m === (int)date('n') && (int)$selectedYear === (int)date('Y'));
                                $rowStyle = $isCurrentMonth ? 'background:#fdf8fa;' : '';
                            @endphp
                            <tr style="{{ $rowStyle }}">
                                <td class="fw-bold text-muted" style="text-align:center;">{{ $m }}</td>
                                <td>
                                    <span style="font-weight:700;color:{{ $isCurrentMonth ? '#7A1C38' : '#1e293b' }};">
                                        {{ $namabulan }}
                                        @if($isCurrentMonth)
                                            <span style="font-size:0.7rem;font-weight:600;color:#7A1C38;margin-left:0.35rem;background:#fce7ef;padding:0.1rem 0.45rem;border-radius:9999px;">Bulan Ini</span>
                                        @endif
                                    </span>
                                </td>
                                <td style="color:#475569;">
                                    @if($history){{ $penilaiNama }}@else<span style="color:#cbd5e1;">—</span>@endif
                                </td>
                                <td style="color:#475569;">
                                    @if($history){{ $verifikatorNama }}@else<span style="color:#cbd5e1;">—</span>@endif
                                </td>
                                <td style="text-align:center;">
                                    @if($history && $nilaiPenilai !== '-' && $nilaiPenilai !== '')
                                        <span class="fw-bold" style="color:#1e293b;">{{ $nilaiPenilai }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if($history && $nilaiVerifikator !== '-' && $nilaiVerifikator !== '')
                                        <span class="fw-bold" style="color:#1e293b;">{{ $nilaiVerifikator }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if($history && $predikat && $predikat !== '-')
                                        <span class="score-badge score-badge-verified">{{ $predikat }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if($history && $statusLabel)
                                        <span class="score-badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div style="margin-top:0.75rem;font-size:0.8rem;color:#94a3b8;text-align:right;">
                Menampilkan 12 bulan tahun <strong>{{ $selectedYear }}</strong>
            </div>
        </div>
    </div>

    <!-- ==============================================================
         PANE 2: DASHBOARD MONITORING PENILAIAN (KHUSUS ADMIN SDM)
         ============================================================== -->
    <div id="pane-monitoring-dashboard" style="{{ $isAdminSdm ? 'display: block;' : 'display: none;' }}">

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
        <div class="metric-card card-purple" onclick="openDetailModal('dikembalikan')">
            <div class="card-icon bg-purple-light"><i class="fas fa-undo text-orange"></i></div>
            <div class="metric-value">{{ $dikembalikan }}</div>
            <div class="metric-label">Dikembalikan</div>
            <div class="metric-sub"><span class="badge-pill bg-purple-light text-purple">Revisi</span> Menunggu Perbaikan Penilai</div>
        </div>

        <div class="metric-card card-danger" onclick="openDetailModal('at_risk')">
            <div class="card-icon bg-red-light"><i class="fas fa-exclamation-triangle text-red"></i></div>
            <div class="metric-value">{{ $atRisk }}</div>
            <div class="metric-label">At Risk / Lewat Batas</div>
            <div class="metric-sub"><span class="text-red fw-bold">Prioritas</span> Tindak Lanjut SDM</div>
        </div>

        <div class="metric-card card-undo" onclick="openDetailModal('dikoreksi')">
            <div class="card-icon bg-orange-light"><i class="fas fa-edit text-purple"></i></div>
            <div class="metric-value">{{ $dikoreksi }}</div>
            <div class="metric-label">Penilaian Dikoreksi</div>
            <div class="metric-sub"><span class="badge-pill bg-red-soft text-red">Tercatat</span> Dalam Audit Trail</div>
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

    <!-- WIDGET: Progress Penilaian per Unit Kerja -->
    <div class="unit-progress-card">
        <!-- Header Card yang Dirapikan -->
        <div class="unit-card-header">
            <div class="unit-header-title-wrapper">
                <div class="widget-icon-box" style="background: #ffe4e6; color: #9f1239; width: 42px; height: 42px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-chart-bar" style="font-size: 1.1rem;"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem; line-height: 1.2;">Progress Penilaian per Unit Kerja</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.3;">Ringkasan Komposisi Pegawai, Status Pengisian, dan Progres Verifikasi</p>
                </div>
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 fw-bold badge-unit-aktif" style="border-radius: 999px; font-size: 0.75rem;">
                {{ $totalUnitAktif ?? 0 }} Unit Kerja
            </span>
        </div>

        <!-- Container Tabel dengan Fitur Scroll Vertikal -->
        <div class="unit-table-scroll-container">
            <table class="unit-table">
                <thead>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>Komposisi</th>
                        <th style="min-width: 180px;">Progress Penilaian</th>
                        <th class="text-center">Draft</th>
                        <th>Submitted</th>
                        <th>Verifikasi</th>
                        <th class="text-center">Koreksi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($progressPerUnitKerja as $row)
                        <tr>
                            <td>
                                <div class="unit-cell-wrapper">
                                    <div class="unit-icon-box">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="unit-text-box">
                                        <div class="fw-bold text-dark unit-name-text">{{ $row->nama_unit }}</div>
                                        <div class="text-muted unit-sub-text">{{ $row->total_pegawai }} Pegawai</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($row->komposisi as $jabatan => $jumlah)
                                        <span class="badge-komposisi">
                                            {{ $jabatan }} : <span>{{ $jumlah }}</span>
                                        </span>
                                    @empty
                                        <span class="text-muted small">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem;">
                                    <span class="fw-bold text-dark">{{ $row->progress_count }}/{{ $row->total_pegawai }} Submit/Verifikasi</span>
                                    <span class="fw-bold text-primary">{{ $row->progress_percent }}%</span>
                                </div>
                                <div class="unit-progress-bar-bg">
                                    <div class="unit-progress-bar-fill" style="width: {{ $row->progress_percent }}%;"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-secondary">{{ $row->draft_count }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ $row->submit_count }} Submit</span>
                            </td>
                            <td>
                                <div style="font-size: 0.75rem;">
                                    <span class="text-muted">Verified</span> <span class="fw-bold text-dark">{{ $row->verified_count }}/{{ $row->total_pegawai }}</span><br>
                                    <span class="text-muted">Persen</span> <span class="fw-bold text-success">{{ $row->percent }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $row->koreksi_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($row->is_at_risk)
                                    <span class="badge-status-atrisk">At Risk</span>
                                @else
                                    <span class="badge-status-aman">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Tidak ada unit kerja yang memiliki data penilaian pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- WIDGET: Distribusi Predikat Kinerja & Rata-Rata Nilai per Unit Kerja -->
    <div class="distribusi-predikat-card">
        <!-- Header Card -->
        <div class="unit-card-header">
            <div class="unit-header-title-wrapper">
                <div class="widget-icon-box" style="background: #f3e8ff; color: #7e22ce; width: 42px; height: 42px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-chart-line" style="font-size: 1.1rem;"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem; line-height: 1.2;">Distribusi Predikat Kinerja</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.3;">Perbandingan Predikat dan Rata-Rata Nilai Setiap Unit Kerja</p>
                </div>
            </div>
            <span class="badge text-purple bg-purple-soft border border-purple-subtle px-3 py-2 fw-bold" style="border-radius: 999px; font-size: 0.75rem; background: #faf5ff; color: #6b21a8;">
                {{ $totalPegawaiBernilai ?? 0 }} Pegawai Bernilai
            </span>
        </div>

        <!-- Body Grid (Kiri Bar Chart, Kanan Ranking) -->
        <div class="distribusi-grid">
            <!-- Bagian Kiri: Komposisi Predikat -->
            <div class="distribution-panel">
                <!-- Legend Warna Predikat -->
                <div class="predikat-legend-bar">
                    <span class="fw-bold text-dark me-2">Komposisi Predikat per Unit Kerja</span>
                    <div class="predikat-legend-items">
                        <span class="legend-item-dot"><span class="dot-p dot-luar-biasa"></span> Luar Biasa</span>
                        <span class="legend-item-dot"><span class="dot-p dot-di-atas"></span> Diatas Ekspektasi</span>
                        <span class="legend-item-dot"><span class="dot-p dot-sesuai"></span> Sesuai Ekspektasi</span>
                        <span class="legend-item-dot"><span class="dot-p dot-di-bawah"></span> Dibawah Ekspektasi</span>
                        <span class="legend-item-dot"><span class="dot-p dot-mengecewakan"></span> Mengecewakan</span>
                    </div>
                </div>

                <!-- List Bar Chart per Unit (Scrollable) -->
                <div class="distribusi-left-scroll">
                    @forelse($distribusiUnitList as $item)
                        <div class="unit-bar-item">
                            <div class="unit-bar-info">
                                <div class="unit-bar-name" title="{{ $item->nama_unit }}">{{ $item->nama_unit }}</div>
                                <div class="unit-bar-sub">{{ $item->jumlah_bernilai }} Pegawai</div>
                            </div>

                            <!-- Stacked Segment Bar -->
                            <div class="stacked-bar-container">
                                @if($item->pct_luar_biasa > 0)
                                    <div class="stacked-bar-seg bg-luar-biasa" style="width: {{ $item->pct_luar_biasa }}%;" title="Luar Biasa: {{ $item->pct_luar_biasa }}%"></div>
                                @endif
                                @if($item->pct_di_atas > 0)
                                    <div class="stacked-bar-seg bg-di-atas" style="width: {{ $item->pct_di_atas }}%;" title="Di Atas Ekspektasi: {{ $item->pct_di_atas }}%"></div>
                                @endif
                                @if($item->pct_sesuai > 0)
                                    <div class="stacked-bar-seg bg-sesuai" style="width: {{ $item->pct_sesuai }}%;" title="Sesuai Ekspektasi: {{ $item->pct_sesuai }}%"></div>
                                @endif
                                @if($item->pct_di_bawah > 0)
                                    <div class="stacked-bar-seg bg-di-bawah" style="width: {{ $item->pct_di_bawah }}%;" title="Di Bawah Ekspektasi: {{ $item->pct_di_bawah }}%"></div>
                                @endif
                                @if($item->pct_mengecewakan > 0)
                                    <div class="stacked-bar-seg bg-mengecewakan" style="width: {{ $item->pct_mengecewakan }}%;" title="Mengecewakan: {{ $item->pct_mengecewakan }}%"></div>
                                @endif
                            </div>

                            <!-- Nilai Rata-Rata -->
                            <div class="unit-bar-avg">{{ $item->rata_rata }}</div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-chart-pie fs-3 mb-2 opacity-50"></i>
                            <p class="mb-0 small">Belum ada pegawai yang nilainya disubmit / diverifikasi pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Bagian Kanan: Ranking Rata-Rata Nilai -->
            <div class="ranking-panel">
                <div class="overall-average-card">
                    <div>
                        <div class="overall-average-value">{{ $rataRataKeseluruhan }}</div>
                        <div class="overall-average-note">Rata-Rata Nilai Keseluruhan</div>
                    </div>
                    <div class="p-2.5 rounded-3" style="background: #fff1f2; color: #801B2B;">
                        <i class="fas fa-arrow-trend-up fs-5"></i>
                    </div>
                </div>

                <div class="ranking-panel-title">
                    <i class="fas fa-ranking-star" aria-hidden="true"></i>
                    Peringkat Rata-Rata Unit Kerja
                </div>

                <div class="ranking-list-scroll">
                    @forelse($rankingUnits as $idx => $rk)
                        <div class="ranking-item">
                            <div class="ranking-left-group">
                                <div class="ranking-badge">{{ $idx + 1 }}</div>
                                <div class="overflow-hidden flex-grow-1">
                                    <div class="ranking-unit-name" title="{{ $rk->nama_unit }}">{{ $rk->nama_unit }}</div>
                                    <div class="ranking-unit-sub">{{ $rk->jumlah_bernilai }} Pegawai Bernilai</div>
                                </div>
                            </div>
                            <div class="ranking-score flex-shrink-0">{{ $rk->rata_rata }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            Tidak ada data peringkat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- WIDGET: Predikat Terendah Bulan Ini & Terendah 3 Bulan Berturut-turut -->
    <div class="low-performance-row">
        <!-- Kiri: Predikat Terendah Bulan Ini -->
        <div class="low-card">
            <div class="low-card-header">
                <div class="low-card-header-main">
                    <div class="low-card-header-icon" style="background: #fff7ed; color: #c2410c;">
                        <i class="fas fa-arrow-up" style="transform: rotate(45deg); font-size: 1rem;"></i>
                    </div>
                    <div class="low-card-header-copy">
                        <h6 class="low-card-title">Predikat Terendah Bulan Ini</h6>
                        <p class="low-card-description">Pegawai dengan Nilai Terendah Periode Aktif</p>
                    </div>
                </div>
                <span class="badge low-card-count" style="background: #ffedd5; color: #9a3412;">
                    {{ $totalTerendahBulanIni ?? 0 }} Pegawai
                </span>
            </div>

            <div class="low-list-container">
                @forelse($terendahBulanIni as $emp)
                    <div class="low-emp-card">
                        <div class="low-emp-info">
                            @if(!empty($emp->foto))
                                <img src="{{ asset('storage/' . $emp->foto) }}" class="low-emp-avatar" alt="{{ $emp->nama }}">
                            @else
                                <div class="low-emp-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div class="low-emp-details">
                                <span class="low-emp-name" title="{{ $emp->nama }}">{{ $emp->nama }}</span>
                                <span class="low-emp-sub" title="{{ $emp->nup }} &bull; {{ $emp->unit_kerja }} &bull; {{ $emp->jabatan }}">
                                    {{ $emp->nup }} &bull; {{ $emp->unit_kerja }} &bull; {{ $emp->jabatan }}
                                </span>
                            </div>
                        </div>
                        <div class="low-emp-score-box">
                            <span class="low-emp-score">{{ $emp->total_nilai }}</span>
                            @if(str_contains(strtolower($emp->predikat), 'mengecewakan'))
                                <span class="badge-predikat-mengecewakan">{{ $emp->predikat }}</span>
                            @else
                                <span class="badge-predikat-dibawah">{{ $emp->predikat }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle text-success fs-3 mb-2 opacity-50"></i>
                        <p class="mb-0 small">Tidak ada pegawai berpredikat rendah pada periode aktif ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kanan: Terendah 3 Bulan Berturut-turut -->
        <div class="low-card">
            <div class="low-card-header">
                <div class="low-card-header-main">
                    <div class="low-card-header-icon" style="background: #ffe4e6; color: #9f1239;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 1rem;"></i>
                    </div>
                    <div class="low-card-header-copy">
                        <h6 class="low-card-title">Terendah 3 Bulan Berturut-turut</h6>
                        <p class="low-card-description">Kandidat yang Membutuhkan Pembinaan atau Evaluasi</p>
                    </div>
                </div>
                <span class="badge low-card-count" style="background: #ffe4e6; color: #881337;">
                    {{ $totalTerendahTigaBulan ?? 0 }} Pegawai
                </span>
            </div>

            <div class="low-list-container">
                @forelse($terendahTigaBulan as $emp)
                    <div class="low-emp-card">
                        <div class="low-emp-info">
                            @if(!empty($emp->foto))
                                <img src="{{ asset('storage/' . $emp->foto) }}" class="low-emp-avatar" alt="{{ $emp->nama }}">
                            @else
                                <div class="low-emp-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div class="low-emp-details">
                                <span class="low-emp-name" title="{{ $emp->nama }}">{{ $emp->nama }}</span>
                                <span class="low-emp-sub" title="{{ $emp->nup }} &bull; {{ $emp->unit_kerja }} &bull; {{ $emp->jabatan }}">
                                    {{ $emp->nup }} &bull; {{ $emp->unit_kerja }} &bull; {{ $emp->jabatan }}
                                </span>
                            </div>
                        </div>
                        <div class="low-emp-score-box">
                            <span class="low-emp-score">{{ $emp->total_nilai }}</span>
                            @if(str_contains(strtolower($emp->predikat), 'mengecewakan'))
                                <span class="badge-predikat-mengecewakan">{{ $emp->predikat }}</span>
                            @else
                                <span class="badge-predikat-dibawah">{{ $emp->predikat }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <p class="mb-0 small" style="color: #64748b;">Tidak ada pegawai pada kategori ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- WIDGET: Monitoring Detail Pegawai -->
    <div class="mon-card">
        <div class="mon-header">
                <div class="mon-header-main">
                    <div class="mon-header-icon">
                    <i class="fas fa-users" style="font-size: 1.1rem;"></i>
                </div>
                    <div class="mon-header-copy">
                        <h6 class="mon-header-title">Monitoring Detail Pegawai</h6>
                        <p class="mon-header-description">Daftar Status Penilaian Untuk Tindak Lanjut Operasional</p>
                </div>
            </div>
                <span id="totalDataBadge" class="mon-header-count">
                0 Data
            </span>
        </div>

        <!-- Tab Navigasi Filter Status[cite: 19] -->
        <div class="mon-tabs-bar">
            <button type="button" class="mon-tab-btn active" onclick="switchMonTab('semua', this)">Semua</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('belum_dinilai', this)">Belum Dinilai</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('draft', this)">Draft</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('menunggu', this)">Belum Verifikasi</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('returned', this)">Returned</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('verified', this)">Verified</button>
            <button type="button" class="mon-tab-btn" onclick="switchMonTab('koreksi', this)">Ada Koreksi</button>
        </div>

        <!-- Data Table Container[cite: 19] -->
        <div class="mon-table-wrapper">
            <table class="mon-table">
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Unit Kerja / Jabatan</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Penilai</th>
                        <th>Submit</th>
                        <th>Verifikator</th>
                        <th>Verified</th>
                        <th>Koreksi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="monTableBody">
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="monPagination" class="mon-pagination-wrap">
            <p id="monPageInfo" class="detail-pagination-label" style="text-align:center;margin-bottom:0.5rem;"></p>
            <div id="monPaginationNav" class="detail-pagination-nav" style="justify-content:center;margin-left:0;"></div>
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

<div class="modal-detail-backdrop" id="pegawaiDetailModal">
    <div class="modal-detail-content">
        <div class="modal-detail-header">
            <div class="modal-detail-heading">
                <h5 class="modal-detail-title" id="modalEmpTitle">Detail Penilaian</h5>
                <p class="modal-detail-subtitle" id="modalEmpSub">-</p>
            </div>
            <button type="button" class="modal-detail-close" aria-label="Tutup detail" onclick="closePegawaiModal()">&times;</button>
        </div>

        <div class="modal-detail-body">
            <div class="modal-summary-grid">
                <div class="modal-summary-card">
                    <span class="modal-summary-label">Status</span>
                    <span id="modalStatusBadge" class="badge-status-custom st-belum">Belum Dinilai</span>
                </div>
                <div class="modal-summary-card">
                    <span class="modal-summary-label">Nilai Akhir</span>
                    <div class="modal-summary-value" id="modalNilaiAkhir">-</div>
                </div>
                <div class="modal-summary-card">
                    <span class="modal-summary-label">Predikat</span>
                    <div class="modal-summary-value modal-summary-predikat" id="modalPredikat">-</div>
                </div>
                <div class="modal-summary-card">
                    <span class="modal-summary-label">Koreksi</span>
                    <span id="modalKoreksiBadge" class="badge-status-custom st-belum">Tidak Ada</span>
                </div>
            </div>

            <table class="modal-info-table">
                <tr>
                    <td class="modal-info-label">Pegawai</td>
                    <td class="modal-info-value" id="mNamaNup">-</td>
                    <td class="modal-info-label">Satker</td>
                    <td class="modal-info-value" id="mSatker">-</td>
                </tr>
                <tr>
                    <td class="modal-info-label">Jabatan</td>
                    <td class="modal-info-value" id="mJabatan">-</td>
                    <td class="modal-info-label">Penempatan</td>
                    <td class="modal-info-value" id="mPenempatan">-</td>
                </tr>
                <tr>
                    <td class="modal-info-label">Dinilai Oleh</td>
                    <td class="modal-info-value" id="mPenilai">-</td>
                    <td class="modal-info-label">Tgl Submit</td>
                    <td class="modal-info-value" id="mTglSubmit">-</td>
                </tr>
                <tr>
                    <td class="modal-info-label">Diverifikasi Oleh</td>
                    <td class="modal-info-value" id="mVerifikator">-</td>
                    <td class="modal-info-label">Tgl Verified</td>
                    <td class="modal-info-value" id="mTglVerified">-</td>
                </tr>
            </table>

            <div class="modal-detail-tabs" role="tablist">
                <button type="button" class="modal-detail-tab active" id="ringkasanTab" data-modal-tab="ringkasanContent" role="tab" aria-controls="ringkasanContent" aria-selected="true">Ringkasan</button>
                <button type="button" class="modal-detail-tab" id="pertanyaanTab" data-modal-tab="pertanyaanContent" role="tab" aria-controls="pertanyaanContent" aria-selected="false">Hasil Penilaian per Pertanyaan</button>
            </div>

            <div id="modalTabContent">
                <div class="modal-detail-tab-pane" id="ringkasanContent" role="tabpanel" aria-labelledby="ringkasanTab">
                    <div class="modal-summary-alert">
                        <i class="fas fa-info-circle me-1"></i> <span id="modalInfoText">Pegawai ini masuk dalam daftar monitoring penilaian operasional.</span>
                    </div>
                </div>
                <div class="modal-detail-tab-pane" id="pertanyaanContent" role="tabpanel" aria-labelledby="pertanyaanTab" hidden>
                    <div id="modalQuestionsList" class="small text-muted">Belum ada rincian penilaian untuk periode ini.</div>
                </div>
            </div>
        </div>

        <div class="modal-detail-footer">
            <button type="button" onclick="closePegawaiModal()">Tutup</button>
        </div>
    </div>
</div>

</div> <!-- Tutup #pane-monitoring-dashboard -->

<!-- JAVASCRIPT FETCH DATA AJAX (FIXED ROUTE & DATA CHECK) -->
<script>
function switchDashboardTab(tabName) {
    const paneMonitoring = document.getElementById('pane-monitoring-dashboard');
    const paneProfile = document.getElementById('pane-profile-dashboard');
    const tabs = document.querySelectorAll('.dash-role-tab');

    if (tabName === 'monitoring') {
        if (paneMonitoring) paneMonitoring.style.display = 'block';
        if (paneProfile) paneProfile.style.display = 'none';
        if (tabs[0]) tabs[0].classList.add('active');
        if (tabs[1]) tabs[1].classList.remove('active');
    } else {
        if (paneMonitoring) paneMonitoring.style.display = 'none';
        if (paneProfile) paneProfile.style.display = 'block';
        if (tabs[0]) tabs[0].classList.remove('active');
        if (tabs[1]) tabs[1].classList.add('active');
    }
}

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

                    // Ambil nilai akhir dengan prioritas nilai verifikator jika ada
                    let rawNilai = item.total_nilai_verifikator ?? item.total_nilai;
                    let nilai = isNaN(parseFloat(rawNilai)) ? 0 : parseFloat(rawNilai);

                    // Penentuan Badge Status khusus untuk card Koreksi atau card lainnya
                    let statusClass = 'badge-status-gray';
                    let statusLabel = 'Belum Diisi';

                    if (type === 'dikoreksi') {
                        statusClass = 'badge-status-danger';
                        statusLabel = 'Ada Koreksi';
                    } else {
                        let statusNilaiRaw = (item.status_nilai || '').toUpperCase().trim();
                        let statusVerifRaw = (item.status_verifikator || '').toUpperCase().trim();

                        if (['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'].includes(statusVerifRaw)) {
                            statusClass = 'badge-status-success';
                            statusLabel = 'Terverifikasi';
                        } else if (['DIKEMBALIKAN', 'REVISI'].includes(statusVerifRaw) || ['DIKEMBALIKAN', 'REVISI'].includes(statusNilaiRaw)) {
                            statusClass = 'badge-status-rev';
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
                    }

                    let statusBadge = `<span class="${statusClass} text-nowrap">${statusLabel}</span>`;

                    // Penentuan Badge Predikat (Mengambil predikat verifikator jika ada)
                    let textPredikat = item.predikat_verifikator || item.predikat;
                    let badgeStyle = 'badge-rose';
                    if (nilai >= 90) badgeStyle = 'badge-emerald';
                    else if (nilai >= 80) badgeStyle = 'badge-blue';
                    else if (nilai >= 70) badgeStyle = 'badge-amber';
                    else if (nilai >= 60) badgeStyle = 'badge-orange';

                    let predikatNama = textPredikat ? textPredikat : 'Belum terdapat data predikat';
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

<script>
let currentMonTab = 'semua';
let currentMonPage = 1;

document.addEventListener("DOMContentLoaded", function () {
    loadMonitoringTable(currentMonTab);

    const periodeDropdown = document.getElementById('periodeSelect');
    if (periodeDropdown) {
        periodeDropdown.addEventListener('change', () => loadMonitoringTable(currentMonTab, 1));
    }

    document.querySelectorAll('.modal-detail-tab').forEach(button => {
        button.addEventListener('click', function () {
            const selectedPanelId = this.dataset.modalTab;
            document.querySelectorAll('.modal-detail-tab').forEach(tab => {
                tab.classList.remove('active');
                tab.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.modal-detail-tab-pane').forEach(panel => {
                panel.hidden = panel.id !== selectedPanelId;
                panel.classList.remove('d-none');
            });

            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
        });
    });

    const detailModal = document.getElementById('pegawaiDetailModal');
    if (detailModal) {
        detailModal.addEventListener('click', event => {
            if (event.target === detailModal) closePegawaiModal();
        });
    }
});

function switchMonTab(tabName, btnElement) {
    currentMonTab = tabName;
    currentMonPage = 1;
    document.querySelectorAll('.mon-tab-btn').forEach(btn => btn.classList.remove('active'));
    btnElement.classList.add('active');
    loadMonitoringTable(tabName, 1);
}

function escapeDashboardHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[character]);
}

function dashboardDate(value) {
    if (!value) return '-';
    const date = new Date(`${String(value).slice(0, 10)}T00:00:00`);
    return Number.isNaN(date.getTime()) ? '-' : date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

function monitoringStatus(item) {
    const nilai = String(item.status_nilai || '').trim().toUpperCase();
    const verifikator = String(item.status_verifikator || item.status_verifitor || '').trim().toUpperCase();
    if (['DIKEMBALIKAN', 'REVISI'].includes(nilai) || ['DIKEMBALIKAN', 'REVISI'].includes(verifikator)) {
        return { label: 'Dikembalikan', style: 'st-returned' };
    }
    if (['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'].includes(verifikator) ||
        ['VERIFIED', 'VERIFIKASI', 'SELESAI', 'APPROVED'].includes(nilai)) {
        return { label: 'Verified', style: 'st-verified' };
    }
    if (['DRAFT', 'SIMPAN', 'SAVED'].includes(nilai)) {
        return { label: 'Draft', style: 'st-draft' };
    }
    if (['DIAJUKAN', 'SUBMIT', 'SUBMITTED', 'MENUNGGU VERIFIKASI', 'MENUNGGU'].includes(nilai)) {
        return { label: 'Menunggu Verifikasi', style: 'st-submit' };
    }
    if (['KOREKSI', 'PERLU DITINJAU'].includes(verifikator)) {
        return { label: 'Menunggu Verifikasi', style: 'st-submit' };
    }
    return { label: 'Belum Dinilai', style: 'st-belum' };
}

async function loadMonitoringTable(tab, page = 1) {
    const periodeSelect = document.getElementById('periodeSelect');
    const periodeId = periodeSelect ? periodeSelect.value : '';
    const tbody = document.getElementById('monTableBody');
    const badge = document.getElementById('totalDataBadge');
    const pageInfo = document.getElementById('monPageInfo');
    const paginationNav = document.getElementById('monPaginationNav');

    if (!tbody || !periodeId) return;
    tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">Memuat data...</td></tr>`;
    currentMonPage = page;

    const params = new URLSearchParams({ periode_id: periodeId, tab, page: String(page) });

    try {
        const response = await fetch(`{{ route('dashboard.monitoring.data') }}?${params.toString()}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const result = await response.json();
        const data = result.data || [];
        badge.innerText = `${result.total ?? 0} Data`;
        pageInfo.innerText = result.total
            ? `Menampilkan ${result.from} sampai ${result.to} dari ${result.total} data`
            : 'Menampilkan 0 data';

        // Render Pagination Nav style Detail Periode
        if (paginationNav) {
            const currentPage = result.current_page || 1;
            const lastPage = result.last_page || 1;

            if (lastPage > 1) {
                let pageNumbers = [];
                if (lastPage <= 5) {
                    for (let i = 1; i <= lastPage; i++) pageNumbers.push(i);
                } else if (currentPage <= 3) {
                    pageNumbers = [1, 2, 3, 4, 5];
                } else if (currentPage >= lastPage - 2) {
                    pageNumbers = [lastPage - 4, lastPage - 3, lastPage - 2, lastPage - 1, lastPage];
                } else {
                    pageNumbers = [currentPage - 1, currentPage, currentPage + 1, currentPage + 2, currentPage + 3];
                }

                let navHtml = '';
                // Previous button
                if (currentPage > 1) {
                    navHtml += `<button type="button" class="page-arrow" onclick="loadMonitoringTable('${tab}', ${currentPage - 1})" aria-label="Previous page">&lsaquo;</button>`;
                } else {
                    navHtml += `<span class="page-arrow disabled" aria-hidden="true">&lsaquo;</span>`;
                }

                // Number buttons
                pageNumbers.forEach(p => {
                    if (p === currentPage) {
                        navHtml += `<span class="page-number active" aria-current="page">${p}</span>`;
                    } else {
                        navHtml += `<button type="button" class="page-number" onclick="loadMonitoringTable('${tab}', ${p})">${p}</button>`;
                    }
                });

                // Ellipsis
                if (lastPage > 5 && currentPage < lastPage - 2) {
                    navHtml += `<span class="page-ellipsis">…</span>`;
                }

                // Next button
                if (currentPage < lastPage) {
                    navHtml += `<button type="button" class="page-arrow" onclick="loadMonitoringTable('${tab}', ${currentPage + 1})" aria-label="Next page">&rsaquo;</button>`;
                } else {
                    navHtml += `<span class="page-arrow disabled" aria-hidden="true">&rsaquo;</span>`;
                }

                paginationNav.innerHTML = navHtml;
                paginationNav.style.display = 'flex';
            } else {
                paginationNav.innerHTML = '';
            }
        }

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data pegawai pada kategori ini untuk periode yang dipilih.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(item => {
            const status = monitoringStatus(item);
            const correctionCount = Number(item.koreksi_count || 0);
            const hasCorrection = correctionCount > 0 || status.label === 'Ada Koreksi';
            const correctionLabel = hasCorrection
                ? `Ada Koreksi${correctionCount > 0 ? ` (${correctionCount})` : ''}`
                : 'Tidak Ada';
            const scoreValue = item.total_nilai_verifikator ?? item.total_nilai;
            const score = scoreValue === null || scoreValue === '' ? '-' : Number(scoreValue).toFixed(2);
            return `
                <tr>
                    <td>
                        <div class="fw-bold text-dark">${escapeDashboardHtml(item.nama || '-')}</div>
                        <div class="text-muted small">${escapeDashboardHtml(item.nup || '-')}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">${escapeDashboardHtml(item.unit_kerja || '-')}</div>
                        <div class="text-muted small">${escapeDashboardHtml(item.jabatan || '-')}</div>
                    </td>
                    <td><span class="badge-status-custom ${status.style}">${status.label}</span></td>
                    <td>
                        <div class="fw-bold">${score}</div>
                        <div class="text-muted small">${escapeDashboardHtml(item.predikat_verifikator || item.predikat || '')}</div>
                    </td>
                    <td>${escapeDashboardHtml(item.penilai_nama || '-')}</td>
                    <td>${dashboardDate(item.tanggal_submit)}</td>
                    <td>${escapeDashboardHtml(item.verifikator_nama || '-')}</td>
                    <td>${dashboardDate(item.tanggal_verifikasi)}</td>

                    <td>
                        ${Number(item.koreksi_count || 0) > 0 
                            ? `<span class="badge-status-danger text-dark fw-bold px-2.5 py-1" style="font-size: 0.75rem;">${item.koreksi_count} Koreksi</span>` 
                            : `<span class="badge bg-light text-dark">Tidak Ada</span>`}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn-action-eye" aria-label="Lihat detail ${escapeDashboardHtml(item.nama || 'pegawai')}" onclick="openPegawaiModal('${encodeURIComponent(item.pgw_id)}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>`;
        }).join('');
    } catch (error) {
        console.error('Gagal memuat data monitoring:', error);
        badge.innerText = 'Data tidak tersedia';
        pageInfo.innerText = '';
        previousButton.disabled = true;
        nextButton.disabled = true;
        tbody.innerHTML = `<tr><td colspan="10" class="text-center py-4 text-danger">Gagal memuat data monitoring. Silakan muat ulang atau coba lagi.</td></tr>`;
    }
}

function renderQuestionDetails(categories) {
    const container = document.getElementById('modalQuestionsList');
    if (!categories || categories.length === 0) {
        container.innerHTML = '<div class="alert alert-light border mb-0 text-center py-4 text-muted"><i class="fas fa-inbox me-2 fa-lg text-secondary"></i>Belum ada rincian penilaian per pertanyaan untuk pegawai ini pada periode tersebut.</div>';
        return;
    }

    container.innerHTML = categories.map((category, categoryIndex) => `
        <section class="modal-question-category mb-4">
            <!-- Header Kategori: Judul di kiri, Bobot di ujung kanan secara sejajar -->
            <div class="modal-question-category-title">
                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                    <span class="modal-question-number">${categoryIndex + 1}</span>
                    <span class="fw-bold text-dark text-uppercase tracking-wide text-truncate" style="font-size: 0.88rem; letter-spacing: 0.02em;">${escapeDashboardHtml(category.nama_kategori)}</span>
                </div>
                <span class="modal-question-bobot">
                    Bobot ${escapeDashboardHtml(category.bobot_kategori ?? 0)}%
                </span>
            </div>
            
            <div class="modal-question-grid">
                ${(category.pertanyaans || []).map((question, questionIndex) => {
                    const penilaiScore = question.nilai_angka ?? question.nilai_akhir;
                    const verifierScore = question.verif_nilai_angka ?? question.verif_nilai_akhir;
                    const hasVerifierValue = verifierScore !== null && verifierScore !== undefined;
                    const isCorrected = String(question.verif_status || '').trim().toUpperCase() === 'KOREKSI' ||
                        (hasVerifierValue && penilaiScore !== null && penilaiScore !== undefined &&
                            Number(verifierScore) !== Number(penilaiScore));

                    const penilaiKode = String(question.kode_nilai || '').trim();
                    const verifKode = String(question.verif_kode_nilai || '').trim();
                    
                    return `
                        <article class="modal-question-card ${isCorrected ? 'has-correction' : ''}">
                            <div>
                                <div class="modal-question-header">
                                    <div class="modal-question-left-group">
                                        <span class="modal-question-number">${questionIndex + 1}</span>
                                        <h6 class="modal-question-title">${escapeDashboardHtml(question.pertanyaan || 'Pertanyaan')}</h6>
                                    </div>
                                    <span class="modal-question-bobot">${escapeDashboardHtml(question.bobot_pertanyaan_persen ?? 0)}%</span>
                                </div>
                                
                                ${isCorrected ? `
                                <div class="mb-2">
                                    <span class="modal-question-correction">
                                        <i class="fas fa-pen-nib"></i> Nilai dikoreksi verifikator
                                    </span>
                                </div>` : ''}
                                
                                ${question.deskripsi ? `<div class="modal-question-description">${escapeDashboardHtml(question.deskripsi)}</div>` : ''}
                            </div>
                            
                            <div class="mt-auto">
                                <div class="modal-question-score-box">
                                    <div class="modal-score-item">
                                        <div class="modal-score-label">Nilai dari Penilai</div>
                                        <div class="modal-score-badge-custom">
                                            ${penilaiKode ? `<span class="modal-grade-pill ${penilaiKode.toUpperCase() === 'B' ? 'blue' : (penilaiKode.toUpperCase() === 'C' ? 'orange' : '')}">${escapeDashboardHtml(penilaiKode)}</span>` : ''}
                                            <span class="modal-score-text">${escapeDashboardHtml(question.nama_nilai || '-')} ${penilaiScore !== null && penilaiScore !== undefined ? `(${escapeDashboardHtml(penilaiScore)})` : ''}</span>
                                        </div>
                                    </div>
                                    ${isCorrected || hasVerifierValue || question.verif_nama_nilai ? `
                                    <div class="modal-score-item">
                                        <div class="modal-score-label ${isCorrected ? 'text-warning-emphasis fw-semibold' : ''}">Nilai dari Verifikator</div>
                                        <div class="modal-score-badge-custom">
                                            ${verifKode ? `<span class="modal-grade-pill ${verifKode.toUpperCase() === 'B' ? 'blue' : (verifKode.toUpperCase() === 'C' ? 'orange' : '')}">${escapeDashboardHtml(verifKode)}</span>` : ''}
                                            <span class="modal-score-text ${isCorrected ? 'text-dark' : ''}">${escapeDashboardHtml(question.verif_nama_nilai || '-')} ${verifierScore !== null && verifierScore !== undefined ? `(${escapeDashboardHtml(verifierScore)})` : ''}</span>
                                        </div>
                                    </div>` : ''}
                                </div>
                                
                                <div class="modal-question-note-box">
                                    <div class="modal-note-title">Catatan Penilai</div>
                                    <div class="modal-note-desc ${question.catatan ? 'has-content' : ''}">${escapeDashboardHtml(question.catatan || 'Tidak ada catatan.')}</div>
                                </div>
                                ${question.verif_catatan ? `
                                <div class="modal-question-note-box">
                                    <div class="modal-note-title text-warning-emphasis">Catatan Verifikator</div>
                                    <div class="modal-note-desc has-content">${escapeDashboardHtml(question.verif_catatan)}</div>
                                </div>` : ''}
                            </div>
                        </article>`;
                }).join('')}
            </div>
        </section>`).join('');
}

async function openPegawaiModal(pgwId) {
    const periodeSelect = document.getElementById('periodeSelect');
    const periodeId = periodeSelect ? periodeSelect.value : '';
    const modal = document.getElementById('pegawaiDetailModal');
    const title = document.getElementById('modalEmpTitle');
    title.innerText = 'Memuat Detail Penilaian...';
    document.getElementById('modalQuestionsList').innerHTML = '<div class="text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Memuat hasil penilaian...</div>';
    modal.classList.add('show');

    try {
        const params = new URLSearchParams({ pgw_id: decodeURIComponent(pgwId), periode_id: periodeId });
        const response = await fetch(`{{ route('dashboard.pegawai.detail') }}?${params.toString()}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        const employee = result.employee || {};
        const transaction = result.trans || {};
        const status = monitoringStatus(transaction);
        const correctionCount = (result.pertanyaan || []).flatMap(category => category.pertanyaans || [])
            .filter(question => {
                const penilaiScore = question.nilai_angka ?? question.nilai_akhir;
                const verifierScore = question.verif_nilai_angka ?? question.verif_nilai_akhir;
                return String(question.verif_status || '').trim().toUpperCase() === 'KOREKSI' ||
                    (verifierScore !== null && verifierScore !== undefined &&
                        penilaiScore !== null && penilaiScore !== undefined &&
                        Number(verifierScore) !== Number(penilaiScore));
            }).length;
        const hasCorrection = correctionCount > 0 ||
            Number(transaction.koreksi_count || 0) > 0 ||
            ['KOREKSI', 'PERLU DITINJAU'].includes(
                String(transaction.status_verifikator || transaction.status_verifitor || '').trim().toUpperCase()
            );

        title.innerText = `Detail Penilaian — ${employee.nama || '-'}`;
        document.getElementById('modalEmpSub').innerText = `${employee.nup || '-'} · ${employee.unit_kerja || '-'} · ${employee.jabatan || '-'}`;
        document.getElementById('mNamaNup').innerText = `${employee.nama || '-'} / ${employee.nup || '-'}`;
        document.getElementById('mSatker').innerText = employee.satker || '-';
        document.getElementById('mJabatan').innerText = employee.jabatan || '-';
        document.getElementById('mPenempatan').innerText = employee.penempatan || '-';
        document.getElementById('mPenilai').innerText = transaction.nama_penilai || '-';
        document.getElementById('mVerifikator').innerText = transaction.nama_verifikator || '-';
        document.getElementById('mTglSubmit').innerText = dashboardDate(transaction.tanggal_submit);
        document.getElementById('mTglVerified').innerText = dashboardDate(transaction.tanggal_verifikasi);
        const finalScore = transaction.total_nilai_verifikator ?? transaction.total_nilai;
        document.getElementById('modalNilaiAkhir').innerText = finalScore === null || finalScore === undefined
            ? '-' : Number(finalScore).toFixed(2);
        document.getElementById('modalPredikat').innerText = transaction.predikat_verifikator || transaction.predikat || '-';
        document.getElementById('modalStatusBadge').innerText = status.label;
        document.getElementById('modalStatusBadge').className = `badge-status-custom ${status.style}`;

        const modalKoreksiElement = document.getElementById('modalKoreksiBadge');
        const totalKoreksi = Number(transaction.koreksi_count ?? correctionCount ?? 0);
        if (modalKoreksiElement) {
            if (totalKoreksi > 0) {
                modalKoreksiElement.innerText = `${totalKoreksi} Koreksi`;
                modalKoreksiElement.className = 'badge-status-danger text-dark fw-bold px-2.5 py-1';
                modalKoreksiElement.style.fontSize = '0.75rem';
            } else {
                modalKoreksiElement.innerText = 'Tidak Ada';
                modalKoreksiElement.className = 'badge bg-light text-dark';
                modalKoreksiElement.style.fontSize = '';
            }
        }

        document.getElementById('modalInfoText').innerText = transaction.catatan || 'Tidak ada catatan atau risiko waktu pada data ini.';
        renderQuestionDetails(result.pertanyaan);

        document.querySelectorAll('.modal-detail-tab').forEach((tab, index) => {
            tab.classList.toggle('active', index === 0);
            tab.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
        });
        document.querySelectorAll('.modal-detail-tab-pane').forEach((panel, index) => {
            panel.hidden = index !== 0;
            panel.classList.remove('d-none');
        });
    } catch (error) {
        console.error('Gagal memuat detail penilaian:', error);
        title.innerText = 'Detail Penilaian Tidak Tersedia';
        document.getElementById('modalQuestionsList').innerHTML = '<div class="alert alert-danger mb-0">Gagal memuat detail pegawai. Silakan tutup dan coba lagi.</div>';
    }
}

function closePegawaiModal() {
    document.getElementById('pegawaiDetailModal').classList.remove('show');
}
</script>

@endsection