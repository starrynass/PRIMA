<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14pt;">
                CATATAN / LAPORAN PENILAIAN KINERJA
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold;">
                Periode Tahun: {{ $tahun }}
            </th>
        </tr>
        <tr></tr> <!-- Baris kosong -->
        <tr>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">NO</th>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">NAMA</th>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">JABATAN</th>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">UNIT KERJA</th>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">TOTAL NILAI</th>
            <th style="font-weight: bold; background-color: #ffc000; text-align: center; border: 1px solid #000000;">PREDIKAT</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                
                {{-- Ambil Nama (prioritas relasi pegawai -> kolom langsung) --}}
                <td style="border: 1px solid #000000;">
                    {{ $row->pegawai->pgw_nama ?? $row->pgw_nama ?? $row->nama ?? '-' }}
                </td>
                
                {{-- Ambil Jabatan (prioritas relasi occupation/jabatan -> kolom langsung) --}}
                <td style="border: 1px solid #000000;">
                    {{ $row->occupation->occ_name ?? $row->jabatan->occ_name ?? $row->pgw_jabatan ?? $row->jabatan ?? '-' }}
                </td>
                
                {{-- Ambil Unit Kerja / Penempatan --}}
                <td style="border: 1px solid #000000;">
                    {{ $row->office->off_name ?? $row->penempatan->off_name ?? $row->pgw_off_name ?? $row->unit_kerja ?? '-' }}
                </td>
                
                {{-- Total Nilai --}}
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ number_format((float)($row->total_nilai_verifikator ?? $row->total_nilai ?? 0), 2) }}
                </td>
                
                {{-- Predikat --}}
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $row->predikat_verifikator ?? $row->predikat ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="border: 1px solid #000000; text-align: center;">Data laporan tahunan tidak ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>