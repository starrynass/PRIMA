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
}
