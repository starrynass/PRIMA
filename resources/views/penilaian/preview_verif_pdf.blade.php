<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FORM HASIL VERIFIKASI PENILAIAN KINERJA PEGAWAI</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 8.5px; color: #1E293B; line-height: 1.25; background-color: #ffffff; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .header-title { font-size: 13px; font-weight: bold; color: #1E293B; }
        .header-sub { font-size: 9px; font-weight: bold; color: #842340; }
        .header-address { font-size: 7.5px; color: #64748B; }
        .line-primary { border-bottom: 2.5px solid #842340; margin-bottom: 10px; }

        .doc-title { text-align: center; font-size: 11.5px; font-weight: bold; color: #1E293B; text-transform: uppercase; }
        .doc-subtitle { text-align: center; font-size: 8.5px; color: #64748B; margin-bottom: 10px; }
        .status-badge { display: inline-block; padding: 2px 8px; background-color: #FFE4E6; color: #9F1239; border: 1px solid #F43F5E; font-weight: bold; border-radius: 4px; font-size: 8px; }

        .table-bordered { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
        .table-bordered td { border: 1px solid #CBD5E1; padding: 4px 6px; vertical-align: middle; word-wrap: break-word; }
        .table-bordered th { background-color: #842340; color: #FFFFFF; font-weight: bold; text-align: center; font-size: 8px; border: 1px solid #5C1329; padding: 4px 6px; vertical-align: middle; }

        .info-table td { padding: 3.5px 6px; font-size: 8px; }
        .info-label { font-weight: bold; background-color: #F8FAFC; color: #475569; width: 15%; }
        .banner-kategori { background-color: #842340; color: #ffffff; font-weight: bold; padding: 4.5px 8px; font-size: 8.5px; border: 1px solid #5C1329; }

        .text-pertanyaan { font-size: 9px; font-weight: bold; color: #1E293B; margin-bottom: 1px; }
        .text-deskripsi { font-size: 7.5px; color: #64748B; }

        .badge-nilai { display: inline-block; padding: 1.5px 5px; color: #ffffff; font-weight: bold; border-radius: 3px; font-size: 8.5px; text-align: center; }
        .bg-a { background-color: #10B981; } .bg-b { background-color: #3B82F6; } .bg-c { background-color: #F59E0B; } .bg-d { background-color: #F43F5E; }

        .catatan-box { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
        .catatan-box td { border: 1px solid #CBD5E1; padding: 4px 6px; font-size: 8px; }

        .ttd-table { width: 100%; border-collapse: collapse; text-align: center; margin-top: 14px; table-layout: fixed; }
        .ttd-table td { width: 33.33%; vertical-align: top; font-size: 8px; }
        .ttd-space { height: 34px; }

        .text-center { text-align: center; } .text-right { text-align: right; } .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="45"></td>
            <td>
                <div class="header-title">PERUMDA Kabupaten Bogor</div>
                <div class="header-sub">Kab.Bogor</div>
                <div class="header-address">Jl. Raya Sukahati No. 12, Sukahati, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16913</div>
            </td>
        </tr>
    </table>
    <div class="line-primary"></div>

    <div class="doc-title">FORM HASIL VERIFIKASI PENILAIAN KINERJA PEGAWAI</div>
    <div class="doc-subtitle">
        Periode {{ $penilaian->periode->kode_periode ?? $penilaian->periode_id }}
        <br><br>
        <span class="status-badge">{{ strtoupper($penilaian->verif_status ?? 'VERIFIED') }}</span>
    </div>

    <table class="table-bordered info-table">
        <tr>
            <td class="info-label">Pegawai</td>
            <td width="35%"><strong>{{ $penilaian->pgw_nama }}</strong></td>
            <td class="info-label">NUP</td>
            <td width="35%">{{ $penilaian->pgw_nup }}</td>
        </tr>
        <tr>
            <td class="info-label">Jabatan</td>
            <td><strong>{{ $penilaian->pgw_jabatan }}</strong></td>
            <td class="info-label">Departemen</td>
            <td>{{ $penilaian->pgw_dept_name }}</td>
        </tr>
        <tr>
            <td class="info-label">Penempatan</td>
            <td><strong>{{ $penilaian->pgw_off_name }}</strong></td>
            <td class="info-label">Template</td>
            <td>{{ $penilaian->template_nama }}</td>
        </tr>
        <tr>
            <td class="info-label">Penilai</td>
            <td><strong>{{ $penilaian->penilai->nama ?? '-' }}</strong></td>
            <td class="info-label">Verifikator</td>
            <td><strong>{{ $penilaian->verifikator->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Verifikasi</td>
            <td>{{ $penilaian->verif_tanggal ? date('Y-m-d', strtotime($penilaian->verif_tanggal)) : '-' }}</td>
            <td class="info-label">Status</td>
            <td><strong>{{ strtoupper($penilaian->verif_status ?? 'VERIFIED') }}</strong></td>
        </tr>
    </table>

    <!-- LOOPING TABEL PER KATEGORI TEMPLATE -->
    @foreach ($kategoriList as $kat)
        <table class="table-bordered">
            <thead>
                <tr>
                    <th colspan="7" class="banner-kategori" style="text-align: left;">
                        {{ strtoupper($kat->nama_kategori) }} 
                        <span style="float: right;">Nilai Kategori: {{ number_format($kat->subtotal, 2) }} (Bobot {{ number_format($kat->bobot_persen, 0) }}%)</span>
                    </th>
                </tr>
                <tr>
                    <th width="5%">NO</th>
                    <th width="35%">PERTANYAAN</th>
                    <th width="10%">BOBOT</th>
                    <th width="11%">NILAI PENILAI</th>
                    <th width="12%">NILAI VERIFIKATOR</th>
                    <th width="15%">CATATAN VERIFIKATOR</th>
                    <th width="12%">NILAI AKHIR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kat->pertanyaans as $index => $item)
                    @php
                        $bgPenilai = match(strtoupper($item->kode_nilai_penilai ?? '')) {
                            'A' => 'bg-a', 'B' => 'bg-b', 'C' => 'bg-c', 'D' => 'bg-d', default => 'bg-b'
                        };
                        $bgVerif = match(strtoupper($item->kode_nilai_verifikator ?? '')) {
                            'A' => 'bg-a', 'B' => 'bg-b', 'C' => 'bg-c', 'D' => 'bg-d', default => 'bg-b'
                        };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="text-pertanyaan">{{ $item->pertanyaan }}</div>
                            @if($item->deskripsi)
                                <div class="text-deskripsi">{{ $item->deskripsi }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->bobot_pertanyaan_persen, 0) }}%</td>
                        <td class="text-center">
                            @if($item->kode_nilai_penilai)
                                <span class="badge-nilai {{ $bgPenilai }}">{{ $item->kode_nilai_penilai }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->kode_nilai_verifikator)
                                <span class="badge-nilai {{ $bgVerif }}">{{ $item->kode_nilai_verifikator }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td style="font-size: 7.5px;">{{ $item->catatan_verifikator ?: '-' }}</td>
                        <td class="text-center fw-bold">{{ number_format($item->nilai_akhir, 2) }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #ffffff;">
                    <td colspan="6" class="fw-bold text-right" style="color: #842340;">Total {{ strtoupper($kat->nama_kategori) }} (Bobot {{ number_format($kat->bobot_persen, 0) }}%)</td>
                    <td class="text-center fw-bold" style="color: #842340;">{{ number_format($kat->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <!-- REKAPITULASI HASIL VERIFIKASI -->
    <table class="table-bordered">
        <thead>
            <tr>
                <th colspan="4" class="banner-kategori" style="text-align: left;">REKAPITULASI HASIL VERIFIKASI</th>
            </tr>
            <tr>
                <th width="5%">NO</th>
                <th width="51%">KATEGORI ASPEK</th>
                <th width="16%">BOBOT</th>
                <th width="28%">NILAI KATEGORI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoriList as $idx => $kat)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ strtoupper($kat->nama_kategori) }}</td>
                    <td class="text-center">{{ number_format($kat->bobot_persen, 0) }}%</td>
                    <td class="text-center fw-bold">{{ number_format($kat->subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #ffffff;">
                <td colspan="2" class="fw-bold text-right" style="color: #842340;">Total Nilai Verifikasi:</td>
                <td class="text-center fw-bold" style="color: #842340;">100%</td>
                <td class="text-center fw-bold" style="color: #842340;">{{ number_format($totalNilai, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold text-right" style="color: #842340;">Predikat Verifikasi:</td>
                <td colspan="2" class="text-center fw-bold" style="color: #842340;">{{ $predikatNama }}</td>
            </tr>
        </tbody>
    </table>

    <table class="catatan-box">
        <tr>
            <td class="info-label" width="18%">Catatan Verifikator</td>
            <td>{{ $penilaian->catatan_verifikator ?: '-' }}</td>
        </tr>
    </table>

    <table class="ttd-table">
        <tr>
            <td>
                <strong>Pegawai Dinilai</strong>
                <div class="ttd-space"></div>
                <u><strong>{{ $penilaian->pgw_nama }}</strong></u>
            </td>
            <td>
                <strong>Penilai</strong>
                <div class="ttd-space"></div>
                <u><strong>{{ $penilaian->penilai->nama ?? '-' }}</strong></u>
            </td>
            <td>
                <strong>Verifikator</strong>
                <div class="ttd-space"></div>
                <u><strong>{{ $penilaian->verifikator->nama ?? '-' }}</strong></u>
            </td>
        </tr>
    </table>

    <div style="font-size: 7px; color: #94A3B8; font-style: italic; margin-top: 8px;">
        Dicetak otomatis oleh sistem pada {{ date('Y-m-d H:i:s') }}
    </div>

</body>
</html>