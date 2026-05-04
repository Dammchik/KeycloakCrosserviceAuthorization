<?php

namespace App\Http\Middleware;

use App\Traits\MiddlewareParametersTrait;
use Closure;
use Illuminate\Http\Request;

class ServicesInteractionMiddleware
{
    use MiddlewareParametersTrait;

    static array $defaultParams = [
        'except' => [],
        'actions' => [],
    ];

    private function isServicesInteraction(Request $request): bool
    {
        return $request->hasHeader(env('SERVICES_INTERACTION_KEY')) &&
            $request->header(env('SERVICES_INTERACTION_KEY')) === env('SERVICES_INTERACTION_TOKEN');
    }

    public function handle(Request $request, Closure $next, ...$params)
    {
        if (!$this->shouldSkipMiddlewareByParameters($request, $params)) {
            $request->merge(["isServicesInteraction" => $this->isServicesInteraction($request)]);
        }

        return $next($request);
    }
}
