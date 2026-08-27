<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanIndexUnitKerjaController extends Controller
{
    public function index()
    {
        return view('laporan.laporan_index_unit_kerja');
    }
}
