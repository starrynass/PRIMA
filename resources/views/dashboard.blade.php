@extends('layout.app')

@section('content')

<style>
 /* =========================================================
   1. Container & Banner Header
   ========================================================= */
.dash-banner {
    background: linear-gradient(135deg, #4A0E17 0%, #2A080D 100%);
    border-radius: 0.875rem;
    padding: 1.5rem 2rem;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(42, 8, 13, 0.12);
}

.dash-sub { 
    font-size: 0.7rem; 
    font-weight: 700; 
    opacity: 0.8; 
    letter-spacing: 0.05em; 
    text-transform: uppercase;
}
.dash-title { font-size: 1.5rem; font-weight: 800; margin: 0.25rem 0; }
.dash-desc { font-size: 0.8125rem; opacity: 0.85; margin: 0; }
.dash-actions { display: flex; gap: 0.75rem; align-items: center; }


/* =========================================================
   2. Grid Layout (3 Card Top, 4 Card Bottom)
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


/* =========================================================
   3. Metric Card Styling (Left Accent & Center Content)
   ========================================================= */
.metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 5px solid #cbd5e1; /* Default Left Accent */
    border-radius: 0.875rem;
    padding: 1.5rem 1rem 1.25rem 1rem;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center; /* Tetap Rata Tengah */
    text-align: center;  /* Tetap Rata Tengah */
}

.metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
}

