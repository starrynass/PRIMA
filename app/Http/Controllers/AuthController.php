<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected function loginAsUser(string $username, string $password, Request $request): bool
    {
        if (strtolower($username) === 'superadmin' && $password === 'adminsuper') {
            $user = User::firstOrCreate(
                ['email' => 'superadmin@dp3.local'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('adminsuper'),
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();

            return true;
        }

        if (preg_match('/^\d+$/', $username) && $password === '1234') {
            $employee = Employee::where('nup', $username)->first();

            if ($employee) {
                $user = User::firstOrCreate(
                    ['email' => $employee->nup . '@dp3.local'],
                    [
                        'name' => $employee->nama,
                        'password' => Hash::make('1234'),
                    ]
                );

                Auth::login($user);
                $request->session()->regenerate();

                return true;
            }
        }

        return false;
    }

    public function redirectToApp()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.penilaian');
        }

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.penilaian');
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
            return redirect()->intended(route('dashboard.penilaian'));
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
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard.penilaian'));
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
