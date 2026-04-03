<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user() ?? auth()->user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        if (! empty($roles)) {
            $currentRole = $user->role instanceof UserRole ? $user->role->value : $user->role;

            if (! in_array($currentRole, $roles, true)) {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }
}
