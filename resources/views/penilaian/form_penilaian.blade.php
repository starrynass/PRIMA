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
    --maroon-glow: rgba(122, 28, 56, 0.15);

    /* Neutral System Shades */
    --text-primary: #0F172A;
    --text-secondary: #475569;
    --text-muted: #94A3B8;
    --border-color: #E2E8F0;

    /* Badges & Status */
    --badge-emerald-bg: #D1FAE5;
    --badge-emerald-text: #065F46;
    --badge-amber-bg: #FEF3C7;
    --badge-amber-text: #92400E;
    --badge-rose-bg: #FFE4E6;
    --badge-rose-text: #9F1239;

    --radius-xl: 1rem;
    --radius-lg: 0.75rem;
    --radius-md: 0.5rem;

    --shadow-card: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background-color: var(--bg-page);
    font-family: var(--font-main);
    color: var(--text-primary);
    -webkit-font-smoothing: antialiased;
}

.main-wrapper {
    padding: 1.5rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header Bar */
.header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.page-title span { color: var(--maroon-primary); }

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Base Buttons */
.btn-custom {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-danger-custom { background-color: #EF4444; color: #ffffff; }
.btn-danger-custom:hover { background-color: #DC2626; }

.btn-maroon {
    background: var(--maroon-gradient);
    color: #ffffff;
    box-shadow: 0 2px 8px var(--maroon-glow);
}
.btn-maroon:hover { background: var(--maroon-hover); color: #fff; }

.btn-outline-maroon {
    background-color: transparent;
    border: 1px solid var(--maroon-primary);
    color: var(--maroon-primary);
}
.btn-outline-maroon:hover { background-color: var(--maroon-soft); }

/* Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}

@media (max-width: 1024px) {
    .form-grid { grid-template-columns: 1fr; }
}

/* Card Panel */
.card-panel {
    background: var(--surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.card-panel-header {
    background: var(--maroon-gradient);
    color: #ffffff;
    padding: 0.875rem 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 0.95rem;
}

.card-panel-body { padding: 1.25rem; }

/* Legend Skala Warna Nilai */
.legend-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    padding: 0.75rem 1rem;
    background: #ffffff;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    margin-bottom: 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
}

.legend-item { display: flex; align-items: center; gap: 0.375rem; }
.dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.dot-a { background-color: #10B981; }
.dot-b { background-color: #3B82F6; }
.dot-c { background-color: #F59E0B; }
.dot-d { background-color: #F97316; }
.dot-e { background-color: #EF4444; }

/* Item Pertanyaan */
.question-item {
    display: grid;
    grid-template-columns: 36px 1fr 80px 220px 260px;
    gap: 1rem;
    align-items: start;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}
.question-item:last-child { border-bottom: none; }

@media (max-width: 1280px) {
    .question-item {
        grid-template-columns: 36px 1fr;
        gap: 0.75rem;
    }
    .q-bobot-cell, .options-group, .evidensi-cell {
        grid-column: 2;
    }
}

.q-number {
    width: 32px;
    height: 32px;
    background-color: var(--maroon-primary);
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.q-title { font-weight: 700; font-size: 0.9375rem; color: var(--text-primary); margin-bottom: 0.25rem; }
.q-desc { font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.4; }
.q-bobot { font-size: 0.8125rem; font-weight: 600; background: #F1F5F9; padding: 0.25rem 0.5rem; border-radius: 4px; text-align: center; display: inline-block; }

/* Options Radio */
.options-group { display: flex; gap: 0.375rem; }
.option-btn-wrapper input[type="radio"] { display: none; }

.option-label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 38px;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.15s ease;
    background: #ffffff;
    color: var(--text-secondary);
}

.option-label:hover {
    border-color: var(--maroon-primary);
    color: var(--maroon-primary);
    background-color: var(--maroon-soft);
}

.option-btn-wrapper input[type="radio"]:checked + .option-label {
    border-color: var(--maroon-primary);
    background-color: var(--maroon-primary);
    color: #ffffff;
    box-shadow: 0 2px 6px var(--maroon-glow);
}

.textarea-evidensi {
    width: 100%;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 0.5rem 0.75rem;
    font-family: var(--font-main);
    font-size: 0.8125rem;
    resize: vertical;
    min-height: 60px;
    outline: none;
}
.textarea-evidensi:focus {
    border-color: var(--maroon-primary);
    box-shadow: 0 0 0 3px var(--maroon-glow);
}

/* Sidebar Styles */
.avatar-circle {
    width: 64px;
    height: 64px;
    background-color: var(--maroon-soft);
    color: var(--maroon-primary);
    border: 2px solid var(--maroon-border);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 0.75rem auto;
}

.employee-name { font-weight: 700; font-size: 1rem; text-align: center; margin-bottom: 1rem; }
.info-list { display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.8125rem; }
.info-row { display: flex; justify-content: space-between; align-items: center; }
.info-label { color: var(--text-secondary); }
.info-val { font-weight: 600; color: var(--text-primary); text-align: right; }

.progress-bar-bg {
    width: 100%;
    height: 8px;
    background-color: #E2E8F0;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 0.5rem;
}
.progress-bar-fill {
    height: 100%;
    background: var(--maroon-gradient);
    width: 0%;
    transition: width 0.3s ease;
}

.table-mini {
    width: 100%;
    font-size: 0.8125rem;
    border-collapse: collapse;
    margin-top: 0.5rem;
}
.table-mini th, .table-mini td {
    padding: 0.375rem 0.5rem;
    border-bottom: 1px solid var(--border-color);
}
.table-mini th { text-align: left; color: var(--text-secondary); font-weight: 600; }
</style>
<div class="main-wrapper">
    <div class="header-bar">
        <div class="page-title">
            Form Penilaian &raquo; <span>{{ $penilaian->pgw_nama ?? '-' }}</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('kelola-penilaian.index') }}" class="btn-custom btn-danger-custom">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <?php
        $kategoriData = is_iterable($kategoriList ?? null) ? $kategoriList : [];
        $skalaData = is_iterable($skalaNilaiList ?? null) ? $skalaNilaiList : [];
        $predikatData = is_iterable($predikatList ?? null) ? $predikatList : [];

        // Mencari nilai skala tertinggi sebagai basis pembagi (Max Scale)
        $maxScale = 0;
        foreach ($skalaData as $s) {
            $val = is_object($s) ? $s->nilai : ($s['nilai'] ?? 0);
            if ($val > $maxScale) {
                $maxScale = $val;
            }
        }
        $maxScale = $maxScale > 0 ? $maxScale : 4; // Default fallback ke 4 jika tidak terdefinisi
    ?>

    <form action="{{ route('penilaian.store', ['id' => $penilaian->penilaian_id ?? 1]) }}" method="POST" id="formPenilaian">
        @csrf
        <!-- Hidden input untuk membedakan aksi status (Draft vs Submit/Ajukan) -->
        <input type="hidden" name="status_aksi" id="statusAksi" value="draft">

        <div class="form-grid">
            
            <!-- KOLOM KIRI: Daftar Kategori & Pertanyaan -->
            <div class="left-content">
                
                <!-- Bar Status Pengisian -->
                <div class="card-panel" style="margin-bottom: 1rem;">
                    <div class="card-panel-body" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem;">
                        <div style="font-weight: 700; color: var(--maroon-primary); display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-edit"></i> Isi Penilaian
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--text-secondary);">
                                Status: 
                                <span id="statusBadge" class="badge" style="background: var(--badge-amber-bg); color: var(--badge-amber-text); padding: 2px 8px; border-radius: 4px;">
                                    BELUM DIISI
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Legend Skala Nilai -->
                <div class="legend-bar">
                    <?php if (count($skalaData) > 0): ?>
                        <?php foreach ($skalaData as $skala): ?>
                            <?php
                                $skalaKode = is_object($skala) ? $skala->kode : ($skala['kode'] ?? '-');
                                $skalaNilai = is_object($skala) ? $skala->nilai : ($skala['nilai'] ?? 0);
                                $skalaKet = is_object($skala) ? $skala->keterangan : ($skala['keterangan'] ?? '-');
                            ?>
                            <span class="legend-item">
                                <span class="dot dot-{{ strtolower($skalaKode) }}"></span> 
                                {{ $skalaKode }} — {{ $skalaKet }} ({{ $skalaNilai }})
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted small">Belum ada data skala nilai</span>
                    <?php endif; ?>
                </div>

                <!-- Section Kategori Penilaian -->
                <?php if (count($kategoriData) > 0): ?>
                    <?php $katIndex = 1; ?>
                    <?php foreach ($kategoriData as $kat): ?>
                        <?php
                            $katId = data_get($kat, 'id', $katIndex);
                            $katNama = data_get($kat, 'nama', 'Kategori ' . $katIndex);
                            $katBobot = data_get($kat, 'bobot', 0);
                            $pertanyaanInKat = data_get($kat, 'pertanyaan', []);
                            $pertanyaanArray = is_iterable($pertanyaanInKat) ? $pertanyaanInKat : [];
                        ?>
                        <div class="card-panel" style="margin-bottom: 1rem;" data-kat-id="{{ $katId }}" data-kat-bobot="{{ $katBobot }}">
                            <div class="card-panel-header">
                                <span><i class="fas fa-briefcase me-2"></i> {{ $katIndex }}. {{ strtoupper($katNama) }}</span>
                                <div style="display: flex; gap: 1rem; align-items: center; font-size: 0.8125rem;">
                                    <span>Bobot {{ $katBobot }}%</span>
                                    <i class="fas fa-chevron-up"></i>
                                </div>
                            </div>
                            <div class="card-panel-body">
                                
                                <?php if (count($pertanyaanArray) > 0): ?>
                                    <?php $qIndex = 1; ?>
                                    <?php foreach ($pertanyaanArray as $q): ?>
                                        <?php
                                            $qId = data_get($q, 'id');
                                            $qJudul = data_get($q, 'judul', '-');
                                            $qDesc = data_get($q, 'deskripsi', '-');
                                            $qBobot = data_get($q, 'bobot', 0);
                                        ?>
                                        <div class="question-item">
                                            <div class="q-number">{{ $qIndex }}</div>
                                            <div>
                                                <div class="q-title">{{ $qJudul }}</div>
                                                <div class="q-desc">{{ $qDesc }}</div>
                                            </div>
                                            <div class="q-bobot-cell">
                                                <span class="q-bobot">{{ number_format($qBobot, 2) }}%</span>
                                            </div>
                                            <div class="options-group">
                                                <?php if (count($skalaData) > 0): ?>
                                                    <?php foreach ($skalaData as $opt): ?>
                                                        <?php
                                                            $optKode = is_object($opt) ? $opt->kode : ($opt['kode'] ?? '-');
                                                            $optVal = is_object($opt) ? $opt->nilai : ($opt['nilai'] ?? 0);
                                                        ?>
                                                        <div class="option-btn-wrapper">
                                                            <input type="radio" 
                                                                   id="q{{ $qId }}_{{ $optKode }}" 
                                                                   name="nilai[{{ $qId }}]" 
                                                                   value="{{ $optKode }}" 
                                                                   data-score="{{ $optVal }}"
                                                                   data-weight="{{ $qBobot }}"
                                                                   data-kat-id="{{ $katId }}"
                                                                   class="radio-score"
                                                                   {{ old('nilai.' . $qId) == $optKode ? 'checked' : '' }}>
                                                            <label for="q{{ $qId }}_{{ $optKode }}" class="option-label">{{ $optKode }}</label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum ada data skala nilai</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="evidensi-cell">
                                                <textarea name="evidensi[{{ $qId }}]" 
                                                          class="textarea-evidensi" 
                                                          placeholder="Tulis catatan atau evidensi (opsional)...">{{ old('evidensi.' . $qId) }}</textarea>
                                            </div>
                                        </div>
                                        <?php $qIndex++; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-3 text-center text-muted">Belum ada data pertanyaan pada kategori ini</div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <?php $katIndex++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card-panel">
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            Belum ada data kategori & pertanyaan (Template belum diset/kosong)
                        </div>
                    </div>
                <?php endif; ?>

                <!-- CONTAINER 3 TOMBOL ACTION SESUAI DENGAN GAMBAR -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; margin-bottom: 2rem;">
                    <!-- Sisi Kiri: Simpan Draft & Preview Hasil -->
                    <div style="display: flex; gap: 0.5rem;">
                        <!-- Tombol Simpan Draft (Kuning / Oranye) -->
                        <button type="button" class="btn-custom" id="btnDraft" style="background-color: #FFB800; color: black; padding: 0.75rem 1.5rem;">
                            <i class="fas fa-save me-1"></i> Simpan Draft
                        </button>

                        <!-- Tombol Preview Hasil (Teal / Biru Toska) -->
                        <button type="button" class="btn-custom" id="btnPreview" style="background-color: #008B8B; color: white; padding: 0.75rem 1.5rem;">
                            <i class="fas fa-eye me-1"></i> Preview Hasil
                        </button>
                    </div>

                    <!-- Sisi Kanan: Ajukan Penilaian -->
                    <div>
                        <!-- Tombol Ajukan Penilaian (Hijau) -->
                        <button type="button" class="btn-custom btn-maroon" id="btnAjukan" style="padding: 0.75rem 1.5rem;">
                            <i class="fas fa-paper-plane me-1"></i> Ajukan Penilaian
                        </button>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: Sidebar Ringkasan & Progress -->
            <div class="right-sidebar">
                
                <!-- Card 1: Ringkasan Pegawai -->
                <div class="card-panel">
                    <div class="card-panel-header">
                        <span><i class="fas fa-user me-2"></i> Ringkasan Pegawai</span>
                    </div>
                    <div class="card-panel-body">
                        <div class="employee-avatar">
                            {{ strtoupper(substr($penilaian->pgw_nama ?? 'A', 0, 1)) }}
                        </div>

                        <div class="employee-name">
                            {{ $penilaian->pgw_nama ?? '-' }}
                        </div>

                        <div class="info-list">
                            <div class="info-row">
                                <span class="info-label">NUP</span>
                                <span class="info-val"><code>{{ $penilaian->pgw_nup ?? '-' }}</code></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Jabatan</span>
                                <span class="info-val">{{ $penilaian->pgw_jabatan ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Penempatan</span>
                                <span class="info-val">{{ $penilaian->pgw_off_name ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Dept</span>
                                <span class="info-val">{{ $penilaian->pgw_dept_name ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Penilai</span>
                                <span class="info-val">
                                    @if(empty($penilaian->penilai_id) && empty($penilaian->penilai_manual))
                                        <span class="text-danger fw-bold">NULL</span>
                                    @else
                                        {{ $penilaian->penilai_id ?? $penilaian->penilai_manual }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Progress Pengisian & Live Score -->
                <div class="card-panel">
                    <div class="card-panel-header">
                        <span><i class="fas fa-chart-line me-2"></i> Progress Pengisian</span>
                    </div>
                    <div class="card-panel-body">
                        <div class="info-row" style="margin-bottom: 0.25rem;">
                            <span class="info-label">Pertanyaan terisi</span>
                            <span class="info-val" id="progressCount">0 / 0</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" id="progressBar" style="width: 0%;"></div>
                        </div>

                        <hr style="margin: 1rem 0; border: none; border-top: 1px dashed var(--border-color);">

                        <div class="info-row" style="margin-bottom: 0.5rem;">
                            <span class="info-label">Total Nilai</span>
                            <span class="info-val" id="totalNilaiDisplay" style="font-size: 1.125rem; color: var(--maroon-primary);">0.00</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Predikat</span>
                            <span class="info-val" id="predikatDisplay" style="color: #6B7280;">-</span>
                        </div>

                        <!-- Tabel Kategori Dinamis -->
                        <table class="table-mini" style="margin-top: 1rem;">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th style="text-align: center;">Bobot</th>
                                    <th style="text-align: right;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($kategoriData) > 0): ?>
                                    <?php foreach ($kategoriData as$kat): ?>
                                        <?php
                                            $katId = data_get($kat, 'id');
                                            $katNama = data_get($kat, 'nama', '-');
                                            $katBobot = data_get($kat, 'bobot', 0);
                                        ?>
                                        <tr>
                                            <td>{{ $katNama }}</td>
                                            <td style="text-align: center;">{{ $katBobot }}%</td>
                                            <td style="text-align: right;" id="catScore_{{ $katId }}">0.00</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted" style="padding: 0.75rem;">
                                            Belum ada kategori
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card 3: Bantuan Penilaian -->
                <div class="card-panel">
                    <div class="card-panel-header">
                        <span><i class="fas fa-info-circle me-2"></i> Bantuan Penilaian</span>
                    </div>
                    <div class="card-panel-body" style="padding: 0.5rem 1rem;">
                        <table class="table-mini">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Kode</th>
                                    <th style="text-align: center;">Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($skalaData) > 0): ?>
                                    <?php foreach ($skalaData as$s): ?>
                                        <?php
                                            $sKode = is_object($s) ? $s->kode : ($s['kode'] ?? '-');
                                            $sNilai = is_object($s) ? $s->nilai : ($s['nilai'] ?? 0);
                                            $sKet = is_object($s) ? $s->keterangan : ($s['keterangan'] ?? '-');
                                        ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <span class="dot dot-{{ strtolower($sKode) }}"></span> {{ $sKode }}
                                            </td>
                                            <td style="text-align: center;">{{ $sNilai }}</td>
                                            <td>{{ $sKet }}</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted" style="padding: 0.75rem;">
                                            Belum ada data
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- MODAL KONFIRMASI AJUKAN PENILAIAN (Bootstrap 5) -->
<div class="modal fade" id="modalKonfirmasiAjukan" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 8px; border: none; overflow: hidden;">
            <div class="modal-header" style="background-color: #28A745; color: #fff;">
                <h5 class="modal-title" id="modalKonfirmasiLabel">
                    <i class="fas fa-paper-plane me-2"></i> Konfirmasi Pengajuan Penilaian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem; font-size: 0.95rem;">
                <p class="mb-2">Apakah Anda yakin ingin mengajukan penilaian ini?</p>
                <div class="p-3 style-box" style="background-color: #F9FAFB; border-radius: 6px; border: 1px solid #E5E7EB;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Pegawai:</span>
                        <strong>{{ $penilaian->pgw_nama ?? '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Nilai:</span>
                        <strong id="modalTotalNilai" style="color: #28A745;">0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Predikat:</span>
                        <strong id="modalPredikat">-</strong>
                    </div>
                </div>
                <small class="text-danger mt-2 d-block">
                    <i class="fas fa-exclamation-circle me-1"></i> Penilaian yang telah diajukan tidak dapat diubah kembali.
                </small>
            </div>
            <div class="modal-footer" style="background-color: #F9FAFB; border-top: 1px solid #E5E7EB;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-custom" id="btnConfirmSubmit" style="background-color: #28A745; color: #fff; font-weight: 600; padding: 0.5rem 1.25rem;">
                    Ya, Ajukan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formPenilaian = document.getElementById('formPenilaian');
    const radioInputs = document.querySelectorAll('.radio-score');
    const progressCount = document.getElementById('progressCount');
    const progressBar = document.getElementById('progressBar');
    const statusBadge = document.getElementById('statusBadge');
    const totalNilaiDisplay = document.getElementById('totalNilaiDisplay');
    const predikatDisplay = document.getElementById('predikatDisplay');
    const statusAksiInput = document.getElementById('statusAksi');

    const btnDraft = document.getElementById('btnDraft');
    const btnPreview = document.getElementById('btnPreview');
    const btnAjukan = document.getElementById('btnAjukan');
    const btnConfirmSubmit = document.getElementById('btnConfirmSubmit');

    const modalKonfirmasiElement = document.getElementById('modalKonfirmasiAjukan');
    const modalTotalNilai = document.getElementById('modalTotalNilai');
    const modalPredikat = document.getElementById('modalPredikat');

    // Mencegah Syntax Error jika data PHP kosong
    const predikatRules = @json($predikatData ?? []);
    const maxScale = Number("{{ $maxScale ?? 4 }}") || 4;

    function updateCalculation() {
        const allQuestions = new Set();
        radioInputs.forEach(r => allQuestions.add(r.name));
        const totalQuestions = allQuestions.size;

        const checkedRadios = document.querySelectorAll('.radio-score:checked');
        const filledCount = checkedRadios.length;
        
        const progressPercent = totalQuestions > 0 ? (filledCount / totalQuestions) * 100 : 0;
        
        if (progressCount) progressCount.textContent = `${filledCount} / ${totalQuestions}`;
        if (progressBar) progressBar.style.width = `${progressPercent}%`;

        if (statusBadge) {
            if (totalQuestions === 0) {
                statusBadge.textContent = 'TIDAK ADA SOAL';
                statusBadge.style.background = '#E5E7EB';
                statusBadge.style.color = '#374151';
            } else if (filledCount === 0) {
                statusBadge.textContent = 'BELUM DIISI';
                statusBadge.style.background = 'var(--badge-amber-bg)';
                statusBadge.style.color = 'var(--badge-amber-text)';
            } else if (filledCount < totalQuestions) {
                statusBadge.textContent = 'PROSES';
                statusBadge.style.background = 'var(--badge-amber-bg)';
                statusBadge.style.color = 'var(--badge-amber-text)';
            } else {
                statusBadge.textContent = 'LENGKAP';
                statusBadge.style.background = 'var(--badge-emerald-bg)';
                statusBadge.style.color = 'var(--badge-emerald-text)';
            }
        }

        let categoryQuestionScores = {};

        checkedRadios.forEach(radio => {
            const score = parseFloat(radio.dataset.score) || 0;
            const qWeight = parseFloat(radio.dataset.weight) || 0;
            const katId = radio.dataset.katId;

            const qWeightedScore = (score / maxScale) * qWeight;

            if (katId) {
                categoryQuestionScores[katId] = (categoryQuestionScores[katId] || 0) + qWeightedScore;
            }
        });

        let grandTotalScore = 0;

        const categoryPanels = document.querySelectorAll('.left-content .card-panel[data-kat-id]');
        categoryPanels.forEach(panel => {
            const katId = panel.dataset.katId;
            const katBobot = parseFloat(panel.dataset.katBobot) || 0;

            const qScoreSum = categoryQuestionScores[katId] || 0;

            const finalCatScore = qScoreSum * (katBobot / 100);
            grandTotalScore += finalCatScore;

            const catElement = document.getElementById(`catScore_${katId}`);
            if (catElement) {
                catElement.textContent = finalCatScore.toFixed(2);
            }
        });

        const formattedScore = grandTotalScore.toFixed(2);
        if (totalNilaiDisplay) totalNilaiDisplay.textContent = formattedScore;

        let predikat = 'Belum Lengkap';
        let predikatColor = '#EF4444';

        if (!predikatRules || predikatRules.length === 0) {
            predikat = 'Belum ada data predikat';
            predikatColor = '#6B7280';
        } else if (filledCount === totalQuestions && totalQuestions > 0) {
            const matchPredikat = predikatRules.find(p => {
                const min = p.min ?? p.min_nilai ?? 0;
                const max = p.max ?? p.max_nilai ?? 100;
                return grandTotalScore >= min && grandTotalScore <= max;
            });
            
            if (matchPredikat) {
                predikat = matchPredikat.nama || matchPredikat.predikat || matchPredikat.nama_predikat;
                predikatColor = matchPredikat.warna || '#10B981';
            } else {
                predikat = 'Tidak Terdefinisi';
            }
        }

        if (predikatDisplay) {
            predikatDisplay.textContent = predikat;
            predikatDisplay.style.color = predikatColor;
        }

        return {
            totalScore: formattedScore,
            predikatName: predikat,
            isComplete: filledCount === totalQuestions && totalQuestions > 0
        };
    }

    radioInputs.forEach(radio => {
        radio.addEventListener('change', updateCalculation);
    });

    // 1. AKSI TOMBOL SIMPAN DRAFT
    if (btnDraft) {
        btnDraft.addEventListener('click', function () {
            if (statusAksiInput) statusAksiInput.value = 'draft';
            if (formPenilaian) formPenilaian.submit();
        });
    }

    // 2. AKSI TOMBOL PREVIEW HASIL
    if (btnPreview) {
        btnPreview.addEventListener('click', function () {
            const calcRes = updateCalculation();
            alert(`--- PREVIEW HASIL PENILAIAN ---\n\nTotal Nilai : ${calcRes.totalScore}\nPredikat    : ${calcRes.predikatName}`);
        });
    }

    // Helper Fungsi Buka Modal Safely
    function showModalKonfirmasi() {
        if (typeof bootstrap !== 'undefined' && modalKonfirmasiElement) {
            const modalInstance = bootstrap.Modal.getInstance(modalKonfirmasiElement) || new bootstrap.Modal(modalKonfirmasiElement);
            modalInstance.show();
        } else {
            // Fallback jika bootstrap JS belum ter-load
            if (confirm("Apakah Anda yakin ingin mengajukan penilaian ini?")) {
                if (statusAksiInput) statusAksiInput.value = 'submitted';
                if (formPenilaian) formPenilaian.submit();
            }
        }
    }

    // 3. AKSI TOMBOL AJUKAN PENILAIAN
    if (btnAjukan) {
        btnAjukan.addEventListener('click', function () {
            const calcRes = updateCalculation();

            if (modalTotalNilai) modalTotalNilai.textContent = calcRes.totalScore;
            if (modalPredikat) modalPredikat.textContent = calcRes.predikatName;

            showModalKonfirmasi();
        });
    }

    // AKSI KLIK TOMBOL KONFIRMASI DI MODAL
    if (btnConfirmSubmit) {
        btnConfirmSubmit.addEventListener('click', function () {
            if (statusAksiInput) statusAksiInput.value = 'submitted';
            
            if (typeof bootstrap !== 'undefined' && modalKonfirmasiElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalKonfirmasiElement);
                if (modalInstance) modalInstance.hide();
            }
            
            if (formPenilaian) formPenilaian.submit();
        });
    }

    updateCalculation();
});
</script>
@endsection