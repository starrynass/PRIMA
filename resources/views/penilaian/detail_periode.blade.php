@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 text-muted">Generate Penilaian &raquo; <span class="text-primary fw-bold">Detail Periode {{ $periode->periode_id }}</span></h5>
        </div>
        <a href="{{ route('periode-penilaian.index') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Ringkasan Stat Cards (8 Kartu) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Total Pegawai</small>
                <h3 class="text-primary mb-0 font-weight-bold">{{ $stats['total_pegawai'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Belum Diisi</small>
                <h3 class="text-secondary mb-0 font-weight-bold">{{ $stats['belum_diisi'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Draft</small>
                <h3 class="text-warning mb-0 font-weight-bold">{{ $stats['draft'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Submit</small>
                <h3 class="text-info mb-0 font-weight-bold">{{ $stats['submit'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Verifikasi</small>
                <h3 class="text-success mb-0 font-weight-bold">{{ $stats['verifikasi'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Tanpa Penilai</small>
                <h3 class="text-danger mb-0 font-weight-bold">{{ $stats['tanpa_penilai'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Penilai Manual</small>
                <h3 class="text-primary mb-0 font-weight-bold">{{ $stats['penilai_manual'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm border-0">
                <small class="text-muted fw-bold">Perlu Ditinjau</small>
                <h3 class="text-warning mb-0 font-weight-bold">{{ $stats['perlu_ditinjau'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">Data Pegawai Generate Penilaian</h6>
            <div>
                <button class="btn btn-info btn-sm text-white"><i class="fas fa-user-check"></i> Set Penilai</button>
                <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo"></i> Kembalikan ke Otomatis</button>
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</button>
            </div>
        </div>

        <div class="card-body">
            <!-- Table View -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center" style="font-size: 13px;">
                    <thead class="bg-light">
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>NO</th>
                            <th>NUP</th>
                            <th>NAMA</th>
                            <th>PENEMPATAN</th>
                            <th>JABATAN</th>
                            <th>DEPARTEMEN</th>
                            <th>SUB DEPARTEMEN</th>
                            <th>PENILAI</th>
                            <th>VERIFIKATOR</th>
                            <th>STATUS</th>
                            <th>NILAI</th>
                            <th>PREDIKAT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penilaianList as $index => $row)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ $penilaianList->firstItem() + $index }}</td>
                            <td>{{ $row->nup }}</td>
                            <td class="text-start font-weight-bold">{{ $row->nama }}</td>
                            <td>{{ $row->penempatan ?? '-' }}</td>
                            <td>{{ $row->jabatan ?? '-' }}</td>
                            <td>{{ $row->departemen ?? '-' }}</td>
                            <td>{{ $row->sub_departemen ?? '-' }}</td>
                            <td>
                                @if($row->nama_penilai)
                                    {{ $row->nama_penilai }}
                                @else
                                    <span class="text-danger font-weight-bold">NULL</span>
                                @endif
                            </td>
                            <td>
                                @if($row->nama_verifikator)
                                    {{ $row->nama_verifikator }}
                                @else
                                    <span class="text-danger font-weight-bold">NULL</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $row->status_penilaian }}</span></td>
                            <td>{{ number_format($row->nilai_akhir, 2) }}</td>
                            <td><span class="badge bg-danger">{{ $row->predikat }}</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm text-white" title="Detail"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center py-4 text-muted">Belum ada data pegawai yang di-generate untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $penilaianList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection