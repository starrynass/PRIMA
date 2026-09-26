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
</style>

<div class="form-penilaian-wrapper">
    <form id="formVerifikasi" action="{{ route('verifikasi-penilaian.store-form', $penilaian->penilaian_id) }}" method="POST">
        @csrf
        <input type="hidden" name="status_aksi" id="statusAksi" value="koreksi">
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
                <button type="button" id="btnPreview" class="btn-top-action btn-preview-cyan">
                    <i class="fas fa-eye"></i> Preview
                </button>
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
                        <div class="card-header-blue">
                            <div>📋 {{ $catIdx + 1 }}. {{ strtoupper($kategori->nama) }}</div>
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
                                                placeholder="Catatan koreksi verifikator untuk item ini (opsional)...">{{ $existingCatatan[$questionId] ?? '' }}</textarea>
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
                        💬 Catatan Umum Verifikator
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
                    <div class="card-header-blue">📊 Hasil Hasil Verifikasi</div>
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

<!-- SCRIPT LOGIC REALTIME SCORE & ACTIONS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formVerifikasi');
    const statusAksiInput = document.getElementById('statusAksi');
    const predikatList = @json($predikatNilai ?? []);

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
            const sameAsAssessor = selected && selected.value === questionItem.dataset.originalScore;

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

    // Event Handlers Tombol Aksi
    document.getElementById('btnSimpanKoreksi').addEventListener('click', function() {
        statusAksiInput.value = 'koreksi';
        form.submit();
    });

    document.getElementById('btnVerifikasiFinal').addEventListener('click', function() {
        if(confirm('Apakah Anda yakin ingin memverifikasi final penilaian ini?')) {
            statusAksiInput.value = 'verified';
            form.submit();
        }
    });

    document.getElementById('btnKembalikanRevisi').addEventListener('click', function() {
        if(confirm('Apakah Anda yakin ingin mengembalikan penilaian ini ke penilai untuk direvisi?')) {
            statusAksiInput.value = 'revisi';
            form.submit();
        }
    });

    document.getElementById('btnPreview').addEventListener('click', function() {
        alert('Total Nilai Verifikator: ' + document.getElementById('totalNilaiDisplay').textContent + '\nPredikat: ' + document.getElementById('predikatDisplay').textContent);
    });
});
</script>
@endsection