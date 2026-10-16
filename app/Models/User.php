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

    public static function roleCatalog(): array
    {
        return [
            'superadmin' => 'Super Admin',
            'direktur' => 'Direktur',
            'manager' => 'Manager',
            'staff' => 'Staff',
        ];
    }

    public function normalizeRole(?string $role): string
    {
        $normalized = strtolower(trim((string) ($role ?? 'staff')));

        return match ($normalized) {
            'employee', 'staff', 'karyawan', 'pegawai' => 'staff',
            'superadmin', 'super_admin' => 'superadmin',
            'direktur', 'director' => 'direktur',
            'manager', 'manajer' => 'manager',
            default => array_key_exists($normalized, self::roleCatalog()) ? $normalized : 'staff',
        };
    }

    public function roleDisplayName(): string
    {
        return self::roleCatalog()[ $this->normalizeRole($this->role) ] ?? 'Staff';
    }

    public function levelForRole(?string $role): int
    {
        return match ($this->normalizeRole($role)) {
            'staff' => 1,
            'manager' => 2,
            'direktur' => 3,
            'superadmin' => 4,
            default => 0,
        };
    }

    public function isSuperAdmin(): bool
    {
        return $this->normalizeRole($this->role) === 'superadmin';
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

    public function defaultDirekturPermissions(): array
    {
        return [
            'dashboard' => ['view' => true],
            'master' => ['view' => true, 'create' => false, 'update' => false, 'delete' => false],
            'penilaian' => ['view' => true, 'create' => true, 'update' => true, 'delete' => false, 'verify' => true],
            'laporan' => ['view' => true],
            'settings' => ['view' => true, 'manage' => false],
        ];
    }

    public function defaultManagerPermissions(): array
    {
        return [
            'dashboard' => ['view' => true],
            'master' => ['view' => true, 'create' => true, 'update' => true, 'delete' => false],
            'penilaian' => ['view' => true, 'create' => true, 'update' => true, 'delete' => false, 'verify' => true],
            'laporan' => ['view' => true],
            'settings' => ['view' => false, 'manage' => false],
        ];
    }

    public function defaultEmployeePermissions(): array
    {
        return [
            'dashboard' => ['view' => true],
            'master' => ['view' => false, 'create' => false, 'update' => false, 'delete' => false],
            'penilaian' => ['view' => true, 'create' => true, 'update' => false, 'delete' => false, 'verify' => false],
            'laporan' => ['view' => false],
            'settings' => ['view' => false, 'manage' => false],
        ];
    }

    public function defaultRolePermissions(?string $role): array
    {
        $normalized = $this->normalizeRole($role);

        return match ($normalized) {
            'superadmin' => $this->defaultPermissions(),
            'direktur' => $this->defaultDirekturPermissions(),
            'manager' => $this->defaultManagerPermissions(),
            'staff' => $this->defaultEmployeePermissions(),
            default => $this->defaultEmployeePermissions(),
        };
    }

    public function effectivePermissions(): array
    {
        $base = $this->defaultRolePermissions($this->normalizeRole($this->role));
        $customPermissions = $this->permissions;

        if (!is_array($customPermissions)) {
            $customPermissions = json_decode((string) $customPermissions, true) ?? [];
        }

        if (!is_array($customPermissions)) {
            $customPermissions = [];
        }

        foreach ($customPermissions as $module => $actions) {
            if (!is_array($actions)) {
                continue;
            }

            if (!isset($base[$module]) || !is_array($base[$module])) {
                $base[$module] = [];
            }

            foreach ($actions as $action => $value) {
                $base[$module][$action] = (bool) $value;
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
