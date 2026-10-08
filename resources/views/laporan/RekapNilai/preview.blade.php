<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Rekapitulasi Penilaian Kinerja Staff - {{ $periodeText }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon-primary: #80243c;
            --maroon-hover: #5c182a;
        }

        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .paper { 
            background: white; 
            padding: 30px; 
            margin: 20px auto; 
            border-radius: 8px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.08); 
            max-width: 1400px; 
        }

        /* Line Separator */
        .line-hr { 
            border-bottom: 3px double #80243c; 
            margin: 15px 0 25px 0; 
        }

        /* Custom Maroon Table Header */
        .table-maroon-header th { 
            background-color: var(--maroon-primary) !important; 
            color: white !important; 
            text-align: center; 
            vertical-align: middle;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px solid #6b1e32 !important;
            padding: 8px 4px;
        }

        .table-custom {
            font-size: 12px;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 6px 8px;
            border-color: #dee2e6;
        }

        /* Group Header Row (Unit Kerja) */
        .row-group-header td {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            font-size: 11px;
            color: #1e293b;
            text-transform: uppercase;
        }

        /* Colors Badges / Cells for Predikat */
        .bg-luar-biasa { background-color: #d1fae5 !important; color: #065f46 !important; font-weight: 600; }
        .bg-diatas-ekspektasi { background-color: #dbeafe !important; color: #1e40af !important; font-weight: 600; }
        .bg-sesuai-ekspektasi { background-color: #fef3c7 !important; color: #92400e !important; font-weight: 600; }
        .bg-dibawah-ekspektasi { background-color: #ffedd5 !important; color: #9a3412 !important; font-weight: 600; }
        .bg-mengecewakan { background-color: #ffe4e6 !important; color: #9f1239 !important; font-weight: 600; }
        .bg-belum-dinilai { background-color: #f3f4f6 !important; color: #6b7280 !important; }

        /* Legend Footer */
        .legend-box {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 4px;
            border: 1px solid rgba(0,0,0,0.1);
        }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .paper { box-shadow: none; margin: 0; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<!-- Top Action Bar -->
<div class="container-fluid no-print mt-3 text-end px-4" style="max-width: 1400px;">
    <button onclick="window.print()" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: var(--maroon-primary);">
        <i class="fas fa-print me-1"></i> Cetak Halaman
    </button>
</div>

<div class="paper">
    <!-- Kop Surat / Header Perusahaan -->
    <div class="row align-items-center">
        <div class="col-2 text-center">
            <img src="{{ $profile['logo'] ?? asset('logo/logo_pdam.png') }}" alt="Logo" style="max-width: 85px;">
        </div>
        <div class="col-10">
            <h5 class="fw-bold mb-0 text-uppercase" style="color: var(--maroon-primary); letter-spacing: 0.5px;">
                {{ $profile['namapdam'] }}
            </h5>
            <div class="fw-semibold text-secondary">{{ $profile['kota'] }}</div>
            <small class="text-muted">{{ $profile['alamat'] }}</small>
        </div>
    </div>

    <div class="line-hr"></div>

    <!-- Judul Laporan -->
    <div class="text-center my-4">
        <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">REKAPITULASI PENILAIAN KINERJA STAFF</h5>
        <div class="fw-bold text-uppercase" style="color: var(--maroon-primary);">
            {{ $profile['namapdam'] }}
        </div>
        <div class="fw-semibold text-secondary mt-1" style="font-size: 13px;">
            PERIODE: {{ $periodeText }}
        </div>
    </div>

    <!-- Tabel Rekapitulasi -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle table-custom">
            <thead class="table-maroon-header">
                <tr>
                    <th rowspan="2" width="4%">NO.</th>
                    <th rowspan="2" width="22%">NAMA</th>
                    <th rowspan="2" width="14%">JABATAN</th>
                    <th rowspan="2" width="20%">UNIT KERJA</th>
                    <th colspan="6" width="18%">GENERAL</th>
                    <th colspan="2" width="6%">SPECIALITY</th>
                    <th rowspan="2" width="8%">TOTAL NILAI</th>
                    <th rowspan="2" width="10%">PREDIKAT</th>
                </tr>
                <tr>
                    <!-- General Sub-Headers -->
                    <th width="3%">G1</th>
                    <th width="3%">G2</th>
                    <th width="3%">G3</th>
                    <th width="3%">G4</th>
                    <th width="3%">G5</th>
                    <th width="3%">G6</th>
                    <!-- Speciality Sub-Headers -->
                    <th width="3%">S1</th>
                    <th width="3%">S2</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($groupedData as $unitName => $items)
                    <!-- Group Header per Unit Kerja -->
                    <tr class="row-group-header">
                        <td colspan="14">
                            <i class="fas fa-building me-1" style="color: var(--maroon-primary);"></i>
                            {{ $unitName }}
                        </td>
                    </tr>

                    @foreach($items as $row)
                        @php
                            $predikat = $row->predikat_verifikator ?? $row->predikat ?? '-';
                            $bgClass = match(strtolower(trim($predikat))) {
                                'luar biasa' => 'bg-luar-biasa',
                                'diatas ekspektasi', 'di atas ekspektasi' => 'bg-diatas-ekspektasi',
                                'sesuai ekspektasi' => 'bg-sesuai-ekspektasi',
                                'dibawah ekspektasi', 'di bawah ekspektasi' => 'bg-dibawah-ekspektasi',
                                'mengecewakan' => 'bg-mengecewakan',
                                default => 'bg-belum-dinilai'
                            };
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="fw-semibold">{{ $row->pgw_nama ?? $row->pegawai->pgw_nama ?? '-' }}</td>
                            <td>{{ $row->occupation->occ_name ?? $row->pgw_jabatan ?? '-' }}</td>
                            <td>{{ $unitName }}</td>
                            
                            <!-- Values Nilai General (G1-G6) -->
                            <td class="text-center">{{ $row->g1 ?? 'B' }}</td>
                            <td class="text-center">{{ $row->g2 ?? 'B' }}</td>
                            <td class="text-center">{{ $row->g3 ?? 'B' }}</td>
                            <td class="text-center">{{ $row->g4 ?? 'C' }}</td>
                            <td class="text-center">{{ $row->g5 ?? 'B' }}</td>
                            <td class="text-center">{{ $row->g6 ?? 'C' }}</td>
                            
                            <!-- Values Nilai Speciality (S1-S2) -->
                            <td class="text-center">{{ $row->s1 ?? 'B' }}</td>
                            <td class="text-center">{{ $row->s2 ?? 'C' }}</td>

                            <!-- Total Nilai -->
                            <td class="text-center fw-bold">
                                {{ number_format((float)($row->total_nilai_verifikator ?? $row->total_nilai ?? 0), 2) }}
                            </td>

                            <!-- Predikat dengan Highlight Warna -->
                            <td class="text-center {{ $bgClass }}">
                                {{ $predikat }}
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="14" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data penilaian yang ditemukan untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Legenda Keterangan Warna Predikat -->
    <div class="mt-4 pt-2 d-flex align-items-center gap-2 flex-wrap" style="font-size: 11px;">
        <span class="fw-bold text-secondary me-1">Keterangan warna predikat:</span>
        <span class="legend-box bg-luar-biasa">Luar Biasa (>= 90)</span>
        <span class="legend-box bg-diatas-ekspektasi">Diatas Ekspektasi (>= 80)</span>
        <span class="legend-box bg-sesuai-ekspektasi">Sesuai Ekspektasi (>= 70)</span>
        <span class="legend-box bg-dibawah-ekspektasi">Dibawah Ekspektasi (>= 60)</span>
        <span class="legend-box bg-mengecewakan">Mengecewakan (&lt; 60)</span>
        <span class="legend-box bg-belum-dinilai">Kosong = belum dinilai</span>
    </div>
</div>

</body>
</html>