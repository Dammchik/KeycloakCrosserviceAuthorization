<?php

namespace App\Http\Middleware;

use App\Exceptions\BadActionHttpException;
use App\Traits\MiddlewareParametersTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RouteNamingMiddleware
{
    use MiddlewareParametersTrait;

    static array $defaultParams = [
        'base' => 'api',
        'prefix' => '',
        'postfixes' => [],
    ];

    public function handle(Request $request, Closure $next, ...$params)
    {
        ['base' => $base, 'prefix' => $prefix, 'postfixes' => $postfixes] = $this->parseParameters($params);

        $routeName = $request->route()?->getName() ?? '';

        $routePrefix = implode(
            '.',
            array_filter(
                [$base, $prefix],
                fn(string $value) => !str_is_empty($value)
            )
        );

        if (!str_is_empty($routePrefix)) {

            if (!Str::startsWith($routeName, "$routePrefix.")) {
                throw new BadActionHttpException();
            }

            $routeName =  Str::after($routeName, "$routePrefix.");
        }

        $routePostfix = Arr::first(
            $postfixes,
            fn(string $postfix) => Str::endsWith($routeName, ".$postfix"),
            ''
        );

        $routeShortName = str_is_empty($routePostfix)
            ? $routeName
            : Str::before($routeName, ".$routePostfix");

        $request->merge([
            'routeBasePrefix' => $base,
            'routePrefix' => $prefix,
            'routeName' => $routeShortName,
            'routePostfix' => $routePostfix,
            'routeNameWithPostfix' => $routeName,
        ]);

        return $next($request);
    }
}
