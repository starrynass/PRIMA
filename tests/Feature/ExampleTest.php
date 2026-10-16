<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    private function grantSuperAdminHakAkses(): void
    {
        \App\Models\RoleMenuPermission::updateOrCreate(
            ['role' => 'superadmin'],
            ['permissions' => [
                'settings' => ['view' => true],
                'settings.hak-akses' => ['view' => true],
            ]]
        );

        \Illuminate\Support\Facades\Auth::user()?->forgetRoleMenuPermissions();
    }

    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_login_when_not_authenticated(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_logout_clears_authenticated_session_and_redirects_to_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'superadmin',
        ]);
        $this->actingAs($user)->withSession(['logout-test-marker' => true]);

        $this->get(route('setting.hak-akses'))
            ->assertOk()
            ->assertSee('action="' . route('logout') . '"', false)
            ->assertSee('aria-label="Keluar"', false);

        $response = $this->post(route('logout'));
        $response
            ->assertRedirect(route('login'))
            ->assertSessionMissing('logout-test-marker');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $this->assertGuest();
        $this->get(route('login'))->assertOk();
    }

    public function test_default_admin_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $response->assertRedirect(route('dashboard.penilaian'));
        $this->assertAuthenticated();
    }

    public function test_user_without_menu_access_is_not_sent_to_dashboard_after_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'no-menu-' . uniqid() . '@dp3.local',
            'password' => bcrypt('secret-password'),
            'role' => 'staff',
        ]);

        $this->withSession(['url.intended' => route('dashboard.penilaian')])
            ->post(route('login'), [
                'username' => $user->email,
                'password' => 'secret-password',
            ])
            ->assertRedirect(route('access.denied'));

        $this->get(route('access.denied'))
            ->assertOk()
            ->assertSeeText('Belum Ada Menu yang Dapat Diakses')
            ->assertSeeText('Keluar dan kembali ke login');

        $this->get(route('dashboard.penilaian'))->assertForbidden();
    }

    public function test_authenticated_user_without_access_is_not_redirected_to_dashboard_from_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'staff',
        ]);

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect('/');

        $this->get('/')
            ->assertRedirect(route('access.denied'));
    }

    public function test_superadmin_email_login_restores_role_and_access(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dp3.local',
            'password' => bcrypt('adminsuper'),
            'role' => 'staff',
        ]);

        $this->post('/login', [
            'username' => 'superadmin@dp3.local',
            'password' => 'adminsuper',
        ])->assertRedirect(route('dashboard.penilaian'));

        $this->assertSame('superadmin', \Illuminate\Support\Facades\Auth::user()->fresh()->role);

        $dashboardResponse = $this->get(route('dashboard.penilaian'));
        $this->assertNotSame(403, $dashboardResponse->getStatusCode());
        $this->get(route('setting.hak-akses'))->assertOk();
        $this->get(route('setting.roles'))->assertOk();
    }

    public function test_admin_session_remains_authenticated_on_settings_request(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        \App\Models\RoleMenuPermission::create([
            'role' => 'superadmin',
            'permissions' => ['dashboard' => ['view' => true]],
        ]);
        $this->actingAs(\App\Models\User::findOrFail(\Illuminate\Support\Facades\Auth::id()));

        $response = $this->get('/setting/otorisasi-menu');

        $response->assertOk();
        $this->assertAuthenticated();
    }

    public function test_roles_have_no_default_menu_access(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'staff',
            'permissions' => [
                'dashboard' => ['view' => true],
                'penilaian' => ['view' => true],
            ],
        ]);

        $this->assertFalse($user->fresh()->hasAccess('dashboard'));
        $this->assertFalse($user->fresh()->hasAccess('penilaian.kelola'));
        $this->actingAs($user)->get('/dashboard-penilaian')->assertForbidden();
    }

    public function test_new_accounts_use_the_role_menu_configuration_without_default_permissions(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        \App\Models\RoleMenuPermission::create([
            'role' => 'staff',
            'permissions' => ['dashboard' => ['view' => true]],
        ]);
        $this->grantSuperAdminHakAkses();
        $employee = \App\Models\Employee::create([
            'pgw_id' => 15000,
            'nup' => '300001012026011111',
            'nama' => 'Pegawai Baru',
        ]);

        $this->post('/setting/hak-akses', [
            'employee_id' => $employee->pgw_id,
            'role' => 'staff',
        ])->assertRedirect(route('setting.hak-akses'));

        $user = \App\Models\User::where('email', $employee->nup . '@dp3.local')->firstOrFail();

        $this->assertNull($user->permissions);
        $this->assertSame($employee->pgw_id, $user->employee_id);
        $this->assertTrue($user->hasAccess('dashboard'));
        $this->assertFalse($user->hasAccess('master'));
    }

    public function test_superadmin_can_open_access_settings(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $this->grantSuperAdminHakAkses();

        $response = $this->get('/setting/hak-akses');

        $response->assertOk();
    }

    public function test_setting_menu_and_pages_follow_role_authorization(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'manager',
        ]);

        \App\Models\RoleMenuPermission::create([
            'role' => 'manager',
            'permissions' => [
                'settings' => ['view' => true],
                'settings.hak-akses' => ['view' => true],
            ],
        ]);

        $this->actingAs($user)
            ->get('/setting/hak-akses')
            ->assertOk()
            ->assertSee('Setting')
            ->assertSee('Hak Akses')
            ->assertDontSee('Otorisasi Menu');

        $this->get('/setting/otorisasi-menu')->assertForbidden();
    }

    public function test_roles_can_be_created_renamed_and_deleted_when_unused(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'manager',
        ]);
        \App\Models\RoleMenuPermission::create([
            'role' => 'manager',
            'permissions' => [
                'settings' => ['view' => true],
                'settings.roles' => ['view' => true],
                'settings.hak-akses' => ['view' => true],
                'settings.otorisasi-menu' => ['view' => true],
            ],
        ]);
        $this->actingAs($user);

        $this->post('/setting/roles', ['name' => 'Auditor Internal'])
            ->assertRedirect(route('setting.roles'));

        $role = \App\Models\Role::where('slug', 'auditor-internal')->firstOrFail();
        $this->assertSame('Auditor Internal', \App\Models\User::roleCatalog()['auditor-internal']);
        $this->get(route('setting.hak-akses'))->assertOk()->assertSee('Auditor Internal');
        $this->get(route('setting.otorisasi-menu'))->assertOk()->assertSee('Auditor Internal');

        $this->put(route('setting.roles.update', $role), ['name' => 'Auditor'])
            ->assertRedirect(route('setting.roles'));
        $this->assertDatabaseHas('roles', ['slug' => 'auditor-internal', 'name' => 'Auditor']);

        $this->delete(route('setting.roles.destroy', $role))
            ->assertRedirect(route('setting.roles'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_roles_in_use_and_superadmin_role_cannot_be_deleted(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'manager',
        ]);
        \App\Models\RoleMenuPermission::create([
            'role' => 'manager',
            'permissions' => ['settings' => ['view' => true], 'settings.roles' => ['view' => true]],
        ]);
        $this->actingAs($user);
        $managerRole = \App\Models\Role::where('slug', 'manager')->firstOrFail();
        $superAdminRole = \App\Models\Role::where('slug', 'superadmin')->firstOrFail();

        $this->delete(route('setting.roles.destroy', $managerRole))
            ->assertRedirect(route('setting.roles'))
            ->assertSessionHasErrors('role');
        $this->delete(route('setting.roles.destroy', $superAdminRole))
            ->assertRedirect(route('setting.roles'))
            ->assertSessionHasErrors('role');
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

        $response->assertRedirect(route('access.denied'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => '199002022022011049@dp3.local',
            'employee_id' => 9999,
        ]);
    }

    public function test_employee_login_uses_existing_password_without_resetting_it(): void
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
            'password' => 'lama123',
        ]);

        $response->assertRedirect(route('access.denied'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('lama123', $user->fresh()->password));
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

        $response->assertRedirect(route('access.denied'));
        $this->assertTrue($user->fresh()->effectivePermissions()['master']['view']);
        $this->assertFalse($user->fresh()->canAccessModule('master', 'view'));
    }

    public function test_menu_visibility_is_based_on_role_menu_settings(): void
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

        \App\Models\RoleMenuPermission::create([
            'role' => 'staff',
            'permissions' => [
                'dashboard' => ['view' => true],
                'penilaian' => ['view' => true],
                'penilaian.catatan' => ['view' => true],
            ],
        ]);

        $this->assertFalse($user->hasAccess('master'));
        $this->assertTrue($user->hasAccess('penilaian'));
        $this->assertFalse($user->hasAccess('penilaian.periode'));
        $this->assertTrue($user->hasAccess('penilaian.catatan'));
    }

    public function test_superadmin_can_delete_user_account_from_access_settings(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);
        $this->grantSuperAdminHakAkses();

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
        $this->grantSuperAdminHakAkses();

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

    public function test_superadmin_has_access_to_all_routes_and_sidebar_menus_without_explicit_permissions(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        \App\Models\RoleMenuPermission::where('role', 'superadmin')->delete();

        \Illuminate\Support\Facades\Route::middleware('permission:dashboard,view')
            ->get('/test-superadmin-permission', fn () => response('ok'));

        $this->get('/test-superadmin-permission')
            ->assertOk()
            ->assertSeeText('ok');

        $this->get(route('setting.hak-akses'))
            ->assertOk()
            ->assertSee('href="' . route('dashboard.penilaian') . '"', false)
            ->assertSee('href="' . route('skala-predikat.index') . '"', false)
            ->assertSee('href="' . route('template-penilaian.index') . '"', false)
            ->assertSee('href="' . route('periode-penilaian.index') . '"', false)
            ->assertSee('href="' . route('setting.hak-akses') . '"', false)
            ->assertSee('href="' . route('setting.otorisasi-menu') . '"', false)
            ->assertSee('href="' . route('setting.roles') . '"', false);
    }

    public function test_superadmin_with_display_name_role_format_can_access_protected_pages(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'Super Admin',
        ]);

        \Illuminate\Support\Facades\Route::middleware('permission:dashboard,view')
            ->get('/test-superadmin-display-role', fn () => response('ok'));

        $this->actingAs($user)
            ->get('/test-superadmin-display-role')
            ->assertOk()
            ->assertSeeText('ok');

        $this->actingAs($user)
            ->get(route('setting.hak-akses'))
            ->assertOk();
    }

    public function test_superadmin_menu_authorization_settings_do_not_restrict_superadmin(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        \App\Models\RoleMenuPermission::where('role', 'superadmin')->delete();

        $response = $this->get('/setting/otorisasi-menu');
        $response->assertOk()
            ->assertDontSee('<option value="superadmin"', false);

        $this->get('/setting/otorisasi-menu?role=superadmin')
            ->assertRedirect(route('setting.otorisasi-menu', ['role' => 'direktur']))
            ->assertSessionHas('info');

        $this->from('/setting/otorisasi-menu')->post('/setting/otorisasi-menu', [
            'role' => 'superadmin',
            'permissions' => [],
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('role_menu_permissions', ['role' => 'superadmin']);

        \Illuminate\Support\Facades\Route::middleware('permission:settings.roles,view')
            ->get('/test-superadmin-any-menu', fn () => response('ok'));

        $this->get('/test-superadmin-any-menu')
            ->assertOk()
            ->assertSeeText('ok');

        $superAdmin = \Illuminate\Support\Facades\Auth::user();
        foreach (\App\Support\MenuCatalog::all() as $menuKey => $menu) {
            $this->assertTrue($superAdmin->hasAccess($menuKey));
            foreach ($menu['children'] as $child) {
                $this->assertTrue($superAdmin->hasAccess($child['key']));
            }
        }
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
                'master' => ['view' => 1],
                'master.template-penilaian' => ['view' => 1],
                'laporan' => ['view' => 1],
                'laporan.tahunan' => ['view' => 1],
            ],
        ]);

        $response->assertRedirect(route('setting.otorisasi-menu', ['role' => 'manager']));
        $this->get(route('setting.otorisasi-menu', ['role' => 'manager']))
            ->assertOk()
            ->assertSee('name="permissions[master.template-penilaian][view]" value="1" data-parent-child="master" checked', false)
            ->assertDontSee('name="permissions[master.skala-predikat][view]" value="1" data-parent-child="master" checked', false);
        $this->assertTrue($first->fresh()->canAccessModule('master', 'view'));
        $this->assertTrue($second->fresh()->canAccessModule('laporan', 'view'));
        $this->assertTrue($first->fresh()->canAccessModule('master.template-penilaian', 'view'));
        $this->assertFalse($first->fresh()->canAccessModule('master.skala-predikat', 'view'));
    }

    public function test_checked_submenu_without_parent_does_not_grant_access(): void
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
                'master.skala-predikat' => ['view' => 1],
            ],
        ]);

        $response->assertRedirect(route('setting.otorisasi-menu', ['role' => 'staff']));
        $this->assertFalse($user->fresh()->canAccessModule('master', 'view'));
        $this->assertFalse($user->fresh()->canAccessModule('master.skala-predikat', 'view'));
        $this->assertDatabaseHas('role_menu_permissions', [
            'role' => 'staff',
            'permissions' => json_encode(['master.skala-predikat' => ['view' => true]]),
        ]);
    }

    public function test_parent_menu_and_submenu_permissions_are_independent(): void
    {
        $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'adminsuper',
        ]);

        $user = \App\Models\User::factory()->create([
            'role' => 'staff',
        ]);

        $this->post('/setting/otorisasi-menu', [
            'role' => 'staff',
            'permissions' => [
                'master.skala-predikat' => ['view' => 1],
            ],
        ])->assertRedirect(route('setting.otorisasi-menu', ['role' => 'staff']));

        $staff = $user->fresh();
        $this->assertFalse($staff->hasAccess('master'));
        $this->assertFalse($staff->hasAccess('master.skala-predikat'));
        $this->actingAs($staff)
            ->get(route('skala-predikat.index'))
            ->assertForbidden();

        $this->actingAs(\App\Models\User::where('email', 'superadmin@dp3.local')->firstOrFail());
        $this->post('/setting/otorisasi-menu', [
            'role' => 'staff',
            'permissions' => [
                'master' => ['view' => 1],
                'master.skala-predikat' => ['view' => 1],
            ],
        ])->assertRedirect(route('setting.otorisasi-menu', ['role' => 'staff']));

        $staff = $user->fresh();
        $this->assertTrue($staff->hasAccess('master'));
        $this->assertTrue($staff->hasAccess('master.skala-predikat'));
        $this->actingAs($staff)
            ->get(route('skala-predikat.index'))
            ->assertOk();

        $this->actingAs(\App\Models\User::where('email', 'superadmin@dp3.local')->firstOrFail());
        $this->get(route('setting.otorisasi-menu', ['role' => 'staff']))
            ->assertOk()
            ->assertSee('name="permissions[master][view]" value="1" data-parent-toggle="master" checked', false)
            ->assertSee('data-parent-row="master"', false)
            ->assertSee('name="permissions[master.skala-predikat][view]" value="1" data-parent-child="master" checked', false);
    }
}
