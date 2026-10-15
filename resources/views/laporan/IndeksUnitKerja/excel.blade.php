<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">INDEKS PENILAIAN KINERJA STAFF BERDASARKAN UNIT KERJA</td>
        </tr>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR</td>
        </tr>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 11px; text-align: center;">PERIODE: {{ $periodeText }}</td>
        </tr>
        <tr><td colspan="6"></td></tr>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">LINGKUP KERJA</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NO.</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">UNIT KERJA</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">JUMLAH PEGAWAI</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NILAI RATA-RATA STAFF</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">PREDIKAT</th>
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
                    $bgHexAvg = match (strtolower(trim($predikatLingkup))) {
                        'luar biasa' => '#d1fae5',
                        'diatas ekspektasi', 'di atas ekspektasi' => '#dbeafe',
                        'sesuai ekspektasi' => '#fef3c7',
                        'dibawah ekspektasi', 'di bawah ekspektasi' => '#ffedd5',
                        'mengecewakan' => '#ffe4e6',
                        default => '#f3f4f6',
                    };
                @endphp

                @foreach($units as $unit)
                    @php
                        $bgHex = match (strtolower(trim($unit['predikat']))) {
                            'luar biasa' => '#d1fae5',
                            'diatas ekspektasi', 'di atas ekspektasi' => '#dbeafe',
                            'sesuai ekspektasi' => '#fef3c7',
                            'dibawah ekspektasi', 'di bawah ekspektasi' => '#ffedd5',
                            'mengecewakan' => '#ffe4e6',
                            default => '#f3f4f6',
                        };
                    @endphp
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $rowCount }}" style="background-color: #f8fafc; font-weight: bold; text-align: center; vertical-align: middle;">{{ $lingkup }}</td>
                        @endif
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $unit['unit_kerja'] }}</td>
                        <td style="text-align: center;">{{ $unit['jumlah_pegawai'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ number_format($unit['nilai_rata'], 2) }}</td>
                        <td style="text-align: center; background-color: {{ $bgHex }}; font-weight: bold;">{{ $unit['predikat'] }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" style="background-color: #f1f5f9; font-weight: bold; text-align: center;">RATA RATA {{ $lingkup }}</td>
                    <td style="background-color: #f1f5f9; font-weight: bold; text-align: center;">{{ number_format($avgNilaiLingkup, 2) }}</td>
                    <td style="background-color: {{ $bgHexAvg }}; font-weight: bold; text-align: center;">{{ $predikatLingkup }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data indeks unit kerja yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>