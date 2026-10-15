<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indeks Penilaian Kinerja Staff Berdasarkan Kompetensi - {{ $periodeText }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon-primary: #80243c;
            --maroon-border: #6b1e32;
            --report-text: #172033;
        }

        body {
            background: #f3f4f6;
            color: var(--report-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .report-paper {
            max-width: 1600px;
            margin: 16px auto;
            padding: 22px 24px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.08);
        }

        .report-header {
            min-height: 58px;
            padding: 4px 8px 12px;
            border-bottom: 2px solid #333;
        }

        .report-logo {
            max-width: 86px;
            max-height: 52px;
            object-fit: contain;
        }

        .report-company {
            font-size: 13px;
            font-weight: 700;
        }

        .report-address {
            margin-top: 2px;
            color: #526078;
            font-size: 11px;
        }

        .report-title {
            margin: 14px 0 2px;
            font-size: 20px;
            font-weight: 800;
        }

        .report-subtitle {
            font-size: 15px;
            font-weight: 700;
        }

        .report-filter {
            margin-top: 2px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .table-wrap {
            overflow-x: auto;
            margin-top: 24px;
        }

        .competency-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            font-size: 12px;
        }

        .competency-table th,
        .competency-table td {
            padding: 6px 8px;
            border: 1px solid #d5d9df;
            vertical-align: middle;
        }

        .competency-table thead th,
        .row-predicate td {
            background: var(--maroon-primary);
            border-color: var(--maroon-border);
            color: #fff;
            text-align: center;
        }

        .competency-table thead th {
            font-weight: 700;
        }

        .competency-table thead tr:last-child th {
            font-weight: 500;
        }

        .col-number {
            width: 48px;
            white-space: nowrap;
        }

        .col-scope {
            min-width: 165px;
            text-align: left;
            white-space: nowrap;
        }

        .score-cell {
            min-width: 92px;
            text-align: center;
        }

        .bg-luar-biasa { background: #c6efce !important; color: #14532d; }
        .bg-diatas-ekspektasi { background: #bdd7ee !important; color: #17365d; }
        .bg-sesuai-ekspektasi { background: #fff2cc !important; color: #664d03; }
        .bg-dibawah-ekspektasi { background: #fce4d6 !important; color: #7c2d12; }
        .bg-mengecewakan { background: #f4cccc !important; color: #7f1d1d; }
        .bg-belum-dinilai { background: #f3f4f6 !important; color: #6b7280; }

        .row-overall td {
            background: #c9c9c9;
            font-weight: 700;
            text-align: center;
        }

        .row-predicate td {
            font-weight: 700;
            text-transform: uppercase;
        }

        .legend {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 22px;
            font-size: 10px;
        }

        .legend-label {
            margin-right: 2px;
            font-weight: 600;
        }

        .legend-item {
            padding: 3px 7px;
            border: 1px solid #d1d5db;
        }

        .no-print {
            max-width: 1600px;
            margin: 12px auto 0;
            padding: 0 24px;
            text-align: right;
        }

        .btn-print {
            border: 0;
            border-radius: 4px;
            padding: 7px 14px;
            background: #80243c;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
        }

        @media print {
            @page { size: landscape; margin: 12mm; }
            body { background: #fff; }
            .no-print { display: none !important; }
            .report-paper { max-width: none; margin: 0; padding: 0; box-shadow: none; }
            .table-wrap { overflow: visible; }
            .competency-table { min-width: 0; font-size: 9px; }
            .competency-table th,
            .competency-table td { padding: 4px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print me-1"></i> Cetak Halaman
        </button>
    </div>

    <main class="report-paper">
        <header class="report-header row align-items-center">
            <div class="col-2 text-center">
                <img src="{{ asset('logo/logo_pdam.png') }}" alt="Logo" class="report-logo">
            </div>
            <div class="col-10">
                <div class="report-company">{{ $profile['namapdam'] }}</div>
                <div class="report-address">{{ $profile['alamat'] }}</div>
            </div>
        </header>

        <section class="text-center">
            <h1 class="report-title">INDEKS PENILAIAN KINERJA STAFF BERDASARKAN KOMPETENSI</h1>
            <div class="report-subtitle">{{ $profile['namapdam'] }}</div>
            <div class="report-filter">PERIODE {{ $periodeText }}</div>
            <div class="report-filter">{{ $unitKerjaText }}</div>
        </section>

        <div class="table-wrap">
            <table class="competency-table">
                <thead>
                    <tr>
                        <th rowspan="3" class="col-number">NO.</th>
                        <th rowspan="3" class="col-scope text-center">LINGKUP KERJA</th>
                        @foreach($questions->groupBy('group') as $group => $groupQuestions)
                            <th colspan="{{ $groupQuestions->count() }}">{{ $group }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($questions as $question)
                            <th class="score-cell">{{ $question['code'] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($questions as $question)
                            <th class="score-cell">{{ $question['label'] }}</th>
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
                            <td colspan="{{ 2 + $questions->count() }}" class="text-center text-muted py-3">
                                Tidak ada data pegawai untuk filter yang dipilih.
                            </td>
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
        </div>

        <footer class="legend">
            <span class="legend-label">Keterangan warna predikat:</span>
            <span class="legend-item bg-luar-biasa">Luar Biasa (&gt;= 90)</span>
            <span class="legend-item bg-diatas-ekspektasi">Diatas Ekspektasi (&gt;= 80)</span>
            <span class="legend-item bg-sesuai-ekspektasi">Sesuai Ekspektasi (&gt;= 70)</span>
            <span class="legend-item bg-dibawah-ekspektasi">Dibawah Ekspektasi (&gt;= 60)</span>
            <span class="legend-item bg-mengecewakan">Mengecewakan (&lt; 60)</span>
            <span class="legend-item bg-belum-dinilai">Kosong = belum dinilai</span>
        </footer>
    </main>
</body>
</html>