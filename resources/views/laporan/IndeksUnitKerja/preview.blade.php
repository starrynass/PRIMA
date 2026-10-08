<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Indeks Penilaian Kinerja Staff Berdasarkan Unit Kerja - {{ $periodeText }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon-primary: #80243c;
            --maroon-hover: #5c182a;
        }

        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .paper { 
            background: white; 
            padding: 30px; 
            margin: 20px auto; 
            border-radius: 8px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.08); 
            max-width: 1300px; 
        }

        .line-hr { 
            border-bottom: 3px double #80243c; 
            margin: 15px 0 25px 0; 
        }

        /* Maroon Table Header */
        .table-maroon-header th { 
            background-color: var(--maroon-primary) !important; 
            color: white !important; 
            text-align: center; 
            vertical-align: middle;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px solid #6b1e32 !important;
            padding: 10px 6px;
        }

        .table-custom {
            font-size: 12px;
        }

        .table-custom td {
            vertical-align: middle;
            padding: 8px 10px;
            border-color: #dee2e6;
        }

        /* Baris Rata-Rata Divisi */
        .row-summary {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            color: #1e293b;
        }

        /* Predikat Color Badges */
        .bg-luar-biasa { background-color: #d1fae5 !important; color: #065f46 !important; font-weight: 700; }
        .bg-diatas-ekspektasi { background-color: #dbeafe !important; color: #1e40af !important; font-weight: 700; }
        .bg-sesuai-ekspektasi { background-color: #fef3c7 !important; color: #92400e !important; font-weight: 700; }
        .bg-dibawah-ekspektasi { background-color: #ffedd5 !important; color: #9a3412 !important; font-weight: 700; }
        .bg-mengecewakan { background-color: #ffe4e6 !important; color: #9f1239 !important; font-weight: 700; }
        .bg-belum-dinilai { background-color: #f3f4f6 !important; color: #6b7280 !important; }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            .paper { box-shadow: none; margin: 0; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container-fluid no-print mt-3 text-end px-4" style="max-width: 1300px;">
    <button onclick="window.print()" class="btn btn-sm text-white px-3 fw-semibold" style="background-color: var(--maroon-primary);">
        <i class="fas fa-print me-1"></i> Cetak Halaman
    </button>
</div>

<div class="paper">
    <!-- Kop Surat -->
    <div class="row align-items-center">
        <div class="col-2 text-center">
            <img src="{{ $profile['logo'] ?? asset('logo/logo_pdam.png') }}" alt="Logo" style="max-width: 85px;">
        </div>
        <div class="col-10">
            <h5 class="fw-bold mb-0 text-uppercase" style="color: var(--maroon-primary); letter-spacing: 0.5px;">
                {{ $profile['namapdam'] }}
            </h5>
            <div class="fw-semibold text-secondary">{{ $profile['kota'] }}</div>
            <small class="text-muted">{{ $profile['alamat'] }}</small>
        </div>
    </div>

    <div class="line-hr"></div>

    <!-- Judul Laporan -->
    <div class="text-center my-4">
        <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">INDEKS PENILAIAN KINERJA STAFF BERDASARKAN UNIT KERJA</h5>
        <div class="fw-bold text-uppercase" style="color: var(--maroon-primary);">
            {{ $profile['namapdam'] }}
        </div>
        <div class="fw-semibold text-secondary mt-1" style="font-size: 13px;">
            PERIODE: {{ $periodeText }}
        </div>
    </div>

    <!-- Tabel Indeks Unit Kerja -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle table-custom">
            <thead class="table-maroon-header">
                <tr>
                    <th width="18%">LINGKUP KERJA</th>
                    <th width="5%">NO.</th>
                    <th width="37%">UNIT KERJA</th>
                    <th width="12%">JUMLAH PEGAWAI</th>
                    <th width="15%">NILAI RATA-RATA STAFF</th>
                    <th width="13%">PREDIKAT</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($groupedData as $lingkup => $units)
                    @php
                        // Hitung rowspan (+1 untuk baris summary Rata-Rata Divisi)
                        $rowCount = $units->count() + 1;
                        $avgNilaiLingkup = $units->avg('nilai_rata');
                        $predikatLingkup = match (true) {
                            $avgNilaiLingkup >= 90 => 'LUAR BIASA',
                            $avgNilaiLingkup >= 80 => 'DIATAS EKSPEKTASI',
                            $avgNilaiLingkup >= 70 => 'SESUAI EKSPEKTASI',
                            $avgNilaiLingkup >= 60 => 'DIBAWAH EKSPEKTASI',
                            $avgNilaiLingkup > 0   => 'MENGECEWAKAN',
                            default                => 'BELUM DINILAI',
                        };
                    @endphp

                    @foreach($units as $u)
                        @php
                            $bgClass = match(strtolower(trim($u['predikat']))) {
                                'luar biasa' => 'bg-luar-biasa',
                                'diatas ekspektasi', 'di atas ekspektasi' => 'bg-diatas-ekspektasi',
                                'sesuai ekspektasi' => 'bg-sesuai-ekspektasi',
                                'dibawah ekspektasi', 'di bawah ekspektasi' => 'bg-dibawah-ekspektasi',
                                'mengecewakan' => 'bg-mengecewakan',
                                default => 'bg-belum-dinilai'
                            };
                        @endphp
                        <tr>
                            {{-- Merge Cell Lingkup Kerja --}}
                            @if($loop->first)
                                <td rowspan="{{ $rowCount }}" class="fw-bold text-center align-middle" style="background-color: #f8fafc; color: #1e293b;">
                                    {{ $lingkup }}
                                </td>
                            @endif

                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $u['unit_kerja'] }}</td>
                            <td class="text-center">{{ $u['jumlah_pegawai'] }}</td>
                            <td class="text-center fw-semibold">{{ number_format($u['nilai_rata'], 2) }}</td>
                            <td class="text-center {{ $bgClass }}">{{ $u['predikat'] }}</td>
                        </tr>
                    @endforeach

                    <!-- Baris Subtotal Rata-Rata Divisi -->
                    <tr class="row-summary">
                        <td colspan="3" class="text-center text-uppercase">
                            Rata Rata {{ $lingkup }}
                        </td>
                        <td class="text-center fw-bold">{{ number_format($avgNilaiLingkup, 2) }}</td>
                        @php
                            $bgClassAvg = match(strtolower(trim($predikatLingkup))) {
                                'luar biasa' => 'bg-luar-biasa',
                                'diatas ekspektasi', 'di atas ekspektasi' => 'bg-diatas-ekspektasi',
                                'sesuai ekspektasi' => 'bg-sesuai-ekspektasi',
                                'dibawah ekspektasi', 'di bawah ekspektasi' => 'bg-dibawah-ekspektasi',
                                'mengecewakan' => 'bg-mengecewakan',
                                default => 'bg-belum-dinilai'
                            };
                        @endphp
                        <td class="text-center {{ $bgClassAvg }}">{{ $predikatLingkup }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada data indeks unit kerja yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>