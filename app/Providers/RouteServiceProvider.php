<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/user/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Routes Admin. Thêm tiền tố admin. vào trước URL
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

            // Route Vendor
            Route::middleware(['web', 'auth', 'role:vendor'])
                ->prefix('vendor')
                ->as('vendor.')
                ->group(base_path('routes/vendor.php'));

            // Route shipper
            Route::middleware(['web', 'auth', 'role:shipper'])
                ->prefix('shipper')
                ->as('shipper.')
                ->group(base_path('routes/shipper.php'));
        });
    }
}
