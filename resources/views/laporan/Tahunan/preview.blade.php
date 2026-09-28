<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Penilaian Tahunan - {{ $tahun }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .paper { background: white; padding: 30px; margin: 20px auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 1200px; }
        .table-maroon th { background-color: #80243c !important; color: white !important; text-align: center; font-size: 13px; }
        .line-hr { border-bottom: 3px double #80243c; margin: 15px 0; }
        @media print {
            .no-print { display: none !important; }
            .paper { box-shadow: none; margin: 0; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container no-print mt-3 text-end">
    <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Cetak Halaman</button>
</div>

<div class="paper">
    <!-- Kop Surat -->
    <div class="row align-items-center">
        <div class="col-2 text-center">
            <img src="{{ asset('logo/logo_pdam.png') }}" alt="Logo" style="max-width: 85px;">
        </div>
        <div class="col-10">
            <h5 class="fw-bold mb-0 text-uppercase" style="color: #80243c;">{{ $profile['namapdam'] }}</h5>
            <div class="fw-semibold text-secondary">{{ $profile['kota'] }}</div>
            <small class="text-muted">{{ $profile['alamat'] }}</small>
        </div>
    </div>

    <div class="line-hr"></div>

    <div class="text-center my-4">
        <h5 class="fw-bold text-uppercase mb-1">LAPORAN BERKALA PENILAIAN KINERJA PEGAWAI</h5>
        <div class="fw-semibold text-muted">PERIODE TAHUN {{ $tahun }}</div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" style="font-size: 13px;">
            <thead class="table-maroon">
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
                        <td class="fw-semibold">{{ $row->pgw_nama ?? '-' }}</td>
                        <td>{{ $row->pgw_jabatan ?? '-' }}</td>
                        <td>{{ $row->pgw_dept_name ?? '-' }}</td>
                        <td class="text-center fw-bold">
                            {{ number_format($row->total_nilai_verifikator ?? $row->total_nilai ?? 0, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">
                                {{ $row->predikat_verifikator ?? $row->predikat ?? '-' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-3 text-muted">Tidak ada data penilaian untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>