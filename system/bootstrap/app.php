<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        console: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(
        middleware: [
            // \App\Http\Middleware\TrustHosts::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ],
        middlewareGroups: [
            'web' => [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                // \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \App\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],

            'api' => [
                // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                'throttle:api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
        ],
        routeMiddleware: [
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'check.role' => \App\Http\Middleware\CheckRole::class
        ]
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('get:notify')->everyMinute();
        $schedule->command('get:refund')->everyMinute();
        $schedule->command('get:flashsale')->everyMinute();
        $schedule->command('get:partaisocmed')->everyMinute();
        $schedule->command('get:irvankede')->everyMinute();
        $schedule->command('get:vipmember')->everyMinute();
        $schedule->command('get:istanamarket')->everyMinute();
        $schedule->command('get:fanstore')->everyMinute();
        $schedule->command('get:rasxmedia')->everyMinute();
        $schedule->command('update:pembelian')->everyMinute();
        $schedule->command('update:pembayaran')->everyMinute();
        $schedule->command('update:pesanan')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $e) {
            $statusCode = $e->getStatusCode();

            if ($statusCode === 404) {
                return response()->view('errors.404', [], 404);
            } elseif ($statusCode === 500) {
                return response()->view('errors.500', [], 500);
            }
        });
    })
    ->create();

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

return $app;
