<?php

namespace App\Http\Controllers;

use App\Models\MasterPredikatNilai;
use App\Models\MasterSkalaNilai;
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
        $validated = $this->validatedData($request);

        MasterPredikatNilai::create(array_merge($validated, [
            'predikat_id' => $request->predikat_id ?? $this->generatePredikatId(),
            'created_by'  => auth()->user()?->name ?? 'Admin',
        ]));

        return redirect()->back()
            ->with('success', 'Data Predikat Nilai Berhasil Ditambahkan!')
            ->with('active_tab', 'predikat');
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validatedData($request);

        MasterPredikatNilai::where('predikat_id', $id)->update($validated);

        return redirect()->back()
            ->with('success', 'Data Predikat Berhasil Diperbarui!')
            ->with('active_tab', 'predikat');
    }

    public function destroy($id)
    {
        MasterPredikatNilai::where('predikat_id', $id)->delete();

        return redirect()->back()
            ->with('success', 'Data Predikat Berhasil Dihapus!')
            ->with('active_tab', 'predikat');
    }

    /**
     * Helper Validasi Input
     */
    protected function validatedData(Request $request)
    {
        return $request->validate([
            'kode'      => 'required|string',
            'nilai_min' => 'required|numeric',
            'nilai_max' => 'required|numeric',
            'predikat'  => 'required|string',
        ]);
    }

    /**
     * Helper Generate Auto ID (PRED001, PRED002, dst)
     */
    protected function generatePredikatId()
    {
        $lastPredikat = MasterPredikatNilai::orderBy('predikat_id', 'desc')->first();

        if ($lastPredikat && preg_match('/PRED(\d+)/', $lastPredikat->predikat_id, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'PRED' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}