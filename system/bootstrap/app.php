<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        console: __DIR__.'/../routes/console.php',
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
    ->create();

RateLimiter::for('api', fn (Request $r) =>
    Limit::perMinute(60)->by($r->user()?->id ?? $r->ip()));

return $app;

