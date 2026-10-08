<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanIndeksUnitKerjaExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $groupedData;
    protected $periodeText;

    public function __construct($groupedData, $periodeText)
    {
        $this->groupedData = $groupedData;
        $this->periodeText = $periodeText;
    }

    public function view(): View
    {
        return view('laporan.IndeksUnitKerja.excel', [
            'groupedData' => $this->groupedData,
            'periodeText' => $this->periodeText
        ]);
    }

    public function title(): string
    {
        return 'Indeks Unit Kerja';
    }
}