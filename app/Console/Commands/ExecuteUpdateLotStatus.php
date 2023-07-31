<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ExecuteUpdateLotStatus extends Command
{
    protected $signature = 'execute:update-status';
    protected $description = 'Execute the lots:update-status command after 1 minute';

    public function handle()
    {
        // Delay execution by 2 minute
        sleep(120);

        // Call the lots:update-status command
        Artisan::call('lots:update-status');

        $this->info('lots:update-status command executed successfully.');
    }
}