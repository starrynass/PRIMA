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

    public function __construct($data, $tahun)
    {
        $this->data = $data;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('laporan.tahunan.excel', [
            'data'  => $this->data,
            'tahun' => $this->tahun
        ]);
    }

    public function title(): string
    {
        return 'Laporan Tahunan ' . $this->tahun;
    }
}