@extends('layout.app')

@section('content')
<style>
    :root {
        --primary-blue: #1E56A0;
        --secondary-blue: #163172;
        --border-color: #E2E8F0;
        --text-muted: #64748B;
        --radius: 8px;
    }

    .form-penilaian-wrapper {
        padding: 1.5rem;
        background: #f8fafc;
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    }

    .header-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0F172A;
    }

    .header-title span { color: var(--primary-blue); }

    .grid-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.25rem;
    }

    .card-native {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .card-header-blue {
        background: linear-gradient(135deg, #1E56A0 0%, #163172 100%);
        color: #ffffff;
        padding: 0.75rem 1rem;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body-native { padding: 1rem; }

    .question-item {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }

    .question-title { font-weight: 600; font-size: 0.875rem; color: #1E293B; }
    .question-desc { font-size: 0.775rem; color: var(--text-muted); margin-bottom: 0.5rem; }

    .scale-group { display: flex; gap: 0.5rem; margin: 0.5rem 0; }
    .scale-item { flex: 1; text-align: center; }
    .scale-item input { display: none; }
    .scale-label {
        display: block;
        padding: 0.4rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.8rem;
        background: #fff;
    }

    .scale-item input:checked + .scale-label {
        background: #1E56A0;
        color: #fff;
        border-color: #1E56A0;
    }

    .native-input-textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.8rem;
        outline: none;
    }

    /* Action Buttons Top Bar */
    .btn-top-action {
        padding: 0.45rem 0.9rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 6px;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }

    .btn-back-gray { background: #E2E8F0; color: #334155; }
    .btn-preview-cyan { background: #E0F2FE; color: #0284C7; border-color: #BAE6FD; }
    .btn-kor-amber { background: #FBBF24; color: #78350F; }
    .btn-ver-green { background: #16A34A; color: #ffffff; }
    .btn-rev-orange {
        background: #fff;
        color: #DC2626;
        border: 1px solid #DC2626;
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
    }

    .badge-status-ver {
        background: #475569;
        color: #fff;
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        font-size: 0.725rem;
        font-weight: 800;
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
                                <div class="question-item">
                                    <div class="question-title">{{ $qIdx + 1 }}. {{ $pertanyaan->pertanyaan }}</div>
                                    <div class="question-desc">{{ $pertanyaan->deskripsi ?? '-' }}</div>

                                    <!-- Choice Radio Options -->
                                    <div class="scale-group">
                                        @foreach($skalaNilai as $skala)
                                            <div class="scale-item">
                                                <input type="radio" 
                                                    class="radio-score" 
                                                    name="jawaban[{{ $pertanyaan->pertanyaan_id }}]" 
                                                    id="q_{{ $pertanyaan->pertanyaan_id }}_{{ $skala->skala_id }}" 
                                                    value="{{ $skala->nilai_angka }}"
                                                    data-kat-id="{{ $kategori->kategori_id }}"
                                                    data-score="{{ $skala->nilai_angka }}"
                                                    {{ (isset($existingJawaban[$pertanyaan->pertanyaan_id]) && $existingJawaban[$pertanyaan->pertanyaan_id] == $skala->nilai_angka) ? 'checked' : '' }}>
                                                
                                                <label class="scale-label" for="q_{{ $pertanyaan->pertanyaan_id }}_{{ $skala->skala_id }}">
                                                    {{ $skala->kode_nilai }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Catatan Verifikator -->
                                    <textarea name="catatan_verifikator[{{ $pertanyaan->pertanyaan_id }}]" 
                                        class="native-input-textarea" 
                                        rows="2" 
                                        placeholder="Catatan koreksi verifikator untuk item ini (opsional)...">{{ $existingCatatan[$pertanyaan->pertanyaan_id] ?? '' }}</textarea>
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
                <div class="card-native" style="padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center;">
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
                    <div class="card-body-native" style="font-size: 0.8125rem;">
                        <div style="font-weight: 700; text-align: center; margin-bottom: 0.75rem;">{{ $penilaian->pgw_nama }}</div>
                        <p><strong>NUP:</strong> {{ $penilaian->pgw_nup ?? '-' }}</p>
                        <p><strong>Jabatan:</strong> {{ $penilaian->pgw_jabatan }}</p>
                        <p><strong>Dept:</strong> {{ $penilaian->pgw_dept_name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Ringkasan Score Verifikator -->
                <div class="card-native">
                    <div class="card-header-blue">📊 Hasil Hasil Verifikasi</div>
                    <div class="card-body-native">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span>Total Nilai Verifikator</span>
                            <strong id="totalNilaiDisplay" style="font-size: 1.25rem; color: var(--primary-blue);">0.00</strong>
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
        radio.addEventListener('change', calculateVerifikatorScore);
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