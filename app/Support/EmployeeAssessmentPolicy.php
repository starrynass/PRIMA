<?php

namespace App\Support;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class EmployeeAssessmentPolicy
{
    private const DIRECTOR_OCCUPATION_IDS = [1, 2, 3];
    private const MANAGER_OCCUPATION_IDS = [5];
    private const ASMEN_OCCUPATION_IDS = [6, 14, 17, 18, 19, 20, 21, 22, 23, 26];
    private const STAFF_OCCUPATION_IDS = [9, 10];

    public static function roleForEmployee(?Employee $employee): ?string
    {
        if (!$employee) {
            return null;
        }

        $occupationId = (int) $employee->occ_id;

        return match (true) {
            in_array($occupationId, self::DIRECTOR_OCCUPATION_IDS, true) => 'direktur',
            in_array($occupationId, self::MANAGER_OCCUPATION_IDS, true) => 'manager',
            in_array($occupationId, self::ASMEN_OCCUPATION_IDS, true) => 'asmen',
            in_array($occupationId, self::STAFF_OCCUPATION_IDS, true) => 'staff',
            default => null,
        };
    }

    public static function targetOccupationIdsForEvaluator(string $role): array
    {
        return match ($role) {
            'asmen' => self::STAFF_OCCUPATION_IDS,
            'manager' => self::ASMEN_OCCUPATION_IDS,
            'direktur' => self::MANAGER_OCCUPATION_IDS,
            default => [],
        };
    }

    public static function canEmployeeEvaluate(?Employee $evaluator, ?Employee $target): bool
    {
        $evaluatorRole = self::roleForEmployee($evaluator);
        $targetRole = self::roleForEmployee($target);

        return $evaluator
            && $target
            && (int) $evaluator->is_hapus === 0
            && (int) $target->is_hapus === 0
            && $evaluatorRole
            && $targetRole
            && self::targetRoleForEvaluator($evaluatorRole) === $targetRole
            && self::sameUnit($evaluator, $target);
    }

    public static function canEvaluate(User $user, ?Employee $target): bool
    {
        $actor = self::employeeForUser($user);
        return self::canEmployeeEvaluate($actor, $target);
    }

    public static function canEdit(User $user, object $transaction): bool
    {
        $target = Employee::query()->find($transaction->pegawai_id);
        if (!self::canEvaluate($user, $target)) {
            return false;
        }

        $actor = self::employeeForUser($user);
        $status = strtoupper(trim((string) ($transaction->status_nilai ?? '')));
        if (!in_array($status, ['', 'BELUM DIISI', 'DRAFT', 'DIKEMBALIKAN', 'REVISI'], true)) {
            return false;
        }

        return empty($transaction->penilai_id)
            || (int) $transaction->penilai_id === (int) $actor->pgw_id
            || (int) $transaction->penilai_id === (int) $user->id;
    }

    public static function canViewEvaluation(User $user, object $transaction): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $target = Employee::query()->find($transaction->pegawai_id);
        $actor = self::employeeForUser($user);

