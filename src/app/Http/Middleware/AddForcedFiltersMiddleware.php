<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Handle an incoming request.
 */
class AddForcedFiltersMiddleware
{

    protected Request $request;
    /**
     * @var bool $useFullName При значении `true` используется полное имя
     */
    protected bool $useFullName = true;

    /**
     * @param string $postFix
     *
     * @return array
     */
    protected function getFilterListByPostfix(string $postFix) : array
    {
        return [];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function getFilterListByName(string $name) : array
    {
        return [];
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->request = $request;

        $routeName = $this->useFullName
            ? $request->routeNameWithPostfix
            : $request->routeName;

        $request->merge([
            'forcedFilters' => array_merge(
                $this->getFilterListByName($routeName ?? ''),
                $this->getFilterListByPostfix($request->routePostfix ?? '')
            ),
        ]);

        return $next($request);
    }
}
