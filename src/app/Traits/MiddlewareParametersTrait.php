<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait MiddlewareParametersTrait
{
    /**
     * Парсинг параметров в формате key=value.
     *
     * key=str
     * key=[val1|val2]
     *
     * @param  array  $params
     * @return array
     */
    protected function parseParameters(array $params): array
    {
        $parsedParams = static::$defaultParams ?? [];

        foreach ($params as $param) {
            if (str_contains($param, '=')) {
                [$key, $value] = explode('=', $param, 2);

                if (Str::startsWith($value, '[') && Str::endsWith($value, ']')) {
                    $value = trim($value, '[]');
                    $value = explode('|', $value);
                }

                $parsedParams[$key] = $value;
            }
        }

        return $parsedParams;
    }

    protected function shouldSkipMiddlewareByParameters(Request $request, $params): bool
    {
        $routeNameWithPostfix = $request->routeNameWithPostfix;
        ['except' => $except, 'actions' => $actions] = $this->parseParameters($params);
        $isUseExcept = is_array($except) && !array_is_empty($except);
        $isUseActions = is_array($actions) && !array_is_empty($actions);

        return ($isUseExcept && in_array($routeNameWithPostfix, $except, true)) ||
            (!$isUseExcept && $isUseActions && !in_array($routeNameWithPostfix, $actions, true));
    }
}
