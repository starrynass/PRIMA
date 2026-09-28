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
        <h3>LAPORAN BERKALA PENILAIAN KINERJA PEGAWAI</h3>
        <p>PERIODE TAHUN {{ $tahun }}</p>
    </div>

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

    <div class="footer-info">
        Dicetak pada: {{ $tglNow }}
    </div>

</body>
</html>