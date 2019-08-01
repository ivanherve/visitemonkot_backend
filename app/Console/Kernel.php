<?php

namespace App\Console;

use App\Console\Commands\FeedbackVisit;
use App\Console\Commands\HideAccomodation;
use App\Console\Commands\RemindVisit;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FeedbackVisit::class,
        RemindVisit::class,
        HideAccomodation::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:feedback')->everyMinute() ;
        $schedule->command('command:remind')->everyMinute() ;
        $schedule->command('command:hide')->everyMinute() ;
    }
}
