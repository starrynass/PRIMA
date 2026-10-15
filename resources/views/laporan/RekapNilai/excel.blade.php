<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    @php $columnCount = 6 + max(1, $questions->count()); @endphp
    @php
        $questionGroups = $questions->groupBy('group');
        if ($questionGroups->isEmpty()) {
            $questionGroups = collect(['GENERAL' => collect()]);
        }
    @endphp
    <!-- Judul Header Excel -->
    <table>
        <tr>
            <td colspan="{{ $columnCount }}" style="font-weight: bold; font-size: 14px; text-align: center;">REKAPITULASI PENILAIAN KINERJA STAFF</td>
        </tr>
        <tr>
            <td colspan="{{ $columnCount }}" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR</td>
        </tr>
        <tr>
            <td colspan="{{ $columnCount }}" style="font-weight: bold; font-size: 11px; text-align: center;">PERIODE: {{ $periodeText }}</td>
        </tr>
        <tr><td colspan="{{ $columnCount }}"></td></tr>
    </table>

    <!-- Tabel Data Excel -->
    <table border="1">
        <thead>
            <tr>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NO.</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NAMA</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">JABATAN</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">UNIT KERJA</th>
                @foreach($questionGroups as $group => $groupQuestions)
                    <th colspan="{{ max(1, $groupQuestions->count()) }}" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">{{ $group }}</th>
                @endforeach
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">TOTAL NILAI</th>
                <th rowspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">PREDIKAT</th>
            </tr>
            <tr>
                @forelse($questions as $question)
                    <th title="{{ $question['id'] }}" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">{{ $question['code'] }}</th>
                @empty
                    <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">-</th>
                @endforelse
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($groupedData as $unitName => $items)
                <tr>
                    <td colspan="{{ $columnCount }}" style="background-color: #f1f5f9; font-weight: bold;">UNIT KERJA: {{ $unitName }}</td>
                </tr>

                @foreach($items as $row)
                    @php
                        $predikat = $row['predikat'] ?? '-';
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
                        <td style="font-weight: bold;">{{ $row['nama'] ?? '-' }}</td>
                        <td>{{ $row['jabatan'] ?? '-' }}</td>
                        <td>{{ $unitName }}</td>
                        
                        @forelse($questions as $question)
                            <td style="text-align: center;">{{ $row['nilai_pertanyaan'][$question['id']] ?? '-' }}</td>
                        @empty
                            <td style="text-align: center;">-</td>
                        @endforelse

                        <td style="text-align: center; font-weight: bold;">
                            {{ number_format((float)($row['total_nilai'] ?? 0), 2) }}
                        </td>

                        <td style="text-align: center; background-color: {{ $bgHex }}; font-weight: bold;">
                            {{ $predikat }}
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="{{ $columnCount }}" style="text-align: center;">Tidak ada data penilaian yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>