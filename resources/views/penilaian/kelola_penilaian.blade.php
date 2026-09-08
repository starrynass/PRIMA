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
    grid-template-columns: repeat(5, minmax(0, 1fr));
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

    padding: 1rem;
    margin-bottom: 1rem;
}

.filter-grid {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
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
    padding: 0.525rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: opacity 0.15s ease-in-out;
}

.btn-maroon-custom:hover {
    opacity: 0.92;
    color: #ffffff;
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

.table-custom th, 
.table-custom td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
}

.table-custom tbody tr {
    border-bottom: 1px solid var(--border-color);
    transition: all 0.15s ease-in-out;
}

.table-custom tbody tr:hover { 
    background-color: var(--maroon-soft) !important; 
}

.table-custom tbody tr.selected-row { 
    background-color: #F1F5F9 !important; 
}

/* Badge Status Custom */
.badge-status-gray {
    background-color: #F1F5F9;
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.btn-outline-maroon {
    color: var(--maroon-primary);
    border-color: var(--maroon-border);
    background-color: var(--maroon-soft);
}

.btn-outline-maroon:hover {
    color: #ffffff;
    background-color: var(--maroon-primary);
    border-color: var(--maroon-primary);
}
</style>

<div class="main-wrapper">
    <!-- BREADCRUMB HEADER -->
    <div class="header-bar d-flex justify-content-between align-items-center mb-3">
        <div class="breadcrumb-title">
            Kelola Penilaian <span>» Penilaian Kinerja Pegawai</span>
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
                    <form method="GET" action="{{ route('kelola-penilaian.index') }}" id="formSelectPeriode">
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
                    <h1 class="toolbar-title">Kelola Penilaian</h1>
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
                            <div class="stat-label">Total Pegawai</div>
                            <div class="stat-value text-primary-custom">{{ $totalPegawai }}</div>
                        </div>
                        <div class="stat-icon bg-primary-light">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Sudah Diajukan</div>
                            <div class="stat-value text-success">{{ $sudahDiajukan }}</div>
                        </div>
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Dikembalikan</div>
                            <div class="stat-value text-danger">{{ $dikembalikan }}</div>
                        </div>
                        <div class="stat-icon bg-danger-light">
                            <i class="fas fa-undo"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Masih Draft</div>
                            <div class="stat-value text-warning">{{ $masihDraft }}</div>
                        </div>
                        <div class="stat-icon bg-warning-light">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                </div>
                <div class="card-stat">
                    <div class="stat-body">
                        <div>
                            <div class="stat-label">Belum Diisi</div>
                            <div class="stat-value text-muted">{{ $belumDiisi }}</div>
                        </div>
                        <div class="stat-icon bg-secondary-light">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL DAFTAR PEGAWAI & FILTER -->
            <div class="card-box">
                <div class="card-header-daftarpegawai">
                    <div class="card-title-daftarpegawai">
                        <i class="fas fa-list me-2"></i> Daftar Pegawai Penilaian
                    </div>
                </div>
                <div class="p-3">
                    
                    <!-- FORM FILTER SEJAJAR KE SAMPING -->
                    <div class="filter-card">
                        <form method="GET" action="{{ route('kelola-penilaian.index') }}" class="filter-grid">
                            <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">
                            
                            <div class="filter-item-search">
                                <input type="text" name="search" class="form-control-custom" placeholder="Cari nama/NUP..." value="{{ request('search') }}">
                            </div>
                            
                            <div class="filter-item">
                                <select name="penempatan" class="form-select-custom">
                                    <option value="">- Semua Penempatan -</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <select name="departemen" class="form-select-custom">
                                    <option value="">- Semua Departemen -</option>
                                </select>
                            </div>
                            
                            <div class="filter-item">
                                <select name="status" class="form-select-custom">
                                    <option value="">- Semua Status -</option>
                                </select>
                            </div>
                            
                            <div class="filter-item-btn">
                                <button type="submit" class="btn-maroon-custom">
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
        <div class="table-container">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                            <tr>
                                <th width="40">NO</th>
                                <th>NUP</th>
                                <th>NAMA</th>
                                <th>PENEMPATAN</th>
                                <th>JABATAN</th>
                                <th>DEPARTEMEN</th>
                                <th>PENILAI</th>
                                <th>STATUS</th>
                                <th>NILAI</th>
                                <th>PREDIKAT</th>
                                <th width="90" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penilaians as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $row->pgw_nup ?? '-' }}</code></td>
                                    <td><strong>{{ $row->pgw_nama ?? '-' }}</strong></td>
                                    <td>{{ $row->pgw_off_name ?? '-' }}</td>
                                    <td>{{ $row->pgw_jabatan ?? '-' }}</td>
                                    <td>{{ $row->pgw_dept_name ?? '-' }}</td>
                                    <td>
                                        @if(empty($row->penilai_id) && empty($row->penilai_manual))
                                            <span class="text-danger fw-bold">NULL</span>
                                        @else
                                            {{ $row->penilai_id ?? 'Penilai Manual' }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $status = strtoupper($row->status_nilai ?? 'BELUM DIISI');
                                        @endphp

                                        @if($status == 'DIAJUKAN')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($status == 'DIKEMBALIKAN')
                                            <span class="badge bg-danger">Dikembalikan</span>
                                        @elseif($status == 'DRAFT')
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @else
                                            <span class="badge badge-status-gray">Belum Diisi</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($row->total_nilai ?? 0, 2) }}</td>
                                    <td>
                                        @if($row->predikat)
                                            <span class="badge bg-info text-dark">{{ $row->predikat }}</span>
                                        @else
                                            <span class="badge bg-danger">Mengecewakan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-maroon" title="Detail Penilaian">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">Belum ada data penilaian pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    });
</script>

@endsection