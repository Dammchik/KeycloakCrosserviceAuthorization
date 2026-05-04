<?php

use App\Http\Middleware\JsonResponseMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([JsonResponseMiddleware::class])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('dictionary')->group(function () {
            require __DIR__.'/dictionary.php';
            require __DIR__.'/dictionary_word.php';
            require __DIR__.'/article.php';
            require __DIR__.'/word.php';
        });
    });
});
