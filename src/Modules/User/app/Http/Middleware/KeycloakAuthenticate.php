<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;

class KeycloakAuthenticate
{
    public function __construct(protected UserService $userService) {}

    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Missing Bearer token'], 401);
        }

        $token = substr($header, 7);

        $userinfo = $this->userService->getUserInfo($token);

        if (!isset($userinfo['sub'])) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        $request->attributes->set('kc_user', $userinfo);

        return $next($request);
    }
}
