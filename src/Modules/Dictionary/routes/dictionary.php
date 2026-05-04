<?php

use App\Http\Middleware\ModelExistsMiddleware;
use App\Http\Middleware\RouteNamingMiddleware;
use App\Http\Middleware\ServicesKeyRequiredMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\ApiGateway\Http\Middleware\ApiGatewayMiddleware;
use Modules\Dictionary\Http\Controllers\DictionaryController;
use Modules\Dictionary\Http\Controllers\DictionaryIndexController;

$prefix = 'dictionary';

$commonMiddlewares = [
    ApiGatewayMiddleware::class,
    'keycloak.jwt',
    RouteNamingMiddleware::class.":prefix=$prefix",
    ServicesKeyRequiredMiddleware::class.':actions=[import]',
    ModelExistsMiddleware::class.':add=[import]',
];

Route::middleware($commonMiddlewares)
    ->group(function () use ($prefix) {
        Route::get('', DictionaryIndexController::class)->name("$prefix.index");
        Route::post('', [DictionaryController::class, 'store'])
            ->middleware('keycloak.role:laravel.admin')
            ->name("$prefix.store");

        Route::prefix('{id}')
            ->where(['id' => '\d+'])
            ->group(function () use ($prefix) {
                Route::get('', [DictionaryController::class, 'show'])->name("$prefix.show");
                Route::patch('', [DictionaryController::class, 'update'])
                    ->middleware('keycloak.role:laravel.admin')
                    ->name("$prefix.update");
                Route::delete('', [DictionaryController::class, 'destroy'])
                    ->middleware('keycloak.role:laravel.admin')
                    ->name("$prefix.destroy");
                Route::patch('alias', [DictionaryController::class, 'alias'])
                    ->middleware('keycloak.role:laravel.admin')
                    ->name("$prefix.alias");
                Route::post('import', [DictionaryController::class, 'importArticle'])
                    ->middleware('keycloak.role:laravel.admin')
                    ->name("$prefix.import");
            });
    })
;
