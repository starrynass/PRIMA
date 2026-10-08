<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dp3TransPenilaian;
use App\Models\Occupation;
use App\Models\Dp3TransPeriodePenilaian;
use App\Models\MasterTemplate;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanIndeksKompetensiExport;  

class LaporanIndeksKompetensiController extends Controller
{
    public function index()
    {
        $templates = MasterTemplate::orderBy('nama_template')->get();
        $occupations = Occupation::where('is_aktif', 1)
            ->orderBy('occ_name', 'asc')
            ->get();

        $periodes = Dp3TransPeriodePenilaian::orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        
        $unitkerja = UnitKerja::orderBy('kode_unit_kerja')->get();

        return view('laporan.IndeksKompetensi.index', compact('occupations', 'periodes', 'templates', 'unitkerja'));

    }
}
