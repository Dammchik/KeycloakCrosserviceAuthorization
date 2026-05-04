<?php

namespace Modules\Auth\Http\Middleware;

use Closure;

class KeycloakRoleMiddleware
{
    public function handle($request, Closure $next, string $role)
    {
        $user = $request->get('auth_user');

        if (!$user || !$user->hasRole($role)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
