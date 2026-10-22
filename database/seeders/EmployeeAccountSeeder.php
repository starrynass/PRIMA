<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class EmployeeAccountSeeder extends Seeder
{
    private const ASMEN_OCCUPATION_IDS = [6, 14, 17, 18, 19, 20, 21, 22, 23, 26];

    public function run(): void
    {
        $employees = DB::table('employee')
            ->where('is_hapus', 0)
            ->orderBy('pgw_id')
            ->get();

        if ($employees->count() !== 204) {
            throw new RuntimeException('EmployeeAccountSeeder membutuhkan tepat 204 pegawai aktif.');
        }

        foreach ($employees as $employee) {
            $this->saveAccount($employee, $this->roleForOccupation((int) $employee->occ_id));
        }
    }

    private function roleForOccupation(int $occupationId): string
    {
        return match (true) {
            in_array($occupationId, [1, 2, 3], true) => 'direktur',
            $occupationId === 5 => 'manager',
            in_array($occupationId, self::ASMEN_OCCUPATION_IDS, true) => 'asmen',
            in_array($occupationId, [9, 10], true) => 'staff',
            default => throw new RuntimeException('Jabatan pegawai tidak memiliki role akun: ' . $occupationId),
        };
    }

    private function saveAccount(object $employee, string $role): void
    {
        $email = strtolower(trim((string) $employee->nup)) . '@dp3.local';
        $user = User::query()->where('employee_id', $employee->pgw_id)->first();
        $emailOwner = User::query()->where('email', $email)->first();

        if ($emailOwner && $emailOwner->employee_id !== null
            && (int) $emailOwner->employee_id !== (int) $employee->pgw_id) {
            throw new RuntimeException('Email akun seed sudah terhubung dengan pegawai lain: ' . $email);
        }

        if ($user && $emailOwner && (int) $user->id !== (int) $emailOwner->id) {
            throw new RuntimeException('Pegawai memiliki lebih dari satu akun yang perlu ditinjau: ' . $employee->pgw_id);
        }

        $user = $user ?? $emailOwner ?? new User();
        $isNew = !$user->exists;

        $user->fill([
            'employee_id' => $employee->pgw_id,
            'name' => $employee->nama,
            'email' => $email,
            'role' => $role,
        ]);

        if ($isNew) {
            $user->password = Hash::make('1234');
            $user->password_reveal = Crypt::encryptString('1234');
        }

        $user->save();
    }
}
