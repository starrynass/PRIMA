<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
    ];

    public function isSuperAdmin(): bool
    {
        return strtolower((string) ($this->role ?? 'employee')) === 'superadmin';
    }

    public function defaultPermissions(): array
    {
        return [
            'dashboard' => ['view' => true],
            'master' => ['view' => true, 'create' => true, 'update' => true, 'delete' => true],
            'penilaian' => ['view' => true, 'create' => true, 'update' => true, 'delete' => true, 'verify' => true],
            'laporan' => ['view' => true],
            'settings' => ['view' => true, 'manage' => true],
        ];
    }

    public function defaultEmployeePermissions(): array
    {
        return [
            'dashboard' => ['view' => true],
            'master' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'penilaian' => ['view' => true, 'create' => true, 'update' => true, 'delete' => false, 'verify' => false],
            'laporan' => ['view' => false],
            'settings' => ['view' => false, 'manage' => false],
        ];
    }

    public function effectivePermissions(): array
    {
        $base = $this->isSuperAdmin() ? $this->defaultPermissions() : $this->defaultEmployeePermissions();
        $customPermissions = $this->permissions ?? [];

        foreach ($base as $module => $actions) {
            foreach ($actions as $action => $value) {
                $base[$module][$action] = (bool) ($customPermissions[$module][$action] ?? $value);
            }
        }

        return $base;
    }

    public function hasAccess(string $module): bool
    {
        return (bool) ($this->effectivePermissions()[$module]['view'] ?? false);
    }

    public function hasAction(string $module, string $action): bool
    {
        return (bool) ($this->effectivePermissions()[$module][$action] ?? false);
    }

    public function canAccessModule(string $module, string $action = 'view'): bool
    {
        return $this->hasAction($module, $action);
    }
}
