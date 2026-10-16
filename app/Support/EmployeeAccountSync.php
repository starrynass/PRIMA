<?php

namespace App\Support;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeAccountSync
{
    public static function emailFor(Employee $employee): string
    {
        return strtolower(trim((string) $employee->nup)) . '@dp3.local';
    }

    public static function find(Employee $employee): ?User
    {
        $linkedUser = User::query()->where('employee_id', $employee->pgw_id)->first();
        if ($linkedUser) {
            return $linkedUser;
        }

        $email = self::emailFor($employee);

        return User::query()
            ->whereNull('employee_id')
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->first();
    }

    public static function sync(Employee $employee): ?User
    {
        if (trim((string) $employee->nup) === '') {
            return null;
        }

        $user = self::find($employee);
        if (! $user) {
            return null;
        }

        $email = self::emailFor($employee);
        $emailOwner = User::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->where('id', '!=', $user->id)
            ->first();

        if ($emailOwner) {
            throw ValidationException::withMessages([
                'nup' => "NUP {$employee->nup} sudah digunakan oleh akun lain. Periksa data pegawai dan akun sebelum menyinkronkan.",
            ]);
        }

        $user->employee_id = $employee->pgw_id;
        $user->email = $email;
        $user->name = $employee->nama;
        $user->save();

        return $user;
    }

    public static function createForEmployee(Employee $employee, string $password, string $role = 'staff'): User
    {
        if (trim((string) $employee->nup) === '') {
            throw ValidationException::withMessages([
                'employee_id' => 'Pegawai harus memiliki NUP sebelum akun dapat dibuat.',
            ]);
        }

        $email = self::emailFor($employee);
        $emailOwner = User::query()->whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();
        if ($emailOwner) {
            if ($emailOwner->employee_id === null) {
                $emailOwner->employee_id = $employee->pgw_id;
                $emailOwner->name = $employee->nama;
                $emailOwner->password = Hash::make($password);
                $emailOwner->password_reveal = Crypt::encryptString($password);
                $emailOwner->role = $role;
                $emailOwner->save();
                return $emailOwner;
            }

            if ((int) $emailOwner->employee_id === (int) $employee->pgw_id) {
                return $emailOwner;
            }

            throw ValidationException::withMessages([
                'employee_id' => 'NUP pegawai ini sudah terhubung dengan akun lain.',
            ]);
        }

        return User::create([
            'employee_id' => $employee->pgw_id,
            'name' => $employee->nama,
            'email' => $email,
            'password' => Hash::make($password),
            'password_reveal' => Crypt::encryptString($password),
            'role' => $role,
        ]);
    }
}
