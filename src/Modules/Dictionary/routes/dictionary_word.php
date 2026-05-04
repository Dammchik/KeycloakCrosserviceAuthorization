<?php

use App\Http\Middleware\RouteNamingMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Dictionary\Http\Controllers\DictionaryWordController;
use Modules\Dictionary\Http\Middleware\DictionaryWordModelExistsMiddleware;
use Modules\Dictionary\Http\Middleware\WordModelExistsMiddleware;

$prefix = 'dictionary';

$commonMiddlewares = [
    RouteNamingMiddleware::class.":prefix=$prefix",
    WordModelExistsMiddleware::class,
    DictionaryWordModelExistsMiddleware::class,
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
                Route::get('{wordId}', [DictionaryWordController::class, 'show'])->name("$prefix.show");
                Route::get('{wordName}', [DictionaryWordController::class, 'show'])->name("$prefix.show");
            });
    })
;
