<?php

namespace App\Console;

use App\Console\Commands\PayoutCryptoCurrencyUpdateCron;
use App\Console\Commands\PayoutCurrencyUpdateCron;
use App\Console\Commands\PlanInvestmentStatus;
use App\Console\Commands\ProjectInvestmentStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        PayoutCurrencyUpdateCron::class,
        PayoutCryptoCurrencyUpdateCron::class,
        PlanInvestmentStatus::class,
        ProjectInvestmentStatus::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('plan-investment-status')->everyFiveMinutes();
        $schedule->command('project-investment-status')->everyFiveMinutes();
        $schedule->command('model:prune')->days(1);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
