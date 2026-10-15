<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Indeks Penilaian Kinerja Staff Berdasarkan Unit Kerja - {{ $periodeText }}</title>
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
            padding: 5px 3px;
        }

        .table-data td {
            border: 1px solid #cccccc;
            padding: 5px;
            vertical-align: middle;
        }

        .row-summary {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
        }

        .row-lingkup {
            background-color: #f8fafc;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
        }

        .bg-luar-biasa { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        .bg-diatas-ekspektasi { background-color: #dbeafe; color: #1e40af; font-weight: bold; }
        .bg-sesuai-ekspektasi { background-color: #fef3c7; color: #92400e; font-weight: bold; }
        .bg-dibawah-ekspektasi { background-color: #ffedd5; color: #9a3412; font-weight: bold; }
        .bg-mengecewakan { background-color: #ffe4e6; color: #9f1239; font-weight: bold; }
        .bg-belum-dinilai { background-color: #f3f4f6; color: #6b7280; }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        .legend-table {
            margin-top: 12px;
            font-size: 8px;
        }

        .legend-box {
            display: inline-block;
            padding: 2px 5px;
            border: 1px solid #dddddd;
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
    <table class="header-table">
        <tr>
            <td width="8%" class="text-center">
                @if(file_exists($profile['logo'] ?? ''))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($profile['logo'])) }}" class="logo-pdam" alt="Logo">
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

    <div class="title-container">
        <div class="report-title">INDEKS PENILAIAN KINERJA STAFF BERDASARKAN UNIT KERJA</div>
        <div class="report-sub">{{ $profile['namapdam'] }}</div>
        <div class="report-periode">PERIODE: {{ $periodeText }}</div>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="18%">LINGKUP KERJA</th>
                <th width="5%">NO.</th>
                <th width="37%">UNIT KERJA</th>
                <th width="12%">JUMLAH PEGAWAI</th>
                <th width="15%">NILAI RATA-RATA STAFF</th>
                <th width="13%">PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($groupedData as $lingkup => $units)
                @php
                    $rowCount = $units->count() + 1;
                    $avgNilaiLingkup = $units->avg('nilai_rata');
                    $predikatLingkup = match (true) {
                        $avgNilaiLingkup >= 90 => 'LUAR BIASA',
                        $avgNilaiLingkup >= 80 => 'DIATAS EKSPEKTASI',
                        $avgNilaiLingkup >= 70 => 'SESUAI EKSPEKTASI',
                        $avgNilaiLingkup >= 60 => 'DIBAWAH EKSPEKTASI',
                        $avgNilaiLingkup > 0 => 'MENGECEWAKAN',
                        default => 'BELUM DINILAI',
                    };
                    $bgClassAvg = match (strtolower(trim($predikatLingkup))) {
                        'luar biasa' => 'bg-luar-biasa',
                        'diatas ekspektasi', 'di atas ekspektasi' => 'bg-diatas-ekspektasi',
                        'sesuai ekspektasi' => 'bg-sesuai-ekspektasi',
                        'dibawah ekspektasi', 'di bawah ekspektasi' => 'bg-dibawah-ekspektasi',
                        'mengecewakan' => 'bg-mengecewakan',
                        default => 'bg-belum-dinilai',
                    };
                @endphp

                @foreach($units as $unit)
                    @php
                        $bgClass = match (strtolower(trim($unit['predikat']))) {
                            'luar biasa' => 'bg-luar-biasa',
                            'diatas ekspektasi', 'di atas ekspektasi' => 'bg-diatas-ekspektasi',
                            'sesuai ekspektasi' => 'bg-sesuai-ekspektasi',
                            'dibawah ekspektasi', 'di bawah ekspektasi' => 'bg-dibawah-ekspektasi',
                            'mengecewakan' => 'bg-mengecewakan',
                            default => 'bg-belum-dinilai',
                        };
                    @endphp
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $rowCount }}" class="row-lingkup">{{ $lingkup }}</td>
                        @endif
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $unit['unit_kerja'] }}</td>
                        <td class="text-center">{{ $unit['jumlah_pegawai'] }}</td>
                        <td class="text-center text-bold">{{ number_format($unit['nilai_rata'], 2) }}</td>
                        <td class="text-center {{ $bgClass }}">{{ $unit['predikat'] }}</td>
                    </tr>
                @endforeach

                <tr class="row-summary">
                    <td colspan="3" class="text-center">RATA RATA {{ $lingkup }}</td>
                    <td class="text-center">{{ number_format($avgNilaiLingkup, 2) }}</td>
                    <td class="text-center {{ $bgClassAvg }}">{{ $predikatLingkup }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data indeks unit kerja yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="legend-table">
        <strong>Keterangan warna predikat:</strong>
        <span class="legend-box bg-luar-biasa">Luar Biasa (&gt;= 90)</span>
        <span class="legend-box bg-diatas-ekspektasi">Diatas Ekspektasi (&gt;= 80)</span>
        <span class="legend-box bg-sesuai-ekspektasi">Sesuai Ekspektasi (&gt;= 70)</span>
        <span class="legend-box bg-dibawah-ekspektasi">Dibawah Ekspektasi (&gt;= 60)</span>
        <span class="legend-box bg-mengecewakan">Mengecewakan (&lt; 60)</span>
    </div>

    <div class="footer-meta">Dicetak pada: {{ $tglNow }}</div>
</body>
</html>