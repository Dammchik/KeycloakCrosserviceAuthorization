<?php

namespace Modules\Dictionary\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Dictionary\Models\Word;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WordModelExistsMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!$request->route()->hasParameter('wordName')) {
            return $next($request);
        }

        $params = request()->route()->parameters;

        $word = Word::query()
            ->where('value', $params['wordName'])
            ->first()
            ?? throw new NotFoundHttpException();

        request()->merge([
            'wordId' => $word->id,
            'model' => $word
        ]);

        return $next($request);
    }
}
