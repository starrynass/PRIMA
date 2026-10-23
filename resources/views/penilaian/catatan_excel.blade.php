<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Catatan Penilaian Pegawai</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .title { 
            font-size: 14pt; 
            font-weight: bold; 
            color: #800000; 
            text-align: center; 
        }
        .subtitle { 
            font-size: 10pt; 
            color: #555; 
            text-align: center; 
            margin-bottom: 15px; 
        }
        .table-custom { border-collapse: collapse; width: 100%; font-size: 9pt; }
        .table-custom th {
            background-color: #800000; /* Warna Maroon */
            color: #ffffff;            /* Teks Putih */
            font-weight: bold;
            text-align: center;
            padding: 9px 6px;
            border: 1px solid #500000;
            vertical-align: middle;
        }
        .table-custom td {
            border: 1px solid #dcdcdc;
            padding: 6px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .bg-light { background-color: #fcfcfc; }
    </style>
</head>
<body>
    <table class="table-custom">
        <!-- Judul & Subjudul Dibuat di Tengah Menggunakan colspan="12" -->
        <tr>
            <td colspan="12" class="title" style="border: none; padding-top: 10px;">REKAP CATATAN PENILAIAN PEGAWAI</td>
        </tr>
        <tr>
            <td colspan="12" class="subtitle" style="border: none; padding-bottom: 15px;">
                Periode Penilaian: {{ $periode ? $periode->tahun . ' - ' . str_pad($periode->bulan, 2, '0', STR_PAD_LEFT) : 'Semua Periode' }}
            </td>
        </tr>
        <thead>
            <tr>
                <th>NO</th>
                <th>NUP</th>
                <th>NAMA PEGAWAI</th>
                <th>UNIT KERJA</th>
                <th>STATUS</th>
                <th>NILAI PENILAI</th>
                <th>CATATAN UMUM PENILAI</th>
                <th>NILAI VERIFIKATOR</th>
                <th>CATATAN UMUM VERIFIKATOR</th>
                <th>PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penilaians as $index => $row)
                @php
                    $isVerified = in_array(strtoupper($row->status_nilai ?? ''), ['VERIFIED', 'VERIFIKASI']);
                    $nilaiPenilai = $row->total_nilai ?? 0;
                    $nilaiVerifikator = $row->total_nilai_verifikator ?? $row->total_nilai ?? 0;
                    $predikat = $isVerified ? ($row->predikat_verifikator ?: $row->predikat ?: '-') : ($row->predikat ?: '-');
                    
                    // Merangkum catatan per pertanyaan dari Penilai
                    $catatanPertanyaanPenilai = $row->details->filter(function($d) {
                        return !empty($d->catatan);
                    })->map(function($d) {
                        return "• " . $d->pertanyaan . ": " . $d->catatan;
                    })->implode("\n");

                    // Merangkum catatan per pertanyaan dari Verifikator
                    $catatanPertanyaanVerif = $row->details->filter(function($d) {
                        return !empty($d->verif_catatan);
                    })->map(function($d) {
                        return "• " . $d->pertanyaan . ": " . $d->verif_catatan;
                    })->implode("\n");
                @endphp
                <tr class="{{ $index % 2 == 1 ? 'bg-light' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->pgw_nup ?? '-' }}</td>
                    <td class="text-bold">{{ $row->pgw_nama ?? '-' }}</td>
                    <td>{{ $row->unitKerja->nama_unit_kerja ?? '-' }}</td>
                    <td class="text-center">{{ $row->status_nilai ?? '-' }}</td>
                    <td class="text-center">{{ number_format($nilaiPenilai, 2) }}</td>
                    <td>{{ $row->catatan ?? '-' }}</td>
                    
                    @if($isVerified)
                        <td class="text-center">{{ number_format($nilaiVerifikator, 2) }}</td>
                        <td>{{ $row->catatan_verifikator ?? '-' }}</td>
                    @else
                        <td class="text-center">-</td>
                        <td>-</td>
                    @endif
                    <td class="text-center">{{ $predikat }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data catatan penilaian ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>