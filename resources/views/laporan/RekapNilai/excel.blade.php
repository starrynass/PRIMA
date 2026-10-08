<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    <!-- Judul Header Excel -->
    <table>
        <tr>
            <td colspan="14" style="font-weight: bold; font-size: 14px; text-align: center;">REKAPITULASI PENILAIAN KINERJA STAFF</td>
        </tr>
        <tr>
            <td colspan="14" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR</td>
        </tr>
        <tr>
            <td colspan="14" style="font-weight: bold; font-size: 11px; text-align: center;">PERIODE: {{ $periodeText }}</td>
        </tr>
        <tr><td colspan="14"></td></tr>
    </table>

    <!-- Tabel Data Excel -->
    <table border="1">
        <thead>
            <tr>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NO.</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NAMA</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">JABATAN</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">UNIT KERJA</th>
                <th colspan="6" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">GENERAL</th>
                <th colspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">SPECIALITY</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">TOTAL NILAI</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">PREDIKAT</th>
            </tr>
            <tr>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G1</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G2</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G3</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G4</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G5</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">G6</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">S1</th>
                <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">S2</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($groupedData as $unitName => $items)
                <tr>
                    <td colspan="14" style="background-color: #f1f5f9; font-weight: bold;">UNIT KERJA: {{ $unitName }}</td>
                </tr>

                @foreach($items as $row)
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
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td style="font-weight: bold;">{{ $row->pgw_nama ?? $row->pegawai->pgw_nama ?? '-' }}</td>
                        <td>{{ $row->occupation->occ_name ?? $row->pgw_jabatan ?? '-' }}</td>
                        <td>{{ $unitName }}</td>
                        
                        <td style="text-align: center;">{{ $row->g1 ?? 'B' }}</td>
                        <td style="text-align: center;">{{ $row->g2 ?? 'B' }}</td>
                        <td style="text-align: center;">{{ $row->g3 ?? 'B' }}</td>
                        <td style="text-align: center;">{{ $row->g4 ?? 'C' }}</td>
                        <td style="text-align: center;">{{ $row->g5 ?? 'B' }}</td>
                        <td style="text-align: center;">{{ $row->g6 ?? 'C' }}</td>
                        
                        <td style="text-align: center;">{{ $row->s1 ?? 'B' }}</td>
                        <td style="text-align: center;">{{ $row->s2 ?? 'C' }}</td>

                        <td style="text-align: center; font-weight: bold;">
                            {{ number_format((float)($row->total_nilai_verifikator ?? $row->total_nilai ?? 0), 2) }}
                        </td>

                        <td style="text-align: center; background-color: {{ $bgHex }}; font-weight: bold;">
                            {{ $predikat }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="14" style="text-align: center;">Tidak ada data penilaian yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>