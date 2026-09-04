<?php

namespace App\Http\Controllers;

use App\Models\MasterKategori;
use Illuminate\Http\Request;

class KategoriPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            // Sesuaikan nama tabel dengan yang ada di validatedData
            'template_id' => 'required|exists:dp3_master_template_penilaian,template_id'
        ]);

        $kategories = MasterKategori::where('template_id', $request->template_id)
            ->with('pertanyaan') // Optional: jika ingin langsung load child relasi pertanyaan
            ->orderBy('urutan', 'asc') // Opsional: Diurutkan sesuai urutan
            ->get();

        return response()->json([
            'status'  => 'success',
            'data'    => $kategories
        ], 200);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'template_id'   => 'required|exists:dp3_master_template_penilaian,template_id',
            'kode' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-Z0-9_-]+$/', // Hanya huruf, angka, - dan _
            ],
            'nama' => 'required|string|max:255',
            'bobot_persen'  => 'required|numeric|min:1|max:100',
            'urutan'        => 'required|integer|min:1',
        ], [
            // Custom pesan error (opsional)
            'template_id.required' => 'Template penilaian harus dipilih.',
            'kode.required' => 'Kode kategori wajib diisi.',
            'kode.regex'   => 'Kode Kategori hanya boleh berisi huruf, angka, tanda - dan _ tanpa spasi/simbol.',
            'nama.required' => 'Nama kategori wajib diisi.',
            'bobot_persen.required' => 'Bobot (%) wajib diisi.',
            'bobot_persen.max'      => 'Bobot persen tidak boleh melebihi 100%.',
            'urutan.required'       => 'Urutan wajib diisi.',
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        MasterKategori::create(array_merge($validated, [
            'kategori_id' => $this->newId(),
            'created_by'  => auth()->user()?->name ?? 'Admin',
        ]));

        // Tambahkan ['template_id' => $request->template_id] agar kembali ke template yang aktif
        return redirect()->route('template-penilaian.index', ['template_id' => $request->template_id])
            ->with('success', 'Data Kategori Berhasil Ditambahkan!');
    }

    public function update(Request $request, MasterKategori $kategori)
    {
        $kategori->update($this->validatedData($request));
        return redirect()->route('template-penilaian.index')->with('success', 'Kategori penilaian berhasil diperbarui.');
    }

    public function destroy(MasterKategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('template-penilaian.index')->with('success', 'Kategori penilaian berhasil dihapus.');
    }

     private function newId(): string
    {
        $latest = MasterKategori::max('kategori_id');
        
        if (!$latest) {
            return 'KAT001';
        }

        // Mengambil angka di akhir ID lalu ditambah 1
        $number = (int) filter_var($latest, FILTER_SANITIZE_NUMBER_INT) + 1;
        
        return 'KAT' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}