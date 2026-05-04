<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Traits\MiddlewareParametersTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 *
 */
class ModelExistsMiddleware
{
    use MiddlewareParametersTrait;

    protected $model;
    protected array $actionsWithModel;
    protected bool $forcedFind = false;

    static array $defaultParams = [
        'except' => [],
        'actions' => ['show', 'update', 'destroy'],
        'add' => [],
    ];

    protected function shouldSkipMiddleware(Request $request, array $parameters): bool
    {
        $actionName = $request->routeNameWithPostfix ?? '';
        ['except' => $except, 'actions' => $actions, 'add' => $add] = $this->parseParameters($parameters);
        $this->actionsWithModel = array_merge($actions, $add);

        return in_array($actionName, $except) || !in_array($actionName, $this->actionsWithModel);
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  mixed  ...$parameters
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$parameters)
    {
        if (
            !$this->shouldSkipMiddleware($request, $parameters) && ($this->forcedFind ||
                var_is_null($request->get('model')))
        ) {

            $this->find($request);
        }

        return $next($request);
    }

    public function find(Request $request): void
    {
        /** @var Controller $controller */
        [$controller] = explode('@', Route::currentRouteAction());
        $request->merge(['model' => $controller::findNestedOrAbort()]);
    }
}
