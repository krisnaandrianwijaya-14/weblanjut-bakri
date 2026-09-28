<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function (): void {
        // Platform Admin
        Route::middleware(['web', 'auth', 'verified', 'role:platform_admin'])
            ->prefix('platform')
            ->name('platform.')
            ->group(base_path('routes/platform.php'));

        // Admin Pesantren
        Route::middleware(['web', 'auth', 'verified', 'client', 'role:admin_pesantren'])
            ->prefix('client/{client:slug}/admin')
            ->name('client.admin.')
            ->scopeBindings()
            ->group(base_path('routes/client.php'));

        // Guru
        Route::middleware(['web', 'auth', 'verified', 'client', 'role:guru'])
            ->prefix('client/{client:slug}/guru')
            ->name('client.guru.')
            ->scopeBindings()
            ->group(base_path('routes/guru.php'));

        // Wali Asrama
        Route::middleware(['web', 'auth', 'verified', 'client', 'role:wali_asrama'])
            ->prefix('client/{client:slug}/asrama')
            ->name('client.asrama.')
            ->scopeBindings()
            ->group(base_path('routes/asrama.php'));

        // Wali Santri
        Route::middleware(['web', 'auth', 'verified', 'client', 'role:wali_santri'])
            ->prefix('client/{client:slug}/wali')
            ->name('client.wali.')
            ->scopeBindings()
            ->group(base_path('routes/wali.php'));

        // Santri
        Route::middleware(['web', 'auth', 'verified', 'client', 'role:santri'])
            ->prefix('client/{client:slug}/santri')
            ->name('client.santri.')
            ->scopeBindings()
            ->group(base_path('routes/santri.php'));
    },
)
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role'   => \App\Http\Middleware\EnsureUserHasRole::class,
        'client' => \App\Http\Middleware\SetClientContext::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
