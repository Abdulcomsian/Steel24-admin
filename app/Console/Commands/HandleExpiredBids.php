<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\BidsOfLots;

class HandleExpiredBids extends Command
{
    protected $signature = 'bid:handle-expired';
    protected $description = 'Handle expired bids and close the lots';

    public function handle()
    {
        $currentTime = Carbon::now();
        $twoMinutesAgo = $currentTime->subMinutes(2);

        $expiredBids = BidsOfLots::where('created_at', '<', $twoMinutesAgo)->get();

        foreach ($expiredBids as $bid) 
        {
            // Perform any necessary actions for closing the lot or notifying users
            // For example: $bid->lot->update(['status' => 'closed']);

            // Trigger the bid-closed event using Pusher
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]);

            $pusher->trigger('bids', 'bid-closed', ['message' => 'You are late! Sorry, another person won this lot.', 'success' => false]);
        }
    }
}
