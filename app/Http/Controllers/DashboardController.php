<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data ringkasan sederhana dari database
        $totalPegawai = DB::table('employee')->where('is_pensiun', 0)->count();
        $totalPeriode = DB::table('dp3_trans_periode_penilaian')->count();
        $totalPenilaian = DB::table('dp3_trans_penilaian')->count();

        return view('dashboard', compact('totalPegawai', 'totalPeriode', 'totalPenilaian'));
    }
}