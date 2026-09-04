<?php

namespace App\Http\Controllers;

use App\Models\MasterPertanyaan;
use App\Models\MasterKategori;
use Illuminate\Http\Request;

class PertanyaanPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:dp3_master_kategori_penilaian,kategori_id'
        ]);

        $pertanyaan = MasterPertanyaan::where('kategori_id', $request->kategori_id)
            ->orderBy('urutan', 'asc')
            ->get();

        return response()->json([
            'status'  => 'success',
            'data'    => $pertanyaan
        ], 200);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kategori_id'  => 'required|exists:dp3_master_kategori_penilaian,kategori_id',
            'pertanyaan'   => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'bobot_persen' => 'required|numeric|min:0.01|max:100',
            'urutan'       => 'required|integer|min:1',
            'jenis'        => 'required|string',
            'status_aktif' => 'required|in:1,0',
        ], [
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'pertanyaan.required'   => 'Pertanyaan wajib diisi.',
            'deskripsi.required'    => 'Deskripsi wajib diisi.',
            'bobot_persen.required' => 'Bobot (%) wajib diisi.',
            'bobot_persen.max'      => 'Bobot persen tidak boleh melebihi 100%.',
            'urutan.required'       => 'Urutan wajib diisi.',
            'jenis.required'        => 'Jenis pertanyaan wajib dipilih.',
            'status_aktif.required' => 'Status aktif wajib dipilih.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['status_aktif'] = ($request->status_aktif === 'Aktif') ? 1 : 0;

        MasterPertanyaan::create(array_merge($validated, [
            'pertanyaan_id' => $this->newId(),
            'created_by'    => auth()->user()?->name ?? 'Admin',
        ]));

        $kategori = MasterKategori::find($request->kategori_id);
        $templateId = $kategori ? $kategori->template_id : $request->template_id;

        return redirect()->route('template-penilaian.index', ['template_id' => $templateId])
            ->with('success', 'Data Pertanyaan Berhasil Ditambahkan!');
    }

    private function newId(): string
    {
        $latest = MasterPertanyaan::max('pertanyaan_id');
        
        if (!$latest) {
            return 'PRT001';
        }

        $number = (int) filter_var($latest, FILTER_SANITIZE_NUMBER_INT) + 1;
        
        return 'PRT' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}