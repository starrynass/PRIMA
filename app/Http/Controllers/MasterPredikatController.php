<?php

namespace App\Http\Controllers;
use App\Models\MasterPredikatNilai;
use App\Models\MasterSkalaNilai; // Tambahkan Model Skala jika ada

use Illuminate\Http\Request;

class MasterPredikatController extends Controller
{
    public function index()
        {
           $predikatNilai = MasterPredikatNilai::all();
            $skalaNilai    = MasterSkalaNilai::all();
            return view('master.skala-predikat.index', compact('predikatNilai', 'skalaNilai'));
        }

    public function store(Request $request)
{
    $request->validate([
        'predikat_id' => 'required|unique:dp3_master_predikat_nilai,predikat_id',
        'kode'        => 'required',
        'nilai_min'   => 'required|numeric',
        'nilai_max'   => 'required|numeric',
        'predikat'    => 'required',
    ]);

    MasterPredikatNilai::create([
        'predikat_id' => $request->predikat_id,
        'kode'        => $request->kode,
        'nilai_min'   => $request->nilai_min,
        'nilai_max'   => $request->nilai_max,
        'predikat'    => $request->predikat,
        'created_by'  => 'Admin',
    ]);

    // Tambahkan ->with('active_tab', 'predikat')
    return redirect()->back()
        ->with('success', 'Data Predikat Nilai Berhasil Ditambahkan!')
        ->with('active_tab', 'predikat');
}

    public function destroy($id)
    {
        MasterPredikatNilai::where('predikat_id', $id)->delete();
        return redirect()->back()->with('success', 'Data Predikat Berhasil Dihapus!');
    }
}
