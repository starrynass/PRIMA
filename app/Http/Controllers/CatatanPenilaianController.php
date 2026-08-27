<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CatatanPenilaianController extends Controller
{
    public function index()
    {
        return view('penilaian.catatan_penilaian');
    }
}