<?php

namespace Modules\Dictionary\Http\Middleware;

use App\Http\Middleware\ModelExistsMiddleware;
use Illuminate\Http\Request;
use Modules\Dictionary\Models\DictionaryWord;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DictionaryWordModelExistsMiddleware extends ModelExistsMiddleware
{
    protected bool $forcedFind = true;

    public function find(Request $request): void
    {
        $params = request()->route()->parameters;
        $wordId = $params['wordId'] ?? request()->get('wordId') ?? throw new NotFoundHttpException();
        $dictionaryId = $params['dictionaryId'] ?? request()->get('dictionaryId') ?? throw new NotFoundHttpException();

        $model = DictionaryWord::query()
            ->where('dictionary_id', $dictionaryId)
            ->where('word_id', $wordId)
            ->first() ?? throw new NotFoundHttpException();

        request()->merge(['model' => $model]);
    }
}
