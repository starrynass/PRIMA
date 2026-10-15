<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    @php
        $monthColumnCount = count($monthColumns);
        $reportColumnCount = $jenisLaporan === 'rekapitulasi'
            ? 1 + 2 * ($monthColumnCount + 1)
            : 1 + $monthColumnCount;
    @endphp
    <table>
        <tr>
            <td colspan="{{ max(6, $reportColumnCount) }}" style="font-weight: bold; font-size: 14px; text-align: center;">{{ $reportTitle }}</td>
        </tr>
        <tr>
            <td colspan="{{ max(6, $reportColumnCount) }}" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR</td>
        </tr>
        <tr>
            <td colspan="{{ max(6, $reportColumnCount) }}" style="font-weight: bold; font-size: 11px; text-align: center;">{{ $periodText }}</td>
        </tr>
        <tr><td colspan="{{ max(6, $reportColumnCount) }}"></td></tr>
    </table>

    @if($jenisLaporan === 'tahunan')
    <!-- Tabel Data Excel -->
    <table border="1">
        <thead>
            <tr>
                <th width="8" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32; height: 30px;">NO</th>
                <th width="35" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32;">NAMA</th>
                <th width="30" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32;">JABATAN</th>
                <th width="35" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32;">UNIT KERJA</th>
                <th width="18" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32;">TOTAL NILAI</th>
                <th width="22" style="font-weight: bold; background-color: #80243c; color: #ffffff; text-align: center; vertical-align: middle; border: 1px solid #6b1e32;">PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                @php
                    $predikat = $row->predikat_verifikator ?? $row->predikat ?? '-';
                    $bgHex = match(strtolower(trim($predikat))) {
                        'luar biasa' => '#d1fae5',
                        'diatas ekspektasi', 'di atas ekspektasi' => '#dbeafe',
                        'sesuai ekspektasi' => '#fef3c7',
                        'dibawah ekspektasi', 'di bawah ekspektasi' => '#ffedd5',
                        'mengecewakan' => '#ffe4e6',
                        default => '#f3f4f6'
                    };
                @endphp
                <tr>
                    <td style="border: 1px solid #cccccc; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}
                    </td>
                    
                    <td style="border: 1px solid #cccccc; font-weight: bold; vertical-align: middle;">
                        {{ $row->pegawai->pgw_nama ?? $row->pgw_nama ?? $row->nama ?? '-' }}
                    </td>
                    
                    <td style="border: 1px solid #cccccc; vertical-align: middle;">
                        {{ $row->occupation->occ_name ?? $row->jabatan->occ_name ?? $row->pgw_jabatan ?? $row->jabatan ?? '-' }}
                    </td>
                    
                    <td style="border: 1px solid #cccccc; vertical-align: middle;">
                        {{ $row->office->off_name ?? $row->penempatan->off_name ?? $row->pgw_off_name ?? $row->unit_kerja ?? '-' }}
                    </td>
                    
                    <td style="border: 1px solid #cccccc; text-align: center; font-weight: bold; vertical-align: middle;">
                        {{ number_format((float)($row->total_nilai_verifikator ?? $row->total_nilai ?? 0), 2) }}
                    </td>
                    
                    <td style="border: 1px solid #cccccc; text-align: center; background-color: {{ $bgHex }}; font-weight: bold; vertical-align: middle;">
                        {{ $predikat }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="border: 1px solid #cccccc; text-align: center; vertical-align: middle; height: 30px;">
                        Data laporan tahunan tidak ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @elseif($jenisLaporan === 'rata_rata')
        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">UNIT KERJA</th>
                    <th colspan="{{ $monthColumnCount }}" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">NILAI RATA-RATA</th>
                </tr>
                <tr>
                    @foreach($monthColumns as $month)
                        <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">{{ $month['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($unitsByScope as $scope => $units)
                    <tr><td colspan="{{ 1 + $monthColumnCount }}" style="background-color: #f1f5f9; color: #80243c; font-weight: bold;">{{ $scope }}</td></tr>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit['name'] }}</td>
                            @foreach($monthColumns as $month)
                                <td style="text-align: center;">{{ $unit['monthly_values'][$month['key']] === null ? '' : number_format($unit['monthly_values'][$month['key']], 2) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    @if(isset($scopeSummaries[$scope]))
                        <tr>
                            <td style="background-color: #f1f5f9; font-weight: bold;">Rata-Rata</td>
                            @foreach($monthColumns as $month)
                                <td style="background-color: #f1f5f9; font-weight: bold; text-align: center;">
                                    {{ $scopeSummaries[$scope]['monthly_values'][$month['key']] === null ? '' : number_format($scopeSummaries[$scope]['monthly_values'][$month['key']], 2) }}
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="{{ 1 + $monthColumnCount }}" style="text-align: center;">Data laporan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    @elseif($jenisLaporan === 'rekapitulasi')
        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">LINGKUP KERJA / UNIT KERJA</th>
                    @foreach($monthColumns as $month)
                        <th colspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">{{ strtoupper($month['label']) }}</th>
                    @endforeach
                    <th colspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">RATA-RATA</th>
                </tr>
                <tr>
                    @foreach($monthColumns as $month)
                        <th style="background-color: #80243c; color: #ffffff;">NILAI</th>
                        <th style="background-color: #80243c; color: #ffffff;">PREDIKAT</th>
                    @endforeach
                    <th style="background-color: #80243c; color: #ffffff;">NILAI</th>
                    <th style="background-color: #80243c; color: #ffffff;">PREDIKAT</th>
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
                                $bgHex = match ($predikat) {
                                    'LUAR BIASA' => '#d1fae5',
                                    'DIATAS EKSPEKTASI' => '#dbeafe',
                                    'SESUAI EKSPEKTASI' => '#fef3c7',
                                    'DIBAWAH EKSPEKTASI' => '#ffedd5',
                                    'MENGECEWAKAN' => '#ffe4e6',
                                    default => '#f3f4f6',
                                };
                            @endphp
                            <td style="text-align: center;">{{ $nilai === null ? '' : number_format($nilai, 2) }}</td>
                            <td style="background-color: {{ $bgHex }}; font-weight: bold; text-align: center;">{{ $nilai === null ? '' : $predikat }}</td>
                        @endforeach
                        @php
                            $average = $unit['average'];
                            $averagePredicate = $unit['average_predicate'];
                            $averageBg = match ($averagePredicate) {
                                'LUAR BIASA' => '#d1fae5',
                                'DIATAS EKSPEKTASI' => '#dbeafe',
                                'SESUAI EKSPEKTASI' => '#fef3c7',
                                'DIBAWAH EKSPEKTASI' => '#ffedd5',
                                'MENGECEWAKAN' => '#ffe4e6',
                                default => '#f3f4f6',
                            };
                        @endphp
                        <td style="font-weight: bold; text-align: center;">{{ $average === null ? '' : number_format($average, 2) }}</td>
                        <td style="background-color: {{ $averageBg }}; font-weight: bold; text-align: center;">{{ $average === null ? '' : $averagePredicate }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $reportColumnCount }}" style="text-align: center;">Data laporan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
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
            <table>
                <tr><td colspan="{{ $monthColumnCount }}" style="font-weight: bold; color: #80243c;">DATA {{ strtoupper($groupName) }}</td></tr>
            </table>
            <table border="1">
                <thead>
                    <tr>
                        <th style="background-color: #80243c; color: #ffffff;">PERIODE</th>
                        @foreach($monthColumns as $month)
                            <th style="background-color: #80243c; color: #ffffff;">{{ $month['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>NILAI KINERJA</td>
                        @foreach($monthColumns as $month)
                            <td style="text-align: center;">{{ $chart['monthly_values'][$month['key']] === null ? 0 : number_format($chart['monthly_values'][$month['key']], 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>GRAFIK (skala 0-100)</td>
                        @foreach($monthColumns as $month)
                            @php $nilai = $chart['monthly_values'][$month['key']] ?? 0; @endphp
                            <td style="color: #80243c; font-weight: bold; font-family: Consolas, monospace;">
                                {{ str_repeat('#', max(0, min(20, (int) round($nilai / 5)))) }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @empty
            <p>Data laporan tidak ditemukan.</p>
        @endforelse
    @endif

</body>
</html>