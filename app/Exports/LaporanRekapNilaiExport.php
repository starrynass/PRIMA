<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanRekapNilaiExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $groupedData;
    protected $periodeText;

    public function __construct($groupedData, $periodeText)
    {
        $this->groupedData = $groupedData;
        $this->periodeText = $periodeText;
    }

    /**
     * Mengarahkan ke view template excel rekap nilai
     */
    public function view(): View
    {
        return view('laporan.RekapNilai.excel', [
            'groupedData' => $this->groupedData,
            'periodeText' => $this->periodeText
        ]);
    }

    /**
     * Nama Sheet di Excel
     */
    public function title(): string
    {
        return 'Rekap Nilai';
    }
}