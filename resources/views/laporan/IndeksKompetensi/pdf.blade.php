<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Indeks Penilaian Kinerja Staff Berdasarkan Kompetensi - {{ $periodeText }}</title>
    <style>
        @page { size: landscape; margin: 15px 20px; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #172033;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td { vertical-align: middle; padding: 0; }
        .logo-pdam { width: 65px; }
        .company-title { font-size: 12px; font-weight: bold; }
        .company-address { margin-top: 3px; color: #526078; font-size: 8px; }
        .line-hr { border-bottom: 1px solid #333333; margin-bottom: 12px; }
        .title-container { text-align: center; margin-bottom: 14px; }
        .report-title { margin-bottom: 3px; font-size: 13px; font-weight: bold; }
        .report-subtitle { font-size: 10px; font-weight: bold; }
        .report-filter { margin-top: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .competency-table th,
        .competency-table td {
            border: 1px solid #d5d9df;
            padding: 5px 4px;
            vertical-align: middle;
        }

        .competency-table thead th,
        .row-predicate td {
            background-color: #80243c;
            border-color: #6b1e32;
            color: #ffffff;
            text-align: center;
        }

        .competency-table thead th { font-weight: bold; }
        .competency-table thead tr:last-child th { font-weight: normal; }
        .text-center { text-align: center; }
        .col-scope { white-space: nowrap; }
        .score-cell { text-align: center; }
        .row-overall td { background-color: #c9c9c9; font-weight: bold; text-align: center; }
        .row-predicate td { font-weight: bold; text-transform: uppercase; }

        .bg-luar-biasa { background-color: #c6efce; color: #14532d; }
        .bg-diatas-ekspektasi { background-color: #bdd7ee; color: #17365d; }
        .bg-sesuai-ekspektasi { background-color: #fff2cc; color: #664d03; }
        .bg-dibawah-ekspektasi { background-color: #fce4d6; color: #7c2d12; }
        .bg-mengecewakan { background-color: #f4cccc; color: #7f1d1d; }
        .bg-belum-dinilai { background-color: #f3f4f6; color: #6b7280; }

        .legend { margin-top: 12px; font-size: 7px; }
        .legend-box {
            display: inline-block;
            margin: 3px 2px 0 0;
            padding: 3px 5px;
            border: 1px solid #d1d5db;
        }
        .footer-meta { margin-top: 8px; color: #888888; font-size: 7px; text-align: right; }
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
                <div class="company-address">{{ $profile['alamat'] }}</div>
            </td>
        </tr>
    </table>

    <div class="line-hr"></div>

    <div class="title-container">
        <div class="report-title">INDEKS PENILAIAN KINERJA STAFF BERDASARKAN KOMPETENSI</div>
        <div class="report-subtitle">{{ $profile['namapdam'] }}</div>
        <div class="report-filter">PERIODE {{ $periodeText }}</div>
        <div class="report-filter">{{ $unitKerjaText }}</div>
    </div>

    <table class="competency-table">
        <thead>
            <tr>
                <th rowspan="3" width="4%">NO.</th>
                <th rowspan="3" width="15%">LINGKUP KERJA</th>
                @foreach($questions->groupBy('group') as $group => $groupQuestions)
                    <th colspan="{{ $groupQuestions->count() }}">{{ $group }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($questions as $question)
                    <th>{{ $question['code'] }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($questions as $question)
                    <th>{{ $question['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td class="col-scope">{{ $row['lingkup'] }}</td>
                    @foreach($questions as $question)
                        @php
                            $nilai = $row['values'][$question['id']];
                            $bgClass = match (true) {
                                $nilai === null => 'bg-belum-dinilai',
                                $nilai >= 90 => 'bg-luar-biasa',
                                $nilai >= 80 => 'bg-diatas-ekspektasi',
                                $nilai >= 70 => 'bg-sesuai-ekspektasi',
                                $nilai >= 60 => 'bg-dibawah-ekspektasi',
                                default => 'bg-mengecewakan',
                            };
                        @endphp
                        <td class="score-cell {{ $bgClass }}">{{ $nilai === null ? '-' : number_format($nilai, 1) }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 2 + $questions->count() }}" class="text-center">Tidak ada data pegawai untuk filter yang dipilih.</td>
                </tr>
            @endforelse

            @if($reportRows->isNotEmpty())
                <tr class="row-overall">
                    <td colspan="2">INDEKS KESELURUHAN</td>
                    @foreach($questions as $question)
                        @php $nilai = $overallValues[$question['id']]; @endphp
                        <td class="score-cell">{{ $nilai === null ? '-' : number_format($nilai, 2) }}</td>
                    @endforeach
                </tr>
                <tr class="row-predicate">
                    <td colspan="2">PREDIKAT</td>
                    @foreach($questions as $question)
                        <td class="score-cell">{{ $predikatNilai[$question['id']] }}</td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan warna predikat:</strong>
        <span class="legend-box bg-luar-biasa">Luar Biasa (&gt;= 90)</span>
        <span class="legend-box bg-diatas-ekspektasi">Diatas Ekspektasi (&gt;= 80)</span>
        <span class="legend-box bg-sesuai-ekspektasi">Sesuai Ekspektasi (&gt;= 70)</span>
        <span class="legend-box bg-dibawah-ekspektasi">Dibawah Ekspektasi (&gt;= 60)</span>
        <span class="legend-box bg-mengecewakan">Mengecewakan (&lt; 60)</span>
        <span class="legend-box bg-belum-dinilai">Kosong = belum dinilai</span>
    </div>

    <div class="footer-meta">Dicetak pada: {{ $tglNow }}</div>
</body>
</html>