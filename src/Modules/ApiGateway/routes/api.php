<?php
//
//use Illuminate\Support\Facades\Route;
//use Modules\ApiGateway\Http\Controllers\GatewayController;
//
//Route::prefix('v1')
//    ->middleware(['keycloak.auth'])
//    ->group(function () {
//
//        // User Service
//        Route::any('/users/{any?}', [GatewayController::class, 'proxy'])
//            ->where('any', '.*');
//
//        //
//        Route::any('/projects/{any?}', [GatewayController::class, 'proxy'])
//            ->where('any', '.*');
//    });
