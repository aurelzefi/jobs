<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Alert;
use App\Models\Company;
use App\Models\Job;
use App\Models\Order;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/jobs/all';

    public function boot(): void
    {
        Route::model('alert', Alert::class);
        Route::model('company', Company::class);
        Route::model('job', Job::class);
        Route::model('order', Order::class);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::prefix('api')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web-api.php'));

            Route::prefix('{locale?}')
                ->middleware(['web', 'locale'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        url()->defaults(['locale' => app()->getLocale()]);
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
