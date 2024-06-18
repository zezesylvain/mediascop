<?php

namespace App\Console;

use App\Console\Commands\sendRecapSpeedNews06am;
use App\Console\Commands\sendRecapSpeedNews18pm;
use App\Console\Commands\sendRecapSpeedNewsNoon;
use App\Console\Commands\SendSpeednewsMail;
use App\Jobs\SendEmailJob;
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
        SendSpeednewsMail::class,
        sendRecapSpeedNews06am::class,
        sendRecapSpeedNewsNoon::class,
        sendRecapSpeedNews18pm::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //$schedule->job(SendEmailJob::class)->everyFiveMinutes();
        $schedule->command('command:sendRecapSpeedNews06am')->weekdays()
            ->dailyAt('06:00')->timezone('Africa/Abidjan')
            ->emailOutputTo('gninatin.coulibaly@gmail.com');
        $schedule->command('command:sendRecapSpeedNewsNoon')->weekdays()
            ->dailyAt('12:00')->timezone('Africa/Abidjan')
            ->emailOutputTo('gninatin.coulibaly@gmail.com');
        $schedule->command('command:sendRecapSpeedNews18pm')->weekdays()
            ->dailyAt('18:00')->timezone('Africa/Abidjan')
            ->emailOutputTo('gninatin.coulibaly@gmail.com');
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
