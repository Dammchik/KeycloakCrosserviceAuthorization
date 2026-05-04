<?php

namespace Modules\ApiGateway\Services;

use Illuminate\Http\Request;

class GatewayService
{
    public function resolveService(Request $request): ?string
    {
        $path = $request->path();

        // api/v1/dictionary
        $segments = explode('/', $path);

        return $segments[2] ?? null;
    }
}
