<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->insertOrIgnore([
            'slug' => 'asmen',
            'name' => 'Asmen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissions = [
            'asmen' => [
                'dashboard' => ['view' => true],
                'penilaian' => ['view' => true],
                'penilaian.kelola' => ['view' => true],
            ],
            'manager' => [
                'dashboard' => ['view' => true],
                'penilaian' => ['view' => true],
                'penilaian.kelola' => ['view' => true],
                'penilaian.verifikasi' => ['view' => true],
            ],
            'direktur' => [
                'dashboard' => ['view' => true],
                'penilaian' => ['view' => true],
                'penilaian.kelola' => ['view' => true],
                'penilaian.verifikasi' => ['view' => true],
            ],
        ];

        foreach ($permissions as $role => $rolePermissions) {
            DB::table('role_menu_permissions')->insertOrIgnore([
                'role' => $role,
                'permissions' => json_encode($rolePermissions),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('role_menu_permissions')->where('role', 'asmen')->delete();
        DB::table('roles')->where('slug', 'asmen')->delete();
    }
};
