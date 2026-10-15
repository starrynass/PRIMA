<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanIndeksKompetensiExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(private array $viewData)
    {
    }

    public function view(): View
    {
        return view('laporan.IndeksKompetensi.excel', $this->viewData);
    }

    public function title(): string
    {
        return 'Indeks Kompetensi';
    }
}
