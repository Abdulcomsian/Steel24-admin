<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LotWinnerNotification;
use Illuminate\Support\Facades\Mail;

class LotWinnerMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $lotWinner;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lastBidder)
    {
        $this->lotWinner = $lastBidder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->lotWinner->email)->send(new LotWinnerNotification($this->lotWinner->name));
    }
}
