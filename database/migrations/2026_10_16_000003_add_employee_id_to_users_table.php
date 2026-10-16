<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->nullable()->after('id');
            $table->unique('employee_id');
            $table->foreign('employee_id')
                ->references('pgw_id')
                ->on('employee')
                ->nullOnDelete();
        });

        $employees = DB::table('employee')
            ->whereNotNull('nup')
            ->where('nup', '!=', '')
            ->get(['pgw_id', 'nup']);

        foreach ($employees as $employee) {
            $matchingEmployees = DB::table('employee')
                ->whereRaw('LOWER(TRIM(nup)) = ?', [strtolower(trim($employee->nup))])
                ->count();

            if ($matchingEmployees === 1) {
                DB::table('users')
                    ->whereNull('employee_id')
                    ->whereRaw('LOWER(TRIM(email)) = ?', [
                        strtolower(trim($employee->nup)) . '@dp3.local',
                    ])
                    ->update(['employee_id' => $employee->pgw_id]);
            }
        }

        $employeesWithoutAccounts = DB::table('employee as e')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('users as u')
                    ->whereColumn('u.employee_id', 'e.pgw_id');
            })
            ->whereNotNull('e.nama')
            ->get(['e.pgw_id', 'e.nama']);

        foreach ($employeesWithoutAccounts as $employee) {
            $matchingEmployees = DB::table('employee')
                ->whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($employee->nama))])
                ->count();

            if ($matchingEmployees !== 1) {
                continue;
            }

            $matchingUsers = DB::table('users')
                ->whereNull('employee_id')
                ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($employee->nama))])
                ->whereRaw("LOWER(email) LIKE '%@dp3.local'")
                ->get(['id']);

            if ($matchingUsers->count() === 1) {
                DB::table('users')
                    ->where('id', $matchingUsers->first()->id)
                    ->update(['employee_id' => $employee->pgw_id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropUnique(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }
};
