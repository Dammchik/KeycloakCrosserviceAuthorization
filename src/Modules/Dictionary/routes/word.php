<?php

use App\Http\Middleware\ModelExistsMiddleware;
use App\Http\Middleware\RouteNamingMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Dictionary\Http\Controllers\WordController;
use Modules\Dictionary\Http\Middleware\WordModelExistsMiddleware;

$prefix = 'dictionary';

$commonMiddlewares = [
    RouteNamingMiddleware::class.":prefix=$prefix",
    WordModelExistsMiddleware::class,
    ModelExistsMiddleware::class,
];

Route::middleware($commonMiddlewares)
    ->where([
        'id' => '\d+',
    ])
    ->group(function () use ($prefix) {
        Route::prefix('word')
            ->group(function () use ($prefix) {
                Route::get('{id}', [WordController::class, 'show'])->name("$prefix.show");
                Route::get('{wordName}', [WordController::class, 'show'])->name("$prefix.show");
            });
    });
