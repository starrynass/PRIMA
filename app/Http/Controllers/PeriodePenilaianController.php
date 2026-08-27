<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class PeriodePenilaianController extends Controller
{
    public function index()
    {
        return view('penilaian.periode_penilaian');
    }
}
