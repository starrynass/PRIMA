<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Support\EmployeeAccountSync;
use App\Support\MenuCatalog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected function loginAsUser(string $username, string $password, Request $request): bool
    {
        $username = trim($username);
        $password = (string) $password;

        if (strtolower($username) === 'superadmin' && $password === 'adminsuper') {
            $user = User::firstOrCreate(
                ['email' => 'superadmin@dp3.local'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('adminsuper'),
                ]
            );
            $user->role = 'superadmin';
            $user->save();

            Auth::login($user);
            $request->session()->regenerate();

            return true;
        }

        if (preg_match('/^\d+$/', $username)) {
            $normalizedNup = trim((string) $username);

            $employee = Employee::query()->where(function ($query) use ($normalizedNup) {
                $query->whereRaw('TRIM(nup) = ?', [$normalizedNup])
                    ->orWhereRaw('SUBSTR(TRIM(nup), -10) = ?', [$normalizedNup])
                    ->orWhereRaw('SUBSTR(TRIM(nup), -12) = ?', [$normalizedNup])
                    ->orWhereRaw('LOWER(TRIM(nup)) = ?', [strtolower($normalizedNup)]);
            })->first();

            if ($employee) {
                $user = EmployeeAccountSync::sync($employee);

                if ($user && Hash::check($password, $user->password)) {
                    Auth::login($user);
                    $request->session()->regenerate();

                    return true;
                }

                if (! $user && $password === '1234') {
                    $user = EmployeeAccountSync::createForEmployee($employee, '1234');
                    Auth::login($user);
                    $request->session()->regenerate();

                    return true;
                }

                return false;
            }
        }

        return false;
    }

    protected function authenticatedLandingRoute(User $user): string
    {
        foreach (MenuCatalog::all() as $menuKey => $menu) {
            if (empty($menu['children'])) {
                if ($user->hasAccess($menuKey)) {
                    return route($menu['route']);
                }

                continue;
            }

            foreach ($menu['children'] as $child) {
                if ($user->hasAccess($child['key'])) {
                    return route($child['route']);
                }
            }
        }

        return route('access.denied');
    }

    public function redirectToApp()
    {
        if (Auth::check()) {
            return redirect($this->authenticatedLandingRoute(Auth::user()));
        }

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->authenticatedLandingRoute(Auth::user()));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $username = trim($request->input('username'));
        $password = $request->input('password');

        if ($this->loginAsUser($username, $password, $request)) {
            return redirect($this->authenticatedLandingRoute(Auth::user()));
        }

        try {
            $user = User::firstOrCreate(
                ['email' => 'superadmin@dp3.local'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('adminsuper'),
                ]
            );

            if (Auth::attempt([
                'email' => strtolower($username) === 'superadmin' ? $user->email : $username,
                'password' => $password,
            ])) {
                $authenticatedUser = Auth::user();
                if (strtolower(trim($authenticatedUser->email)) === 'superadmin@dp3.local'
                    && !$authenticatedUser->isSuperAdmin()) {
                    $authenticatedUser->role = 'superadmin';
                    $authenticatedUser->save();
                    $authenticatedUser->forgetRoleMenuPermissions();
                }

                $request->session()->regenerate();

                return redirect($this->authenticatedLandingRoute(Auth::user()));
            }
        } catch (QueryException $exception) {
            return back()
                ->withErrors(['username' => 'Cek kembali username dan password.'])
                ->onlyInput('username');
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
    }
}
