<?php

namespace Tests\Feature;

use App\Models\RoleMenuPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PeriodeDetailTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'manager']);
        RoleMenuPermission::create([
            'role' => 'manager',
            'permissions' => [
                'penilaian' => ['view' => true],
                'penilaian.periode' => ['view' => true],
            ],
        ]);

        DB::table('dp3_trans_periode_penilaian')->insert([
            'periode_id' => 'PERIODE-TEST',
            'bulan' => 1,
            'tahun' => 2026,
            'status' => 'OPEN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->user);
    }

    private function createEmployee(string $name, string $nup, ?int $occupationId = null): int
    {
        return DB::table('employee')->insertGetId([
            'nama' => $name,
            'nup' => $nup,
            'occ_id' => $occupationId,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'pgw_id');
    }

    private function addPeriodEntry(int $employeeId): int
    {
        return DB::table('dp3_trans_penilaian')->insertGetId([
            'kode' => 'PEN-PERIODE-TEST-' . $employeeId,
            'periode_id' => 'PERIODE-TEST',
            'pegawai_id' => $employeeId,
            'pgw_nama' => DB::table('employee')->where('pgw_id', $employeeId)->value('nama'),
            'pgw_nup' => DB::table('employee')->where('pgw_id', $employeeId)->value('nup'),
            'status_nilai' => 'Belum Diisi',
            'created_at' => now(),
            'updated_at' => now(),
        ], 'penilaian_id');
    }

    public function test_period_can_be_created_and_marks_admin_as_opening_period(): void
    {
        $response = $this->post(route('periode.store'), [
            'bulan' => 10,
            'tahun' => 2026,
            'tanggal_mulai' => '2026-10-01',
            'tanggal_deadline' => '2026-10-31',
            'status' => 'LOCKED',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dp3_trans_periode_penilaian', [
            'periode_id' => 'PER202610',
            'dibuka_oleh_admin' => 'Ya',
            'created_by' => $this->user->email,
        ]);
    }

    public function test_manual_evaluator_can_be_assigned_and_cleared_and_deactivation_requires_reason(): void
    {
        $employeeId = $this->createEmployee('Pegawai Peserta', 'NUP-001');
        $evaluatorId = $this->createEmployee('Pegawai Penilai', 'NUP-002');
        $entryId = $this->addPeriodEntry($employeeId);

        $this->post(route('periode.evaluators.assign', 'PERIODE-TEST'), [
            'penilaian_ids' => [$entryId],
            'penilai_id' => $evaluatorId,
        ])->assertRedirect();

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => $entryId,
            'penilai_id' => $evaluatorId,
            'penilai_manual' => 1,
        ]);

        $this->post(route('periode.evaluators.automatic', 'PERIODE-TEST'), [
            'penilaian_ids' => [$entryId],
        ])->assertRedirect();

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => $entryId,
            'penilai_id' => null,
            'penilai_manual' => 0,
        ]);

        $this->from(route('periode.detail', 'PERIODE-TEST'))
            ->post(route('periode.employees.deactivate', 'PERIODE-TEST'), [
                'penilaian_ids' => [$entryId],
            ])
            ->assertSessionHasErrors('reason');

        $this->post(route('periode.employees.deactivate', 'PERIODE-TEST'), [
            'penilaian_ids' => [$entryId],
            'reason' => 'Tidak lagi memenuhi kriteria periode',
        ])->assertRedirect();

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => $entryId,
            'is_active' => 0,
            'inactive_reason' => 'Tidak lagi memenuhi kriteria periode',
        ]);
    }

    public function test_employee_can_be_searched_added_once_and_shown_in_period_detail(): void
    {
        $existingId = $this->createEmployee('Peserta Lama', 'NUP-101');
        $newEmployeeId = $this->createEmployee('Peserta Baru', 'NUP-102');
        $existingEntryId = $this->addPeriodEntry($existingId);

        $this->getJson(route('periode.employees.search', [
            'id' => 'PERIODE-TEST',
            'q' => 'NUP-102',
        ]))
            ->assertOk()
            ->assertJsonFragment(['pgw_id' => $newEmployeeId, 'nup' => 'NUP-102']);

        $this->post(route('periode.employees.add', 'PERIODE-TEST'), [
            'pegawai_id' => $newEmployeeId,
        ])->assertRedirect();

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'periode_id' => 'PERIODE-TEST',
            'pegawai_id' => $newEmployeeId,
            'pgw_nama' => 'Peserta Baru',
            'template_id' => null,
            'is_active' => 1,
        ]);

        $this->post(route('periode.employees.update', 'PERIODE-TEST'), [
            'penilaian_ids' => [$existingEntryId],
            'kode_satker' => 'SATKER-UPDATED',
        ])->assertRedirect();
        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'penilaian_id' => $existingEntryId,
            'pgw_kode_satker' => 'SATKER-UPDATED',
        ]);

        $this->from(route('periode.detail', 'PERIODE-TEST'))
            ->post(route('periode.employees.add', 'PERIODE-TEST'), [
                'pegawai_id' => $newEmployeeId,
            ])
            ->assertSessionHasErrors('pegawai_id');

        $this->get(route('periode.detail', 'PERIODE-TEST'))
            ->assertOk()
            ->assertSee('Peserta Baru');

        $this->get(route('periode.export', 'PERIODE-TEST'))->assertOk();
    }

    public function test_added_employee_receives_matching_active_template(): void
    {
        $existingId = $this->createEmployee('Peserta Lama', 'NUP-201');
        $this->addPeriodEntry($existingId);
        $occupationId = DB::table('occupation')->insertGetId([
            'occ_name' => 'Jabatan Template',
            'is_aktif' => 1,
        ], 'occ_id');
        DB::table('dp3_master_template_penilaian')->insert([
            'template_id' => 'TPL-TEST',
            'nama_template' => 'Template Jabatan',
            'occ_id' => (string) $occupationId,
            'status_aktif' => 'Aktif',
        ]);
        $newEmployeeId = $this->createEmployee('Peserta Bertemplate', 'NUP-202', $occupationId);

        $this->post(route('periode.employees.add', 'PERIODE-TEST'), [
            'pegawai_id' => $newEmployeeId,
        ])->assertRedirect();

        $this->assertDatabaseHas('dp3_trans_penilaian', [
            'periode_id' => 'PERIODE-TEST',
            'pegawai_id' => $newEmployeeId,
            'template_id' => 'TPL-TEST',
            'template_nama' => 'Template Jabatan',
        ]);
    }
}
