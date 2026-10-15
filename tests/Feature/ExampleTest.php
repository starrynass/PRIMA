<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
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
}
