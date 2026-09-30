@extends('layout.app')

@section('content')

<style>
    :root {
        --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        --maroon-primary: #7A1C38;
        --maroon-hover: #5C1329;
        --maroon-soft: #FBF0F3;
        --maroon-glow: rgba(122, 28, 56, 0.18);
        --report-text: #0F172A;
        --report-muted: #64748B;
        --report-border: #CBD5E1;
    }

    .custom-label-sm {
    width: 105px !important;
    min-width: 105px !important;
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 600 !important;
    font-size: 0.825rem !important;
    border: 1px solid #cbd5e1 !important;
    border-right: none !important;
    border-top-left-radius: 6px !important;
    border-bottom-left-radius: 6px !important;
    justify-content: flex-start !important;
}

    .annual-report {
        min-height: 100vh;
        padding: 1.5rem;
        background: #ffffff;
        color: var(--report-text);
        font-family: var(--font-main);
        -webkit-font-smoothing: antialiased;
    }

    .annual-report .breadcrumb-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 1.5rem;
        color: var(--maroon-primary);
        font-size: 1.125rem;
        font-weight: 700;
    }

    .annual-report .breadcrumb-title span {
        color: var(--report-muted);
        font-weight: 500;
    }

    .annual-report-layout {
        width: min(860px, 100%);
        margin: 0 auto;
        padding: 1rem;
        border: 1px solid #DCE3EA;
        border-radius: 0.5rem;
        background: #F1F5F9;
    }

    .annual-report-card {
        overflow: hidden;
        border: 1px solid var(--report-border);
        border-radius: 0.375rem;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    }

    .annual-report-heading {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        min-height: 48px;
        padding: 0.75rem 1rem;
        background: var(--maroon-primary);
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .annual-report-body {
        padding: 1.25rem;
    }

    .annual-report-field {
        display: grid;
        grid-template-columns: 108px minmax(0, 1fr);
        min-width: 0;
        margin-bottom: 0.75rem;
    }

    .annual-report-label {
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--report-border);
        border-right: 0;
        border-radius: 0.3rem 0 0 0.3rem;
        background: #F8FAFC;
        color: #334155;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .annual-report-control {
        width: 100%;
        min-width: 0;
        min-height: 40px;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--report-border);
        border-radius: 0 0.3rem 0.3rem 0;
        background-color: #ffffff;
        color: var(--report-text);
        font-family: var(--font-main);
        font-size: 0.8125rem;
    }

    .annual-report-control:focus {
        position: relative;
        z-index: 1;
        border-color: var(--maroon-primary);
        outline: 0;
        box-shadow: 0 0 0 3px var(--maroon-glow);
    }

    .annual-report-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1.25rem;
        padding-top: 0.75rem;
        border-top: 1px solid #E2E8F0;
    }

    .annual-report-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 38px;
        padding: 0.45rem 0.85rem;
        border: 1px solid var(--maroon-primary);
        border-radius: 0.3rem;
        background: var(--maroon-primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.8125rem;
        font-weight: 600;
        white-space: nowrap;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .annual-report-button:hover {
        border-color: var(--maroon-hover);
        background: var(--maroon-hover);
        color: #ffffff;
    }

    @media (max-width: 576px) {
        .annual-report { padding: 1rem; }
        .annual-report-layout { padding: 0.65rem; }
        .annual-report-body { padding: 1rem; }
        .annual-report-field { grid-template-columns: 88px minmax(0, 1fr); }
        .annual-report-label,
        .annual-report-control { padding-right: 0.5rem; padding-left: 0.5rem; }
        .annual-report-actions { flex-direction: column; }
        .annual-report-button { width: 100%; }
    }

    .d-none {
        display: none !important;
    }

</style>

<div class="annual-report">

    <div class="breadcrumb-title">
        Laporan Penilaian <span>» Tahunan</span>
    </div>

    <div class="annual-report-layout">
        <section class="annual-report-card" aria-labelledby="annualReportTitle">
            <div class="annual-report-heading" id="annualReportTitle">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
                <span>Form Laporan Penilaian Tahunan</span>
            </div>

            <div class="annual-report-body">
                <form action="{{ route('laporan.tahunan.export') }}" method="GET" target="_blank">

                    <div class="annual-report-field">
                        <label class="annual-report-label" for="jenis_filter">Jenis</label>
                        <select id="jenis_filter" name="jenis_filter" class="annual-report-control">
                            <option value="per_tahun" selected>Per Tahun</option>
                            <option value="per_periode">Per Periode</option>
                        </select>
                    </div>

                    <!-- 2. Section Tahun (DEFAULT TAMPIL) -->
                    <div id="wrapper_per_tahun" class="annual-report-field">
                        <label class="annual-report-label" for="tahun">Tahun</label>
                        <input type="number" id="tahun" name="tahun" value="{{ date('Y') }}" class="annual-report-control">
                    </div>

                    <!-- 3. Section Periode (DEFAULT SEMBUNYI dengan class 'd-none') -->
                    <div id="wrapper_per_periode" class="d-none">
                        <!-- Dropdown Periode Awal -->
                        <div class="annual-report-field">
                            <label class="annual-report-label" for="periode_awal">Periode Awal</label>
                            <select id="periode_awal" name="periode_awal" class="annual-report-control">
                                <option value="">-- Pilih Periode Awal --</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->periode_id }}">
                                        {{ $p->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Periode Akhir -->
                        <div class="annual-report-field">
                            <label class="annual-report-label" for="periode_akhir">Periode Akhir</label>
                            <select id="periode_akhir" name="periode_akhir" class="annual-report-control">
                                <option value="">-- Pilih Periode Akhir --</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->periode_id }}">
                                        {{ $p->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- 4. Jabatan -->
                    <div class="annual-report-field">
                        <label class="annual-report-label" for="jabatan">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="annual-report-control">
                            <option value="SEMUA JABATAN">SEMUA JABATAN</option>
                            @foreach($occupations ?? [] as $occ)
                                <option value="{{ $occ->occ_id }}">{{ $occ->occ_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. Jenis Laporan -->
                    <div class="annual-report-field">
                        <label class="annual-report-label" for="jenis_laporan">Jenis Laporan</label>
                        <select id="jenis_laporan" name="jenis_laporan" class="annual-report-control">
                            <option value="Tahunan">Tahunan</option>
                        </select>
                    </div>

                    <div class="annual-report-actions">
                        <button type="submit" name="action" value="preview" class="annual-report-button">
                            <i class="fas fa-eye" aria-hidden="true"></i> Preview <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="submit" name="action" value="excel" class="annual-report-button">
                            <i class="fas fa-file-excel" aria-hidden="true"></i> Export to Excel <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="submit" name="action" value="pdf" class="annual-report-button">
                            <i class="fas fa-file-pdf" aria-hidden="true"></i> Export to PDF <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisFilter = document.getElementById('jenis_filter');
        const wrapperTahun = document.getElementById('wrapper_per_tahun');
        const wrapperPeriode = document.getElementById('wrapper_per_periode');

        function toggleFilter() {
            if (jenisFilter.value === 'per_periode') {
                wrapperTahun.classList.add('d-none');
                wrapperPeriode.classList.remove('d-none');
            } else {
                wrapperTahun.classList.remove('d-none');
                wrapperPeriode.classList.add('d-none');
            }
        }

        // Jalankan saat pertama kali dipanggil (untuk memastikan kondisi awal)
        toggleFilter();

        // Jalankan saat dropdown jenis diubah
        jenisFilter.addEventListener('change', toggleFilter);
    });
</script>

@endsection