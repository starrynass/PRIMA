<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gemini;

class AiChatController extends Controller
{
    private array $supportedModels = [
        'gemini-3.5-flash-lite',
        'gemini-3-flash-preview',
        'gemini-flash-latest',
        'gemini-3.5-flash'
    ];

    public function chat(Request $request)
    {
        set_time_limit(120);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = trim($request->message);

        // Deteksi sapaan / greeting sederhana agar respon ramah, wajar, dan tidak berlebihan
        $simpleGreetings = [
            'halo', 'halo!', 'halo prima', 'hai', 'hai!', 'hei', 'hello', 'hi', 'selamat pagi', 
            'selamat siang', 'selamat sore', 'selamat malam', 'assalamualaikum', 'assalamu\'alaikum',
            'tes', 'test', 'ping', 'p'
        ];

        $cleanedMsg = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $userMessage));
        if (in_array($cleanedMsg, $simpleGreetings)) {
            $greetingsReply = "Halo! Senang bisa menyapa Anda. Ada yang bisa saya bantu terkait data pegawai, status penilaian DP3, atau informasi periode kerja hari ini?";
            return response()->json([
                'status' => 'success',
                'reply' => $greetingsReply
            ]);
        }

        try {
            $client = Gemini::client(config('services.gemini.api_key'));

            // 1. Eksekusi Text-to-SQL untuk menjawab data spesifik/apapun
            $dynamicData = $this->queryDatabaseForQuestion($client, $userMessage);

            // 2. Data ringkasan global aplikasi PRIMA (termasuk status penilaian per periode)
            $globalSummary = $this->getGlobalApplicationSummary();

            // 3. Susun Prompt final untuk menjawab pertanyaan pengguna
            $contextText = "=== RINGKASAN SISTEM & STATISTIK PERIODE PENILAIAN PRIMA ===\n" . json_encode($globalSummary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

            if (!empty($dynamicData)) {
                $contextText .= "=== HASIL QUERY DATA DARI DATABASE (TERKAIT PERTANYAAN) ===\n"
                              . "Query Dijalankan: " . ($dynamicData['sql'] ?? '-') . "\n"
                              . "Hasil Data: " . json_encode($dynamicData['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
            }

            $finalPrompt = "Kamu adalah Asisten AI Resmi PRIMA (Sistem Informasi Manajemen Pegawai & Penilaian Prestasi Kerja / DP3).\n"
                         . "Tugasmu adalah menjawab pertanyaan pengguna secara proporsional, ramah, profesional, cerdas, logis, dan sesuai dengan bobot pertanyaan dalam Bahasa Indonesia.\n\n"
                         . $contextText
                         . "Pertanyaan Pengguna: \"{$userMessage}\"\n\n"
                         . "Petunjuk Format dan Logika Jawaban:\n"
                         . "1. DILARANG KERAS MENGGUNAKAN TANDA BINTANG (karakter *) dalam bentuk apapun! Jangan gunakan markdown bold (**kata**), italic (*kata*), atau bullet list menggunakan tanda bintang (* item). Gunakan format teks biasa, atau gunakan tanda strip (-) atau angka (1., 2.) untuk daftar/list.\n"
                         . "2. SESUAIKAN JAWABAN DENGAN KONDISI & BOBOT PERTANYAAN (PROPORSIONAL):\n"
                         . "   - Jika pengguna HANYA MENYAPA atau mengobrol santai (misal: salam, terima kasih, apa kabar), jawablah secara singkat, ramah, dan wajar layaknya percakapan biasa. JANGAN langsung memuntahkan seluruh data ringkasan sistem jika tidak ditanyakan!\n"
                         . "   - Jika pengguna menanyakan data penilaian atau pegawai secara umum (misalnya 'siapa saja pegawai yang sudah terverifikasi', 'status penilaian pegawai', 'nilai pegawai'), barulah jabarkan data secara komprehensif per periode penilaian (misal: periode Februari 2026, Maret 2026) lengkap dengan nama pegawai, jabatan, nilai, dan statusnya.\n"
                         . "3. Pahami logika data status penilaian:\n"
                         . "   - Terverifikasi / Verified merujuk pada pegawai dengan status_verifikator = 'VERIFIED' atau status_nilai = 'VERIFIED'.\n"
                         . "   - Pegawai yang berstatus 'Belum Diisi' atau total_nilai = 0 adalah pegawai yang BELUM DINILAI, BUKAN pegawai dengan nilai terendah.\n"
                         . "   - Jika pengguna menanyakan nilai terendah/tertinggi, urutkan pegawai yang SUDAH dinilai (status_nilai != 'Belum Diisi' dan total_nilai > 0), tampilkan beberapa nama (top 3-5) secara jelas.\n"
                         . "4. Sajikan data secara faktual sesuai hasil query dan ringkasan di atas. Jangan mengarang nama orang atau angka.\n"
                         . "5. Pastikan gaya penyampaian ramah, wajar, terstruktur rapi, dan mudah dibaca.";

            $reply = $this->callGeminiWithFallback($client, $finalPrompt);

            // Pembersihan ekstra: hapus semua tanda bintang (*) dari jawaban agar teks selalu bersih
            $cleanedReply = str_replace('*', '', $reply);

            return response()->json([
                'status' => 'success',
                'reply' => trim($cleanedReply)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'reply' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memanggil Gemini API dengan fallback model jika ada yang sibuk / error / kena kuota.
     */
    private function callGeminiWithFallback($client, string $prompt): string
    {
        $lastException = null;

        foreach ($this->supportedModels as $modelName) {
            try {
                $result = $client->generativeModel(model: $modelName)->generateContent($prompt);
                
                // Ambil teks dari result
                $text = '';
                try {
                    $text = $result->text();
                } catch (\Exception $eText) {
                    // Fallback jika .text() melempar exception saat kandidat multi-part
                    if (!empty($result->candidates[0]->content->parts[0]->text)) {
                        $text = $result->candidates[0]->content->parts[0]->text;
                    }
                }

                if (!empty($text)) {
                    return $text;
                }
            } catch (\Exception $ex) {
                $lastException = $ex;
                continue;
            }
        }

        throw $lastException ?? new \Exception('Tidak ada model AI yang dapat merespons saat ini.');
    }

    /**
     * Menghasilkan query SQL SELECT otomatis berdasarkan pertanyaan pengguna lalu mengeksekusinya.
     */
    private function queryDatabaseForQuestion($client, string $question): ?array
    {
        $schemaPrompt = "Kamu adalah pakar MySQL untuk aplikasi PRIMA (Penilaian DP3 & Kepegawaian).\n"
            . "Tugas: Buat TEPAT SATU query SQL `SELECT` MySQL (READ-ONLY) yang paling tepat untuk menjawab pertanyaan pengguna.\n\n"
            . "Skema Tabel Tersedia:\n"
            . "- employee (pgw_id, pin, nup, nama, sex, tempat, tgl_lahir, tgl_masuk, alamat, nohp, gol_darah, kode_unit_kerja, dept_id, subdept_id, occ_id, off_id, is_pensiun, is_hapus)\n"
            . "- department (dept_id, dept_code, dept_name)\n"
            . "- department_sub (subdept_id, subdept_name, dept_id)\n"
            . "- occupation (occ_id, occ_name, level)\n"
            . "- office (off_id, off_code, off_name, off_addr)\n"
            . "- unit_kerja (kode_unit_kerja, nama_unit_kerja)\n"
            . "- dp3_trans_periode_penilaian (periode_id, bulan, tahun, tanggal_mulai, tanggal_deadline, status)\n"
            . "- dp3_trans_penilaian (penilaian_id, kode, periode_id, pegawai_id, pgw_nup, pgw_nama, pgw_jabatan, pgw_dept_name, pgw_off_name, pgw_kode_unit_kerja, penilai_id, verifikator_id, total_nilai, total_nilai_verifikator, predikat, predikat_verifikator, status_nilai, status_verifikator, status_verifitor, is_active)\n"
            . "- dp3_trans_penilaian_detail (penilaian_detail_id, penilaian_id, pertanyaan, nilai_angka, nilai_akhir, catatan, verif_nilai_angka, verif_alasan_koreksi)\n"
            . "- dp3_master_template_penilaian (template_id, nama_template, status_aktif)\n"
            . "- dp3_master_kategori_penilaian (kategori_id, template_id, nama, bobot_persen)\n"
            . "- dp3_master_predikat_nilai (predikat_id, kode, nilai_min, nilai_max, predikat)\n"
            . "- dp3_master_skala_nilai (skala_id, kode_nilai, nama_nilai, nilai_angka)\n\n"
            . "Aturan Wajib dan Domain Knowledge:\n"
            . "1. Query HANYA boleh SELECT (tidak boleh INSERT, UPDATE, DELETE, DROP, dsb).\n"
            . "2. Batasi hasil maksimal dengan `LIMIT 50` jika query mengembalikan banyak baris.\n"
            . "3. Status Verifikasi dan Penilaian pada `dp3_trans_penilaian`:\n"
            . "   - Di database, status verifikasi disimpan dalam kolom `status_verifikator` dan `status_verifitor` dengan nilai: 'VERIFIED', 'BELUM VERIFIKASI', 'REVISI', 'KOREKSI'.\n"
            . "   - Kolom `status_nilai` memiliki nilai: 'Belum Diisi', 'DRAFT', 'SUBMIT', 'VERIFIED', 'DIKEMBALIKAN'.\n"
            . "   - Untuk mencari pegawai yang 'terverifikasi', 'sudah verifikasi', 'verified', gunakan kondisi:\n"
            . "     (UPPER(status_verifikator) = 'VERIFIED' OR UPPER(status_verifitor) = 'VERIFIED' OR UPPER(status_nilai) = 'VERIFIED')\n"
            . "   - Untuk mencari pegawai yang 'sudah submit' / 'menunggu verifikasi':\n"
            . "     (UPPER(status_nilai) = 'SUBMIT' AND UPPER(COALESCE(status_verifikator, '')) != 'VERIFIED')\n"
            . "   - Untuk mencari pegawai yang 'draft': UPPER(status_nilai) = 'DRAFT'.\n"
            . "   - Untuk mencari pegawai yang 'belum dinilai' / 'belum diisi': UPPER(status_nilai) = 'BELUM DIISI' OR total_nilai = 0.\n"
            . "4. Pengelompokan & Konteks Periode:\n"
            . "   - Jika pengguna TIDAK menyebutkan periode tertentu (misalnya 'siapa saja yang sudah terverifikasi'), JANGAN batasi ke satu periode saja! Ambil data dari SEMUA periode dan sertakan `periode_id`, atau JOIN ke `dp3_trans_periode_penilaian` untuk menampilkan kolom `bulan` dan `tahun` agar jawaban bisa dikelompokkan per periode.\n"
            . "   - Sertakan kolom penting: `p.pgw_nama`, `p.pgw_jabatan`, `p.pgw_dept_name`, `p.total_nilai`, `p.total_nilai_verifikator`, `p.predikat_verifikator`, `p.status_verifikator`, `pr.bulan`, `pr.tahun`.\n"
            . "5. Nilai Terendah / Tertinggi:\n"
            . "   - Filter selalu: `WHERE p.status_nilai NOT IN ('Belum Diisi') AND p.total_nilai > 0`.\n"
            . "   - Jangan hanya `LIMIT 1`, gunakan `LIMIT 5` agar informatif.\n"
            . "6. Pencarian teks: gunakan `LIKE '%keyword%'`.\n"
            . "7. Jika pertanyaan murni sapaan umum seperti 'halo', 'hai', 'siapa kamu', 'terima kasih', balas dengan string: NONE\n"
            . "8. Format keluaran HANYA query SQL di dalam blok ```sql ... ``` atau kata NONE.\n\n"
            . "Pertanyaan Pengguna: \"{$question}\"";

        try {
            $sqlResponse = $this->callGeminiWithFallback($client, $schemaPrompt);
            if (stripos($sqlResponse, 'NONE') !== false) {
                return null;
            }

            // Ekstrak query dari markdown code block ```sql ... ```
            if (preg_match('/```(?:sql)?\s*([\s\S]*?)\s*```/i', $sqlResponse, $matches)) {
                $sql = trim($matches[1]);
            } else {
                $sql = trim($sqlResponse);
            }

            // Validasi Keamanan: Pastikan hanya SELECT murni
            if (empty($sql) || !preg_match('/^\s*SELECT\b/i', $sql)) {
                return null;
            }

            $dangerousKeywords = ['insert', 'update', 'delete', 'drop', 'truncate', 'alter', 'create', 'grant', 'revoke', 'outfile', 'dumpfile'];
            foreach ($dangerousKeywords as $kw) {
                if (preg_match('/\b' . $kw . '\b/i', $sql)) {
                    return null;
                }
            }

            $queryResult = DB::select($sql);

            return [
                'sql' => $sql,
                'data' => array_slice($queryResult, 0, 50)
            ];

        } catch (\Exception $e) {
            // Jika parsing query gagal, lanjutkan dengan data ringkasan umum
            return null;
        }
    }

    /**
     * Mengambil ringkasan data umum sistem aplikasi PRIMA beserta statistik penilaian per periode.
     */
    private function getGlobalApplicationSummary(): array
    {
        try {
            $totalPegawai = DB::table('employee')->count();
            $totalUnit = DB::table('unit_kerja')->count();
            $totalDepartemen = DB::table('department')->count();
            $totalJabatan = DB::table('occupation')->count();

            $periodeList = DB::table('dp3_trans_periode_penilaian')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(5)
                ->get(['periode_id', 'bulan', 'tahun', 'tanggal_mulai', 'tanggal_deadline', 'status']);

            $periodeStats = [];
            foreach ($periodeList as $p) {
                $stats = DB::table('dp3_trans_penilaian')
                    ->where('periode_id', $p->periode_id)
                    ->selectRaw("
                        COUNT(*) as total_peserta,
                        SUM(CASE WHEN UPPER(COALESCE(status_verifikator, status_verifitor, '')) = 'VERIFIED' OR UPPER(status_nilai) = 'VERIFIED' THEN 1 ELSE 0 END) as total_verified,
                        SUM(CASE WHEN UPPER(status_nilai) = 'SUBMIT' AND UPPER(COALESCE(status_verifikator, status_verifitor, '')) != 'VERIFIED' THEN 1 ELSE 0 END) as total_submit,
                        SUM(CASE WHEN UPPER(status_nilai) = 'DRAFT' THEN 1 ELSE 0 END) as total_draft,
                        SUM(CASE WHEN UPPER(status_nilai) = 'BELUM DIISI' OR total_nilai = 0 THEN 1 ELSE 0 END) as total_belum_diisi
                    ")->first();

                $periodeStats[] = [
                    'periode_id' => $p->periode_id,
                    'bulan' => $p->bulan,
                    'tahun' => $p->tahun,
                    'status_periode' => $p->status,
                    'deadline' => $p->tanggal_deadline,
                    'total_peserta' => $stats->total_peserta ?? 0,
                    'total_terverifikasi' => $stats->total_verified ?? 0,
                    'total_submit_menunggu_verifikasi' => $stats->total_submit ?? 0,
                    'total_draft' => $stats->total_draft ?? 0,
                    'total_belum_diisi' => $stats->total_belum_diisi ?? 0,
                ];
            }

            return [
                'total_pegawai_terdaftar' => $totalPegawai,
                'total_unit_kerja' => $totalUnit,
                'total_departemen' => $totalDepartemen,
                'total_jabatan' => $totalJabatan,
                'statistik_per_periode' => $periodeStats,
            ];
        } catch (\Exception $e) {
            return [];
        }
    }
}
