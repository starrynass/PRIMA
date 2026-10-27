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
        'employee_id',
        'password',
        'password_reveal',
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
        'password_reveal',
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

    private ?string $loadedMenuRole = null;

    private array $loadedMenuPermissions = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'pgw_id');
    }

    public static function roleCatalog(): array
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return [
                'superadmin' => 'Super Admin',
                'direktur' => 'Direktur',
                'manager' => 'Manager',
                'asmen' => 'Asmen',
                'staff' => 'Staff',
            ];
        }

        return Role::orderBy('name')->pluck('name', 'slug')->all();
    }

    public function normalizeRole(?string $role): string
    {
        $normalized = strtolower(trim((string) ($role ?? 'staff')));

        return match ($normalized) {
            'employee', 'staff', 'karyawan', 'pegawai' => 'staff',
            'superadmin', 'super_admin', 'super admin', 'super-admin' => 'superadmin',
            'admin_sdm', 'adminsdm', 'admin sdm', 'admin-sdm' => 'admin_sdm',
            'direktur', 'director' => 'direktur',
            'manager', 'manajer' => 'manager',
            'asmen', 'assistant manager', 'asisten manager', 'asisten manajer' => 'asmen',
            default => $normalized,
        };
    }

    public function roleDisplayName(): string
    {
        return self::roleCatalog()[ $this->normalizeRole($this->role) ] ?? ucfirst($this->normalizeRole($this->role));
    }

    public function levelForRole(?string $role): int
    {
        return match ($this->normalizeRole($role)) {
            'staff' => 1,
            'asmen' => 2,
            'manager' => 3,
            'direktur' => 4,
            'admin_sdm' => 5,
            'superadmin' => 6,
            default => 1,
        };
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->normalizeRole($this->role), ['superadmin', 'admin_sdm'], true);
    }

    public function effectivePermissions(): array
    {
        $customPermissions = $this->permissions;

        if (!is_array($customPermissions)) {
            $customPermissions = json_decode((string) $customPermissions, true) ?? [];
        }

        if (!is_array($customPermissions)) {
            $customPermissions = [];
        }

        $permissions = [];

        foreach ($customPermissions as $module => $actions) {
            if (!is_array($actions)) {
                continue;
            }

            foreach ($actions as $action => $value) {
                $permissions[$module][$action] = (bool) $value;
            }
        }

        return $permissions;
    }

    public function roleMenuPermissions(): array
    {
        $role = $this->normalizeRole($this->role);

        if ($this->loadedMenuRole !== $role) {
            $this->loadedMenuRole = $role;
            $permissions = RoleMenuPermission::where('role', $role)->first()?->permissions ?? [];
            $this->loadedMenuPermissions = is_array($permissions) ? $permissions : [];
        }

        return $this->loadedMenuPermissions;
    }

    public function forgetRoleMenuPermissions(): void
    {
        $this->loadedMenuRole = null;
        $this->loadedMenuPermissions = [];
    }

    public function hasAccess(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->roleMenuPermissions();

        if (str_contains($module, '.')) {
            [$parent] = explode('.', $module, 2);

            return (bool) ($permissions[$parent]['view'] ?? false)
                && (bool) ($permissions[$module]['view'] ?? false);
        }

        if (array_key_exists($module, $permissions)) {
            return (bool) ($permissions[$module]['view'] ?? false);
        }

        return false;
    }

    public function hasAction(string $module, string $action): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($action === 'view') {
            return $this->hasAccess($module);
        }

        return (bool) ($this->effectivePermissions()[$module][$action] ?? false);
    }

    public function canAccessModule(string $module, string $action = 'view'): bool
    {
        return $this->hasAction($module, $action);
    }
}
