<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    

class LaporanIndexKompetensiController extends Controller
{
    public function index()
    {
        return view('laporan.laporan_index_kompetensi');
    }
}
