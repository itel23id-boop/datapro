<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
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

