<?php

namespace App\Http\Controllers;

use App\Models\MasterSkalaNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterSkalaController extends Controller
{
    public function index()
    {
        $skalaNilai = MasterSkalaNilai::orderBy('nilai_angka')->get();
        $predikatNilai = [];

        return view('master.skala-predikat.index', compact('skalaNilai', 'predikatNilai'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['skala_id'] = $this->newId();
        $data['created_by'] = auth()->user()?->name ?? 'system';
        MasterSkalaNilai::create($data);
        return redirect()->route('skala-predikat.index')->with('success', 'Skala nilai berhasil ditambahkan.');
    }

    public function update(Request $request, MasterSkalaNilai $skalaNilai)
    {
        $skalaNilai->update($this->validatedData($request));
        return redirect()->route('skala-predikat.index')->with('success', 'Skala nilai berhasil diperbarui.');
    }

    public function destroy(MasterSkalaNilai $skalaNilai)
    {
        $skalaNilai->delete();
        return redirect()->route('skala-predikat.index')->with('success', 'Skala nilai berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate(['kode_nilai' => ['required', 'string', 'max:255'], 'nama_nilai' => ['required', 'string', 'max:255'], 'nilai_angka' => ['required', 'numeric'], 'deskripsi' => ['nullable', 'string', 'max:255']]);
    }

    private function newId(): string
    {
        do { $id = Str::upper(Str::random(16)); } while (MasterSkalaNilai::whereKey($id)->exists());
        return $id;
    }
}
