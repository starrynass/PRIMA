<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class VerifikasiPenilaianController extends Controller
{
    public function index()
    {
        return view('penilaian.verifikasi_penilaian');
    }
}