<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\lots;
use Symfony\Component\HttpFoundation\Response;
use App\Events\winLotsEvent;
use Pusher\Pusher;
use App\Jobs\LotMail;
use App\Models\{BidsOfLots , CustomerLot};


class UpdateLotStatus extends Command
{
    protected $signature = 'lots:update-status';
    protected $description = 'Update lot status to Sold for lots without new bids within the last two minutes';

    public function handle()
    {
        //new code starts here
        $lots = lots::with('bids.customer','autobids.customer')->whereNotIn('lot_status' ,  ['Sold' , 'Expired'])->get();        
        $currentTime = Carbon::now();
        
        foreach($lots as $lot)
        {
            $startTime = Carbon::createFromFormat("Y-m-d H:i:s" , $lot->StartDate);
            $endTime = Carbon::createFromFormat("Y-m-d H:i:s" , $lot->EndDate);

            //turn on lot that are not working and their starting time passed away
            if($startTime < $currentTime && $endTime > $currentTime && $lot->lot_status != "live")
            {
                $lot->lot_status = "live";
            }

            if( $endTime < $currentTime && $lot->lot_status != "live"){
                $lot->lot_status = "Expired";
            }


            if($lot->lot_status == "live")
            {

                if(!$lot->bids->isEmpty())
                {
                    info("if live");
    
                    $lastBid = $lot->bids()->latest()->first();
    
                    $lastBidTime = Carbon::createFromFormat("Y-m-d H:i:s" ,$lastBid->created_at);
    
                    $timeDifferenceInSeconds = $lastBidTime->diffInSeconds($currentTime);

                    info(`time difference $timeDifferenceInSeconds`);
    
                    if($timeDifferenceInSeconds <= 120)
                    {
    
                        $newPricing = $lastBid->amount;
    
                        if($lot->autobids->count() == 1)
                        {
                            //if previous bid done by same customer then return
                            $autoBidder = $lot->autobids->first();
                            $customer = $autoBidder->customer;
                            if($autoBidder->customerId == $lastBid->customerId)
                            {
                                return;
                            }else{
                                $autoBid = BidsOfLots::create([
                                    "customerId" => $autoBidder->customerId,
                                    "amount" => $newPricing+100,
                                    "lotId" => $lot->id,
                                    "autoBid" => 1,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
        
                                event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));
        
                            }


                        }else{

                            foreach($lot->autoBids as $bidder)
                            {
                                $newPricing = $newPricing + 100;
                                $customer = $bidder->customer;
                                $autoBid = BidsOfLots::create([
                                    "customerId" => $bidder->customerId,
                                    "amount" => $newPricing,
                                    "lotId" => $lot->id,
                                    "autoBid" => 1,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
        
                                event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));
        
                            }

                        }

                    }else{
    
                        info("else");
                        $lastBid = $lot->bids()->latest()->first();
                        $latestBidCustomer = $lastBid->customer;
                        
                        $customerLot = CustomerLot::updateOrCreate(
                            ['lot_id' => $lot->id],
                            ['lot_id' => $lot->id  , 'customer_id' => $latestBidCustomer->id , 'created_at' => date('Y-m-d H:i:s')]
                        );
                        if( $customerLot ){
                            $lot->lot_status = "Sold";
                            $lot->save();
                            
                            dispatch(new LotMail($lot , $latestBidCustomer));
                            //sending winner bidders email  
    
                            event(new winLotsEvent('Bidding Has Been Won By The Customer', $lastBid, $latestBidCustomer, true));
    
                        }
    
                        info("Bidding Assign To User");
    
                    }
                    
                }
                else
                {
                    info("empty bid");

                    $newPricing = $lot->price;

                    foreach($lot->autoBids as $bidder)
                    {

                        $newPricing = $newPricing + 100;
                        $customer = $bidder->customer;
                        $autoBid = BidsOfLots::create([
                            "customerId" => $bidder->customerId,
                            "amount" => $newPricing,
                            "lotId" => $lot->id,
                            "autoBid" => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                    event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));


                    }
                }

            }


        }

        //new code ends here


        //   // Get the current time and two minutes ago
        // $currentTime = Carbon::now();
        // $twoMinutesAgo = $currentTime->subMinutes(2);

        // // Find lots with live status and no new bids in the last two minutes
        // $lotsToUpdate = lots::where('lot_status', 'live')
        //     ->whereDoesntHave('bids', function ($query) use ($twoMinutesAgo) {
        //         $query->where('created_at', '>', $twoMinutesAgo);
        //     })
        //     ->get();

        // $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
        //     'cluster' => env('PUSHER_APP_CLUSTER'),
        //     'useTLS' => true,
        // ]);

        // foreach ($lotsToUpdate as $lot) {
        //     // Check if the latest bid time is greater than two minutes ago
        //     $latestBid = $lot->bids()->latest('created_at')->first();

        //     if ($latestBid) {
        //         $latestBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $latestBid->created_at);
        //         if ($latestBidTime->greaterThan($twoMinutesAgo)) {
        //             // The lot is still involved in the bidding process, do not update its status
        //             continue;
        //         }
        //     } else {
        //         // The lot has no bids, do not update its status
        //         continue;
        //     }

        //     // Update the lot status to Sold
        //     $lot->update(['lot_status' => 'Sold']);

        //     // Send the Pusher event to notify that the lot is sold with the last bid data and customer details
        //     $pusher->trigger('steel24', 'Sold Lots', [
        //         'message' => 'You are late! Sorry, another person won this lot.',
        //         'detail' => array_merge($latestBid->toArray(), ['customer' => $latestBid->customer ?? null]),
        //         'success' => true,
        //     ]);
            

        //     // // Log that the lot status has been updated
        //     // $this->info("Lot {$lot->id} has been updated to 'Sold' status.");
        // }
       
    }
}
