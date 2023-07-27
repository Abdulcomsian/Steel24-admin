<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// use App\Console\Commands\CheckLotsStatus;
use App\Console\Commands\UpdateLotStatus; // Add this line to include the command class



class Kernel extends ConsoleKernel
{

    // protected $commands = [
    //     // Other commands...
    //     \App\Console\Commands\UpdateLotStatus::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Add the task to update lot status to Sold for lots without new bids after 2 minutes
        $schedule->command('lots:update-status')->everyTwoMinutes();
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
        
        // $this->commands([
        //     CheckLotsStatus::class,
        // ]);
       
    }

    
    
}
