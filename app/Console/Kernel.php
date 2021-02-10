<?php

declare(strict_types=1);

namespace App\Console;

use App\Jobs\SendJobExpiresTodayNotifications;
use App\Jobs\SendNewJobsDailyNotifications;
use App\Jobs\SendNewJobsWeeklyNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('telescope:prune')->daily();

        $schedule->job(new SendJobExpiresTodayNotifications())->dailyAt('09:00');
        $schedule->job(new SendNewJobsDailyNotifications())->dailyAt('09:00');
        $schedule->job(new SendNewJobsWeeklyNotifications())->mondays()->at('09:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
