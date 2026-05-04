<?php

namespace Modules\Dictionary\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Dictionary\Models\DictionaryWord;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleModelExistsMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        /** @var DictionaryWord $dictionaryWord */
        $dictionaryWord = request()->get('model');

        $params = request()->route()->parameters;

        $article = Arr::has($params, 'articleNumber')
            ? $dictionaryWord->articles()->where('number', $params['articleNumber'])->first() ?? throw new NotFoundHttpException()
            : $dictionaryWord->articles()->where('id', $params['id'])->first() ?? throw new NotFoundHttpException();

        request()->merge(['model' => $article]);

        return $next($request);
    }
}
