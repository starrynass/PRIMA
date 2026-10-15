<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penilaian Tahunan {{ $tahun }}</title>
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #333; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .header-table td { vertical-align: middle; }
        .logo { width: 70px; }
        .company-title { font-weight: bold; font-size: 11pt; color: #80243c; }
        .company-sub { font-size: 9pt; font-weight: bold; }
        .company-addr { font-size: 8pt; color: #555; }
        
        .line-hr { border-bottom: 2px solid #80243c; margin-bottom: 12px; }

        .title-section { text-align: center; margin-bottom: 12px; }
        .title-section h3 { margin: 0; font-size: 11pt; color: #111; text-transform: uppercase; }
        .title-section p { margin: 2px 0 0 0; font-size: 9pt; color: #555; font-weight: bold; }

        .content-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .content-table th, .content-table td { border: 1px solid #444; padding: 5px; font-size: 8pt; }
        .content-table th { background-color: #80243c; color: #ffffff; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .section-row td { background: #f1f5f9; color: #80243c; font-weight: bold; }
        .summary-row td { background: #f1f5f9; font-weight: bold; }
        .score-predikat { font-weight: bold; text-align: center; }
        .bg-luar-biasa { background: #d1fae5; color: #065f46; }
        .bg-diatas-ekspektasi { background: #dbeafe; color: #1e40af; }
        .bg-sesuai-ekspektasi { background: #fef3c7; color: #92400e; }
        .bg-dibawah-ekspektasi { background: #ffedd5; color: #9a3412; }
        .bg-mengecewakan { background: #ffe4e6; color: #9f1239; }
        .bg-belum-dinilai { background: #f3f4f6; color: #6b7280; }
        .chart-section { margin-bottom: 15px; page-break-inside: avoid; }
        .chart-title { margin: 8px 0; color: #80243c; font-size: 10pt; font-weight: bold; }
        .chart-data { width: 100%; border-collapse: collapse; }
        .chart-data th, .chart-data td { border: 1px solid #cbd5e1; padding: 4px; font-size: 7pt; text-align: center; }
        .chart-data th { background: #80243c; color: #fff; }
        .bar-chart { width: 100%; height: 145px; margin: 8px 0 12px; border-bottom: 1px solid #94a3b8; white-space: nowrap; }
        .bar-slot { display: inline-block; width: {{ max(5, floor(90 / max(1, count($monthColumns)))) }}%; height: 100%; margin: 0 0.2%; vertical-align: bottom; text-align: center; white-space: normal; }
        .bar-value { display: block; height: 12px; font-size: 6pt; color: #475569; }
        .bar { width: 55%; margin: 0 auto; background: #80243c; }
        .bar-label { display: block; margin-top: 3px; font-size: 6pt; }
        .legend { margin-top: 8px; font-size: 7pt; }

        .footer-info { margin-top: 10px; font-size: 7.5pt; color: #777; font-style: italic; text-align: right; }
    </style>
</head>
<body>

    <!-- Header Kop Surat PDAM -->
    <table class="header-table">
        <tr>
            <td width="10%" class="text-center">
                <img src="{{ $profile['logo'] }}" class="logo">
            </td>
            <td width="90%">
                <div class="company-title">{{ $profile['namapdam'] }}</div>
                <div class="company-sub">{{ $profile['kota'] }}</div>
                <div class="company-addr">{{ $profile['alamat'] }}</div>
            </td>
        </tr>
    </table>

    <div class="line-hr"></div>

    <div class="title-section">
        <h3>{{ $reportTitle }}</h3>
        <p>{{ $periodText }}</p>
    </div>

    @if($jenisLaporan === 'tahunan')
    <!-- Tabel Data Dp3TransPenilaian -->
    <table class="content-table">
        <thead>
            <tr>
                <th width="4%">NO</th>
                <th width="12%">NUP</th>
                <th width="24%">NAMA PEGAWAI</th>
                <th width="22%">JABATAN</th>
                <th width="18%">DEPARTEMEN</th>
                <th width="10%">NILAI TOTAL</th>
                <th width="10%">PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->pgw_nup ?? '-' }}</td>
                    <td class="text-left">{{ $row->pgw_nama ?? '-' }}</td>
                    <td class="text-left">{{ $row->pgw_jabatan ?? '-' }}</td>
                    <td class="text-left">{{ $row->pgw_dept_name ?? '-' }}</td>
                    <td class="text-center">
                        {{ number_format($row->total_nilai_verifikator ?? $row->total_nilai ?? 0, 2) }}
                    </td>
                    <td class="text-center">
                        {{ $row->predikat_verifikator ?? $row->predikat ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data penilaian untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @elseif($jenisLaporan === 'rata_rata')
        <table class="content-table">
            <thead>
                <tr><th rowspan="2">UNIT KERJA</th><th colspan="{{ count($monthColumns) }}">NILAI RATA-RATA</th></tr>
                <tr>@foreach($monthColumns as $month)<th>{{ $month['label'] }}</th>@endforeach</tr>
            </thead>
            <tbody>
                @forelse($unitsByScope as $scope => $units)
                    <tr class="section-row"><td colspan="{{ 1 + count($monthColumns) }}">{{ $scope }}</td></tr>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit['name'] }}</td>
                            @foreach($monthColumns as $month)
                                <td class="text-center">{{ $unit['monthly_values'][$month['key']] === null ? '' : number_format($unit['monthly_values'][$month['key']], 2) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    @if(isset($scopeSummaries[$scope]))
                        <tr class="summary-row">
                            <td>Rata-Rata</td>
                            @foreach($monthColumns as $month)
                                <td class="text-center">{{ $scopeSummaries[$scope]['monthly_values'][$month['key']] === null ? '' : number_format($scopeSummaries[$scope]['monthly_values'][$month['key']], 2) }}</td>
                            @endforeach
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="{{ 1 + count($monthColumns) }}" class="text-center">Data laporan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    @elseif($jenisLaporan === 'rekapitulasi')
        <table class="content-table">
            <thead>
                <tr>
                    <th rowspan="2">LINGKUP KERJA / UNIT KERJA</th>
                    @foreach($monthColumns as $month)<th colspan="2">{{ strtoupper($month['label']) }}</th>@endforeach
                    <th colspan="2">RATA-RATA</th>
                </tr>
                <tr>
                    @foreach($monthColumns as $month)<th>NILAI</th><th>PREDIKAT</th>@endforeach
                    <th>NILAI</th><th>PREDIKAT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unitMatrix as $unit)
                    <tr>
                        <td>{{ $unit['name'] }}</td>
                        @foreach($monthColumns as $month)
                            @php
                                $nilai = $unit['monthly_values'][$month['key']];
                                $predikat = $unit['predicates'][$month['key']];
                                $bgClass = match ($predikat) {
                                    'LUAR BIASA' => 'bg-luar-biasa',
                                    'DIATAS EKSPEKTASI' => 'bg-diatas-ekspektasi',
                                    'SESUAI EKSPEKTASI' => 'bg-sesuai-ekspektasi',
                                    'DIBAWAH EKSPEKTASI' => 'bg-dibawah-ekspektasi',
                                    'MENGECEWAKAN' => 'bg-mengecewakan',
                                    default => 'bg-belum-dinilai',
                                };
                            @endphp
                            <td class="text-center">{{ $nilai === null ? '' : number_format($nilai, 2) }}</td>
                            <td class="score-predikat {{ $bgClass }}">{{ $nilai === null ? '' : $predikat }}</td>
                        @endforeach
                        @php
                            $average = $unit['average'];
                            $averagePredicate = $unit['average_predicate'];
                            $averageClass = match ($averagePredicate) {
                                'LUAR BIASA' => 'bg-luar-biasa',
                                'DIATAS EKSPEKTASI' => 'bg-diatas-ekspektasi',
                                'SESUAI EKSPEKTASI' => 'bg-sesuai-ekspektasi',
                                'DIBAWAH EKSPEKTASI' => 'bg-dibawah-ekspektasi',
                                'MENGECEWAKAN' => 'bg-mengecewakan',
                                default => 'bg-belum-dinilai',
                            };
                        @endphp
                        <td class="text-center">{{ $average === null ? '' : number_format($average, 2) }}</td>
                        <td class="score-predikat {{ $averageClass }}">{{ $average === null ? '' : $averagePredicate }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ 1 + 2 * (count($monthColumns) + 1) }}" class="text-center">Data laporan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="legend"><strong>Keterangan warna predikat:</strong> Luar Biasa (&gt;= 90) | Diatas Ekspektasi (&gt;= 80) | Sesuai Ekspektasi (&gt;= 70) | Dibawah Ekspektasi (&gt;= 60) | Mengecewakan (&lt; 60)</div>
    @else
        @php
            $chartGroups = $jenisLaporan === 'grafik_divisi'
                ? $scopeSummaries
                : $unitMatrix->keyBy('name')->map(fn ($unit) => [
                    'monthly_values' => $unit['monthly_values'],
                    'average' => $unit['average'],
                    'average_predicate' => $unit['average_predicate'],
                    'predicates' => $unit['predicates'],
                ])->all();
        @endphp
        @forelse($chartGroups as $groupName => $chart)
            <div class="chart-section">
                <div class="chart-title">Data {{ $groupName }}</div>
                <table class="chart-data">
                    <thead><tr>@foreach($monthColumns as $month)<th>{{ $month['label'] }}</th>@endforeach</tr></thead>
                    <tbody>
                        <tr>@foreach($monthColumns as $month)<td>{{ $chart['monthly_values'][$month['key']] === null ? '0' : number_format($chart['monthly_values'][$month['key']], 2) }}</td>@endforeach</tr>
                    </tbody>
                </table>
                <div class="bar-chart">
                    @foreach($monthColumns as $month)
                        @php $nilai = $chart['monthly_values'][$month['key']] ?? null; @endphp
                        <div class="bar-slot">
                            <span class="bar-value">{{ $nilai === null ? '0' : number_format($nilai, 1) }}</span>
                            <div class="bar" style="height: {{ max(2, min(100, (float) ($nilai ?? 0))) }}%;"></div>
                            <span class="bar-label">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <table class="chart-data">
                    <thead><tr><th>PERIODE KERJA (BULAN)</th><th>NILAI</th><th>PREDIKAT</th></tr></thead>
                    <tbody>
                        @foreach($monthColumns as $month)
                            @php
                                $nilai = $chart['monthly_values'][$month['key']];
                                $predikat = $chart['predicates'][$month['key']];
                                $bgClass = match ($predikat) {
                                    'LUAR BIASA' => 'bg-luar-biasa',
                                    'DIATAS EKSPEKTASI' => 'bg-diatas-ekspektasi',
                                    'SESUAI EKSPEKTASI' => 'bg-sesuai-ekspektasi',
                                    'DIBAWAH EKSPEKTASI' => 'bg-dibawah-ekspektasi',
                                    'MENGECEWAKAN' => 'bg-mengecewakan',
                                    default => 'bg-belum-dinilai',
                                };
                            @endphp
                            <tr><td>{{ $month['label'] }}</td><td>{{ $nilai === null ? '0' : number_format($nilai, 2) }}</td><td class="{{ $bgClass }}">{{ $nilai === null ? '' : $predikat }}</td></tr>
                        @endforeach
                        <tr class="summary-row">
                            <td>RATA-RATA</td>
                            <td>{{ $chart['average'] === null ? '0' : number_format($chart['average'], 2) }}</td>
                            <td>{{ $chart['average'] === null ? '' : $chart['average_predicate'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-center">Data laporan tidak ditemukan.</p>
        @endforelse
    @endif

    <div class="footer-info">
        Dicetak pada: {{ $tglNow }}
    </div>

</body>
</html>