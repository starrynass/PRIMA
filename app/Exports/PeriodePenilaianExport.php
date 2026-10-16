<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeriodePenilaianExport implements FromCollection, WithHeadings
{
    public function __construct(
        private readonly string $periodeId,
        private readonly array $filters = []
    ) {
    }

    public function collection(): Collection
    {
        $query = DB::table('dp3_trans_penilaian as tp')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id')
            ->leftJoin('employee as verifikator', 'tp.verifikator_id', '=', 'verifikator.pgw_id')
            ->where('tp.periode_id', $this->periodeId)
            ->where('tp.is_active', true)
            ->select(
                'tp.pgw_nup as nup',
                'tp.pgw_nama as nama',
                'tp.pgw_jabatan as jabatan',
                'tp.pgw_dept_name as departemen',
                'tp.pgw_id_subdept_name as sub_departemen',
                'tp.pgw_off_name as penempatan',
                'tp.pgw_kode_satker as satker',
                'tp.pgw_kode_unit_kerja as unit_kerja',
                'penilai.nama as penilai',
                'verifikator.nama as verifikator',
                'tp.status_nilai as status',
                'tp.total_nilai as nilai',
                'tp.predikat'
            );

        if (! empty($this->filters['search'])) {
            $search = trim((string) $this->filters['search']);
            $query->where(function ($builder) use ($search) {
                $builder->where('tp.pgw_nama', 'like', "%{$search}%")
                    ->orWhere('tp.pgw_nup', 'like', "%{$search}%");
            });
        }

        if (! empty($this->filters['status'])) {
            $query->whereRaw('UPPER(COALESCE(tp.status_nilai, "BELUM DIISI")) = ?', [
                strtoupper((string) $this->filters['status']),
            ]);
        }

        foreach ([
            'dept_id' => 'tp.pgw_id_dept',
            'subdept_id' => 'tp.pgw_id_subdept',
            'kode_unit_kerja' => 'tp.pgw_kode_unit_kerja',
        ] as $filter => $column) {
            if (! empty($this->filters[$filter])) {
                $query->where($column, $this->filters[$filter]);
            }
        }

        return $query->orderBy('tp.pgw_nama')->get();
    }

    public function headings(): array
    {
        return [
            'NUP',
            'Nama',
            'Jabatan',
            'Departemen',
            'Sub Departemen',
            'Penempatan',
            'Satker',
            'Unit Kerja',
            'Penilai',
            'Verifikator',
            'Status',
            'Nilai',
            'Predikat',
        ];
    }
}
