@extends('layout.app')

@section('content')
<style>
    :root {
        --primary-burgundy: #80243B;
        --primary-burgundy-hover: #661c2f;
        --bg-light: #F4F6F9;
        --border-color: #E2E8F0;
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --radius: 6px;
    }

    .form-penilaian-wrapper {
        padding: 20px;
        background: var(--bg-light);
        min-height: 100vh;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        color: var(--text-dark);
    }

    .header-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 20px;
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .header-title span { color: var(--primary-burgundy); }

    .grid-container {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 20px;
    }

    .card-native {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        margin-bottom: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-header-blue {
        background: var(--primary-burgundy);
        color: #FFFFFF;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 14px;
        align-items: center;
    }

    .card-header-bobot {
        background: var(--primary-burgundy);
        color: #FFFFFF;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body-native { padding: 16px; }

    .question-item {
        display: grid;
        grid-template-columns: 32px minmax(180px, 1fr) minmax(220px, 0.9fr) minmax(300px, 1.25fr);
        align-items: start;
        gap: 12px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 16px;
        margin-bottom: 16px;
    }

    .question-number {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--primary-burgundy);
        color: #FFFFFF;
        font-size: 12px;
        font-weight: 700;
    }

    .question-title { font-weight: 600; font-size: 13px; color: var(--text-dark); margin-bottom: 4px; }
    .question-desc { font-size: 12px; color: var(--text-muted); }

    .weight-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 8px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: #F1F5F9;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
    }

    .scale-group { display: flex; gap: 6px; margin: 8px 0 12px; }
    .scale-item { flex: 1; text-align: center; }
    .scale-item { position: relative; }
    .scale-item input[type="radio"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .scale-label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        font-weight: 700;
        cursor: pointer;
        font-size: 12px;
        background: #FFFFFF;
        transition: all 0.2s ease;
    }

    .scale-item input:checked + .scale-label {
        background: var(--primary-burgundy);
        color: #FFFFFF;
        border-color: var(--primary-burgundy);
    }

    .score-section-title {
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .assessor-score {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 30px;
        color: var(--text-dark);
        font-size: 13px;
    }

    .score-code {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        padding: 0 6px;
        border-radius: 6px;
        background: #16A34A;
        color: #FFFFFF;
        font-weight: 800;
    }

    .assessor-note {
        margin-top: 8px;
        padding: 9px 11px;
        border: 1px solid #DBEAFE;
        border-radius: 8px;
        background: #F3F7FD;
        color: #475569;
        font-size: 12px;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .assessor-note strong {
        display: block;
        margin-bottom: 3px;
        color: #334155;
        font-size: 11px;
    }

    .assessor-note-empty { color: #94A3B8; font-style: italic; }

    .verification-section { min-width: 0; }

    .verification-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .btn-correct-score {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 6px 10px;
        border: 1px solid #CBD5E1;
        border-radius: 7px;
        background: #FFFFFF;
        color: var(--text-dark);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-correct-score:hover,
    .question-item.is-correcting .btn-correct-score {
        border-color: var(--primary-burgundy);
        color: var(--primary-burgundy);
    }

    .verification-state {
        display: flex;
        align-items: center;
        gap: 7px;
        min-height: 42px;
        padding: 9px 11px;
        border: 1px solid #BBE7C7;
        border-radius: 8px;
        background: #EAF7ED;
        color: #15803D;
        font-size: 12px;
        line-height: 1.4;
    }

    .verification-state.is-corrected {
        border-color: #F5D28A;
        background: #FFFBEB;
        color: #92400E;
    }

    .verification-help {
        margin: 7px 0 0;
        color: var(--text-muted);
        font-size: 11px;
    }

    .verification-controls {
        display: none;
        margin-top: 10px;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: #FFFFFF;
    }

    .question-item.is-correcting .verification-controls { display: block; }

    .verification-controls .native-input-textarea { margin-top: 4px; }

    .native-input-textarea {
        width: 100%;
        min-height: 40px;
        padding: 6px 8px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        font-family: inherit;
        font-size: 12px;
        outline: none;
        resize: vertical;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .native-input-textarea:focus {
        border-color: var(--primary-burgundy);
        box-shadow: 0 0 0 3px rgba(128, 36, 59, 0.12);
    }

    /* Action Buttons Top Bar */
    .btn-top-action {
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        cursor: pointer;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .btn-back-gray { background: #E2E8F0; color: #334155; }
    .btn-preview-cyan { background: #E0F2FE; color: #0284C7; border-color: #BAE6FD; }
    .btn-kor-amber { background: #FBBF24; color: #78350F; }
    .btn-ver-green { background: #16A34A; color: #ffffff; }
    .btn-rev-orange {
        background: #FFFFFF;
        color: #DC2626;
        border: 1px solid #DC2626;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 700;
        border-radius: var(--radius);
        cursor: pointer;
    }

    .badge-status-ver {
        background: #F1F5F9;
        color: #334155;
        border: 1px solid var(--border-color);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 800;
    }

    .verification-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1rem;
    }

    .employee-name {
        margin-bottom: 16px;
        text-align: center;
        font-size: 15px;
        font-weight: 700;
    }

    .employee-info p {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        margin: 0 0 10px;
        color: var(--text-muted);
    }

    .employee-info p strong { color: var(--text-dark); }

    @media (max-width: 1200px) {
        .question-item {
            grid-template-columns: 32px minmax(0, 1fr);
        }

        .question-item > .assessor-score-section,
        .question-item > .verification-section {
            grid-column: 2;
        }

        .question-item > .weight-badge { justify-self: start; }
    }

    @media (max-width: 576px) {
        .verification-section-header { align-items: flex-start; flex-direction: column; }
        .btn-correct-score { width: 100%; }
        .scale-group { flex-wrap: wrap; }
    }

    @media (max-width: 992px) {
        .grid-container { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .form-penilaian-wrapper { padding: 1rem; }

        .header-nav {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-nav > div:last-child {
            width: 100%;
            flex-wrap: wrap;
        }

        .header-nav > div:last-child .btn-top-action { flex: 1 1 auto; }

        .verification-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .verification-footer > div:last-child,
        .verification-footer .btn-rev-orange { width: 100%; }
    }

    /* Modal Pop-up Revisi & Notifikasi Form Verifikasi */
    .form-modal-backdrop {
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

    .form-modal-dialog {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 100%;
        max-width: 520px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }

    .form-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #E2E8F0;
        background: #ffffff;
    }

    .form-modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .form-modal-close {
        background: none;
        border: none;
        font-size: 1.35rem;
        line-height: 1;
        color: #94A3B8;
        cursor: pointer;
        padding: 0.2rem 0.4rem;
    }

    .form-modal-close:hover {
        color: #334155;
    }

    .form-modal-body {
        padding: 1.25rem;
        font-size: 0.875rem;
        color: #334155;
    }

    .form-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.6rem;
        padding: 0.85rem 1.25rem;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
    }

    .btn-modal-gray {
        background: #64748B;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1.1rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-modal-gray:hover { background: #475569; }

    .btn-modal-orange {
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
    }

    .btn-modal-orange:hover { background: #D97706; }

    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="form-penilaian-wrapper">
    @if(session('success'))
        <div style="background-color: #ECFDF5; border: 1px solid #10B981; color: #065F46; padding: 0.85rem 1.25rem; border-radius: var(--radius); margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem;">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <i class="fas fa-check-circle" style="color: #10B981; font-size: 1.1rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065F46; cursor: pointer; font-size: 1rem;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #FFF1F2; border: 1px solid #F43F5E; color: #9F1239; padding: 0.85rem 1.25rem; border-radius: var(--radius); margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem;">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <i class="fas fa-exclamation-triangle" style="color: #F43F5E; font-size: 1.1rem;"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #9F1239; cursor: pointer; font-size: 1rem;">&times;</button>
        </div>
    @endif

    <form id="formVerifikasi" action="{{ route('verifikasi-penilaian.store-form', $penilaian->penilaian_id) }}" method="POST">
        @csrf
        <input type="hidden" name="status_aksi" id="statusAksi" value="koreksi">
        <input type="hidden" name="alasan_revisi" id="alasanRevisiInput" value="">
        <input type="hidden" name="grand_total" id="inputGrandTotal" value="0">
        <input type="hidden" name="predikat_nama" id="inputPredikatNama" value="-">

        <!-- HEADER TOP ACTION BAR -->
        <div class="header-nav">
            <div class="header-title">
                Verifikasi Penilaian » <span>{{ $penilaian->pgw_nama }}</span>
            </div>
            <!-- BARIS TOMBOL ATAS[cite: 13] -->
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('verifikasi-penilaian.index', ['periode_id' => $penilaian->periode_id]) }}" class="btn-top-action btn-back-gray">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
               <a href="{{ route('verifikasi.preview', $penilaian->penilaian_id) }}"
                            class="btn-top-action btn-preview-cyan">
                            <i class="fas fa-eye me-1"></i> Preview 
                        </a>
                <button type="button" id="btnSimpanKoreksi" class="btn-top-action btn-kor-amber">
                    <i class="fas fa-save"></i> Simpan Koreksi
                </button>
                <button type="button" id="btnVerifikasiFinal" class="btn-top-action btn-ver-green">
                    <i class="fas fa-check-circle"></i> Verifikasi
                </button>
            </div>
        </div>

        <div class="grid-container">
            <!-- LEFT COLUMN: PENILAIAN & KOREKSI -->
            <div>
                @foreach($template->kategoris as $catIdx => $kategori)
                    <div class="card-native card-panel" data-kat-id="{{ $kategori->kategori_id }}" data-kat-bobot="{{ $kategori->bobot_persen }}">
                        <div class="card-header-bobot">
                            <div><i class="fas fa-clipboard-list me-1"></i> {{ $catIdx + 1 }}. {{ strtoupper($kategori->nama) }}</div>
                            <div style="font-size: 11px; opacity: 0.9;">Bobot {{ $kategori->bobot_persen }}%</div>
                        </div>

                        <div class="card-body-native">
                            @foreach($kategori->pertanyaans as $qIdx => $pertanyaan)
                                @php
                                    $questionId = $pertanyaan->pertanyaan_id;
                                    $hasCorrection = !is_null($existingJawaban[$questionId] ?? null);
                                    $selectedScore = $hasCorrection
                                        ? $existingJawaban[$questionId]
                                        : ($nilaiPenilai[$questionId] ?? null);
                                @endphp
                                <div class="question-item" data-original-score="{{ $nilaiPenilai[$questionId] ?? '' }}">
                                    <div class="question-number">{{ $qIdx + 1 }}</div>
                                    <div>
                                        <div class="question-title">{{ $pertanyaan->pertanyaan }}</div>
                                        <div class="question-desc">{{ $pertanyaan->deskripsi ?? '-' }}</div>
                                        <div class="weight-badge">Bobot {{ number_format($pertanyaan->bobot_persen, 2) }}%</div>
                                    </div>

                                    <div class="assessor-score-section">
                                        <div class="score-section-title">Nilai dari Penilai</div>
                                        <div class="assessor-score">
                                            <span class="score-code">{{ $kodeNilaiPenilai[$questionId] ?? '-' }}</span>
                                            <span>{{ $namaNilaiPenilai[$questionId] ?? 'Belum ada nilai' }} ({{ $nilaiPenilai[$questionId] ?? '-' }})</span>
                                        </div>
                                        <div class="assessor-note">
                                            <strong>Catatan Penilai</strong>
                                            @if(filled($catatanPenilai[$questionId] ?? null))
                                                {{ $catatanPenilai[$questionId] }}
                                            @else
                                                <span class="assessor-note-empty">Tidak ada catatan.</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="verification-section {{ $hasCorrection ? 'is-correcting' : '' }}">
                                        <div class="verification-section-header">
                                            <div class="score-section-title" style="margin: 0;">Verifikasi</div>
                                            <button type="button" class="btn-correct-score js-correct-score" aria-expanded="{{ $hasCorrection ? 'true' : 'false' }}">
                                                <i class="fas fa-pen"></i> Koreksi Nilai
                                            </button>
                                        </div>
                                        <div class="verification-state {{ $hasCorrection ? 'is-corrected' : '' }}" aria-live="polite">
                                            <i class="fas {{ $hasCorrection ? 'fa-pen' : 'fa-check-circle' }}"></i>
                                            {{ $hasCorrection ? 'Nilai koreksi tersimpan.' : 'Mengikuti nilai penilai.' }}
                                        </div>
                                        <p class="verification-help">Jika tidak dikoreksi, nilai akan mengikuti penilai.</p>
                                        <div class="verification-controls">
                                            <div class="score-section-title">Pilih nilai verifikator</div>
                                            <div class="scale-group">
                                                @foreach($skalaNilai as $skala)
                                                    <div class="scale-item">
                                                        <input type="radio"
                                                            class="radio-score"
                                                            name="jawaban[{{ $questionId }}]"
                                                            id="q_{{ $questionId }}_{{ $skala->skala_id }}"
                                                            value="{{ $skala->nilai_angka }}"
                                                            data-kat-id="{{ $kategori->kategori_id }}"
                                                            data-score="{{ $skala->nilai_angka }}"
                                                            @checked($selectedScore !== null && $selectedScore == $skala->nilai_angka)
                                                            @disabled(!$hasCorrection)>

                                                        <label class="scale-label" for="q_{{ $questionId }}_{{ $skala->skala_id }}" title="{{ $skala->nama_nilai }} ({{ $skala->nilai_angka }})">
                                                            {{ $skala->kode_nilai }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <textarea name="catatan_verifikator[{{ $questionId }}]"
                                                class="native-input-textarea"
                                                rows="2"
                                                placeholder="Catatan / alasan koreksi verifikator untuk item ini (wajib diisi jika nilai diubah)...">{{ $existingCatatan[$questionId] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- CATATAN UMUM VERIFIKATOR (SESUAI GAMBAR)[cite: 12] -->
                <div class="card-native">
                    <div class="card-header-blue">
                        <i class="fas fa-comment-alt"></i> Catatan Umum Verifikator
                    </div>
                    <div class="card-body-native">
                        <textarea name="catatan_umum_verifikator" class="native-input-textarea" rows="3" placeholder="Masukkan catatan umum verifikator (opsional)...">{{ $penilaian->catatan_verifikator ?? '' }}</textarea>
                    </div>
                </div>

                <!-- FOOTER STATUS & REVISI BUTTON (SESUAI GAMBAR)[cite: 12] -->
                <div class="card-native verification-footer">
                    <div>
                        Status Verifikasi: <span class="badge-status-ver">{{ strtoupper($penilaian->status_verifikator ?? 'BELUM VERIFIKASI') }}</span>
                    </div>
                    <div>
                        <button type="button" id="btnKembalikanRevisi" class="btn-rev-orange">
                            <i class="fas fa-undo"></i> Kembalikan Revisi ke Penilai
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div>
                <!-- Info Pegawai -->
                <div class="card-native">
                    <div class="card-header-blue"><i class="fas fa-user me-1"></i> Info Pegawai</div>
                    <div class="card-body-native employee-info" style="font-size: 0.8125rem;">
                        <div class="employee-name">{{ $penilaian->pgw_nama }}</div>
                        <p><strong>NUP:</strong><span>{{ $penilaian->pgw_nup ?? '-' }}</span></p>
                        <p><strong>Jabatan:</strong><span>{{ $penilaian->pgw_jabatan }}</span></p>
                        <p><strong>Dept:</strong><span>{{ $penilaian->pgw_dept_name ?? '-' }}</span></p>
                    </div>
                </div>

                <!-- Ringkasan Score Verifikator -->
                <div class="card-native">
                    <div class="card-header-blue"><i class="fas fa-chart-bar"></i> Hasil Hasil Verifikasi</div>
                    <div class="card-body-native">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span>Total Nilai Verifikator</span>
                            <strong id="totalNilaiDisplay" style="font-size: 1.25rem; color: var(--primary-burgundy);">0.00</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Predikat</span>
                            <strong id="predikatDisplay">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Pop-up Revisi Penilaian -->
<div id="modalRevisiPopUp" class="form-modal-backdrop">
    <div class="form-modal-dialog">
        <div class="form-modal-header">
            <h3 class="form-modal-title">
                <i class="fas fa-undo" style="color: #F59E0B;"></i>
                Kembalikan Revisi ke Penilai
            </h3>
            <button type="button" class="form-modal-close" id="btnCloseRevisiModal">&times;</button>
        </div>
        <div class="form-modal-body">
            <p style="margin-bottom: 0.75rem; line-height: 1.45; font-size: 0.85rem; color: #475569;">
                Penilaian pegawai <strong>{{ $penilaian->pgw_nama }}</strong> akan dikembalikan statusnya ke penilai untuk diperbaiki / direvisi. <strong>Alasan revisi wajib diisi.</strong>
            </p>
            <div style="margin-bottom: 0.5rem;">
                <label for="inputModalAlasanRevisi" style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1E293B; margin-bottom: 0.35rem;">
                    Alasan Revisi <span style="color: #EF4444;">*</span>
                </label>
                <textarea id="inputModalAlasanRevisi" class="native-input-textarea" rows="4" placeholder="Tuliskan catatan/alasan detail mengapa penilaian ini perlu direvisi oleh penilai..." required></textarea>
                <div id="errorModalAlasanRevisi" style="color: #EF4444; font-size: 0.75rem; margin-top: 0.35rem; display: none;">
                    Alasan revisi wajib diisi sebelum mengembalikan penilaian!
                </div>
            </div>
        </div>
        <div class="form-modal-footer">
            <button type="button" class="btn-modal-gray" id="btnBatalRevisiModal">
                <i class="fas fa-times me-1"></i> Batal
            </button>
            <button type="button" class="btn-modal-orange" id="btnKonfirmasiKirimRevisi">
                <i class="fas fa-paper-plane me-1"></i> Kirim Revisi
            </button>
        </div>
    </div>
</div>

<!-- Modal Pop-up Peringatan Validasi Alasan Koreksi -->
<div id="modalAlertPopUp" class="form-modal-backdrop">
    <div class="form-modal-dialog">
        <div class="form-modal-header">
            <h3 class="form-modal-title" style="color: #EF4444;">
                <i class="fas fa-exclamation-triangle"></i>
                Peringatan Validasi
            </h3>
            <button type="button" class="form-modal-close" id="btnCloseAlertModal">&times;</button>
        </div>
        <div class="form-modal-body">
            <div id="modalAlertMessage" style="font-size: 0.875rem; line-height: 1.5; color: #334155;"></div>
        </div>
        <div class="form-modal-footer">
            <button type="button" class="btn-modal-gray" id="btnOkAlertModal">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT LOGIC REALTIME SCORE & ACTIONS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formVerifikasi');
    const statusAksiInput = document.getElementById('statusAksi');
    const alasanRevisiInput = document.getElementById('alasanRevisiInput');
    const predikatList = @json($predikatNilai ?? []);

    const modalRevisi = document.getElementById('modalRevisiPopUp');
    const inputAlasanRevisi = document.getElementById('inputModalAlasanRevisi');
    const errorAlasanRevisi = document.getElementById('errorModalAlasanRevisi');

    const modalAlert = document.getElementById('modalAlertPopUp');
    const modalAlertMsg = document.getElementById('modalAlertMessage');

    function showAlertPopUp(message) {
        modalAlertMsg.innerHTML = message;
        modalAlert.style.display = 'flex';
    }

    function closeAlertPopUp() {
        modalAlert.style.display = 'none';
    }

    document.getElementById('btnCloseAlertModal').addEventListener('click', closeAlertPopUp);
    document.getElementById('btnOkAlertModal').addEventListener('click', closeAlertPopUp);
    modalAlert.addEventListener('click', function(e) {
        if (e.target === this) closeAlertPopUp();
    });

    function calculateVerifikatorScore() {
        const questionItems = document.querySelectorAll('.question-item');
        const categoryData = {};

        document.querySelectorAll('.card-panel[data-kat-id]').forEach(el => {
            const katId = el.getAttribute('data-kat-id');
            const katBobot = parseFloat(el.getAttribute('data-kat-bobot')) || 0;
            if (katId) {
                categoryData[katId] = { totalScore: 0, filledCount: 0, bobot: katBobot };
            }
        });

        questionItems.forEach(item => {
            const checkedRadio = item.querySelector('.radio-score:checked');
            if (checkedRadio) {
                const score = parseFloat(checkedRadio.getAttribute('data-score')) || 0;
                const katId = checkedRadio.getAttribute('data-kat-id');
                if (katId && categoryData[katId]) {
                    categoryData[katId].totalScore += score;
                    categoryData[katId].filledCount += 1;
                }
            }
        });

        let grandTotal = 0;
        Object.keys(categoryData).forEach(katId => {
            const kat = categoryData[katId];
            if (kat.filledCount > 0) {
                let avgScore = kat.totalScore / kat.filledCount;
                grandTotal += avgScore * (kat.bobot / 100);
            }
        });

        const grandTotalFormatted = grandTotal.toFixed(2);
        document.getElementById('totalNilaiDisplay').textContent = grandTotalFormatted;
        document.getElementById('inputGrandTotal').value = grandTotalFormatted;

        let matchedPredikat = '-';
        if (Array.isArray(predikatList) && predikatList.length > 0) {
            const found = predikatList.find(p => grandTotal >= parseFloat(p.nilai_min) && grandTotal <= parseFloat(p.nilai_max));
            if (found && found.predikat) matchedPredikat = found.predikat;
        }

        document.getElementById('predikatDisplay').textContent = matchedPredikat;
        document.getElementById('inputPredikatNama').value = matchedPredikat;
    }

    document.querySelectorAll('.radio-score').forEach(radio => {
        radio.addEventListener('change', function () {
            const questionItem = radio.closest('.question-item');
            const state = questionItem.querySelector('.verification-state');
            const selected = questionItem.querySelector('.radio-score:checked');
            const sameAsAssessor = selected && String(selected.value) === String(questionItem.dataset.originalScore);

            state.classList.toggle('is-corrected', !sameAsAssessor);
            state.innerHTML = sameAsAssessor
                ? '<i class="fas fa-check-circle"></i> Mengikuti nilai penilai.'
                : '<i class="fas fa-pen"></i> Nilai dikoreksi.';
            calculateVerifikatorScore();
        });
    });

    document.querySelectorAll('.js-correct-score').forEach(button => {
        button.addEventListener('click', function () {
            const questionItem = button.closest('.question-item');
            const isOpen = questionItem.classList.toggle('is-correcting');
            button.setAttribute('aria-expanded', String(isOpen));

            if (isOpen) {
                questionItem.querySelectorAll('.radio-score').forEach(radio => {
                    radio.disabled = false;
                });
            }
        });
    });

    calculateVerifikatorScore();

    // Fungsi validasi: Setiap butir yang nilainya diubah wajib memiliki alasan / catatan koreksi
    function validateCorrections() {
        const questionItems = document.querySelectorAll('.question-item');
        let unnotedCount = 0;
        let firstUnnotedInput = null;

        questionItems.forEach(item => {
            const checked = item.querySelector('.radio-score:checked');
            const origScore = item.dataset.originalScore;
            if (checked && origScore !== '' && String(checked.value) !== String(origScore)) {
                const noteInput = item.querySelector('textarea[name^="catatan_verifikator"]');
                const noteVal = noteInput ? noteInput.value.trim() : '';
                if (noteVal === '') {
                    unnotedCount++;
                    if (!firstUnnotedInput && noteInput) {
                        firstUnnotedInput = noteInput;
                    }
                }
            }
        });

        if (unnotedCount > 0) {
            showAlertPopUp(`Terdapat <strong>${unnotedCount} butir penilaian</strong> yang Anda koreksi nilainya namun belum diberikan alasan koreksi.<br><br>Mohon isi catatan koreksi pada setiap butir yang nilainya diubah.`);
            if (firstUnnotedInput) {
                firstUnnotedInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstUnnotedInput.focus();
                firstUnnotedInput.style.borderColor = '#EF4444';
            }
            return false;
        }

        return true;
    }

    // Event Handlers Tombol Aksi
    document.getElementById('btnSimpanKoreksi').addEventListener('click', function() {
        if (!validateCorrections()) return;
        statusAksiInput.value = 'koreksi';
        form.submit();
    });

    document.getElementById('btnVerifikasiFinal').addEventListener('click', function() {
        if (!validateCorrections()) return;
        if(confirm('Apakah Anda yakin ingin memverifikasi final penilaian ini?')) {
            statusAksiInput.value = 'verified';
            form.submit();
        }
    });

    // Pop-up Revisi Modal Handlers
    function openRevisiModal() {
        inputAlasanRevisi.value = '';
        errorAlasanRevisi.style.display = 'none';
        modalRevisi.style.display = 'flex';
        inputAlasanRevisi.focus();
    }

    function closeRevisiModal() {
        modalRevisi.style.display = 'none';
    }

    document.getElementById('btnKembalikanRevisi').addEventListener('click', function() {
        openRevisiModal();
    });

    document.getElementById('btnCloseRevisiModal').addEventListener('click', closeRevisiModal);
    document.getElementById('btnBatalRevisiModal').addEventListener('click', closeRevisiModal);
    modalRevisi.addEventListener('click', function(e) {
        if (e.target === this) closeRevisiModal();
    });

    document.getElementById('btnKonfirmasiKirimRevisi').addEventListener('click', function() {
        const alasan = inputAlasanRevisi.value.trim();
        if (alasan === '') {
            errorAlasanRevisi.style.display = 'block';
            inputAlasanRevisi.focus();
            return;
        }

        errorAlasanRevisi.style.display = 'none';
        alasanRevisiInput.value = alasan;
        statusAksiInput.value = 'revisi';
        form.submit();
    });

    document.getElementById('btnPreview').addEventListener('click', function() {
        alert('Total Nilai Verifikator: ' + document.getElementById('totalNilaiDisplay').textContent + '\nPredikat: ' + document.getElementById('predikatDisplay').textContent);
    });
});
</script>
@endsection