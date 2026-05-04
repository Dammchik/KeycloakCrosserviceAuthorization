<?php

use Illuminate\Support\Facades\Route;
use Modules\TestService\Http\Controllers\TestController;
use Modules\ApiGateway\Http\Middleware\ApiGatewayMiddleware;

Route::prefix('v1')->middleware([
    ApiGatewayMiddleware::class,
    'keycloak.jwt'
])->group(function () {

    Route::prefix('test-service')->group(function () {

        Route::get('hello', [TestController::class, 'hello']);

    });

});
