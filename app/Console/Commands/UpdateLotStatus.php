<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\lots;

class UpdateLotStatus extends Command
{
    protected $signature = 'lots:update-status';
    protected $description = 'Update lot status to Sold for lots without new bids within the last two minutes';

    public function handle()
    {
        // info('job is running');

        // Find lots with live status and no new bids in the last two minutes
        // $currentTime = Carbon::now();
        // $twoMinutesAgo = $currentTime->subMinutes(2);



        // $currentTime = Carbon::now();
        // $twoMinutesLess = $currentTime->subMinutes(2);

        // lots::where('lot_status' , 'live')
        //       ->whereHas('bids' , function($query) use ($twoMinutesLess) 
        //       {
        //         $query->where('created_at' , $twoMinutesAgo);
        //       })->get();




        // $lotsToUpdate = lots::where('lot_status', 'live')
        //     ->whereHas('bids', function ($query) use ($twoMinutesAgo) 
        //     {
        //         $query->where('created_at', '>', $twoMinutesAgo);
        //     }, '<', 1)
        //     ->get();

        // foreach ($lotsToUpdate as $lot) 
        // {
        //     // Update the lot status to Sold
        //     $lot->update(['lot_status' => 'Sold']);
        // }


    //     // Get the current time and two minutes ago
    //     $currentTime = Carbon::now();
    //     $twoMinutesAgo = $currentTime->subMinutes(2);

    //     // Update the lot_status to 'Sold' for lots without new bids in the last two minutes
    //     $numUpdatedLots = Lots::where('lot_status', 'live')
    //         ->whereDoesntHave('bids', function ($query) use ($twoMinutesAgo) {
    //             $query->where('created_at', '>', $twoMinutesAgo);
    //         })
    //         ->update(['lot_status' => 'Sold']);

    //     // Fetch the lots that were updated
    //     $updatedLots = Lots::where('lot_status', 'Sold')
    //         ->whereDoesntHave('bids', function ($query) use ($twoMinutesAgo) 
    //         {
    //             $query->where('created_at', '>', $twoMinutesAgo);
    //         })
    //         ->get();

    //     // Log the number of lots updated
    //     $this->info("{$numUpdatedLots} lots have been updated to 'Sold' status.");
    // }

        // Get the current time and two minutes ago
        $currentTime = Carbon::now();
        $twoMinutesAgo = $currentTime->subMinutes(2);

        // Find lots with live status and no new bids in the last two minutes
        $lotsToUpdate = lots::where('lot_status', 'live')
            ->whereDoesntHave('bids', function ($query) use ($twoMinutesAgo) 
            {
                $query->where('created_at', '>', $twoMinutesAgo);
            })
            ->get();

        foreach ($lotsToUpdate as $lot)
        {
            // Check if the latest bid time is greater than two minutes ago
            $latestBid = $lot->bids()->latest('created_at')->first();

            if ($latestBid) 
            {
                $latestBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $latestBid->created_at);
                if ($latestBidTime->greaterThan($twoMinutesAgo)) {
                    // The lot is still involved in the bidding process, do not update its status
                    continue;
                }
            } else {
                // The lot has no bids, do not update its status
                continue;
            }

            // Update the lot status to Sold
            $lot->update(['lot_status' => 'Sold']);

            // Perform any other necessary actions here

            // Log that the lot status has been updated
            $this->info("Lot {$lot->id} has been updated to 'Sold' status.");
        }
    }
}
