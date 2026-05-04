<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Modules\Auth\Services\KeycloakJwtValidator;
use Modules\Auth\Models\AuthUser;

class KeycloakJwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            abort(401, 'Authorization token required');
        }

        $payload = app(KeycloakJwtValidator::class)->validate($token);

        $user = AuthUser::fromJwtPayload($payload);

        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}
