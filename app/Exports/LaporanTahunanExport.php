<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanTahunanExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $tahun;
    protected $jenisLaporan;
    protected $reportTitle;
    protected $periodText;
    protected $monthColumns;
    protected $unitsByScope;
    protected $unitMatrix;
    protected $scopeSummaries;

    public function __construct(
        $data,
        $tahun,
        $jenisLaporan,
        $reportTitle,
        $periodText,
        $monthColumns,
        $unitsByScope,
        $unitMatrix,
        $scopeSummaries
    ) {
        $this->data = $data;
        $this->tahun = $tahun;
        $this->jenisLaporan = $jenisLaporan;
        $this->reportTitle = $reportTitle;
        $this->periodText = $periodText;
        $this->monthColumns = $monthColumns;
        $this->unitsByScope = $unitsByScope;
        $this->unitMatrix = $unitMatrix;
        $this->scopeSummaries = $scopeSummaries;
    }

    public function view(): View
    {
        return view('laporan.tahunan.excel', [
            'data'  => $this->data,
            'tahun' => $this->tahun,
            'jenisLaporan' => $this->jenisLaporan,
            'reportTitle' => $this->reportTitle,
            'periodText' => $this->periodText,
            'monthColumns' => $this->monthColumns,
            'unitsByScope' => $this->unitsByScope,
            'unitMatrix' => $this->unitMatrix,
            'scopeSummaries' => $this->scopeSummaries,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Tahunan ' . $this->tahun;
    }
}