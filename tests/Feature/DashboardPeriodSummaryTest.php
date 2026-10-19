<?php

namespace Tests\Feature;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardPeriodSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_summary_and_card_details_only_count_active_entries_in_selected_period(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($user);

        Dp3TransPeriodePenilaian::create([
            'periode_id' => 'PER202610',
            'bulan' => 10,
            'tahun' => 2026,
            'tanggal_mulai' => '2026-10-01',
            'tanggal_deadline' => '2026-12-31',
            'status' => 'OPEN',
        ]);

        foreach (range(1, 12) as $employeeId) {
            DB::table('employee')->insert([
                'pgw_id' => $employeeId,
                'nup' => 'NUP-' . $employeeId,
                'nama' => 'Pegawai ' . $employeeId,
            ]);
        }

        $this->insertAssessment(1, 'Submit', null, 'VERIFIED');
        $this->insertAssessment(2, 'Submit', 'BELUM VERIFIKASI', null);
        $this->insertAssessment(3, 'REVISI', 'REVISI', null);
        $this->insertAssessment(4, 'Draft');
        $this->insertAssessment(5, 'Belum Diisi');
        $this->insertAssessment(6, 'Submit', 'VERIFIED', null, 'PER202610', false);

        $this->get(route('dashboard.penilaian', ['periode_id' => 'PER202610']))
            ->assertOk()
            ->assertViewHas('totalPegawai', 5)
            ->assertViewHas('sudahTerverifikasi', 1)
            ->assertViewHas('menungguVerifikasi', 1)
            ->assertViewHas('dikembalikan', 1)
            ->assertViewHas('masihDraft', 1)
            ->assertViewHas('belumDinilai', 1);

        $this->getJson(route('dashboard.modal-detail', [
            'type' => 'total',
            'periode_id' => 'PER202610',
        ]))
            ->assertOk()
            ->assertJsonPath('total', 5)
            ->assertJsonMissing(['nama' => 'Pegawai 6']);
    }

    private function insertAssessment(
        int $employeeId,
        string $statusNilai,
        ?string $statusVerifikator = null,
        ?string $legacyStatusVerifitor = null,
        string $periodeId = 'PER202610',
        bool $isActive = true,
    ): void {
        DB::table('dp3_trans_penilaian')->insert([
            'kode' => 'DASH-' . $employeeId,
            'periode_id' => $periodeId,
            'pegawai_id' => (string) $employeeId,
            'pgw_nup' => 'NUP-' . $employeeId,
            'pgw_nama' => 'Pegawai ' . $employeeId,
            'status_nilai' => $statusNilai,
            'status_verifikator' => $statusVerifikator,
            'status_verifitor' => $legacyStatusVerifitor,
            'is_active' => $isActive,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
