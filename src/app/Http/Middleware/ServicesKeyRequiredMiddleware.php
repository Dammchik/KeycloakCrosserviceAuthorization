<?php

namespace App\Http\Middleware;

use App\Exceptions\BadActionHttpException;
use App\Traits\MiddlewareParametersTrait;
use Closure;
use Illuminate\Http\Request;

class ServicesKeyRequiredMiddleware
{
    use MiddlewareParametersTrait;

    static array $defaultParams = [
        'except' => [],
        'actions' => [],
    ];

    public function handle(Request $request, Closure $next, ...$params)
    {
        if (
            $this->shouldSkipMiddlewareByParameters($request, $params) ||
            $request->isServicesInteraction
        ) {
            return $next($request);
        }

        throw new BadActionHttpException();
    }
}
