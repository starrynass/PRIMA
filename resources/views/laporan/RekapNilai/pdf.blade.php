<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Penilaian Kinerja Staff - {{ $periodeText }}</title>
    <style>
        @page {
            margin: 15px 20px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333333;
            line-height: 1.2;
        }

        /* Kop Surat Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-pdam {
            width: 65px;
        }

        .company-title {
            font-size: 13px;
            font-weight: bold;
            color: #80243c;
            text-transform: uppercase;
        }

        .company-sub {
            font-size: 10px;
            font-weight: bold;
            color: #555555;
        }

        .company-address {
            font-size: 8px;
            color: #666666;
        }

        .line-hr {
            border-bottom: 2px double #80243c;
            margin-bottom: 12px;
        }

        /* Title Laporan */
        .title-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .report-sub {
            font-size: 11px;
            font-weight: bold;
            color: #80243c;
            text-transform: uppercase;
        }

        .report-periode {
            font-size: 10px;
            font-weight: bold;
            color: #555555;
            margin-top: 2px;
        }

        /* Tabel Content */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .table-data th {
            background-color: #80243c;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #6b1e32;
            padding: 5px 2px;
        }

        .table-data td {
            border: 1px solid #cccccc;
            padding: 4px 5px;
            vertical-align: middle;
        }

        .row-group {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 9px;
            color: #1e293b;
            text-transform: uppercase;
        }

        /* Predikat Colors */
        .bg-luar-biasa { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        .bg-diatas-ekspektasi { background-color: #dbeafe; color: #1e40af; font-weight: bold; }
        .bg-sesuai-ekspektasi { background-color: #fef3c7; color: #92400e; font-weight: bold; }
        .bg-dibawah-ekspektasi { background-color: #ffedd5; color: #9a3412; font-weight: bold; }
        .bg-mengecewakan { background-color: #ffe4e6; color: #9f1239; font-weight: bold; }
        .bg-belum-dinilai { background-color: #f3f4f6; color: #6b7280; }

        /* Alignment Utilities */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        /* Footer Legenda */
        .legend-table {
            margin-top: 12px;
            font-size: 8px;
        }

        .legend-box {
            display: inline-block;
            padding: 2px 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-right: 3px;
        }

        .footer-meta {
            margin-top: 10px;
            font-size: 8px;
            color: #888888;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="header-table">
        <tr>
            <td width="8%" class="text-center">
                @if(file_exists($profile['logo'] ?? ''))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($profile['logo'])) }}" class="logo-pdam">
                @endif
            </td>
            <td width="92%" style="padding-left: 10px;">
                <div class="company-title">{{ $profile['namapdam'] }}</div>
                <div class="company-sub">{{ $profile['kota'] }}</div>
                <div class="company-address">{{ $profile['alamat'] }}</div>
            </td>
        </tr>
    </table>

    <div class="line-hr"></div>

    <!-- Judul Laporan -->
    <div class="title-container">
        <div class="report-title">REKAPITULASI PENILAIAN KINERJA STAFF</div>
        <div class="report-sub">{{ $profile['namapdam'] }}</div>
        <div class="report-periode">PERIODE: {{ $periodeText }}</div>
    </div>

    <!-- Tabel Data -->
    <table class="table-data">
        <thead>
            <tr>
                <th rowspan="2" width="3%">NO</th>
                <th rowspan="2" width="22%">NAMA</th>
                <th rowspan="2" width="15%">JABATAN</th>
                <th rowspan="2" width="20%">UNIT KERJA</th>
                <th colspan="6" width="18%">GENERAL</th>
                <th colspan="2" width="6%">SPECIALITY</th>
                <th rowspan="2" width="7%">TOTAL NILAI</th>
                <th rowspan="2" width="9%">PREDIKAT</th>
            </tr>
            <tr>
                <th width="3%">G1</th>
                <th width="3%">G2</th>
                <th width="3%">G3</th>
                <th width="3%">G4</th>
                <th width="3%">G5</th>
                <th width="3%">G6</th>
                <th width="3%">S1</th>
                <th width="3%">S2</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($groupedData as $unitName => $items)
                <tr class="row-group">
                    <td colspan="14">UNIT KERJA: {{ $unitName }}</td>
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
                        <td class="text-bold">{{ $row->pgw_nama ?? $row->pegawai->pgw_nama ?? '-' }}</td>
                        <td>{{ $row->occupation->occ_name ?? $row->pgw_jabatan ?? '-' }}</td>
                        <td>{{ $unitName }}</td>
                        
                        <td class="text-center">{{ $row->g1 ?? 'B' }}</td>
                        <td class="text-center">{{ $row->g2 ?? 'B' }}</td>
                        <td class="text-center">{{ $row->g3 ?? 'B' }}</td>
                        <td class="text-center">{{ $row->g4 ?? 'C' }}</td>
                        <td class="text-center">{{ $row->g5 ?? 'B' }}</td>
                        <td class="text-center">{{ $row->g6 ?? 'C' }}</td>
                        
                        <td class="text-center">{{ $row->s1 ?? 'B' }}</td>
                        <td class="text-center">{{ $row->s2 ?? 'C' }}</td>

                        <td class="text-center text-bold">
                            {{ number_format((float)($row->total_nilai_verifikator ?? $row->total_nilai ?? 0), 2) }}
                        </td>

                        <td class="text-center {{ $bgClass }}">
                            {{ $predikat }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="14" class="text-center" style="padding: 15px;">Tidak ada data penilaian yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Legenda Keterangan -->
    <div class="legend-table">
        <strong>Keterangan warna predikat:</strong>
        <span class="legend-box bg-luar-biasa">Luar Biasa (>= 90)</span>
        <span class="legend-box bg-diatas-ekspektasi">Diatas Ekspektasi (>= 80)</span>
        <span class="legend-box bg-sesuai-ekspektasi">Sesuai Ekspektasi (>= 70)</span>
        <span class="legend-box bg-dibawah-ekspektasi">Dibawah Ekspektasi (>= 60)</span>
        <span class="legend-box bg-mengecewakan">Mengecewakan (< 60)</span>
        <span class="legend-box bg-belum-dinilai">Kosong = belum dinilai</span>
    </div>

    <div class="footer-meta">
        Dicetak pada: {{ $tglNow }}
    </div>

</body>
</html>