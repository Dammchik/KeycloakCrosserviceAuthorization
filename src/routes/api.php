<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('api')->group(function () {

    Route::get('/metrics', function () {

        $registry = app(CollectorRegistry::class);
        $renderer = new RenderTextFormat();

        $result = $renderer->render($registry->getMetricFamilySamples());

        return response($result, 200)
            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
    });

});
