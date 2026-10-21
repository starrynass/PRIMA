<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Support\EmployeeAccountSync;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeAccountSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_nup_and_linked_access_account_stay_in_sync(): void
    {
        $employee = Employee::create([
            'pgw_id' => 78001,
            'nup' => '111122223333',
            'nama' => 'Pegawai Awal',
        ]);
        $user = User::create([
            'employee_id' => $employee->pgw_id,
            'name' => $employee->nama,
            'email' => '111122223333@dp3.local',
            'password' => Hash::make('password-rahasia'),
            'role' => 'manager',
        ]);

        $employee->update([
            'nup' => '999988887777',
            'nama' => 'Nama Pegawai Terbaru',
        ]);

        EmployeeAccountSync::sync($employee->fresh());

        $user->refresh();
        $this->assertSame($employee->pgw_id, $user->employee_id);
        $this->assertSame('999988887777@dp3.local', $user->email);
        $this->assertSame('Nama Pegawai Terbaru', $user->name);
        $this->assertSame('manager', $user->role);
        $this->assertTrue(Hash::check('password-rahasia', $user->password));
    }

    public function test_hak_akses_page_displays_linked_employee_nup(): void
    {
        $employee = Employee::create([
            'pgw_id' => 78002,
            'nup' => '123450009999',
            'nama' => 'Pegawai Terhubung',
        ]);
        $user = User::factory()->create([
            'employee_id' => $employee->pgw_id,
            'name' => 'Pegawai Terhubung',
            'email' => '123450009999@dp3.local',
            'role' => 'superadmin',
        ]);

        \App\Models\RoleMenuPermission::create([
            'role' => 'superadmin',
            'permissions' => [
                'settings' => ['view' => true],
                'settings.hak-akses' => ['view' => true],
            ],
        ]);

        $this->actingAs($user)
            ->get(route('setting.hak-akses'))
            ->assertOk()
            ->assertSee('NUP pegawai: 123450009999');
    }

    public function test_legacy_nup_email_account_is_linked_to_employee(): void
    {
        $employee = Employee::create([
            'pgw_id' => 78003,
            'nup' => '123450008888',
            'nama' => 'Pegawai Legacy',
        ]);
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => '123450008888@dp3.local',
            'role' => 'staff',
        ]);

        $linked = EmployeeAccountSync::sync($employee);

        $this->assertSame($user->id, $linked?->id);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'employee_id' => $employee->pgw_id,
            'email' => '123450008888@dp3.local',
            'name' => 'Pegawai Legacy',
        ]);
    }

    public function test_account_created_from_hak_akses_keeps_custom_password_usable_and_recoverable(): void
    {
        $employee = Employee::create([
            'pgw_id' => 78004,
            'nup' => '765432109876',
            'nama' => 'Akun Password Custom',
        ]);
        $admin = User::factory()->create(['role' => 'superadmin']);
        \App\Models\RoleMenuPermission::create([
            'role' => 'superadmin',
            'permissions' => [
                'settings' => ['view' => true],
                'settings.hak-akses' => ['view' => true],
            ],
        ]);

        $this->actingAs($admin)
            ->post(route('setting.hak-akses.store'), [
                'employee_id' => $employee->pgw_id,
                'role' => 'staff',
                'password' => 'pw-rahasia-456',
            ])
            ->assertRedirect(route('setting.hak-akses'));

        $user = User::where('employee_id', $employee->pgw_id)->firstOrFail();
        $this->assertTrue(Hash::check('pw-rahasia-456', $user->password));
        $this->assertSame('pw-rahasia-456', Crypt::decryptString($user->password_reveal));

        $this->get(route('setting.hak-akses', ['user_id' => $user->id]))
            ->assertOk()
            ->assertSee('NUP pegawai: 765432109876')
            ->assertSee('pw-rahasia-456');

        auth()->logout();
        $this->post('/login', [
            'username' => substr($employee->nup, -10),
            'password' => '1234',
        ])->assertSessionHasErrors('username');
        $this->assertGuest();

        $this->post('/login', [
            'username' => substr($employee->nup, -10),
            'password' => 'pw-rahasia-456',
        ])->assertRedirect(route('access.denied'));
        $this->assertAuthenticatedAs($user);
    }
}
