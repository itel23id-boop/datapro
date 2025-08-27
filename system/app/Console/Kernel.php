<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
/**
* The Artisan commands provided by your application.
*
* @var array
*/
protected $commands = [
//
];

/**
* Define the application's command schedule.
*
* @param \Illuminate\Console\Scheduling\Schedule $schedule
* @return void
*/

protected function schedule(Schedule $schedule)
{
// $schedule->command('inspire')
// ->hourly();
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

}

/**
* Register the commands for the application.
*
* @return void
*/
protected function commands()
{
$this->load(__DIR__.'/Commands');

require base_path('routes/console.php');
}
}
