<?php

namespace App\Console\Commands;

use App\Models\lots;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LotChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lot:change:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Cron job update the lot status according to the START DATE';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date_status = '';
        $current_date = Carbon::now();
        $current_date = $current_date->format('Y-m-d H:i:s');
        $lots = lots::all();
        foreach ($lots as $key => $lot) 
        {
            $date_status = $this->checkDateStatus($lot->StartDate, $current_date, $lot->EndDate);
            if($date_status == 1)
            {
                $lot->lot_status = "Active";
            }
            else if ($date_status == 2) 
            {
                $lot->lot_status = "Expired";
            }
            else
            {
                $lot->lot_status = "Pending";
            }
            $lot->save();

        }
        $items = $lots;
        $progressbar = $this->output->createProgressBar(count($lots));
        $progressbar->start();
        foreach ($items as $item) 
        {
            sleep(1);
            $progressbar->setMessage("Lot Status Perfomed");
            $progressbar->advance();
        }
        $progressbar->finish();
        return 0;
    }

    public function checkDateStatus($start_date=null, $current_date, $end_date=null) 
    {
        if($end_date<=$current_date)
        {
            return 2;
        }
        else if($start_date<=$current_date)
        {
            return 1;
        }
        else
        {
            return 3;
        }
    }
}