        return self::canEvaluate($user, $target)
            && ($actor !== null)
            && (empty($transaction->penilai_id)
                || (int) $transaction->penilai_id === (int) $actor->pgw_id
                || (int) $transaction->penilai_id === (int) $user->id);
    }

    public static function canVerify(User $user, object $transaction): bool
    {
        $actor = self::employeeForUser($user);
        $target = Employee::query()->find($transaction->pegawai_id);
        $evaluator = Employee::query()->find($transaction->penilai_id);
        $actorRole = self::roleForEmployee($actor);
        $targetRole = self::roleForEmployee($target);
        $evaluatorRole = $evaluator
            ? self::roleForEmployee($evaluator)
            : self::evaluatorRoleForTarget($targetRole);

        if (!$actor || !$target || !$actorRole || !$evaluatorRole || !$targetRole
            || (int) $actor->is_hapus !== 0
            || (int) $target->is_hapus !== 0
            || !self::sameUnit($actor, $target)
            || ($evaluator && ((int) $evaluator->is_hapus !== 0 || !self::sameUnit($actor, $evaluator)))) {
            return false;
        }

        if ($actorRole === 'manager' && $evaluatorRole === 'asmen' && $targetRole === 'staff') {
            return true;
        }

        if ($actorRole === 'direktur') {
            if ($evaluatorRole === 'asmen' && $targetRole === 'staff') {
                return true;
            }

            if ($evaluatorRole === 'manager' && $targetRole === 'asmen') {
                return true;
            }

            return $evaluatorRole === 'direktur'
                && $targetRole === 'manager'
                && (!$evaluator || (int) $evaluator->pgw_id === (int) $actor->pgw_id);
        }

        return false;
    }

    public static function applyEvaluationListScope(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        $actor = self::employeeForUser($user);
        $actorRole = self::roleForEmployee($actor);
        $targetOccupationIds = $actorRole ? self::targetOccupationIdsForEvaluator($actorRole) : [];

        if (!$actor || !$targetOccupationIds || !$actor->kode_unit_kerja) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereIn('e.occ_id', $targetOccupationIds)
            ->where('e.is_hapus', 0)
            ->where('e.kode_unit_kerja', $actor->kode_unit_kerja)
            ->where(function (Builder $scope) use ($actor, $user) {
                $scope->whereNull('tp.penilai_id')
                    ->orWhere('tp.penilai_id', $actor->pgw_id)
                    ->orWhere('tp.penilai_id', $user->id);
            });
    }

    public static function applyVerificationListScope(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        $actor = self::employeeForUser($user);
        $actorRole = self::roleForEmployee($actor);

        if (!$actor || !$actorRole || !$actor->kode_unit_kerja) {
            return $query->whereRaw('1 = 0');
        }

        $query->where('e.kode_unit_kerja', $actor->kode_unit_kerja)
            ->where('e.is_hapus', 0)
            ->where(function (Builder $scope) use ($actor) {
                $scope->whereNull('tp.penilai_id')
                    ->orWhere(function (Builder $assigned) use ($actor) {
                        $assigned->where('penilai.is_hapus', 0)
                            ->where('penilai.kode_unit_kerja', $actor->kode_unit_kerja);
                    });
            })
            ->where(function (Builder $scope) use ($actor, $user) {
                $scope->whereNull('tp.verifikator_id')
                    ->orWhere('tp.verifikator_id', $actor->pgw_id)
                    ->orWhere('tp.verifikator_id', $user->id);
            });

        if ($actorRole === 'manager') {
            return $query
                ->whereIn('e.occ_id', self::STAFF_OCCUPATION_IDS)
                ->where(function (Builder $scope) {
                    $scope->whereNull('tp.penilai_id')
                        ->orWhereIn('penilai.occ_id', self::ASMEN_OCCUPATION_IDS);
                });
        }

        if ($actorRole === 'direktur') {
            return $query->where(function (Builder $scope) use ($actor): void {
                $scope->where(function (Builder $hierarchy): void {
                    $hierarchy->whereIn('e.occ_id', self::STAFF_OCCUPATION_IDS)
                        ->where(function (Builder $evaluator) {
                            $evaluator->whereNull('tp.penilai_id')
                                ->orWhereIn('penilai.occ_id', self::ASMEN_OCCUPATION_IDS);
                        });
                });
                $scope->orWhere(function (Builder $hierarchy): void {
                    $hierarchy->whereIn('e.occ_id', self::ASMEN_OCCUPATION_IDS)
                        ->where(function (Builder $evaluator) {
                            $evaluator->whereNull('tp.penilai_id')
                                ->orWhereIn('penilai.occ_id', self::MANAGER_OCCUPATION_IDS);
                        });
                });
                $scope->orWhere(function (Builder $selfVerifiedManager) use ($actor): void {
                    $selfVerifiedManager->whereIn('e.occ_id', self::MANAGER_OCCUPATION_IDS)
                        ->where(function (Builder $evaluator) use ($actor) {
                            $evaluator->whereNull('tp.penilai_id')
                                ->orWhere(function (Builder $assigned) use ($actor) {
                                    $assigned->whereIn('penilai.occ_id', self::DIRECTOR_OCCUPATION_IDS)
                                        ->where('tp.penilai_id', $actor->pgw_id);
                                });
                        });
                });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    private static function employeeForUser(User $user): ?Employee
    {
        return $user->employee_id ? Employee::query()->find($user->employee_id) : null;
    }

    private static function sameUnit(Employee $first, Employee $second): bool
    {
        return trim((string) $first->kode_unit_kerja) !== ''
            && trim((string) $first->kode_unit_kerja) === trim((string) $second->kode_unit_kerja);
    }

    private static function targetRoleForEvaluator(string $role): ?string
    {
        return match ($role) {
            'asmen' => 'staff',
            'manager' => 'asmen',
            'direktur' => 'manager',
            default => null,
        };
    }

    private static function evaluatorRoleForTarget(?string $targetRole): ?string
    {
        return match ($targetRole) {
            'staff' => 'asmen',
            'asmen' => 'manager',
            'manager' => 'direktur',
            default => null,
        };
    }
}