/* Variant Left Border per Status Card */
.metric-card.card-total   { border-left-color: #0284c7; }
.metric-card.card-success { border-left-color: #16a34a; }
.metric-card.card-warning { border-left-color: #d97706; background: #fffcf5; }
.metric-card.card-undo    { border-left-color: #ea580c; }
.metric-card.card-danger  { border-left-color: #dc2626; background: #fff8f8; }
.metric-card.card-purple  { border-left-color: #9333ea; }
.metric-card.card-gray    { border-left-color: #64748b; }


/* =========================================================
   4. Icon & Typography Inside Card
   ========================================================= */
.card-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%; /* Lingkaran Bulat Halus */
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    font-size: 1.2rem;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.6);
}

.metric-value {
    font-size: 2.25rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -0.03em;
}

.metric-label {
    font-size: 0.875rem;
    font-weight: 700;
    color: #334155;
    margin: 0.5rem 0 0.35rem 0;
}

.metric-sub {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    margin-top: auto;
}


/* =========================================================
   5. Colors & Badges
   ========================================================= */
.bg-blue-light       { background: #e0f2fe; color: #0284c7; }
.bg-green-light      { background: #dcfce7; color: #16a34a; }
.bg-amber-light      { background: #fef3c7; color: #d97706; }
.bg-orange-light     { background: #ffedd5; color: #ea580c; }
.bg-red-light        { background: #fee2e2; color: #dc2626; }
.bg-purple-light     { background: #f3e8ff; color: #9333ea; }
.bg-gray-light       { background: #f1f5f9; color: #64748b; }

.badge-pill {
    padding: 0.15rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.6875rem;
    font-weight: 700;
}
.bg-green-soft  { background: #d1fae5; color: #065f46; }
.bg-red-soft    { background: #ffe4e6; color: #991b1b; }
.bg-orange-soft { background: #ffedd5; color: #9a3412; }


/* =========================================================
   6. Custom Buttons & Modal Popup
   ========================================================= */
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

.btn-secondary-custom:hover {
    background-color: #f8fafc;
    color: #1e293b;
    border-color: #94a3b8;
}

.btn-secondary-custom:active {
    background-color: #f1f5f9;
    transform: translateY(1px);
}

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
    display: flex !important;
}

.assessment-modal-panel {
    width: min(920px, 100%);
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    border-radius: 0.75rem;
    background: #ffffff;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.assessment-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.assessment-modal-body {
    padding: 1.5rem;
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
    background-color: #f1f5f9;
    padding: 0.75rem 1rem;
    font-weight: 700;
    color: #334155;
    border-bottom: 1px solid #cbd5e1;
}

.assessment-detail-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e2e8f0;
}


/* =========================================================
   7. Responsive Design
   ========================================================= */
@media (max-width: 992px) {
    .dash-cards-row-top,
    .dash-cards-row-bottom {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .dash-cards-row-top,
    .dash-cards-row-bottom {
        grid-template-columns: 1fr;
    }
}
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
                    <option value="{{ $p->periode_id }}" {{ $selectedPeriodeId == $p->periode_id ? 'selected' : '' }}>
                        {{ $p->nama_periode ?? $p->periode_id }}
                    </option>
                @endforeach
            </select>
            <button class="btn-print"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>

        <!-- Baris Atas (3 Card) -->
    <div class="dash-cards-row-top">
        <!-- Total Pegawai -->
        <div class="metric-card" onclick="openDetailModal('total')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-blue-light">
                <i class="fas fa-users text-blue"></i>
            </div>
            <div class="metric-value">{{ $totalPegawai }}</div>
            <div class="metric-label">Total Pegawai</div>
            <div class="metric-sub"><span class="badge-pill bg-green-soft text-green">Aktif</span> Periode Berjalan</div>
        </div>

        <!-- Sudah Terverifikasi -->
        <div class="metric-card" onclick="openDetailModal('terverifikasi')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-green-light">
                <i class="fas fa-check text-green"></i>
            </div>
            <div class="metric-value">{{ $sudahTerverifikasi }}</div>
            <div class="metric-label">Sudah Terverifikasi</div>
            <div class="metric-sub"><span class="text-green fw-bold">{{ $totalPegawai > 0 ? round(($sudahTerverifikasi/$totalPegawai)*100) : 0 }}%</span> Dari Total Pegawai</div>
        </div>

        <!-- Menunggu Verifikasi (Card Krem/Kuning) -->
        <div class="metric-card card-warning" onclick="openDetailModal('menunggu')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-amber-light">
                <i class="fas fa-clock text-amber"></i>
            </div>
            <div class="metric-value">{{ $menungguVerifikasi }}</div>
            <div class="metric-label">Menunggu Verifikasi</div>
            <div class="metric-sub"><span class="text-amber fw-bold">Perlu Aksi</span> Oleh Atasan</div>
        </div>
    </div>

    <!-- Baris Bawah (4 Card) -->
    <div class="dash-cards-row-bottom">
        <!-- Dikembalikan -->
        <div class="metric-card" onclick="openDetailModal('dikembalikan')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-orange-light">
                <i class="fas fa-undo text-orange"></i>
            </div>
            <div class="metric-value">{{ $dikembalikan }}</div>
            <div class="metric-label">Dikembalikan</div>
            <div class="metric-sub"><span class="badge-pill bg-red-soft text-red">Revisi</span> Menunggu Perbaikan Penilai</div>
        </div>

        <!-- At Risk / Lewat Batas (Card Pink/Merah) -->
        <div class="metric-card card-danger" onclick="openDetailModal('at_risk')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-red-light">
                <i class="fas fa-exclamation-triangle text-red"></i>
            </div>
            <div class="metric-value">{{ $atRisk }}</div>
            <div class="metric-label">At Risk / Lewat Batas</div>
            <div class="metric-sub"><span class="text-red fw-bold">Prioritas</span> Tindak Lanjut SDM</div>
        </div>

        <!-- Penilaian Dikoreksi -->
        <div class="metric-card" onclick="openDetailModal('dikoreksi')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-purple-light">
                <i class="fas fa-edit text-purple"></i>
            </div>
            <div class="metric-value">{{ $dikoreksi }}</div>
            <div class="metric-label">Penilaian Dikoreksi</div>
            <div class="metric-sub"><span class="badge-pill bg-orange-soft text-orange">Tercatat</span> Dalam Audit Trail</div>
        </div>

        <!-- Belum Dinilai -->
        <div class="metric-card" onclick="openDetailModal('belum_dinilai')">
            <div class="card-circle-bg"></div>
            <div class="card-icon bg-blue-dark-light">
                <i class="fas fa-clock text-blue-dark"></i>
            </div>
            <div class="metric-value">{{ $belumDinilai }}</div>
            <div class="metric-label">Belum Dinilai</div>
            <div class="metric-sub"><span class="text-amber fw-bold">Follow Up</span> Penilai Terkait</div>
        </div>
    </div>
</div>

<!-- Pop-up Modal Detail -->
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
                            <th style="width: 50px;">#</th>
                            <th>PEGAWAI</th>
                            <th>SATKER</th>
                            <th>JABATAN</th>
                            <th>STATUS</th>
                            <th>NILAI</th>
                            <th>PREDIKAT</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody">
                        <!-- Data dikirim via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="assessment-modal-footer">
            <button type="button" class="btn-secondary-custom" onclick="closeDetailModal()">Tutup</button>
        </footer>
    </section>
</div>

<script>
   function openDetailModal(type) {
    const periodeSelect = document.getElementById('periodeSelect');
    const periodeId = periodeSelect ? periodeSelect.value : '';
    const modal = document.getElementById('dashDetailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubTitle = document.getElementById('modalSubTitle');
    const tableBody = document.getElementById('modalTableBody');
    
    // Buka Modal & Loading
    modal.classList.add('is-open');
    modalTitle.innerText = 'Memuat Data...';
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Mengambil data...</td></tr>';

    fetch(`{{ route('dashboard.modal-detail') }}?type=${type}&periode_id=${periodeId}`)
        .then(response => response.json())
        .then(response => {
            modalTitle.innerText = response.title;
            modalSubTitle.innerText = `${response.total} data ditemukan.`;

            let rows = '';
            if (!response.data || response.data.length === 0) {
                rows = '<tr><td colspan="7" class="text-center py-4">Tidak ada data ditemukan.</td></tr>';
            } else {
                response.data.forEach((item, index) => {
                    let nameParts = item.nama ? item.nama.split(' ') : ['-'];
                    let initials = nameParts[0].charAt(0);
                    if (nameParts.length > 1) initials += nameParts[1].charAt(0);

                    let statusBadge = item.status_nilai 
                        ? `<span class="badge-status status-verified">${item.status_nilai}</span>`
                        : `<span class="badge-status status-pending">Belum Dinilai</span>`;

                    let predikat = item.predikat 
                        ? `<span class="badge-predikat">${item.predikat}</span>` 
                        : `-`;

                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle">${initials.toUpperCase()}</div>
                                    <div>
                                        <div class="fw-bold text-dark">${item.nama}</div>
                                        <div class="text-muted small">${item.nup ?? '-'}</div>
                                    </div>
                                </div>
                            </td>
                            <td>${item.satker ?? '-'}</td>
                            <td>${item.jabatan ?? '-'}</td>
                            <td>${statusBadge}</td>
                            <td>${item.total_nilai ? parseFloat(item.total_nilai).toFixed(2) : '-'}</td>
                            <td>${predikat}</td>
                        </tr>
                    `;
                });
            }
            tableBody.innerHTML = rows;
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">Gagal memuat data. Silakan coba lagi.</td></tr>';
        });
}

function closeDetailModal() {
    document.getElementById('dashDetailModal').classList.remove('is-open');
}

document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('periodeSelect');
    if(select) {
        select.addEventListener('change', function() {
            window.location.href = "{{ route('dashboard.penilaian') }}?periode_id=" + this.value;
        });
    }
});
</script>
@endsection