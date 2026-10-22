@extends('layout.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet" />
@endpush

@section('content')

@php
    $managePenilaianUrl = route('kelola-penilaian.index');
    $formPenilaianUrl = route('penilaian.form', $penilaian->penilaian_id);
    $referrer = request()->headers->get('referer');
    $referrerParts = $referrer ? parse_url($referrer) : [];
    $isSameHost = isset($referrerParts['host'])
        && strtolower($referrerParts['host']) === strtolower(request()->getHost());
    $referrerPath = $referrerParts['path'] ?? null;
    $referrerQuery = [];
    parse_str($referrerParts['query'] ?? '', $referrerQuery);

    $backUrl = $managePenilaianUrl;
    if ($isSameHost && $referrerPath === parse_url($formPenilaianUrl, PHP_URL_PATH)) {
        $backUrl = $formPenilaianUrl;
    } elseif ($isSameHost && $referrerPath === parse_url($managePenilaianUrl, PHP_URL_PATH)) {
        $periodeId = $referrerQuery['periode_id'] ?? null;
        $backUrl = is_string($periodeId) && $periodeId !== ''
            ? route('kelola-penilaian.index', ['periode_id' => $periodeId])
            : $managePenilaianUrl;
    }
@endphp

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
        --border-color: #CBD5E1;

        /* Badge Grade Colors */
        --badge-emerald-bg: #D1FAE5; --badge-emerald-text: #065F46; --badge-emerald-border: #10B981;
        --badge-blue-bg: #DBEAFE; --badge-blue-text: #1E40AF; --badge-blue-border: #3B82F6;
        --badge-amber-bg: #FEF3C7; --badge-amber-text: #92400E; --badge-amber-border: #F59E0B;
        --badge-orange-bg: #FFEDD5; --badge-orange-text: #9A3412; --badge-orange-border: #F97316;
        --badge-rose-bg: #FFE4E6; --badge-rose-text: #9F1239; --badge-rose-border: #F43F5E;

        --radius-lg: 0.75rem;
        --radius-md: 0.5rem;
        --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
    }

    body {
        font-family: var(--font-main);
        background-color: var(--bg-page);
        color: var(--text-primary);
    }

    .penilaian-preview-page {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0.5rem 0 2rem;
        font-family: var(--font-main);
        color: var(--text-primary);
    }

    .penilaian-preview-page *,
    .penilaian-preview-page *::before,
    .penilaian-preview-page *::after {
        box-sizing: border-box;
    }

    .preview-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin: 0 auto 1.25rem;
    }

    .preview-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.55rem;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .preview-breadcrumb-label {
        color: var(--maroon-primary);
        font-weight: 600;
    }

    .preview-breadcrumb-separator {
        color: #CBD5E1;
        font-size: 0.7rem;
    }

    .preview-breadcrumb-current {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .preview-action-buttons {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .preview-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 38px;
        padding: 0.55rem 0.9rem;
        border: 1px solid transparent;
        border-radius: 0.5rem;
        font: 600 0.8125rem var(--font-main);
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }

    .preview-action-btn:hover {
        transform: translateY(-1px);
    }

    /* Container Dokumen Preview ala Kertas/Card Besar */
    .preview-container {
        position: relative;
        background: var(--surface);
        border-radius: 1rem;
        box-shadow: 0 16px 38px -16px rgba(15, 23, 42, 0.22), var(--shadow-card);
        border: 1px solid #E2E8F0;
        max-width: 1000px;
        margin: 1.5rem auto;
        padding: 2.25rem;
        overflow: hidden;
    }

    .preview-container::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 5px;
        background: var(--maroon-gradient);
    }

    .brand-title {
        color: var(--maroon-primary);
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .document-heading {
        padding: 0.5rem 0 1.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .document-heading h4 {
        margin: 0 0 0.45rem;
        font-size: 1.2rem;
        line-height: 1.4;
    }

    .document-heading p {
        margin: 0 0 0.8rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .document-status {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.7rem;
        border: 1px solid var(--maroon-border);
        border-radius: 999px;
        background: var(--maroon-soft);
        color: var(--maroon-primary);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    /* Header Tabel Aspek / Kategori */
    .table-section-header {
        background: var(--maroon-gradient);
        color: #ffffff;
        padding: 0.8rem 1rem;
        font-weight: 600;
        font-size: 0.88rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        margin-top: 1.5rem;
    }

    /* Styling Tabel Dokumen */
    .table-preview {
        width: 100%;
        border-collapse: collapse;
        border-color: var(--border-color);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        min-width: 720px;
    }

    .table-preview th {
        background-color: #f1f5f9;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.04em;
        vertical-align: middle;
        text-align: center;
        padding: 0.75rem 0.65rem;
        border: 1px solid var(--border-color);
    }

    .table-preview td {
        vertical-align: middle;
        color: var(--text-primary);
        padding: 0.75rem 0.65rem;
        border: 1px solid var(--border-color);
        line-height: 1.5;
    }

    .penilaian-preview-page .text-center {
        text-align: center !important;
    }

    .penilaian-preview-page .text-end {
        text-align: right !important;
    }

    .penilaian-preview-page .font-monospace {
        font-family: var(--font-code);
        font-size: 0.82em;
    }

    .penilaian-preview-page .fw-bold {
        font-weight: 700;
    }

    .penilaian-preview-page .text-secondary,
    .penilaian-preview-page .text-muted {
        color: var(--text-secondary);
    }

    .penilaian-preview-page .pe-3 {
        padding-right: 1rem;
    }

    .table-preview tbody tr:nth-child(even):not(.subtotal-row) {
        background: #FAFBFC;
    }

    .table-preview tbody tr:hover:not(.subtotal-row) {
        background: #F8FAFC;
    }

    .employee-info-table {
        min-width: 680px;
    }

    .employee-info-table th {
        width: 15%;
        text-align: left;
        white-space: nowrap;
    }

    .employee-info-table td {
        width: 35%;
    }

    .employee-info-table td:nth-child(2) {
        font-weight: 600;
    }

    .question-title {
        margin-bottom: 0.2rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .question-description {
        color: var(--text-secondary);
        font-size: 0.78rem;
    }

    .subtotal-row td {
        background-color: var(--maroon-soft);
        color: var(--maroon-primary);
        font-weight: 700;
    }

    .score-total {
        color: #047857 !important;
        font-size: 1rem;
    }

    .score-predicate {
        color: var(--maroon-primary);
        font-weight: 700;
        text-transform: uppercase;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E2E8F0;
        text-align: center;
    }

    .signature-label {
        margin: 0 0 3.5rem;
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .signature-name {
        display: inline-block;
        min-width: 65%;
        padding-top: 0.5rem;
        border-top: 1px solid #94A3B8;
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 700;
    }

    .preview-table-wrap {
        border: 1px solid var(--border-color);
        border-top: 0;
        border-radius: 0 0 var(--radius-md) var(--radius-md);
        overflow-x: auto;
        margin-bottom: 1.25rem;
    }

    .preview-table-wrap .table-preview {
        margin-bottom: 0;
    }

    .preview-table-wrap + .table-section-header {
        margin-top: 1.75rem;
    }

    .employee-info-table th {
        background: #F8FAFC;
        text-transform: none;
        letter-spacing: 0;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    /* Badge Nilai Huruf Kotak Elegan */
    .badge-grade-box {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: var(--font-code);
        font-size: 0.85rem;
        border-radius: 6px;
        border: 1px solid transparent;
    }

    .grade-a { background-color: var(--badge-emerald-bg); color: var(--badge-emerald-text); border-color: var(--badge-emerald-border); }
    .grade-b { background-color: var(--badge-blue-bg); color: var(--badge-blue-text); border-color: var(--badge-blue-border); }
    .grade-c { background-color: var(--badge-amber-bg); color: var(--badge-amber-text); border-color: var(--badge-amber-border); }
    .grade-d { background-color: var(--badge-orange-bg); color: var(--badge-orange-text); border-color: var(--badge-orange-border); }
    .grade-e { background-color: var(--badge-rose-bg); color: var(--badge-rose-text); border-color: var(--badge-rose-border); }
    .grade-none { background-color: #F1F5F9; color: var(--text-muted); border-color: var(--border-color); }

    .btn-maroon {
        background: var(--maroon-primary);
        color: #ffffff;
        border: none;
    }
    .btn-maroon:hover {
        background: var(--maroon-hover);
        color: #ffffff;
    }
    .btn-maroon-soft {
        background: var(--maroon-soft);
        color: var(--maroon-primary);
        border: 1px solid var(--maroon-border);
    }
    .btn-maroon-soft:hover {
        background: var(--maroon-border);
        color: var(--maroon-hover);
    }

    @media (max-width: 768px) {
        .penilaian-preview-page {
            padding: 0;
        }

        .preview-container {
            padding: 1.25rem 1rem;
            margin: 0.75rem auto;
            border-radius: 0.75rem;
        }

        .preview-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .preview-action-buttons {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .preview-breadcrumb {
            font-size: 0.8125rem;
        }

        .document-heading h4 {
            font-size: 1.05rem;
        }

        .table-section-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.25rem;
        }

        .signature-grid {
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        .signature-label {
            margin-bottom: 2rem;
        }
    }

    @media print {
        .no-print { display: none !important; }
        .preview-container { box-shadow: none; border: none; padding: 0; margin: 0; max-width: 100%; overflow: visible; }
        .preview-container::before { display: none; }
        .preview-table-wrap { border: 0; overflow: visible; }
        .table-preview { min-width: 0; }
        body { background-color: #ffffff; }
        .table-preview th,
        .table-preview td {
            padding: 0.45rem;
        }
    }
</style>

<div class="penilaian-preview-page">
    <div class="preview-actions no-print">
        <nav class="preview-breadcrumb" aria-label="Breadcrumb">
            <span class="preview-breadcrumb-label">Kelola Penilaian</span>
            <i class="fa-solid fa-chevron-right preview-breadcrumb-separator" aria-hidden="true"></i>
            <span class="preview-breadcrumb-current" aria-current="page">Preview Penilaian</span>
        </nav>
        <div class="preview-action-buttons">
            <a href="{{ $backUrl }}" class="preview-action-btn btn-maroon-soft">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali
            </a>
            <button onclick="window.print()" class="preview-action-btn btn-maroon-soft" type="button">
                <i class="fa-solid fa-print" aria-hidden="true"></i> Preview Cetak
            </button>
            <a href="{{ route('penilaian.cetak-pdf', $penilaian->penilaian_id) }}" target="_blank" class="preview-action-btn btn-maroon">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- Container Utama Preview Dokumen -->
    <div class="preview-container">
        
        <!-- Judul Dokumen -->
        <div class="text-center mb-4 document-heading">
            <h4 class="brand-title">FORM HASIL PENILAIAN KINERJA PEGAWAI</h4>
            <p>Periode {{ $penilaian->kode ?? optional($penilaian->periode)->nama_periode }}</p>
            <span class="document-status">
                {{ $penilaian->status_nilai ?? 'SUBMIT' }}
            </span>
        </div>

        <!-- Tabel Informasi Pegawai -->
        <div class="table-responsive preview-table-wrap">
        <table class="table table-bordered table-sm table-preview employee-info-table mb-0">
            <tbody>
                <tr>
                    <th>Pegawai</th>
                    <td>{{ $penilaian->pgw_nama ?? '-' }}</td>
                    <th>NUP</th>
                    <td class="font-monospace">{{ $penilaian->pgw_nup ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $penilaian->pgw_jabatan ?? '-' }}</td>
                    <th>Departemen</th>
                    <td>{{ $penilaian->pgw_dept_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Penempatan</th>
                    <td>{{ $penilaian->pgw_off_name ?? '-' }}</td>
                    <th>Template</th>
                    <td>{{ optional($template)->nama_template ?? $penilaian->template_nama ?? 'Template Standar' }}</td>
                </tr>
                <tr>
                    <th>Penilai</th>
                    <td>{{ optional($penilaian->penilai)->nama ?? $penilaian->penilai_manual ?? '-' }}</td>
                    <th>Verifikator</th>
                    <td>{{ optional($penilaian->verifikator)->nama ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        </div>

        <!-- LOOPING TABEL KATEGORI & PERTANYAAN -->
        @foreach($kategoriList as $kat)
            <!-- Header Kategori Warna Maroon -->
            <div class="table-section-header">
                <span>{{ strtoupper($kat->nama_kategori) }}</span>
                <span class="small font-monospace opacity-90">Nilai Kategori: {{ number_format($kat->subtotal, 2) }} (Bobot {{ $kat->bobot_persen }}%)</span>
            </div>

            <div class="table-responsive preview-table-wrap">
            <table class="table table-bordered table-sm table-preview">
                <thead>
                    <tr>
                        <th style="width: 6%;">No</th>
                        <th style="width: 38%; text-align: left;" class="ps-3">Pertanyaan</th>
                        <th style="width: 10%;">Bobot</th>
                        <th style="width: 10%;">Nilai</th>
                        <th style="width: 21%; text-align: left;" class="ps-3">Catatan</th>
                        <th style="width: 15%;">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kat->pertanyaans as $idx => $item)
                        @php
                            $kodeHuruf = strtoupper($item->kode_nilai ?? '');
                            $classGrade = match($kodeHuruf) {
                                'A' => 'grade-a',
                                'B' => 'grade-b',
                                'C' => 'grade-c',
                                'D' => 'grade-d',
                                'E' => 'grade-e',
                                default => 'grade-none'
                            };
                        @endphp
                        <tr>
                            <td class="text-center font-monospace">{{ $loop->iteration }}</td>
                            <td>
                                <div class="question-title">{{ $item->pertanyaan }}</div>
                                @if(!empty($item->deskripsi))
                                    <div class="question-description">{{ $item->deskripsi }}</div>
                                @endif
                            </td>
                            <td class="text-center font-monospace">{{ $item->bobot_pertanyaan_persen }}%</td>
                            <td class="text-center">
                                <span class="badge-grade-box {{ $classGrade }}">{{ $kodeHuruf !== '' ? $kodeHuruf : '-' }}</span>
                            </td>
                            <td>{{ $item->catatan ?? '-' }}</td>
                            <td class="text-center font-monospace fw-bold">{{ number_format($item->nilai_akhir, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada pertanyaan dalam kategori ini.</td>
                        </tr>
                    @endforelse
                    
                    <!-- Subtotal Bar per Kategori -->
                    <tr class="subtotal-row">
                        <td colspan="5" class="text-end pe-3">Total {{ strtoupper($kat->nama_kategori) }} (Bobot {{ $kat->bobot_persen }}%)</td>
                        <td class="text-center font-monospace">{{ number_format($kat->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        @endforeach

        <!-- TABEL REKAPITULASI NILAI AKHIR -->
        <div class="table-section-header mt-4">
            <span>REKAPITULASI NILAI KINERJA</span>
        </div>
        <div class="table-responsive preview-table-wrap">
        <table class="table table-bordered table-sm table-preview mb-0">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 52%; text-align: left;" class="ps-3">Kategori Aspek</th>
                    <th style="width: 20%;">Bobot</th>
                    <th style="width: 20%;">Nilai Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategoriList as $kat)
                    <tr>
                        <td class="text-center font-monospace">{{ $loop->iteration }}</td>
                        <td class="ps-3">{{ strtoupper($kat->nama_kategori) }}</td>
                        <td class="text-center font-monospace">{{ $kat->bobot_persen }}%</td>
                        <td class="text-center font-monospace fw-bold">{{ number_format($kat->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal-row">
                    <td colspan="2" class="text-end pe-3">Total Nilai</td>
                    <td class="text-center font-monospace">100%</td>
                    <td class="text-center font-monospace score-total">
                        {{ number_format($totalNilaiPreview, 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end pe-3 fw-bold">Predikat</td>
                    <td colspan="2" class="text-center score-predicate">
                        {{ $penilaian->predikat_verifikator ?? $penilaian->predikat ?? 'Belum Dinilai' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end pe-3 fw-bold">Catatan Penilai / Verifikator</td>
                    <td colspan="2" class="ps-3 text-secondary">{{ $penilaian->catatan_verifikator ?? $penilaian->catatan ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        </div>

        <!-- AREA TANDA TANGAN -->
        <div class="signature-grid">
            <div>
                <p class="signature-label">Pegawai Dinilai</p>
                <div class="signature-name">{{ $penilaian->pgw_nama ?? '-' }}</div>
            </div>
            <div>
                <p class="signature-label">Penilai</p>
                <div class="signature-name">{{ optional($penilaian->penilai)->nama ?? $penilaian->penilai_manual ?? '-' }}</div>
            </div>
            <div>
                <p class="signature-label">Verifikator</p>
                <div class="signature-name">{{ optional($penilaian->verifikator)->nama ?? '-' }}</div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush