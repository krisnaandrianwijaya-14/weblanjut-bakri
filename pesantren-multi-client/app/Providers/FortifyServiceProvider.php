<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\LoginViewResponse;
use App\Http\Responses\LogoutResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding manual karena provider Fortify package
        // tidak ter-load otomatis di project ini.
        $this->app->singleton(
            LoginViewResponseContract::class,
            LoginViewResponse::class,
        );

        $this->app->singleton(
            LoginResponseContract::class,
            LoginResponse::class,
        );

        $this->app->singleton(
            LogoutResponseContract::class,
            LogoutResponse::class,
        );
    }

    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });
    }
}
