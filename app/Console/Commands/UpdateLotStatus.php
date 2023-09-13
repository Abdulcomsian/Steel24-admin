<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\lots;
use Symfony\Component\HttpFoundation\Response;
use App\Events\{winLotsEvent , LotStatusUpdate};
use Pusher\Pusher;
use App\Jobs\LotMail;
use App\Models\{BidsOfLots , CustomerLot , customerBalance , LotParticipant};
use Illuminate\Support\Facades\DB;



class UpdateLotStatus extends Command
{
    protected $signature = 'lots:update-status';
    protected $description = 'Update lot status to Sold for lots without new bids after the end date';

    public function handle()
    {
        //Two minute code starts here 
        //new code starts here
        // $lots = lots::with('bids.customer','autobids.customer' , 'participant')->whereNotIn('lot_status' ,  ['Sold' , 'Expired'])->get();        
        // $currentTime = Carbon::now();
        
        // foreach($lots as $lot)
        // {
        //     $startTime = Carbon::createFromFormat("Y-m-d H:i:s" , $lot->StartDate);
        //     $endTime = Carbon::createFromFormat("Y-m-d H:i:s" , $lot->EndDate);

        //     //turn on lot that are not working and their starting time passed away
        //     if($startTime < $currentTime && $endTime > $currentTime && $lot->lot_status != "live")
        //     {
        //         $lot->lot_status = "live";
        //     }

        //     if( $endTime < $currentTime && $lot->lot_status != "live"){
        //         $lot->lot_status = "Expired";
        //     }


        //     if($lot->lot_status == "live")
        //     {

        //         if(!$lot->bids->isEmpty())
        //         {
    
        //             $lastBid = $lot->bids()->latest()->orderBy('id','desc')->first();
    
        //             $lastBidTime = Carbon::createFromFormat("Y-m-d H:i:s" ,$lastBid->created_at);
    
        //             $timeDifferenceInSeconds = $lastBidTime->diffInSeconds($currentTime);
    
        //             if($timeDifferenceInSeconds <= 120)
        //             {
    
        //                 $newPricing = $lastBid->amount;
    
        //                 if($lot->autobids->count() == 1)
        //                 {
        //                     info("if");
        //                     //if previous bid done by same customer then return
        //                     $autoBidder = $lot->autobids->first();
        //                     $customer = $autoBidder->customer;
        //                     if($autoBidder->customerId == $lastBid->customerId)
        //                     {
        //                         return;
        //                     }else{
        //                         $autoBid = BidsOfLots::create([
        //                             "customerId" => $autoBidder->customerId,
        //                             "amount" => $newPricing+100,
        //                             "lotId" => $lot->id,
        //                             "autoBid" => 1,
        //                             'created_at' => date('Y-m-d H:i:s'),
        //                             'updated_at' => date('Y-m-d H:i:s')
        //                         ]);
        
        //                         event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));
        
        //                     }


        //                 }else{
        //                     info("else | $lastBid->amount");
        //                     foreach($lot->autoBids as $bidder)
        //                     {
        //                         $newPricing = $newPricing + 100;
        //                         info("Pricing $newPricing");
        //                         $customer = $bidder->customer;
        //                         $autoBid = BidsOfLots::create([
        //                             "customerId" => $bidder->customerId,
        //                             "amount" => $newPricing,
        //                             "lotId" => $lot->id,
        //                             "autoBid" => 1,
        //                             'created_at' => date('Y-m-d H:i:s'),
        //                             'updated_at' => date('Y-m-d H:i:s')
        //                         ]);
        
        //                         event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));
        
        //                     }

        //                 }

        //             }else{
        //                 info("last else 2 minute gone");
        //                 $lastBid = $lot->bids()->latest()->orderBy('id' , 'desc')->first();
        //                 $latestBidCustomer = $lastBid->customer;
                        
        //                 $customerLot = CustomerLot::updateOrCreate(
        //                     ['lot_id' => $lot->id],
        //                     ['lot_id' => $lot->id  , 'customer_id' => $latestBidCustomer->id , 'created_at' => date('Y-m-d H:i:s')]
        //                 );
        //                 if( $customerLot ){
        //                     $lot->lot_status = "Sold";
        //                     $lot->save();
                            
        //                     dispatch(new LotMail($lot , $latestBidCustomer));
        //                     //sending winner bidders email  
        //                     event(new winLotsEvent('Bidding Has Been Won By The Customer', $lastBid, $latestBidCustomer, false));


        //                     $participationAmount = $lot->participate_fee;
        //                     foreach($lot->participant as $participant)
        //                     {
        //                         if($lastBid->customerId != $participant->id){
        //                             $lastCustomerBalance = CustomerBalance::where('customerId' , $participant->id)->orderBy('id' , 'desc')->first();
        //                             $newAmount = $lastCustomerBalance->balanceAmount + $participationAmount;
        //                             $customerBalance = new CustomerBalance;
        //                             $customerBalance->customerId = $participant->id;
        //                             $customerBalance->balanceAmount = $newAmount;
        //                             $customerBalance->finalAmount = $newAmount;
        //                             $customerBalance->actionAmount = $participationAmount;
        //                             $customerBalance->action = "Return Participation Fee";
        //                             $customerBalance->lotId = $lot->id;
        //                             $customerBalance->created_at = date("Y-m-d H:i:s");
        //                             $customerBalance->updated_at = date("Y-m-d H:i:s");
        //                             $customerBalance->status  = 1; 
        //                             $customerBalance->save();
        //                             LotParticipant::where('lot_id' , $lot->id)->where('customer_id' , $participant->id)->update(['status' => 'Participate Fees Back']);
        //                         }
        //                     }

    
        //                 }
    
        //             }
                    
        //         }
        //         else
        //         {

        //             $newPricing = $lot->price;

        //             foreach($lot->autoBids as $bidder)
        //             {

        //                 $newPricing = $newPricing + 100;
        //                 $customer = $bidder->customer;
        //                 $autoBid = BidsOfLots::create([
        //                     "customerId" => $bidder->customerId,
        //                     "amount" => $newPricing,
        //                     "lotId" => $lot->id,
        //                     "autoBid" => 1,
        //                     'created_at' => date('Y-m-d H:i:s'),
        //                     'updated_at' => date('Y-m-d H:i:s')
        //                 ]);

        //             event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));


        //             }
        //         }

        //     }

        //             $newPricing = $lot->price;

        //             foreach($lot->autoBids as $bidder)
        //             {

        //                 $newPricing = $newPricing + 100;
        //                 $customer = $bidder->customer;
        //                 $autoBid = BidsOfLots::create([
        //                     "customerId" => $bidder->customerId,
        //                     "amount" => $newPricing,
        //                     "lotId" => $lot->id,
        //                     "autoBid" => 1,
        //                     'created_at' => date('Y-m-d H:i:s'),
        //                     'updated_at' => date('Y-m-d H:i:s')
        //                 ]);

        //             event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $customer, true));

        // }
        //Two minute code ends here
        info("update lot status running");
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        info($currentDateTime);
        $upcomingLots = lots::where('StartDate' , '<=' ,$currentDateTime)
                                        ->where('EndDate' , '>=' ,$currentDateTime)
                                        ->where('lot_status' , 'Upcoming')
                                        ->get();

        foreach($upcomingLots as $lot)
        {
            event(new LotStatusUpdate($lot));
            $lot->lot_status = 'live';
            $lot->save();
            // ->update(['lot_status' => 'live']);
        }
        
        //setting expired lots
        lots::doesntHave('bids')
            ->where('EndDate' , '<=' ,$currentDateTime)
            ->where('lot_status' , 'live')
            ->update(['lot_status' => 'Expired']);
            //->get();
            // ->get();

        //updating lots to STA where time has been over
            // lots::with('bids')->whereHas('bids')
        

            $runningLot = lots::with('bids.customer')
                                ->whereHas('bids')
                                ->where('EndDate' , '<=' , $currentDateTime)
                                ->where('lot_status' , 'live')
                                ->get();


            foreach($runningLot as $lot){
                $lastBid = $lot->bids()->latest()->orderBy('id' , 'desc')->first();
                $latestBidCustomer = $lastBid->customer;
                $customerLot = CustomerLot::updateOrCreate(
                                        ['lot_id' => $lot->id],
                                        ['lot_id' => $lot->id  , 'customer_id' => $latestBidCustomer->id , 'created_at' => date('Y-m-d H:i:s')]
                                    );
                if( $customerLot ){
                        $lot->lot_status = "STA";
                        $lot->save();
                        
                        dispatch(new LotMail($lot , $latestBidCustomer));
                        //sending winner bidders email  
                        event(new winLotsEvent('Bidding Has Been Won By The Customer', $lastBid, $latestBidCustomer, false));
                }
            }
    }
}