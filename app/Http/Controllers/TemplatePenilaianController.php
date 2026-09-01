<?php

namespace App\Http\Controllers;

use App\Models\MasterTemplate;
use App\Models\MasterSkalaNilai;
use App\Models\Occupation; 
use Illuminate\Http\Request;

class TemplatePenilaianController extends Controller
{
    public function index(Request $request) 
    {
        $templates = MasterTemplate::orderBy('nama_template')->get();
        $selectedTemplateId = $request->get('template_id', $templates->first()?->template_id);

        $selectedTemplate = null;
        if ($selectedTemplateId) {
            $selectedTemplate = MasterTemplate::with(['kategoris.pertanyaans'])
                ->find($selectedTemplateId);
        }

        $skalaNilai = MasterSkalaNilai::orderBy('nilai_angka', 'desc')->get();

        // 2. Ambil data occupation yang aktif
        $occupation = Occupation::where('is_aktif', 1)->orderBy('occ_name', 'asc')->get();

        $totalBobotKategori = $selectedTemplate ? $selectedTemplate->kategoris->sum('bobot_persen') : 0;

        // 3. Tambahkan 'occupation' ke compact
        return view('master.template-penilaian.index', compact(
            'templates',
            'selectedTemplate',
            'skalaNilai',
            'totalBobotKategori',
            'occupation'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        // Ubah array ['6','14','17'] menjadi "6,14,17"
        $validated['occ_id'] = implode(',', $validated['occ_id']);
        $validated['template_id'] = $this->newId();
        $validated['created_by']  = auth()->user()?->name ?? 'Admin';

        MasterTemplate::create($validated);

        return redirect()->route('template-penilaian.index')
            ->with('success', 'Template penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, MasterTemplate $template)
    {
        $template->update($this->validatedData($request));
        return redirect()->route('template-penilaian.index')->with('success', 'Template penilaian berhasil diperbarui.');
    }

    public function destroy(MasterTemplate $template)
    {
        $template->delete();
        return redirect()->route('template-penilaian.index')->with('success', 'Template penilaian berhasil dihapus.');
    }

    protected function validatedData(Request $request)
    {
        return $request->validate([
            'nama_template' => 'required|string|max:255',
            'occ_id'        => 'required|array',
            'occ_id.*'      => 'required|string',
            'status_aktif'  => 'required|in:Aktif,Non-Aktif',
        ]);
    }

    protected function newId()
    {
        $lastData = MasterTemplate::orderBy('template_id', 'desc')->first();
        
        if ($lastData && preg_match('/TPL(\d+)/', $lastData->template_id, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'TPL' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}