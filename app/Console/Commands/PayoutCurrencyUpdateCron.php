<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PayoutCurrencyUpdateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payout-currency-update-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to payout fiat currency conversion rate update.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
