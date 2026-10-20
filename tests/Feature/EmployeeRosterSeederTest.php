<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeRosterSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_expected_employee_roles_and_active_accounts(): void
    {
        (new DatabaseSeeder())->run();

        $this->assertSame(34, DB::table('unit_kerja')->count());
        $this->assertGreaterThan(0, DB::table('office')->count());
        $this->assertGreaterThan(0, DB::table('department')->count());
        $this->assertGreaterThan(0, DB::table('department_sub')->count());
        $this->assertSame(204, DB::table('employee')->where('is_hapus', 0)->count());
        $this->assertSame(10, DB::table('employee')->where('is_hapus', 1)->count());
        $this->assertSame(0, DB::table('employee')->whereBetween('pgw_id', [1, 214])->whereNull('off_id')->count());
        $this->assertSame(0, DB::table('employee')->whereBetween('pgw_id', [1, 214])->whereNull('dept_id')->count());
        $this->assertSame(0, DB::table('employee')->whereBetween('pgw_id', [1, 214])->whereNull('subdept_id')->count());
        $this->assertSame(0, DB::table('employee as e')
            ->join('department_sub as ds', 'e.subdept_id', '=', 'ds.subdept_id')
            ->whereBetween('e.pgw_id', [1, 214])
            ->whereColumn('e.dept_id', '<>', 'ds.dept_id')
            ->count());
        $this->assertStringStartsWith('Aditya ', DB::table('employee')->where('pgw_id', 1)->value('nama'));
        $this->assertStringStartsWith('Aisyah ', DB::table('employee')->where('pgw_id', 2)->value('nama'));
        $this->assertSame(214, DB::table('employee')->whereBetween('pgw_id', [1, 214])->distinct('nama')->count('nama'));
        $seededEmployees = DB::table('employee')->whereBetween('pgw_id', [1, 214])->get(['nama', 'tempat']);
        $this->assertTrue($seededEmployees->every(
            fn ($employee) => $employee->nama === mb_convert_case($employee->nama, MB_CASE_TITLE, 'UTF-8')
                && $employee->tempat === strtoupper($employee->tempat)
        ));
        $this->assertSame(214, $seededEmployees->map(fn ($employee) => explode(' ', $employee->nama)[0])->unique()->count());
        $this->assertSame(214, $seededEmployees->map(fn ($employee) => explode(' ', $employee->nama)[1])->unique()->count());
        $this->assertGreaterThan(20, DB::table('employee')
            ->whereBetween('pgw_id', [1, 214])
            ->distinct('tempat')
            ->count('tempat'));
        $this->assertSame(0, DB::table('employee')->where('pgw_id', 1)->where('sex', '<>', 'L')->count());
        $this->assertSame(0, DB::table('employee')->where('pgw_id', 2)->where('sex', '<>', 'P')->count());
        $this->assertSame(204, User::query()->whereNotNull('employee_id')->count());
        $this->assertSame(34, User::query()->where('role', 'direktur')->count());
        $this->assertSame(34, User::query()->where('role', 'manager')->count());
        $this->assertSame(34, User::query()->where('role', 'asmen')->count());
        $this->assertSame(102, User::query()->where('role', 'staff')->count());
        $this->assertSame(0, User::query()->whereIn('employee_id', [205, 206, 207, 208, 209, 210, 211, 212, 213, 214])->count());
        $this->assertTrue(Hash::check('1234', User::query()->where('employee_id', 1)->value('password')));
        $this->assertTrue(User::query()->where('role', 'asmen')->firstOrFail()->hasAccess('penilaian.kelola'));
        $this->assertTrue(User::query()->where('role', 'manager')->firstOrFail()->hasAccess('penilaian.verifikasi'));
        $this->assertTrue(User::query()->where('role', 'direktur')->firstOrFail()->hasAccess('penilaian.kelola'));
        $this->assertFalse(User::query()->where('role', 'staff')->firstOrFail()->hasAccess('penilaian.kelola'));

        $unitCounts = DB::table('employee')
            ->where('is_hapus', 0)
            ->groupBy('kode_unit_kerja')
            ->selectRaw('kode_unit_kerja, COUNT(*) as total')
            ->pluck('total', 'kode_unit_kerja');

        $this->assertCount(34, $unitCounts);
        $this->assertSame([6], $unitCounts->unique()->values()->all());

        $account = User::query()->where('employee_id', 1)->firstOrFail();
        $account->password = Hash::make('custom-password');
        $account->name = 'NAMA TIDAK SESUAI';
        $account->save();

        (new DatabaseSeeder())->run();

        $this->assertSame(214, DB::table('employee')->count());
        $this->assertSame(204, User::query()->whereNotNull('employee_id')->count());
        $this->assertTrue(Hash::check(
            'custom-password',
            User::query()->where('employee_id', 1)->value('password')
        ));
        $this->assertSame(
            DB::table('employee')->where('pgw_id', 1)->value('nama'),
            User::query()->where('employee_id', 1)->value('name')
        );
        $this->assertSame(0, DB::table('users as u')
            ->join('employee as e', 'u.employee_id', '=', 'e.pgw_id')
            ->whereBetween('e.pgw_id', [1, 204])
            ->whereColumn('u.name', '<>', 'e.nama')
            ->count());
    }
}
