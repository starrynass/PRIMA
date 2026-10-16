<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasAccess
{
    public function handle(Request $request, Closure $next, string $module, string $action = 'view')
    {
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->canAccessModule($module, $action)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
