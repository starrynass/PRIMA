<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700;800&display=swap');

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

    * { box-sizing: border-box; margin: 0; padding: 0; }

    .page-container {
        font-family: var(--font-main);
        padding: 2rem;
        background-color: var(--bg-page);
        min-height: 100vh;
        color: var(--text-primary);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        -webkit-font-smoothing: antialiased;
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

    /* --- Action Controls Toolbar --- */
   .action-controls {
    display: flex;
    align-items: center;
    gap: 0.625rem; /* Jarak antar tombol */
    flex-wrap: wrap; /* Agar responsif di layar kecil */
}
/* Base style untuk semua tombol di toolbar */
.action-controls .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: var(--radius-md, 0.5rem);
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    border: 1px solid transparent;
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

/* Tombol Ubah / Edit ketika AKTIF (disabled dilepas) */
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

/* Tombol Hapus / Delete ketika AKTIF (disabled dilepas) */
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

/* Kursor pointer untuk baris tabel */
#predikatTableBody tr {
    cursor: pointer;
}

/* ==========================================
   STYLE UNTUK TOMBOL DISABLED (MATI / PUDAR)
   ========================================== */
.action-controls .btn:disabled,
.action-controls .btn[disabled] {
    background-color: #f1f5f9 !important;
    color: #94a3b8 !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    opacity: 0.6;
}

