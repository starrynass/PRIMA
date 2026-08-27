<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanRekapNilaiController extends Controller
{
    public function index()
    {
        return view('laporan.laporan_rekap_nilai');
    }
}
