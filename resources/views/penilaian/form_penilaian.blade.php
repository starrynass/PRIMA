@extends('layout.app')

@section('content')
<style>
    /* ==========================================================================
       VARIABLES & BASE STYLES
       ========================================================================== */
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
        background-color: var(--bg-light);
        padding: 20px;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        color: var(--text-dark);
    }

    /* ==========================================================================
       HEADER & NAVIGATION
       ========================================================================== */
    .header-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .header-title span {
        color: var(--primary-burgundy);
    }

    .btn-native {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius);
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .btn-back {
        background-color: #DC2626;
        color: #FFFFFF;
    }
    .btn-back:hover { background-color: #B91C1C; }

    .btn-draft {
        background-color: #F59E0B;
        color: #000000;
    }
    .btn-draft:hover { background-color: #D97706; }

    .btn-preview {
        background-color: #0D9488;
        color: #FFFFFF;
    }
    .btn-preview:hover { background-color: #0F766E; }

    .btn-submit {
        background-color: var(--primary-burgundy);
        color: #FFFFFF;
    }
    .btn-submit:hover { background-color: var(--primary-burgundy-hover); }

    /* ==========================================================================
       LAYOUT GRID
       ========================================================================== */
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
    }

    @media (max-width: 992px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }

    /* ==========================================================================
       CARD COMPONENTS
       ========================================================================== */
    .card-native {
        background-color: #FFFFFF;
        border-radius: var(--radius);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .card-header-burgundy {
        background-color: var(--primary-burgundy);
        color: #FFFFFF;
        padding: 12px 16px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-body-native {
        padding: 16px;
    }

    /* Status Badge */
    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .badge-draft { background-color: #FEF3C7; color: #92400E; }
    .badge-submitted { background-color: #D1FAE5; color: #065F46; }

    /* ==========================================================================
       QUESTION LIST & RADIO SCALES
       ========================================================================== */
    .legend-box {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: var(--text-muted);
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
        margin-top: 10px;
    }

    .question-item {
        display: grid;
        grid-template-columns: 32px 1fr 60px 180px 200px;
        gap: 12px;
        align-items: start;
        padding-bottom: 16px;
        margin-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
    }

    @media (max-width: 1200px) {
        .question-item {
            grid-template-columns: 32px 1fr;
        }
    }

    .question-number {
        width: 28px;
        height: 28px;
        background-color: var(--primary-burgundy);
        color: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
    }

    .question-title {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .question-desc {
        font-size: 12px;
        color: var(--text-muted);
    }

    .weight-badge {
        background-color: #F1F5F9;
        border: 1px solid var(--border-color);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
    }

    /* Radio Custom Scale */
    .scale-group {
        display: flex;
        gap: 4px;
    }

    .scale-item {
        position: relative;
    }

    .scale-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .scale-label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        background-color: #FFFFFF;
        transition: all 0.2s;
    }

    .scale-item input[type="radio"]:checked + .scale-label {
        background-color: var(--primary-burgundy);
        border-color: var(--primary-burgundy);
        color: #FFFFFF;
    }

    .native-input-textarea {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 6px 8px;
        font-size: 12px;
        resize: vertical;
        font-family: inherit;
        box-sizing: border-box;
    }

    /* ==========================================================================
       SIDEBAR & PROGRESS
       ========================================================================== */
    .profile-avatar-circle {
        width: 50px;
        height: 50px;
        background-color: #E2E8F0;
        color: var(--text-muted);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        margin: 0 auto 8px auto;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        margin-bottom: 8px;
    }
    .info-label { color: var(--text-muted); }
    .info-value { font-weight: 600; text-align: right; }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background-color: #E2E8F0;
        border-radius: 4px;
        overflow: hidden;
        margin: 8px 0 16px 0;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: #10B981;
        width: 0%;
        transition: width 0.3s ease;
    }

    .native-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .native-table th, .native-table td {
        padding: 6px 4px;
        border-bottom: 1px solid var(--border-color);
    }
    .native-table th { text-align: left; color: var(--text-muted); font-weight: 600; }

    /* ==========================================================================
       MODAL NATIVE
       ========================================================================== */
    .modal-backdrop-native {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .modal-content-native {
        background: #FFFFFF;
        width: 100%;
        max-width: 450px;
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="form-penilaian-wrapper">
    
    <!-- Top Header -->
    <div class="header-nav">
        <div class="header-title">
            Form Penilaian » <span>{{ $penilaian->pgw_nama }}</span>
        </div>
        <a href="{{ route('kelola-penilaian.index', ['periode_id' => $penilaian->periode_id]) }}" class="btn-native btn-back">
            &larr; Kembali
        </a>
    </div>

    @if(!$template)
        <div class="card-native" style="padding: 30px; text-align: center;">
            <h3 style="color: #DC2626; margin-bottom: 8px;">Belum Ada Template Untuk Jabatan Ini</h3>
            <p style="color: var(--text-muted); font-size: 13px;">Silakan buat atau atur template penilaian untuk jabatan <strong>{{ $penilaian->pgw_jabatan }}</strong> terlebih dahulu pada Master Template.</p>
        </div>
    @else

    <form id="formPenilaian" action="{{ route('penilaian.store', $penilaian->penilaian_id) }}" method="POST">
        @csrf
        <input type="hidden" name="status_aksi" id="statusAksi" value="draft">
        <input type="hidden" name="grand_total" id="inputGrandTotal" value="0">
        <input type="hidden" name="predikat_nama" id="inputPredikatNama" value="-">

        <div class="grid-container">
            
            <!-- LEFT COLUMN: ASSESSMENT FORM -->
            <div>
                <!-- Form Header Card -->
                <div class="card-native">
                    <div class="card-body-native">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-weight: 700; font-size: 14px;">📝 Isi Penilaian</div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-muted);">Status: </span>
                                <span class="status-badge {{ strtolower($penilaian->status_nilai) == 'submitted' ? 'badge-submitted' : 'badge-draft' }}">
                                    {{ strtoupper($penilaian->status_nilai ?? 'BELUM DIISI') }}
                                </span>
                            </div>
                        </div>

                        <!-- Legend Quick Access -->
                        <div class="legend-box">
                            @foreach($skalaNilai as $skala)
                                <div>
                                    <span style="color: #10B981; font-weight: bold;">•</span>
                                    <strong>{{ $skala->kode_nilai }}</strong> — {{ $skala->nama_nilai }} ({{ (int)$skala->nilai_angka }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CATEGORIES AND QUESTIONS -->
               @foreach($template->kategoris as $catIdx => $kategori)
                <div class="card-native card-panel" data-kat-id="{{ $kategori->kategori_id }}" data-kat-bobot="{{ $kategori->bobot_persen }}">
                    <div class="card-header-burgundy">
                        <div>📋 {{ $catIdx + 1 }}. {{ strtoupper($kategori->nama) }}</div>
                        <div style="font-size: 12px; opacity: 0.9;">Bobot {{ $kategori->bobot_persen }}%</div>
                    </div>
                    
                    <div class="card-body-native">
                        @foreach($kategori->pertanyaans as $qIdx => $pertanyaan)
                            <div class="question-item">
                                <!-- Number -->
                                <div class="question-number">{{ $qIdx + 1 }}</div>
                                
                                <!-- Text & Desc -->
                                <div>
                                    <div class="question-title">{{ $pertanyaan->pertanyaan }}</div>
                                    <div class="question-desc">{{ $pertanyaan->deskripsi ?? '-' }}</div>
                                </div>

                                <!-- Weight -->
                                <div class="weight-badge">
                                    {{ number_format($pertanyaan->bobot_persen, 2) }}%
                                </div>

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
                                                data-weight="{{ $pertanyaan->bobot_persen }}"
                                                {{ (isset($existingJawaban[$pertanyaan->pertanyaan_id]) && $existingJawaban[$pertanyaan->pertanyaan_id] == $skala->nilai_angka) ? 'checked' : '' }}
                                                required>
                                            
                                            <label class="scale-label" for="q_{{ $pertanyaan->pertanyaan_id }}_{{ $skala->skala_id }}">
                                                {{ $skala->kode_nilai }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Evidensi / Catatan -->
                                <div>
                                    <textarea name="catatan[{{ $pertanyaan->pertanyaan_id }}]" 
                                            class="native-input-textarea" 
                                            rows="2" 
                                            placeholder="Tulis catatan atau evidensi (opsional)...">{{ $existingCatatan[$pertanyaan->pertanyaan_id] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

                <!-- Action Footer Buttons -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; margin-bottom: 40px;">
                    <div style="display: flex; gap: 8px;">
                        <button type="button" id="btnDraft" class="btn-native btn-draft">
                            💾 Simpan Draft
                        </button>
                        <button type="button" id="btnPreview" class="btn-native btn-preview">
                            👁️ Preview Hasil
                        </button>
                    </div>
                    <div>
                        <button type="button" id="btnAjukan" class="btn-native btn-submit">
                            🚀 Ajukan Penilaian
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div>
                <!-- 1. Ringkasan Pegawai -->
                <div class="card-native">
                    <div class="card-header-burgundy">👤 Ringkasan Pegawai</div>
                    <div class="card-body-native">
                        <div class="profile-avatar-circle">
                            {{ strtoupper(substr($penilaian->pgw_nama, 0, 1)) }}
                        </div>
                        <div style="text-align: center; font-weight: 700; margin-bottom: 16px;">{{ $penilaian->pgw_nama }}</div>

                        <div class="info-row">
                            <span class="info-label">NUP</span>
                            <span class="info-value">{{ $penilaian->pgw_nup ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Jabatan</span>
                            <span class="info-value" style="max-width: 60%;">{{ $penilaian->pgw_jabatan }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Penempatan</span>
                            <span class="info-value">{{ $penilaian->pgw_off_name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Dept</span>
                            <span class="info-value">{{ $penilaian->pgw_dept_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Progress Pengisian -->
                <div class="card-native">
                    <div class="card-header-burgundy">📊 Progress Pengisian</div>
                    <div class="card-body-native">
                        <div style="display: flex; justify-content: space-between; font-size: 12px;">
                            <span class="info-label">Pertanyaan terisi</span>
                            <span id="progressCount" style="font-weight: 700;">0 / 0</span>
                        </div>
                        
                        <div class="progress-bar-container">
                            <div id="progressBar" class="progress-bar-fill"></div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <span class="info-label">Total Nilai</span>
                            <span id="totalNilaiDisplay" style="font-weight: 700; font-size: 18px; color: var(--primary-burgundy);">0.00</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span class="info-label">Predikat</span>
                            <span id="predikatDisplay" style="font-weight: 700;">-</span>
                        </div>

                        <table class="native-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th style="text-align: center;">Bobot</th>
                                    <th style="text-align: right;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($template->kategoris as $kategori)
                                    <tr>
                                        <td>{{ $kategori->nama }}</td>
                                        <td style="text-align: center;">{{ (int)$kategori->bobot_persen }}%</td>
                                        <td style="text-align: right; font-weight: 700;" id="catScore_{{ $kategori->kategori_id }}">0.00</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Bantuan Penilaian -->
                <div class="card-native">
                    <div class="card-header-burgundy">ℹ️ Bantuan Penilaian</div>
                    <div class="card-body-native">
                        <table class="native-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skalaNilai as $skala)
                                    <tr>
                                        <td>
                                            <span style="color: #10B981;">•</span> <strong>{{ $skala->kode_nilai }}</strong>
                                        </td>
                                        <td>{{ (int)$skala->nilai_angka }}</td>
                                        <td>{{ $skala->nama_nilai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- NATIVE MODAL CONFIRMATION -->
    <div id="modalKonfirmasi" class="modal-backdrop-native">
        <div class="modal-content-native">
            <h3 style="margin-top: 0; margin-bottom: 12px; font-size: 16px;">Konfirmasi Pengajuan Penilaian</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                Apakah Anda yakin ingin mengajukan penilaian ini? Data yang telah diajukan tidak dapat diubah kembali.
            </p>
            <div style="background-color: #F8FAFC; padding: 12px; border-radius: var(--radius); margin-bottom: 20px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span>Total Nilai:</span>
                    <strong id="modalTotalNilai">0.00</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Predikat:</span>
                    <strong id="modalPredikat">-</strong>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" id="btnModalCancel" class="btn-native" style="background-color: #E2E8F0; color: #333;">Batal</button>
                <button type="button" id="btnModalConfirm" class="btn-native btn-submit">Ya, Ajukan Penilaian</button>
            </div>
        </div>
    </div>

    @endif

</div>

<!-- SCRIPT LOGIC REALTIME SCORE & MODAL -->
<!-- SCRIPT LOGIC REALTIME SCORE & MODAL -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPenilaian');
    const statusAksiInput = document.getElementById('statusAksi');
    const btnDraft = document.getElementById('btnDraft');
    const btnAjukan = document.getElementById('btnAjukan');
    const btnPreview = document.getElementById('btnPreview');
    const modalEl = document.getElementById('modalKonfirmasi');
    const btnModalCancel = document.getElementById('btnModalCancel');
    const btnModalConfirm = document.getElementById('btnModalConfirm');

    // Load Data Predikat Master dari Database
    const predikatList = @json($predikatNilai ?? []);

    // Perhitungan Realtime Progress dan Score
    function calculateProgressAndScore() {
        const questionItems = document.querySelectorAll('.question-item');
        const totalQuestions = questionItems.length;
        let answeredQuestions = 0;

        const categoryData = {};

        // 1. Inisialisasi Data Kategori
        document.querySelectorAll('.card-panel[data-kat-id]').forEach(el => {
            const katId = el.getAttribute('data-kat-id');
            const katBobot = parseFloat(el.getAttribute('data-kat-bobot')) || 0;
            if (katId) {
                categoryData[katId] = {
                    totalScore: 0,
                    filledCount: 0,
                    bobot: katBobot
                };
            }
        });

        // 2. Hitung Jawaban Terisi per Kategori
        questionItems.forEach(item => {
            const checkedRadio = item.querySelector('.radio-score:checked');
            if (checkedRadio) {
                answeredQuestions++;
                const score = parseFloat(checkedRadio.getAttribute('data-score')) || 0;
                const katId = checkedRadio.getAttribute('data-kat-id');

                if (katId && categoryData[katId]) {
                    categoryData[katId].totalScore += score;
                    categoryData[katId].filledCount += 1;
                }
            }
        });

        // 3. Update UI Progress Bar
        const percent = totalQuestions > 0 ? (answeredQuestions / totalQuestions) * 100 : 0;
        document.getElementById('progressCount').textContent = `${answeredQuestions} / ${totalQuestions}`;
        document.getElementById('progressBar').style.width = `${percent}%`;

        // 4. Hitung Nilai Kategori & Grand Total
        let grandTotal = 0;

        Object.keys(categoryData).forEach(katId => {
            const kat = categoryData[katId];
            let avgScore = 0;
            let katContribution = 0;

            if (kat.filledCount > 0) {
                // Rata-rata Skor Kategori = (Nilai Pertanyaan 1 + Nilai Pertanyaan 2 + ...) / Jumlah Pertanyaan Terisi
                avgScore = kat.totalScore / kat.filledCount;

                // Kontribusi ke Total = Rata-rata Skor * (Bobot Kategori / 100)
                katContribution = avgScore * (kat.bobot / 100);
            }

            grandTotal += katContribution;

            // Tampilkan Rata-rata Skor Kategori di Tabel Ringkasan Sidebar
            const scoreCell = document.getElementById(`catScore_${katId}`);
            if (scoreCell) {
                scoreCell.textContent = avgScore.toFixed(2);
            }
        });

        // 5. Update Grand Total Display & Input Form
        const grandTotalFormatted = grandTotal.toFixed(2);
        document.getElementById('totalNilaiDisplay').textContent = grandTotalFormatted;
        document.getElementById('inputGrandTotal').value = grandTotalFormatted;

        // 6. Tentukan Nama Predikat Berdasarkan Rentang Master Predikat
        let matchedPredikat = 'Belum terdapat data predikat';

        if (Array.isArray(predikatList) && predikatList.length > 0) {
            // Urutkan atau cari baris yang sesuai dengan range nilai
            const found = predikatList.find(p => {
                const min = parseFloat(p.nilai_min);
                const max = parseFloat(p.nilai_max);
                
                // Pengecekan apakah grandTotal berada di dalam range [nilai_min, nilai_max]
                return grandTotal >= min && grandTotal <= max;
            });

            if (found && found.predikat) {
                matchedPredikat = found.predikat;
            }
        }

        // Tampilkan ke UI dan simpan ke hidden input
        document.getElementById('predikatDisplay').textContent = matchedPredikat;
        document.getElementById('inputPredikatNama').value = matchedPredikat;
    }

    // Attach Event Handler Radio Change
    document.querySelectorAll('.radio-score').forEach(radio => {
        radio.addEventListener('change', calculateProgressAndScore);
    });

    // Jalankan kalkulasi awal saat halaman selesai dimuat
    calculateProgressAndScore();

    // Event Handler Button Draft
    if (btnDraft) {
        btnDraft.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.radio-score').forEach(r => r.removeAttribute('required'));
            statusAksiInput.value = 'draft';
            form.submit();
        });
    }

    // Event Handler Button Ajukan Penilaian
    if (btnAjukan) {
        btnAjukan.addEventListener('click', function (e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            document.getElementById('modalTotalNilai').textContent = document.getElementById('totalNilaiDisplay').textContent;
            document.getElementById('modalPredikat').textContent = document.getElementById('predikatDisplay').textContent;
            
            modalEl.style.display = 'flex';
        });
    }

    // Event Handler Modal Konfirmasi
    if (btnModalCancel) {
        btnModalCancel.addEventListener('click', function() {
            modalEl.style.display = 'none';
        });
    }

    if (btnModalConfirm) {
        btnModalConfirm.addEventListener('click', function() {
            statusAksiInput.value = 'submitted';
            form.submit();
        });
    }

    // Event Handler Button Preview
    if (btnPreview) {
        btnPreview.addEventListener('click', function () {
            const total = document.getElementById('totalNilaiDisplay').textContent;
            const predikat = document.getElementById('predikatDisplay').textContent;
            alert(`--- PREVIEW HASIL PENILAIAN ---\n\nTotal Nilai : ${total}\nPredikat    : ${predikat}`);
        });
    }
});
</script>
@endsection