/* Tombol Refresh */
.btn-refresh {
    background-color: #f1f5f9 !important; 
    color: #1e293b !important;          
    border: 1.5px solid #919dad !important; 
    border-radius: 0.5rem;
    padding: 0.5rem 1.25rem;
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

/* Tombol Tambah Data (Maroon Primary) */
.btn-add {
    background-color: var(--maroon-primary, #800020);
    color: #ffffff;
}
.btn-add:hover {
    background-color: #660019;
    box-shadow: 0 4px 12px rgba(128, 0, 32, 0.25);
}

/* Ukuran Ikon SVG Sesuai */
.icon-svg-sm {
    width: 1.125rem;
    height: 1.125rem;
    flex-shrink: 0;
}
    /* --- Data Table --- */
    .table-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        overflow: visible; /* Memastikan dropdown tidak terpotong */
        box-shadow: var(--shadow-card);
    }

    .table-responsive { overflow-x: auto; }

    .data-table {
        width: 100%;
        font-size: 0.8125rem;
        text-align: left;
        border-collapse: collapse;
    }

    /* --- UBAH BAGIAN INI --- */
    .data-table thead {
        background-color: var(--maroon-primary); 
        color: white;       
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.725rem;
        border-bottom: 1px solid var(--maroon-border, #FFE4E6); /* Border bawah senada */
    }

    .data-table th, .data-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #F1F5F9;
        transition: background-color 0.15s;
    }

    .data-table tbody tr:hover { background-color: #FDF7F9; }

    .id-cell { 
        color: var(--maroon-primary); 
        font-weight: 800; 
        background-color: var(--maroon-soft);
        padding: 0.25rem 0.625rem;
        border-radius: 0.375rem;
        border: 1px solid var(--maroon-border);
        font-size: 0.75rem;
    }

    .text-center { text-align: center; }
    .font-mono { font-family: var(--font-code); }

    /* --- BADGES --- */
    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 0.5rem;
        font-size: 0.775rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        letter-spacing: 0.02em;
        border-width: 1.5px;
        border-style: solid;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .badge-dot {
        width: 0.45rem;
        height: 0.45rem;
        border-radius: 50%;
        background-color: currentColor;
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


    .icon-svg { width: 1.15rem; height: 1.15rem; }
    .icon-svg-sm { width: 0.95rem; height: 0.95rem; }

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

/* Hilangkan animasi dari .modal-backdrop, pindahkan efek transisi ke .modal-card */
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

tr.selected-row {
    background-color: #f1f5f9 !important;
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

/* Modal Body & Forms */
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

/* Box Informasi Catatan */
.info-alert-box {
    background-color: #E0F2FE;
    border: 1px solid #BAE6FD;
    padding: 0.875rem 1rem;
    border-radius: var(--radius-md);
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.info-alert-icon {
    color: #0284C7;
    margin-top: 0.1rem;
    flex-shrink: 0;
}

.info-alert-text {
    font-size: 0.785rem;
    color: #0369A1;
    line-height: 1.45;
}

/* Modal Footer */
/* Container Modal Footer */
.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
}

/* Base Style Tombol */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    outline: none;
}
/* Backdrop Modal */
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

/* Card Container */
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

/* Top Red Accent Bar */
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

/* Warning Icon Styling */
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
    border: 1px solid #E2E8F0;
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

.btn-amber {
    background-color: #D97706 !important;
    color: #FFFFFF !important;
    border: none;
    transition: all 0.2s ease-in-out;
}

.btn-amber:hover {
    background-color: #B45309 !important;
    box-shadow: 0 4px 6px -1px rgba(217, 119, 6, 0.3);
}

/* Animasi Pop Up */
@keyframes modalPop {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<div class="page-container mb-6">
    <!-- TOOLBAR PREDIKAT -->
    <div class="toolbar-card">
        <div class="toolbar-header">
            <div class="toolbar-icon-wrapper">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="toolbar-title">Master Predikat Nilai</h1>
                <p class="toolbar-subtitle">
                    Total Master Data: <span class="toolbar-subtitle-count">{{ count($predikatNilai) }} Data</span>
                </p>
            </div>
        </div>

        <div class="action-controls d-flex gap-2">
            <button type="button" id="btnRefreshPredikat" onclick="window.location.reload()" class="btn btn-refresh">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui
            </button>

            <button type="button" id="btnAddPredikat" onclick="openModalTambahPredikat()" class="btn btn-add">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </button>

            <button type="button" class="btn btn-edit" id="btnEditPredikat" onclick="openModalEditPredikat()" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Ubah
            </button>

            <button type="button" class="btn btn-delete" id="btnDeletePredikat" onclick="deletePredikatRow()" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- TABEL DATA PREDIKAT -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table" id="predikatTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 2.5rem;">PILIH</th>
                        <th class="text-center" style="width: 3.5rem;">#</th>
                        <th>ID PREDIKAT</th>
                        <th>KODE</th>
                        <th class="text-center">NILAI MIN</th>
                        <th class="text-center">NILAI MAX</th>
                        <th>PREDIKAT</th>
                        <th class="text-center">PREVIEW BADGE</th>
                    </tr>
                </thead>
                <tbody id="predikatTableBody">
                    @forelse($predikatNilai as $index => $item)
                        <tr onclick="selectRowPredikat(this, '{{ $item->predikat_id }}', '{{ $item->kode }}', '{{ $item->nilai_min }}', '{{ $item->nilai_max }}', '{{ $item->predikat }}')">
                            <td class="text-center" onclick="event.stopPropagation()">
                                <input type="radio" name="row_select_predikat" value="{{ $item->predikat_id }}" 
                                       onchange="onRadioChangePredikat(this, '{{ $item->predikat_id }}', '{{ $item->kode }}', '{{ $item->nilai_min }}', '{{ $item->nilai_max }}', '{{ $item->predikat }}')"
                                       class="radio-input">
                            </td>
                            <td class="text-center font-mono" style="color: #94A3B8;">{{ sprintf('%02d', $index + 1) }}</td>
                            <td><span class="font-mono id-cell">{{ $item->predikat_id }}</span></td>
                            <td style="font-weight: 700; color: var(--text-primary);">{{ $item->kode }}</td>
                            <td class="text-center font-mono" style="font-weight: 600;">{{ number_format($item->nilai_min, 2) }}</td>
                            <td class="text-center font-mono" style="font-weight: 600;">{{ number_format($item->nilai_max, 2) }}</td>
                            <td style="font-weight: 700; color: var(--text-primary);">{{ $item->predikat }}</td>
                            <td class="text-center">
                                @php
                                    $badgeStyle = 'badge-amber';
                                    if ($item->nilai_min >= 90) $badgeStyle = 'badge-emerald';
                                    elseif ($item->nilai_min >= 80) $badgeStyle = 'badge-blue';
                                    elseif ($item->nilai_min >= 70) $badgeStyle = 'badge-amber';
                                    elseif ($item->nilai_min >= 60) $badgeStyle = 'badge-orange';
                                    else $badgeStyle = 'badge-rose';
                                @endphp
                                <span class="badge {{ $badgeStyle }}">
                                    <span class="badge-dot"></span>
                                    {{ $item->predikat }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 3rem; color: var(--text-muted);">
                                Belum ada data predikat nilai yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FORM PREDIKAT -->
<div class="modal-backdrop" id="modalPredikat" onclick="closeModalPredikatOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge" id="modalIconBadgePredikat">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="modalIconPathPredikat" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitlePredikat">Tambah Predikat Nilai</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalPredikat()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formPredikat" method="POST" action="">
            @csrf
            <input type="hidden" id="formMethodPredikat" name="_method" value="POST">
            <input type="hidden" id="predikat_id" name="predikat_id">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="kode">Kode Predikat</label>
                    <div class="input-addon-group">
                        <span class="input-addon">Kode</span>
                        <input type="text" id="kode" name="kode" class="form-input" placeholder="contoh: PRED001 (opsional)">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nilai_min">Nilai Minimum <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Min</span>
                            <input type="number" step="0.01" id="nilai_min" name="nilai_min" class="form-input" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nilai_max">Nilai Maksimum <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Max</span>
                            <input type="number" step="0.01" id="nilai_max" name="nilai_max" class="form-input" placeholder="100.00" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="predikat">Predikat <span class="text-danger">*</span></label>
                    <div class="input-addon-group">
                        <span class="input-addon">Predikat</span>
                        <input type="text" id="predikat" name="predikat" class="form-input" placeholder="contoh: Sangat Baik / Luar Biasa" required>
                    </div>
                </div>

                <div class="info-alert-box">
                    <div class="info-alert-icon">
                        <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="info-alert-text">
                        <strong>Catatan:</strong> Range nilai tidak boleh bertabrakan dengan predikat lain yang sudah ada.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-refresh" onclick="closeModalPredikat()">Batal</button>
                <button type="submit" class="btn btn-add" id="btnSubmitModalPredikat">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="btnSubmitTextPredikat">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DELETE PREDIKAT -->
<div id="modalDeletePredikat" class="modal-backdrop-delete" onclick="closeModalDeletePredikatOnBackdrop(event)" style="display: none;">
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
                Anda yakin akan menghapus predikat <strong id="deletePredikatName"></strong>?
            </p>
            <div class="delete-actions">
                <button type="button" class="btn-delete-confirm" onclick="confirmDeletePredikat()">HAPUS</button>
                <button type="button" class="btn-delete-cancel" onclick="closeModalDeletePredikat()">BATAL</button>
            </div>
        </div>
    </div>
</div>

<script>
    function selectRowPredikat(tr, id, kode, min, max, predikat) {
        const radio = tr.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        onRadioChangePredikat(radio, id, kode, min, max, predikat);
    }

    function onRadioChangePredikat(radio, id, kode, min, max, predikat) {
        window.selectedPredikatData = { 
            id: id, 
            kode: kode, 
            min: min, 
            max: max, 
            predikat: predikat 
        };

        const btnEdit = document.getElementById('btnEditPredikat');
        const btnDelete = document.getElementById('btnDeletePredikat');

        if (btnEdit) {
            btnEdit.disabled = false;
            btnEdit.removeAttribute('disabled');
        }

        if (btnDelete) {
            btnDelete.disabled = false;
            btnDelete.removeAttribute('disabled');
        }

        const tbody = radio.closest('tbody');
        if (tbody) {
            tbody.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
        }
        
        const row = radio.closest('tr');
        if (row) {
            row.classList.add('selected-row');
        }
    }

    function openModalTambahPredikat() {
        const form = document.getElementById('formPredikat');
        if (form) form.reset();

        const inputId = document.getElementById('predikat_id');
        const inputMethod = document.getElementById('formMethodPredikat'); // PERBAIKAN TYPO
        
        if (inputId) inputId.value = '';
        if (inputMethod) inputMethod.value = 'POST';
        
        if (form) form.action = "{{ route('predikat.store') }}";

        const title = document.getElementById('modalTitlePredikat');
        const btnText = document.getElementById('btnSubmitTextPredikat');
        if (title) title.textContent = 'Tambah Predikat Nilai';
        if (btnText) btnText.textContent = 'Simpan Data';

        // Set Icon Modal ke Plus (+)
        const iconPath = document.getElementById('modalIconPathPredikat');
        const iconBadge = document.getElementById('modalIconBadgePredikat');
        if (iconPath) iconPath.setAttribute('d', 'M12 4v16m8-8H4'); // <-- Langsung pakai string SVG plus
        if (iconBadge) {
            iconBadge.style.background = '';
            iconBadge.style.color = '';
        }

        // Reset Warna Tombol Submit
        const btnSubmit = document.getElementById('btnSubmitModalPredikat');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-amber');
            btnSubmit.classList.add('btn-add');
        }

        // Tampilkan Modal
        const modal = document.getElementById('modalPredikat');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

    // FUNGSI OPEN MODAL EDIT
    function openModalEditPredikat() {
        if (!window.selectedPredikatData || !window.selectedPredikatData.id) {
            alert('Silakan pilih data predikat terlebih dahulu!');
            return;
        }

        const form = document.getElementById('formPredikat');
        
        document.getElementById('predikat_id').value = window.selectedPredikatData.id;
        document.getElementById('kode').value = window.selectedPredikatData.kode || '';
        document.getElementById('nilai_min').value = window.selectedPredikatData.min || '';
        document.getElementById('nilai_max').value = window.selectedPredikatData.max || '';
        document.getElementById('predikat').value = window.selectedPredikatData.predikat || '';

        document.getElementById('formMethodPredikat').value = 'PUT';
        
        if (form) form.action = `{{ url('/master/predikat') }}/${window.selectedPredikatData.id}`;

        document.getElementById('modalTitlePredikat').textContent = 'Ubah Predikat Nilai';
        document.getElementById('btnSubmitTextPredikat').textContent = 'Perbarui Data';

        const btnSubmit = document.getElementById('btnSubmitModalPredikat');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-add');
            btnSubmit.classList.add('btn-amber');
        }

        const iconPath = document.getElementById('modalIconPathPredikat');
        const iconBadge = document.getElementById('modalIconBadgePredikat');
        if (iconPath) iconPath.setAttribute('d', 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'); // <-- Langsung pakai string SVG pencil
        if (iconBadge) {
            iconBadge.style.background = '#FEF3C7';
            iconBadge.style.color = '#D97706';
        }

        const modal = document.getElementById('modalPredikat');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

    // FUNGSI HAPUS DATA
    function deletePredikatRow() {
        if (!window.selectedPredikatData || !window.selectedPredikatData.id) {
            alert('Silakan pilih data predikat terlebih dahulu!');
            return;
        }

        const nameEl = document.getElementById('deletePredikatName');
        if (nameEl) {
            nameEl.textContent = `"${window.selectedPredikatData.predikat}"`; // PERBAIKAN TYPO VARIABEL
        }

        const modal = document.getElementById('modalDeletePredikat');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function confirmDeletePredikat() {
        if (!window.selectedPredikatData || !window.selectedPredikatData.id) return;

        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `{{ url('/master/predikat') }}/${window.selectedPredikatData.id}`;

        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        deleteForm.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }

    // FUNGSI TUTUP MODAL PREDIKAT
    function closeModalPredikat() {
        const modal = document.getElementById('modalPredikat');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
    }

    function closeModalPredikatOnBackdrop(event) {
        if (event.target.id === 'modalPredikat') {
            closeModalPredikat();
        }
    }

    function closeModalDeletePredikat() {
        const modal = document.getElementById('modalDeletePredikat');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function closeModalDeletePredikatOnBackdrop(event) {
        if (event.target.id === 'modalDeletePredikat') {
            closeModalDeletePredikat();
        }
    }

    // FUNGSI TUTUP MODAL
    function closeModalPredikat() {
        const modal = document.getElementById('modalPredikat');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
    }

    function closeModalPredikatOnBackdrop(event) {
        if (event.target.id === 'modalPredikat') {
            closeModalPredikat();
        }
    }
</script>