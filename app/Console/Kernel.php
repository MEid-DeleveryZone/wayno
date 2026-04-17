<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CartReminder::class,
        Commands\AutoRejectOrders::class,
        Commands\SetDummyDataForDemo::class,
        Commands\SendOrderEncouragementNotification::class
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('cart:reminder')->hourly();
        //$schedule->command('auto:reject_order')->everyMinute();
        //$schedule->command('set_default_dummy:data')->dailyAt('00:30');
        $schedule->command('notification:encourage-orders')->weeklyOn(3, '12:00'); // Every Wednesday at 12:00 PM
        // $schedule->command('inspire')->hourly();
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
