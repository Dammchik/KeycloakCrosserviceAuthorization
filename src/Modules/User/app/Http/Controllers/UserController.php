<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    protected UserService $users;

    public function __construct(UserService $users)
    {
        $this->users = $users;
    }

    public function me(Request $request): JsonResponse
    {
        $userInfo = $request->attributes->get('kc_user');
        return response()->json($userInfo);
    }

    public function list(): JsonResponse
    {
        return response()->json($this->users->getUsers());
    }

    public function logout(Request $request): JsonResponse
    {
        $refreshToken = $request->bearerToken();
        return response()->json($this->users->logout($refreshToken));
    }
}
