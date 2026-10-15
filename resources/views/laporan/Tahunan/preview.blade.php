<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Penilaian Tahunan - {{ $tahun }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .paper { background: white; padding: 30px; margin: 20px auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 1200px; }
        .table-maroon th { background-color: #80243c !important; color: white !important; text-align: center; font-size: 13px; }
        .table-maroon thead th { background-color: #80243c !important; color: white !important; }
        .line-hr { border-bottom: 3px double #80243c; margin: 15px 0; }
        .section-row td { background: #f1f5f9; color: #80243c; font-weight: 700; }
        .summary-row td { background: #f1f5f9; font-weight: 700; }
        .score-predikat { font-weight: 700; text-align: center; }
        .bg-luar-biasa { background: #d1fae5 !important; color: #065f46; }
        .bg-diatas-ekspektasi { background: #dbeafe !important; color: #1e40af; }
        .bg-sesuai-ekspektasi { background: #fef3c7 !important; color: #92400e; }
        .bg-dibawah-ekspektasi { background: #ffedd5 !important; color: #9a3412; }
        .bg-mengecewakan { background: #ffe4e6 !important; color: #9f1239; }
        .bg-belum-dinilai { background: #f3f4f6 !important; color: #6b7280; }
        .chart-card { margin-bottom: 18px; padding: 14px; border: 1px solid #dee2e6; border-radius: 6px; }
        .chart-card h6 { color: #80243c; font-weight: 700; }
        .chart-bars { display: flex; align-items: end; gap: 8px; height: 190px; padding: 10px 6px 0; border-bottom: 1px solid #cbd5e1; }
        .chart-column { display: flex; flex: 1; height: 100%; min-width: 28px; flex-direction: column; justify-content: end; align-items: center; }
        .chart-bar { width: 65%; min-height: 2px; background: #80243c; border-radius: 3px 3px 0 0; }
        .chart-value { color: #475569; font-size: 10px; }
        .chart-label { margin-top: 5px; color: #475569; font-size: 10px; text-align: center; }
        .legend { margin-top: 14px; font-size: 11px; }
        @media print {
            .no-print { display: none !important; }
            .paper { box-shadow: none; margin: 0; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container no-print mt-3 text-end">
    <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Cetak Halaman</button>
</div>

<div class="paper">
    <!-- Kop Surat -->
    <div class="row align-items-center">
        <div class="col-2 text-center">
            <img src="{{ asset('logo/logo_pdam.png') }}" alt="Logo" style="max-width: 85px;">
        </div>
        <div class="col-10">
            <h5 class="fw-bold mb-0 text-uppercase" style="color: #80243c;">{{ $profile['namapdam'] }}</h5>
            <div class="fw-semibold text-secondary">{{ $profile['kota'] }}</div>
            <small class="text-muted">{{ $profile['alamat'] }}</small>
        </div>
    </div>

    <div class="line-hr"></div>

    <div class="text-center my-4">
        <h5 class="fw-bold text-uppercase mb-1">LAPORAN BERKALA PENILAIAN KINERJA PEGAWAI</h5>
        <div class="fw-semibold text-muted">{{ $periodText }}</div>
    </div>

    @if($jenisLaporan === 'tahunan')
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" style="font-size: 13px;">
            <thead class="table-maroon">
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
                        <td class="fw-semibold">{{ $row->pgw_nama ?? '-' }}</td>
                        <td>{{ $row->pgw_jabatan ?? '-' }}</td>
                        <td>{{ $row->pgw_dept_name ?? '-' }}</td>
                        <td class="text-center fw-bold">
                            {{ number_format($row->total_nilai_verifikator ?? $row->total_nilai ?? 0, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">
                                {{ $row->predikat_verifikator ?? $row->predikat ?? '-' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-3 text-muted">Tidak ada data penilaian untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @elseif($jenisLaporan === 'rata_rata')
        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="font-size: 12px;">
                <thead class="table-maroon">
                    <tr>
                        <th rowspan="2">UNIT KERJA</th>
                        <th colspan="{{ count($monthColumns) }}">NILAI RATA-RATA</th>
                    </tr>
                    <tr>
                        @foreach($monthColumns as $month)
                            <th>{{ $month['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitsByScope as $scope => $units)
                        <tr class="section-row"><td colspan="{{ 1 + count($monthColumns) }}">{{ $scope }}</td></tr>
                        @foreach($units as $unit)
                            <tr>
                                <td>{{ $unit['name'] }}</td>
                                @foreach($monthColumns as $month)
                                    <td class="text-center">
                                        {{ $unit['monthly_values'][$month['key']] === null ? '' : number_format($unit['monthly_values'][$month['key']], 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        @if(isset($scopeSummaries[$scope]))
                            <tr class="summary-row">
                                <td>Rata-Rata</td>
                                @foreach($monthColumns as $month)
                                    <td class="text-center">
                                        {{ $scopeSummaries[$scope]['monthly_values'][$month['key']] === null ? '' : number_format($scopeSummaries[$scope]['monthly_values'][$month['key']], 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="{{ 1 + count($monthColumns) }}" class="text-center">Data laporan tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif($jenisLaporan === 'rekapitulasi')
        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="font-size: 11px;">
                <thead class="table-maroon">
                    <tr>
                        <th rowspan="2">LINGKUP KERJA / UNIT KERJA</th>
                        @foreach($monthColumns as $month)
                            <th colspan="2">{{ strtoupper($month['label']) }}</th>
                        @endforeach
                        <th colspan="2">RATA-RATA</th>
                    </tr>
                    <tr>
                        @foreach($monthColumns as $month)
                            <th>NILAI</th><th>PREDIKAT</th>
                        @endforeach
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
                                $avg = $unit['average'];
                                $avgPredikat = match (true) {
                                    $avg === null => '-',
                                    $avg >= 90 => 'LUAR BIASA',
                                    $avg >= 80 => 'DIATAS EKSPEKTASI',
                                    $avg >= 70 => 'SESUAI EKSPEKTASI',
                                    $avg >= 60 => 'DIBAWAH EKSPEKTASI',
                                    $avg > 0 => 'MENGECEWAKAN',
                                    default => 'BELUM DINILAI',
                                };
                                $avgClass = match ($avgPredikat) {
                                    'LUAR BIASA' => 'bg-luar-biasa',
                                    'DIATAS EKSPEKTASI' => 'bg-diatas-ekspektasi',
                                    'SESUAI EKSPEKTASI' => 'bg-sesuai-ekspektasi',
                                    'DIBAWAH EKSPEKTASI' => 'bg-dibawah-ekspektasi',
                                    'MENGECEWAKAN' => 'bg-mengecewakan',
                                    default => 'bg-belum-dinilai',
                                };
                            @endphp
                            <td class="text-center fw-bold">{{ $avg === null ? '' : number_format($avg, 2) }}</td>
                            <td class="score-predikat {{ $avgClass }}">{{ $avg === null ? '' : $avgPredikat }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 1 + 2 * (count($monthColumns) + 1) }}" class="text-center">Data laporan tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="legend">
            <strong>Keterangan warna predikat:</strong>
            <span class="badge bg-luar-biasa">Luar Biasa (&gt;= 90)</span>
            <span class="badge bg-diatas-ekspektasi">Diatas Ekspektasi (&gt;= 80)</span>
            <span class="badge bg-sesuai-ekspektasi">Sesuai Ekspektasi (&gt;= 70)</span>
            <span class="badge bg-dibawah-ekspektasi">Dibawah Ekspektasi (&gt;= 60)</span>
            <span class="badge bg-mengecewakan">Mengecewakan (&lt; 60)</span>
        </div>
    @else
        @php
            $chartGroups = $jenisLaporan === 'grafik_divisi' ? $scopeSummaries : $unitMatrix->keyBy('name')->map(fn ($unit) => [
                'monthly_values' => $unit['monthly_values'],
                'average' => $unit['average'],
                    'average_predicate' => $unit['average_predicate'],
                    'predicates' => $unit['predicates'],
            ])->all();
        @endphp
        @forelse($chartGroups as $groupName => $chart)
            <section class="chart-card">
                <h6>Data {{ $groupName }}</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center" style="font-size: 11px;">
                        <thead class="table-maroon">
                            <tr>
                                @foreach($monthColumns as $month)<th>{{ $month['label'] }}</th>@endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($monthColumns as $month)
                                    <td>{{ $chart['monthly_values'][$month['key']] === null ? '0' : number_format($chart['monthly_values'][$month['key']], 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="chart-bars">
                    @foreach($monthColumns as $month)
                        @php $nilai = $chart['monthly_values'][$month['key']] ?? null; @endphp
                        <div class="chart-column">
                            <span class="chart-value">{{ $nilai === null ? '0' : number_format($nilai, 1) }}</span>
                            <div class="chart-bar" style="height: {{ max(2, min(100, (float) ($nilai ?? 0))) }}%;"></div>
                            <span class="chart-label">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <table class="table table-bordered table-sm mt-3" style="font-size: 11px;">
                    <thead class="table-maroon"><tr><th>PERIODE KERJA (BULAN)</th><th>NILAI</th><th>PREDIKAT</th></tr></thead>
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
                            <tr>
                                <td>{{ $month['label'] }}</td>
                                <td class="text-center">{{ $nilai === null ? '0' : number_format($nilai, 2) }}</td>
                                <td class="score-predikat {{ $bgClass }}">{{ $nilai === null ? '' : $predikat }}</td>
                            </tr>
                        @endforeach
                        <tr class="summary-row">
                            <td>RATA-RATA</td>
                            <td class="text-center">{{ $chart['average'] === null ? '0' : number_format($chart['average'], 2) }}</td>
                            <td class="score-predikat">{{ $chart['average'] === null ? '' : $chart['average_predicate'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        @empty
            <div class="alert alert-info">Data laporan tidak ditemukan.</div>
        @endforelse
    @endif
</div>

</body>
</html>