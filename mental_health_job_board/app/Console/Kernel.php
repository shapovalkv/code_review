<?php

namespace App\Console;

use App\Console\Commands\AutoCompleteGigOrder;
use App\Console\Commands\ScanExpiredUserPlan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Payout\Commands\CreatePayoutsCommand;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user_plan:expired')->hourly()->withoutOverlapping()->onOneServer();
        $schedule->command('user_plan:waiting')->hourly()->withoutOverlapping()->onOneServer();
        $schedule->command(CreatePayoutsCommand::class)->monthlyOn(15)->onOneServer();
        $schedule->command('job:expired')->dailyAt('00:01')->timezone('America/Los_Angeles')->withoutOverlapping()->onOneServer();
        $schedule->command('announcement:expired')->dailyAt('00:01')->timezone('America/Los_Angeles')->withoutOverlapping()->onOneServer();
        $schedule->command('announcement:expired5days')->dailyAt('00:01')->timezone('America/Los_Angeles')->withoutOverlapping()->onOneServer();
//        $schedule->command('cron:test')->everyFiveMinutes()->withoutOverlapping()->onOneServer();
//        $schedule->call(new AutoCompleteGigOrder())->hourly()->withoutOverlapping()->withoutOverlapping()->onOneServer();
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
