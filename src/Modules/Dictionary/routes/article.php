<?php

use App\Http\Middleware\RouteNamingMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Dictionary\Http\Controllers\ArticleController;
use Modules\Dictionary\Http\Middleware\ArticleModelExistsMiddleware;
use Modules\Dictionary\Http\Middleware\DictionaryWordModelExistsMiddleware;
use Modules\Dictionary\Http\Middleware\WordModelExistsMiddleware;

$prefix = 'dictionary';

$commonMiddlewares = [
    RouteNamingMiddleware::class.":prefix=$prefix",
    WordModelExistsMiddleware::class,
    DictionaryWordModelExistsMiddleware::class,
    ArticleModelExistsMiddleware::class,
];

Route::middleware($commonMiddlewares)
    ->where([
        'dictionaryId' => '\d+',
        'wordId' => '\d+',
        'id' => '\d+',
    ])
    ->group(function () use ($prefix) {

        Route::prefix('{dictionaryId}/word')
            ->group(function () use ($prefix) {
                Route::get('{wordId}/article/{id}', [ArticleController::class, 'show'])->name("$prefix.show");
                Route::get('{wordName}/article/{articleNumber}', [ArticleController::class, 'show'])->name("$prefix.show");
            });
    })
;
