<?php

namespace Tests\Feature;

use App\Models\Dp3TransPeriodePenilaian;
use App\Models\Employee;
use App\Models\User;
use App\Support\EmployeeAssessmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeAssessmentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_and_verification_are_limited_to_hierarchy_and_unit(): void
    {
        $this->createUnit('UNIT-A');
        $this->createUnit('UNIT-B');
        $this->createEmployee(1, 9, 'UNIT-A');
        $this->createEmployee(2, 6, 'UNIT-A');
        $this->createEmployee(3, 5, 'UNIT-A');
        $this->createEmployee(4, 1, 'UNIT-A');
        $this->createEmployee(5, 9, 'UNIT-B');
        $this->createPeriod();

        $asmen = User::factory()->create(['employee_id' => 2, 'role' => 'asmen']);
        $manager = User::factory()->create(['employee_id' => 3, 'role' => 'manager']);
        $director = User::factory()->create(['employee_id' => 4, 'role' => 'direktur']);
        $staff = Employee::query()->find(1);
        $otherUnitStaff = Employee::query()->find(5);

        $this->assertTrue(EmployeeAssessmentPolicy::canEvaluate($asmen, $staff));
        $this->assertFalse(EmployeeAssessmentPolicy::canEvaluate($asmen, $otherUnitStaff));
        $this->assertTrue(EmployeeAssessmentPolicy::canVerify($manager, (object) [
            'pegawai_id' => 1,
            'penilai_id' => 2,
        ]));
        $this->assertFalse(EmployeeAssessmentPolicy::canVerify($asmen, (object) [
            'pegawai_id' => 1,
            'penilai_id' => 2,
        ]));
        $this->assertTrue(EmployeeAssessmentPolicy::canVerify($director, (object) [
            'pegawai_id' => 3,
            'penilai_id' => 4,
        ]));
        $this->assertFalse(EmployeeAssessmentPolicy::canVerify($director, (object) [
            'pegawai_id' => 5,
            'penilai_id' => 2,
        ]));
    }

    public function test_evaluation_list_scope_returns_only_assigned_subordinates_in_actor_unit(): void
    {
        $this->createUnit('UNIT-A');
        $this->createUnit('UNIT-B');
        $this->createEmployee(1, 9, 'UNIT-A');
        $this->createEmployee(2, 9, 'UNIT-B');
        $this->createEmployee(3, 6, 'UNIT-A');
        $this->createEmployee(4, 5, 'UNIT-A');
        $this->createPeriod();

        $asmen = User::factory()->create(['employee_id' => 3, 'role' => 'asmen']);
        DB::table('dp3_trans_penilaian')->insert([
            $this->transaction(1, 3),
            $this->transaction(2, 3),
            $this->transaction(3, 4),
        ]);

        $query = DB::table('dp3_trans_penilaian as tp')
            ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id');
        EmployeeAssessmentPolicy::applyEvaluationListScope($query, $asmen);

        $this->assertSame([1], array_map('intval', $query->orderBy('tp.pegawai_id')->pluck('tp.pegawai_id')->all()));
    }

    public function test_legacy_submissions_without_assessor_ids_remain_verifiable_by_hierarchy(): void
    {
        $this->createUnit('UNIT-A');
        $this->createEmployee(1, 9, 'UNIT-A');
        $this->createEmployee(2, 6, 'UNIT-A');
        $this->createEmployee(3, 5, 'UNIT-A');
        $this->createEmployee(4, 1, 'UNIT-A');
        $this->createPeriod();

        $manager = User::factory()->create(['employee_id' => 3, 'role' => 'manager']);
        $director = User::factory()->create(['employee_id' => 4, 'role' => 'direktur']);

        $this->assertTrue(EmployeeAssessmentPolicy::canVerify($manager, (object) [
            'pegawai_id' => 1,
            'penilai_id' => null,
        ]));
        $this->assertTrue(EmployeeAssessmentPolicy::canVerify($director, (object) [
            'pegawai_id' => 2,
            'penilai_id' => null,
        ]));
        $this->assertTrue(EmployeeAssessmentPolicy::canVerify($director, (object) [
            'pegawai_id' => 3,
            'penilai_id' => null,
        ]));

        $legacyStaff = $this->transaction(1, 2);
        $legacyStaff['penilai_id'] = null;
        $legacyAsmen = $this->transaction(2, 3);
        $legacyAsmen['penilai_id'] = null;
        $legacyManager = $this->transaction(3, 4);
        $legacyManager['penilai_id'] = null;
        DB::table('dp3_trans_penilaian')->insert([$legacyStaff, $legacyAsmen, $legacyManager]);

        $managerQueue = DB::table('dp3_trans_penilaian as tp')
            ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id');
        EmployeeAssessmentPolicy::applyVerificationListScope($managerQueue, $manager);
        $this->assertSame([1], array_map('intval', $managerQueue->orderBy('tp.pegawai_id')->pluck('tp.pegawai_id')->all()));

        $directorQueue = DB::table('dp3_trans_penilaian as tp')
            ->join('employee as e', 'tp.pegawai_id', '=', 'e.pgw_id')
            ->leftJoin('employee as penilai', 'tp.penilai_id', '=', 'penilai.pgw_id');
        EmployeeAssessmentPolicy::applyVerificationListScope($directorQueue, $director);
        $this->assertSame([1, 2, 3], array_map('intval', $directorQueue->orderBy('tp.pegawai_id')->pluck('tp.pegawai_id')->all()));
    }

    private function createUnit(string $code): void
    {
        DB::table('unit_kerja')->insert([
            'kode_unit_kerja' => $code,
            'nama_unit_kerja' => $code,
        ]);
    }

    private function createPeriod(): void
    {
        Dp3TransPeriodePenilaian::create([
            'periode_id' => 'POLICY-PERIOD',
            'bulan' => 10,
            'tahun' => 2026,
            'status' => 'OPEN',
        ]);
    }

    private function createEmployee(int $id, int $occupationId, string $unit): void
    {
        DB::table('employee')->insert([
            'pgw_id' => $id,
            'nup' => 'NUP-' . $id,
            'nama' => 'Pegawai ' . $id,
            'occ_id' => $occupationId,
            'kode_unit_kerja' => $unit,
            'is_hapus' => 0,
        ]);
    }

    private function transaction(int $employeeId, int $assessorId): array
    {
        return [
            'kode' => 'POLICY-' . $employeeId,
            'periode_id' => 'POLICY-PERIOD',
            'pegawai_id' => (string) $employeeId,
            'penilai_id' => (string) $assessorId,
            'status_nilai' => 'DRAFT',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
