<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini;
use App\Models\Employee;
use App\Models\Dp3TransPeriodePenilaian;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            // 1. Ambil data secara aman dengan batasan agar tidak berat
            $totalPegawai = Employee::count();
            
            // Ambil beberapa data pegawai terbaru
            $listEmployee = Employee::select('nup', 'nama', 'alamat', 'sex')
                ->limit(10)
                ->get()
                ->toArray();

            // Ambil data periode penilaian aktif
            $periode = Dp3TransPeriodePenilaian::limit(5)->get()->toArray();

            // 2. Bungkus data untuk konteks AI
            $contextInfo = [
                'total_pegawai' => $totalPegawai,
                'sampel_pegawai' => $listEmployee,
                'periode_penilaian' => $periode
            ];

            $contextJson = json_encode($contextInfo, JSON_PRETTY_PRINT);

            // 3. Susun Prompt untuk Gemini
            $prompt = "Kamu adalah Asisten AI untuk aplikasi manajemen pegawai dan penilaian kinerja PRIMA.\n"
                    . "Berikut adalah data ringkas dari database sistem:\n"
                    . $contextJson . "\n\n"
                    . "Pertanyaan Pengguna: " . $request->message . "\n\n"
                    . "Instruksi: Jawablah pertanyaan pengguna secara ramah, profesional, dan ringkas dalam Bahasa Indonesia berdasarkan data di atas.";

            // 4. Kirim ke Gemini API
            $client = Gemini::client(config('services.gemini.api_key'));
            $result = $client->generativeModel(model: 'gemini-3.8-flash')->generateContent($prompt);

            return response()->json([
                'status' => 'success',
                'reply' => $result->text()
            ]);

        } catch (\Exception $e) {
            // Mengembalikan pesan error aslinya agar terlihat di console/respons
            return response()->json([
                'status' => 'error',
                'reply' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}