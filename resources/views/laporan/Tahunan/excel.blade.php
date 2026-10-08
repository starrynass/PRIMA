<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

    <!-- Judul Header Excel -->
    <table>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">LAPORAN BERKALA PENILAIAN KINERJA SEMUA JABATAN</td>
        </tr>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 12px; text-align: center; color: #80243c;">PERUMDA AIR MINUM TIRTA KAHURIPAN KABUPATEN BOGOR</td>
        </tr>
        <tr>
            <td colspan="6" style="font-weight: bold; font-size: 11px; text-align: center;">PERIODE TAHUN : {{ $tahun }}</td>
        </tr>
        <tr><td colspan="6"></td></tr>
    </table>

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

</body>
</html>