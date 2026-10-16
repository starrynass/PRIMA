<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_login_when_not_authenticated(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_default_admin_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $response->assertRedirect('/dashboard-penilaian');
        $this->assertAuthenticated();
    }

    public function test_admin_session_remains_authenticated_on_dashboard_request(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $response = $this->get('/dashboard-penilaian');

        $response->assertOk();
        $this->assertAuthenticated();
    }

    public function test_superadmin_can_open_access_settings(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $response = $this->get('/setting/hak-akses');

        $response->assertOk();
    }

    public function test_non_superadmin_cannot_open_access_settings(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'karyawan-' . uniqid() . '@dp3.local',
            'name' => 'Karyawan',
            'password' => bcrypt('1234'),
            'role' => 'employee',
            'permissions' => json_encode([
                'dashboard' => ['view' => true],
                'master' => ['view' => false],
                'penilaian' => ['view' => true],
                'laporan' => ['view' => false],
                'settings' => ['view' => false],
            ]),
        ]);

        $this->actingAs($user);

        $response = $this->get('/setting/hak-akses');

        $response->assertStatus(403);
    }

    public function test_employee_can_login_using_full_nup_as_username(): void
    {
        \App\Models\Employee::query()->where('nup', '199002022022011049')->delete();

        \App\Models\Employee::create([
            'pgw_id' => 9999,
            'nup' => '199002022022011049',
            'nama' => 'Deni Sumargo',
        ]);

        $response = $this->post('/login', [
            'username' => '199002022022011049',
            'password' => '1234',
        ]);

        $response->assertRedirect('/dashboard-penilaian');
        $this->assertAuthenticated();
    }

    public function test_employee_login_rewrites_existing_user_password_to_default_1234(): void
    {
        $employee = \App\Models\Employee::firstOrCreate(
            ['nup' => '199903032022011099'],
            ['pgw_id' => 9998, 'nama' => 'Rizky Pratama']
        );

        $user = \App\Models\User::firstOrCreate(
            ['email' => $employee->nup . '@dp3.local'],
            [
                'name' => $employee->nama,
                'password' => bcrypt('lama123'),
                'role' => 'employee',
            ]
        );

        $response = $this->post('/login', [
            'username' => $employee->nup,
            'password' => '1234',
        ]);

        $response->assertRedirect('/dashboard-penilaian');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('1234', $user->fresh()->password));
    }

    public function test_employee_permissions_are_not_reset_on_login(): void
    {
        $employee = \App\Models\Employee::firstOrCreate(
            ['nup' => '299901012022011111'],
            ['pgw_id' => 10000, 'nama' => 'Pegawai Dengan Hak Khusus']
        );

        $user = \App\Models\User::firstOrCreate(
            ['email' => strtolower($employee->nup) . '@dp3.local'],
            [
                'name' => $employee->nama,
                'password' => bcrypt('1234'),
                'role' => 'employee',
                'permissions' => [
                    'dashboard' => ['view' => true],
                    'master' => ['view' => true, 'create' => true, 'update' => true, 'delete' => true],
                    'penilaian' => ['view' => true, 'create' => true, 'update' => true, 'delete' => false, 'verify' => false],
                    'laporan' => ['view' => true],
                    'settings' => ['view' => false, 'manage' => false],
                ],
            ]
        );

        $response = $this->post('/login', [
            'username' => $employee->nup,
            'password' => '1234',
        ]);

        $response->assertRedirect('/dashboard-penilaian');
        $this->assertTrue($user->fresh()->canAccessModule('master', 'view'));
    }

    public function test_sidebar_hides_disabled_menus_based_on_permission_flags(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'menu-hidden-' . uniqid() . '@dp3.local',
            'name' => 'User Menu Tertutup',
            'password' => bcrypt('1234'),
            'role' => 'staff',
            'permissions' => json_encode([
                'dashboard' => ['view' => true],
                'master' => ['view' => false],
                'penilaian' => ['view' => true],
                'laporan' => ['view' => false],
                'settings' => ['view' => false],
            ]),
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard-penilaian');

        $response->assertOk();
        $response->assertDontSee('Master');
        $response->assertSee('Penilaian');
    }

    public function test_superadmin_can_delete_user_account_from_access_settings(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $user = \App\Models\User::factory()->create([
            'email' => 'hapus-akun-' . uniqid() . '@dp3.local',
            'name' => 'Akun Dihapus',
            'password' => bcrypt('1234'),
            'role' => 'employee',
        ]);

        $response = $this->delete(route('setting.hak-akses.destroy', $user));

        $response->assertRedirect(route('setting.hak-akses'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_add_account_dropdown_hides_employees_that_already_have_accounts(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $employee = \App\Models\Employee::firstOrCreate(
            ['nup' => '199901012022011111'],
            ['pgw_id' => 9997, 'nama' => 'Pegawai Sudah Punya Akun']
        );

        \App\Models\User::firstOrCreate(
            ['email' => strtolower($employee->nup) . '@dp3.local'],
            ['name' => $employee->nama, 'password' => bcrypt('1234'), 'role' => 'employee']
        );

        $response = $this->get('/setting/hak-akses');

        $response->assertOk();
        $this->assertStringNotContainsString('<option value="' . $employee->pgw_id . '">', $response->getContent());
    }

    public function test_superadmin_can_open_menu_authorization_page(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $response = $this->get('/setting/otorisasi-menu');

        $response->assertOk();
    }

    public function test_role_menu_authorization_updates_all_users_with_same_role(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $first = \App\Models\User::factory()->create([
            'name' => 'Manager A',
            'email' => 'manager-a-' . uniqid() . '@dp3.local',
            'role' => 'manager',
            'permissions' => [
                'dashboard' => ['view' => false],
                'master' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
                'penilaian' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false, 'verify' => false],
                'laporan' => ['view' => false],
                'settings' => ['view' => false, 'manage' => false],
            ],
        ]);

        $second = \App\Models\User::factory()->create([
            'name' => 'Manager B',
            'email' => 'manager-b-' . uniqid() . '@dp3.local',
            'role' => 'manager',
            'permissions' => [
                'dashboard' => ['view' => false],
                'master' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
                'penilaian' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false, 'verify' => false],
                'laporan' => ['view' => false],
                'settings' => ['view' => false, 'manage' => false],
            ],
        ]);

        $response = $this->post('/setting/otorisasi-menu', [
            'role' => 'manager',
            'permissions' => [
                'dashboard' => ['view' => 1],
                'master' => ['view' => 1, 'create' => 1, 'update' => 1, 'delete' => 0],
                'penilaian' => ['view' => 1, 'create' => 1, 'update' => 1, 'delete' => 0, 'verify' => 1],
                'laporan' => ['view' => 1],
                'settings' => ['view' => 0, 'manage' => 0],
            ],
        ]);

        $response->assertRedirect(route('setting.otorisasi-menu', ['role' => 'manager']));
        $this->assertTrue($first->fresh()->canAccessModule('master', 'view'));
        $this->assertTrue($second->fresh()->canAccessModule('laporan', 'view'));
    }

    public function test_checked_submenu_in_menu_authorization_enables_module_access(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Staff Submenu',
            'email' => 'submenu-' . uniqid() . '@dp3.local',
            'role' => 'staff',
            'permissions' => [
                'dashboard' => ['view' => false],
                'master' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
                'penilaian' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false, 'verify' => false],
                'laporan' => ['view' => false],
                'settings' => ['view' => false, 'manage' => false],
            ],
        ]);

        $response = $this->post('/setting/otorisasi-menu', [
            'role' => 'staff',
            'permissions' => [
                'master.department' => ['view' => 1],
            ],
        ]);

        $response->assertRedirect(route('setting.otorisasi-menu', ['role' => 'staff']));
        $this->assertTrue($user->fresh()->canAccessModule('master', 'view'));
    }
}
