<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\{ LotLoserNotification , LotWinnerNotification };
use App\Models\BidsOfLots;

class LotMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $lot;
    protected $lotWinner;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lot , $lotWinner)
    {
        $this->lot = $lot;
        $this->lotWinner = $lotWinner;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $lotBids = BidsOfLots::with('customer')->where('lotId' , $this->lot->id)->distinct('customerId')->get(['customerId']);

        foreach($lotBids as $bid)
        {
            if($this->lotWinner->id != $bid->customerId )
            {
                Mail::to($bid->customer->email)->send(new LotLoserNotification($this->lot->id, $bid->customer->name));
            }
            else{
                Mail::to($this->lotWinner->email)->send(new LotWinnerNotification($this->lotWinner->name));
            }
        }

    }
}
