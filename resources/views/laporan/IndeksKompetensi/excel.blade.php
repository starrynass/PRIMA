<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="{{ 2 + $questions->count() }}" style="font-weight: bold; font-size: 14px; text-align: center;">
                INDEKS PENILAIAN KINERJA STAFF BERDASARKAN KOMPETENSI
            </td>
        </tr>
        <tr>
            <td colspan="{{ 2 + $questions->count() }}" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">
                {{ $profile['namapdam'] }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ 2 + $questions->count() }}" style="font-weight: bold; font-size: 11px; text-align: center;">
                PERIODE {{ $periodeText }} - {{ $unitKerjaText }}
            </td>
        </tr>
        <tr><td colspan="{{ 2 + $questions->count() }}"></td></tr>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th rowspan="3" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">NO.</th>
                <th rowspan="3" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">LINGKUP KERJA</th>
                @foreach($questions->groupBy('group') as $group => $groupQuestions)
                    <th colspan="{{ $groupQuestions->count() }}" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle;">{{ $group }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($questions as $question)
                    <th style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">{{ $question['code'] }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($questions as $question)
                    <th style="background-color: #80243c; color: #ffffff; text-align: center;">{{ $question['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($reportRows as $row)
                <tr>
                    <td style="text-align: center;">{{ $row['no'] }}</td>
                    <td>{{ $row['lingkup'] }}</td>
                    @foreach($questions as $question)
                        @php
                            $nilai = $row['values'][$question['id']];
                            $bgHex = match (true) {
                                $nilai === null => '#f3f4f6',
                                $nilai >= 90 => '#c6efce',
                                $nilai >= 80 => '#bdd7ee',
                                $nilai >= 70 => '#fff2cc',
                                $nilai >= 60 => '#fce4d6',
                                default => '#f4cccc',
                            };
                        @endphp
                        <td style="background-color: {{ $bgHex }}; text-align: center;">
                            {{ $nilai === null ? '-' : number_format($nilai, 1) }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 2 + $questions->count() }}" style="text-align: center;">Tidak ada data pegawai untuk filter yang dipilih.</td>
                </tr>
            @endforelse

            @if($reportRows->isNotEmpty())
                <tr>
                    <td colspan="2" style="background-color: #c9c9c9; font-weight: bold; text-align: center;">INDEKS KESELURUHAN</td>
                    @foreach($questions as $question)
                        @php $nilai = $overallValues[$question['id']]; @endphp
                        <td style="background-color: #c9c9c9; font-weight: bold; text-align: center;">
                            {{ $nilai === null ? '-' : number_format($nilai, 2) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="2" style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">PREDIKAT</td>
                    @foreach($questions as $question)
                        <td style="background-color: #80243c; color: #ffffff; font-weight: bold; text-align: center;">
                            {{ $predikatNilai[$question['id']] }}
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <table>
        <tr><td colspan="{{ 2 + $questions->count() }}"></td></tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Keterangan warna predikat:</td>
            <td style="background-color: #c6efce;">Luar Biasa (&gt;= 90)</td>
            <td style="background-color: #bdd7ee;">Diatas Ekspektasi (&gt;= 80)</td>
            <td style="background-color: #fff2cc;">Sesuai Ekspektasi (&gt;= 70)</td>
            <td style="background-color: #fce4d6;">Dibawah Ekspektasi (&gt;= 60)</td>
            <td style="background-color: #f4cccc;">Mengecewakan (&lt; 60)</td>
            <td style="background-color: #f3f4f6;">Kosong = belum dinilai</td>
        </tr>
    </table>
</body>
</html>