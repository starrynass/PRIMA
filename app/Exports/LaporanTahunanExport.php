<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanTahunanExport implements FromView, ShouldAutoSize
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
}