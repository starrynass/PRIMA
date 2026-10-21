<?php

namespace Tests\Feature;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Dp3TransPenilaian;
use App\Models\Dp3TransPenilaianDetail;
use App\Models\MasterSkalaNilai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VerificationStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $verifier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->verifier = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($this->verifier);

        Dp3TransPeriodePenilaian::create([
            'periode_id' => 'PER-VERIFY-TEST',
            'bulan' => 10,
            'tahun' => 2026,
            'status' => 'OPEN',
        ]);

        DB::table('employee')->insert([
            'pgw_id' => 1,
            'nup' => 'NUP-VERIFY-1',
            'nama' => 'Pegawai Verifikasi',
        ]);

        DB::table('dp3_trans_penilaian')->insert([
            'penilaian_id' => 1,
            'kode' => 'VERIFY-TEST-1',
            'periode_id' => 'PER-VERIFY-TEST',
            'pegawai_id' => '1',
            'pgw_nup' => 'NUP-VERIFY-1',
            'pgw_nama' => 'Pegawai Verifikasi',
            'status_nilai' => 'SUBMIT',
            'status_verifikator' => null,
            'status_verifitor' => 'BELUM VERIFIKASI',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        MasterSkalaNilai::create([
            'skala_id' => 'S-VERIFY-1',
            'kode_nilai' => 'B',
            'nama_nilai' => 'Baik',
            'nilai_angka' => 7,
        ]);

        DB::table('dp3_master_kategori_penilaian')->insert([
            'kategori_id' => 'K-VERIFY-1',
            'kode' => 'KAT-VERIFY-1',
            'nama' => 'Kategori Verifikasi',
            'bobot_persen' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('dp3_master_pertanyaan_penilaian')->insert([
            'pertanyaan_id' => 'Q-VERIFY-1',
            'kategori_id' => 'K-VERIFY-1',
            'pertanyaan' => 'Pertanyaan satu',
            'bobot_persen' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('dp3_trans_penilaian_detail')->insert([
            'penilaian_id' => 1,
            'pertanyaan_id' => 'Q-VERIFY-1',
            'pertanyaan' => 'Pertanyaan satu',
            'nilai_angka' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_verifying_submission_stores_verified_status_in_both_status_columns(): void
    {
        $this->get(route('verifikasi-penilaian.index', ['periode_id' => 'PER-VERIFY-TEST']))
            ->assertOk()
            ->assertSeeText('Menunggu Verifikasi')
            ->assertDontSeeText('Diajukan');

        $this->post(route('verifikasi-penilaian.store-form', 1), [
            'status_aksi' => 'verified',
            'grand_total' => 5,
            'predikat_nama' => 'Cukup',
        ])->assertRedirect(route('verifikasi-penilaian.index', ['periode_id' => 'PER-VERIFY-TEST']));

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => 1,
            'status_nilai' => 'VERIFIED',
            'status_verifikator' => 'VERIFIED',
            'status_verifitor' => 'VERIFIED',
        ]);

        $this->get(route('verifikasi-penilaian.index', ['periode_id' => 'PER-VERIFY-TEST']))
            ->assertOk()
            ->assertSeeText('Verified');
    }

    public function test_corrected_question_is_saved_counted_and_filterable(): void
    {
        $this->post(route('verifikasi-penilaian.store-form', 1), [
            'status_aksi' => 'koreksi',
            'jawaban' => ['Q-VERIFY-1' => 7],
            'catatan_verifikator' => ['Q-VERIFY-1' => 'Nilai diperbaiki'],
            'grand_total' => 7,
            'predikat_nama' => 'Baik',
        ])->assertRedirect(route('verifikasi-penilaian.index', ['periode_id' => 'PER-VERIFY-TEST']));

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => 1,
            'status_verifikator' => 'KOREKSI',
            'status_verifitor' => 'KOREKSI',
        ]);
        $this->assertDatabaseHas('dp3_trans_penilaian_detail', [
            'penilaian_id' => 1,
            'pertanyaan_id' => 'Q-VERIFY-1',
            'nilai_angka' => 5,
            'verif_nilai_angka' => 7,
            'verif_status' => 'KOREKSI',
        ]);

        $this->get(route('verifikasi-penilaian.index', [
            'periode_id' => 'PER-VERIFY-TEST',
            'koreksi' => 'ADA_KOREKSI',
        ]))
            ->assertOk()
            ->assertViewHas('adaKoreksi', 1)
            ->assertSeeText('Pegawai Verifikasi')
            ->assertSeeText('Ada Koreksi');
    }
}