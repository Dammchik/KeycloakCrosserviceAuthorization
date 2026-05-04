<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Modules\GostechUtilities\Http\Middleware\AuditMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once base_path().'/app/Functions/GlobalHelpers.php';

//        $this->app->singleton(AuditMiddleware::class);

    }
}
