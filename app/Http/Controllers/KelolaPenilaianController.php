<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class KelolaPenilaianController extends Controller
{
    public function index()
    {
        return view('penilaian.kelola_penilaian');
    }
}
