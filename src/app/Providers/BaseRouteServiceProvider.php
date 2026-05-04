<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\User\Http\Middleware\UserSubstitution;

class BaseRouteServiceProvider extends ServiceProvider
{
    protected string $name = '';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->middleware(env('USER_SUBSTITUTION', false) ? [UserSubstitution::class] : [])
            ->prefix(env('API_PREFIX', 'api'))
            ->name('api.')
            ->group(module_path($this->name, '/routes/api.php'))
        ;
    }
}
