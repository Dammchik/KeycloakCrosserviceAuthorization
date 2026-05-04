<?php

namespace App\Http\Middleware;

use App\Exceptions\BadActionHttpException;
use App\Traits\MiddlewareParametersTrait;
use Closure;
use Illuminate\Http\Request;

/**
 *
 */
class ActionModelAccess
{
    use MiddlewareParametersTrait;

    protected $model;

    static array $defaultParams = [
        'except' => [],
        'actions' => [],
    ];

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  mixed  ...$params
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$params): mixed {
        if (
            !env('APP_CHECK_ACCESS', true) ||
            $this->shouldSkipMiddlewareByParameters($request, $params)
        ) {
            return $next($request);
        }

        $this->model = $request->model;
        $conditionChecker = $this->getConditionsForAction($request->routeNameWithPostfix);

        if (var_is_null($conditionChecker)) {
            throw new BadActionHttpException();
        }

        $conditionChecker();

        return $next($request);
    }

    protected function getConditionsForAction(string $actionName): ?Closure
    {
        return null;
    }
}
