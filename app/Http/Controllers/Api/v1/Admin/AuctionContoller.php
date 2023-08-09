<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\BidsOfLots;
use App\Http\Controllers\Admin\LotsController;
use App\Models\Customer;
use App\Models\customerBalance;
use App\Models\lots;
use App\Models\lotTerms;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
// use \Illuminate\Support\Carbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Tymon\JWTAuth\Payload;
use Illuminate\Support\Facades\Validator;
// use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;
use App\Events\winLotsEvent;
use App\Jobs\LotMail;
use Pusher\Pusher;
use Illuminate\Support\Facades\Mail;
use App\Mail\LotWinnerNotification;
use App\Mail\LotLoserNotification;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Support\Facades\Artisan;
use App\Models\AutoBid;
use App\Models\CustomerLot;
use App\Jobs\{ LotWinnerMail , LotMail as LotJob};



class AuctionContoller extends Controller
{
    private $user, $defaultNumber;

    public function auction(Request $request)
    {

        $request =  $request->validate([
            'requestActionType' => 'required',
            'customerId' => 'nullable',
        ]);


        $lotList = null;
        if ($request['requestActionType'] == 'current' && $request['customerId']) {
            $live = DB::select("SELECT * FROM `lots` WHERE (date(StartDate) = CURDATE() or date(EndDate) = CURDATE() OR date(ReStartDate) = CURDATE() OR date(ReEndDate) = CURDATE()) and lot_status  IN ('live','Restart','pause') ORDER BY LiveSequenceNumber;");


            // $liveList = DB::select("SELECT * FROM `lots` WHERE date(StartDate) = CURDATE() and lot_status = 'live';");
            // $custoemrDetais = DB::select("SELECT * FROM customer_balances WHERE date(created_at) = CURDATE() and action ='participate' and customerId = " . $request['customerId']);
            // dump($liveList);
            // dd($custoemrDetais);
            // $upcoming = DB::select("SELECT * FROM `lots` WHERE date(EndDate) =  CURDATE() and lot_status !=  'upcoming'");
            $upcoming = DB::select("SELECT * FROM `lots` WHERE  lot_status =  'upcoming'");
            $lotList = ['liveList' => $live, 'upcoming' => $upcoming];
        } elseif ($request['requestActionType'] == 'upcoming') {
            $lotList =  DB::select("SELECT * FROM `lots` WHERE StartDate >=NOW() ");
        } elseif ($request['requestActionType'] == 'sold') {
            $lotList = lots::where('lot_status', 'sold')->orderBy('id', 'desc')->get();
        } elseif ($request['requestActionType'] == 'expired') {
            $lotList = lots::where('lot_status', 'expired')->orderBy('id', 'desc')->get();
        }
        $response = null;
        if ($lotList) {
            $response = [
                'data' =>  $lotList,
                'sucess' => true,
            ];
        } else {
            $response = [
                'message' => 'No Record Found.',
                'sucess' => false,
            ];
        }

        return json_encode($response);
        //    $start = Carbon::parse($request->auction_date);

        //    $get_all_user = Auction::whereDate('date','<=',$end->format('m-d-y'))
        //    ->whereDate('date','>=',$start->format('m-d-y'));


    }

    public  function lotDetails(Request $request, $lotId)
    {

        // $materialidata =  newMaterial::where("lotid", $lotId)->get();

        // // dd($materialidata);
        // // if (count($materialidata)) {
        // //     $newMaterial = [];
        // //     foreach ($materialidata as $materiali) {
        // //         array_push($newMaterial, ['data' => (array) json_decode($materiali->materialdata), "image" => 'https://admin.steel24.in/files/' . $materiali->image, "materialid" => $materiali->materialid]);
        // //     }
        // // }
        // $materialilist = new_maerials_2::where('lotid', $lotId)->get();
        // $lotTerms = lotTerms::where('lotid', $lotId)->first();

        // $lotDetails = DB::select('SELECT lots.* ,bids_of_lots.amount as lastBidAmount from bids_of_lots RIGHT JOIN lots ON  lots.id = bids_of_lots.lotId 
        // WHERE lots.id  = ' . $lotId . ' 
        // ORDER by bids_of_lots.amount DESC LIMIT 1;');
        // return json_encode([
        //     'lotDetails' => $lotDetails,
        //     'materialList' => $materialilist,
        //     'lotTerms' => $lotTerms,
        //     'sucess' => true,
        // ]);




        // $materialidata = newMaterial::where("lotid", $lotId)->get();

        // $materialilist = new_maerials_2::where('lotid', $lotId)->get();
        // $lotTerms = lotTerms::where('lotid', $lotId)->first();
    
        // $lotDetails = DB::select('SELECT lots.* ,bids_of_lots.amount as lastBidAmount from bids_of_lots RIGHT JOIN lots ON  lots.id = bids_of_lots.lotId 
        //     WHERE lots.id  = ' . $lotId . ' 
        //     ORDER by bids_of_lots.amount DESC LIMIT 1;');
    
        // // Return the response in JSON format using response()->json()
        // return response()->json([
        //     'lotDetails' => $lotDetails,
        //     'materialList' => $materialilist,
        //     'lotTerms' => $lotTerms,
        //     'success' => true,
        // ]);

        $materialidata = newMaterial::where("lotid", $lotId)->get();
        $materialilist = new_maerials_2::where('lotid', $lotId)->get();
        $lotTerms = lotTerms::where('lotid', $lotId)->first();
        $maxbid = DB::table('bids_of_lots')
        ->select('customerId', 'amount', 'lotId', 'created_at')
        ->where('lotId',$lotId )
        ->orderBy('amount', 'DESC')
        ->first();

        $lotDetails = DB::select('SELECT lots.* ,bids_of_lots.amount as lastBidAmount from bids_of_lots RIGHT JOIN lots ON  lots.id = bids_of_lots.lotId 
            WHERE lots.id  = ' . $lotId . ' 
            ORDER by bids_of_lots.amount DESC LIMIT 1;');
        
        // Return the response in JSON format using response()->json()
        return response()->json([
            'lotDetails' => $lotDetails[0], // Assuming $lotDetails is not empty, get the first element
            'materialList' => $materialilist,
            'lotTerms' => $lotTerms,
            'maxbid' => $maxbid,
            'success' => true,
        ]);

    }


    // Payment API
    public function getLotPaymentId(Request $request)
    {
        $request =  $request->validate([
            'lotId' => 'required',
            'customerId' => 'required',
        ]);

        $lotdata = payments::where('lotId', $request['lotId'])->orderBy('id', 'desc')->first();

        if ($lotdata && $lotdata->customerId == $request['customerId']) {

            $lotdetails = lots::where('id', $request['lotId'])->orderBy('id', 'desc')->first();
            $custoemrDetais = Customer::where('id', $request['customerId'])->first();

            $api = new Api('rzp_test_8PGJWSObrIzWmn', 'u6ruIsAKiggwDMKQ2fyv3dPr');
            $orderDetails =  $api->order->create(array('receipt' => '', 'amount' =>  $lotdata->remaining_amount * 100, 'currency' => 'INR'));

            return response()->json([
                'orderDetails' => [
                    "id" =>  $orderDetails->id,
                    "entity" =>  $orderDetails->entity,
                    "amount" =>  $orderDetails->amount,
                    "amount_paid" =>  $orderDetails->amount_paid,
                    "amount_due" =>  $orderDetails->amount_due,
                    "currency" =>  $orderDetails->currency,
                    "receipt" =>  $orderDetails->receipt,
                    "offer_id" =>  $orderDetails->offer_id,
                    "status" =>  $orderDetails->status,
                    "attempts" =>  $orderDetails->attempts,
                    "created_at" =>  $orderDetails->created_at,
                ],
                'custoemrDetais' => $custoemrDetais,
                'lotDetails' => $lotdetails,
                'sucess' => true,
            ]);
        }
    }


    // Complete Payment
    public function completelotpayment(Request $request)
    {
        $lastPaymentDetails = payments::where('lotId', $request->lotId)->orderBy('id', 'desc')->first();

        payments::create([
            'lotId' => $lastPaymentDetails->lotId,
            'customerId' => $lastPaymentDetails->customerId,
            'total_amount' => $lastPaymentDetails->total_amount,
            'paid_amount' => $lastPaymentDetails->remaining_amount,
            'remaining_amount' => 0,
            'date' => Carbon::now(),
        ]);
        return response()->json([
            'sucess' => true,
            'message' => 'Payment Sucess.'
        ]);
    }


    // customer balance
    public function getCustomerBalance($custoemrId)
    {
        $lastBalance =  customerBalance::where('customerId', $custoemrId)->orderBy('id', 'desc')->first();
        $balanceHistory =  DB::select('SELECT customer_balances.*,lots.id as lotid, lots.title as lottitle from customer_balances
        LEFT JOIN lots on lots.id= customer_balances.lotid 
        WHERE customerId = ' . $custoemrId . ' ORDER BY id DESC;');
        return response()->json([
            'lastBalance' => $lastBalance,
            'balanceHistory' => $balanceHistory,
            'sucess' => true,
        ]);
    }

    public function addBalanceOrderIdGenerate(Request $request)
    {
        $custoemrDetais = Customer::where('id', $request->customerId)->first();

        $api = new Api('rzp_test_8PGJWSObrIzWmn', 'u6ruIsAKiggwDMKQ2fyv3dPr');
        $orderDetails =  $api->order->create(array('receipt' => '', 'amount' =>  $request->amount * 100, 'currency' => 'INR'));
        // dd($orderDetails);


        return response()->json([
            'orderDetails' => [
                "id" =>  $orderDetails->id,
                "entity" =>  $orderDetails->entity,
                "amount" =>  $orderDetails->amount,
                "amount_paid" =>  $orderDetails->amount_paid,
                "amount_due" =>  $orderDetails->amount_due,
                "currency" =>  $orderDetails->currency,
                "receipt" =>  $orderDetails->receipt,
                "offer_id" =>  $orderDetails->offer_id,
                "status" =>  $orderDetails->status,
                "attempts" =>  $orderDetails->attempts,
                "created_at" =>  $orderDetails->created_at,
            ],
            'custoemrDetais' => $custoemrDetais,
            'sucess' => true,
        ]);
    }

    public function addcustomerbalance(Request $request)
    {
        $lastBalance =  customerBalance::where('customerId', $request->customerId)->orderBy('id', 'desc')->first();
        $finalBalance = null;
        if ($lastBalance) 
        {
            $finalBalance = customerBalance::create([
                'customerId' => $request->customerId,
                'balanceAmount' => $lastBalance->finalAmount,
                'action' => 'credit',
                'actionAmount' => $request->amount,
                'finalAmount' => $lastBalance->finalAmount + $request->amount,
                'date' => Carbon::now(),
            ]);
        } else {
            $finalBalance = customerBalance::create([
                'customerId' => $request->customerId,
                'balanceAmount' => $request->amount,
                'action' => 'credit',
                'actionAmount' => $request->amount,
                'finalAmount' => $request->amount,
                'date' => Carbon::now(),
            ]);
        }
        return response()->json([
            'finalBalance' => $finalBalance,
            'sucess' => true,
        ]);
    }



    // Participate On Lot update by Z.R

    // public  function participateOnLot(Request $request)
    // {
    //     $custoemrDetais =  Customer::find($request->customerId);
    //     $response = null;
    //     if (isset($custoemrDetais->isApproved)) 
    //     {
    //         $lotDetails = lots::find($request->lotid);
    //         $lastBalance =  customerBalance::where('customerId', $request->customerId)->orderBy('id', 'desc')->first();

    //         if ($lastBalance && ($lotDetails->participate_fee  <= $lastBalance->finalAmount)) 
    //         {
               
    //             customerBalance::create([
    //                 'customerId' => $request->customerId,
    //                 'balanceAmount' => $lastBalance->finalAmount,
    //                 'action' => 'Participate Fees',
    //                 'actionAmount' => $lotDetails->participate_fee,
    //                 'finalAmount' => $lastBalance->finalAmount - $lotDetails->participate_fee,
    //                 'lotid' => $request->lotid,
    //                 'status' => 0,
    //                 'date' => Carbon::now(),
    //             ]);
               

    //             $participatedCustomers = customerBalance::where([['lotid', $request->lotid], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();
    //             $lotDetails['ParticipateUsers'] = $participatedCustomers;

                

    //             // $firebase = (new Factory)
    //             //     ->withServiceAccount(json_encode([
    //             //         "type" => "service_account",
    //             //         "project_id" => "steel24-a898f",
    //             //         "private_key_id" => "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
    //             //         "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
    //             //         "client_email" => "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
    //             //         "client_id" => "106107898058399972491",
    //             //         "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
    //             //         "token_uri" => "https://oauth2.googleapis.com/token",
    //             //         "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
    //             //         "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"

    //             //     ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');
    //             // $database = $firebase->createDatabase();
    //             // $database->getReference('TodaysLots/liveList/' . $request->lotid)->set($lotDetails);


    //             $response = [
    //                 'message' => 'User can participate on lot.',
    //                 'sucess' => true
    //             ];
    //         } else {
    //             $response = [
    //                 'message' => 'User don`t have enough balance.',
    //                 'sucess' => false
    //             ];
    //         }
    //     } else {
    //         $response = [
    //             'message' => 'User Is Not Active.',
    //             'sucess' => false
    //         ];
    //     }
    //     return response()->json($response);
    // }


    // Previous code 
    // public function participateOnLot(Request $request)
    // {
    //     $customerDetails = Customer::find($request->customerId);
    //     $response = null;

    //     if (isset($customerDetails->isApproved)) 
    //     {
    //         $lotDetails = lots::find($request->lotid);

    //         if (!$lotDetails) {
    //             return response()->json([
    //                 'message' => 'Lot not found',
    //                 'success' => false,
    //             ], Response::HTTP_NOT_FOUND);
    //         }

    //         $lastBalance = customerBalance::where('customerId', $request->customerId)->orderBy('id', 'desc')->first();

    //         if ($lastBalance && ($lotDetails->participate_fee <= $lastBalance->finalAmount)) 
    //         {
    //             customerBalance::create([
    //                 'customerId' => $request->customerId,
    //                 'balanceAmount' => $lastBalance->finalAmount,
    //                 'action' => 'Participate Fees',
    //                 'actionAmount' => $lotDetails->participate_fee,
    //                 'finalAmount' => $lastBalance->finalAmount - $lotDetails->participate_fee,
    //                 'lotid' => $request->lotid,
    //                 'status' => 0,
    //                 'date' => Carbon::now(),
    //             ]);

    //             $participatedCustomers = customerBalance::where([['lotid', $request->lotid], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();
    //             $lotDetails['ParticipateUsers'] = $participatedCustomers;

    //             $response = [
    //                 'message' => 'User can participate on lot.',
    //                 'success' => true,
    //             ];
    //         } else {
    //             $response = [
    //                 'message' => 'User don\'t have enough balance for participation',
    //                 'success' => false,
    //             ];
    //         }
    //     } else {
    //         $response = [
    //             'message' => 'User Is Not Active.',
    //             'success' => false,
    //         ];
    //     }

    //     return response()->json($response);
    // }

    public function participateOnLot(Request $request)
    {
        $customerDetails = Customer::find($request->customerId);
        $response = null;
    
        if (isset($customerDetails->isApproved)) {
            $lotDetails = lots::find($request->lotid);
    
            if (!$lotDetails) {
                return response()->json([
                    'message' => 'Lot not found',
                    'success' => false,
                ], Response::HTTP_NOT_FOUND);
            }
    
            // Check if the user has already participated in the same lot
            $existingParticipation = customerBalance::where('customerId', $request->customerId)
                ->where('lotid', $request->lotid)
                ->where('status', '!=', '1')
                ->first();
    
            if ($existingParticipation) {
                return response()->json([
                    'message' => 'User already paid the participation fee for this lot.',
                    'success' => true,
                ]);
            }
    
            $lastBalance = customerBalance::where('customerId', $request->customerId)->orderBy('id', 'desc')->first();
    
            if ($lastBalance && ($lotDetails->participate_fee <= $lastBalance->finalAmount)) 
            {
                customerBalance::create([
                    'customerId' => $request->customerId,
                    'balanceAmount' => $lastBalance->finalAmount,
                    'action' => 'Participate Fees',
                    'actionAmount' => $lotDetails->participate_fee,
                    'finalAmount' => $lastBalance->finalAmount - $lotDetails->participate_fee,
                    'lotid' => $request->lotid,
                    'status' => 0,
                    'date' => Carbon::now(),
                ]);
    
                $participatedCustomers = customerBalance::where([['lotid', $request->lotid], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();
                $lotDetails['ParticipateUsers'] = $participatedCustomers;
    
                $response = [
                    'message' => 'User can pay the participation fee successfully.',
                    'success' => true,
                ];
            } else {
                $response = [
                    'message' => 'User don\'t have enough balance for this participation.',
                    'success' => false,
                ];
            }
        } else {
            $response = [
                'message' => 'User Is Not Active.',
                'success' => false,
            ];
        }
    
        return response()->json($response);
    }

    

    // public function participateOnLot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'lotid' => 'required',
    //     ]);

    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDetails = lots::find($newBid['lotid']);

    //     if (!$lotDetails) {
    //         return response()->json([
    //             'message' => 'Lot not found',
    //             'success' => false,
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         // Check if the user has already participated in the same lot
    //         $existingParticipation = customerBalance::where('customerId', $newBid['customerId'])
    //             ->where('lotid', $newBid['lotid'])
    //             ->where('status', '!=', '1')
    //             ->first();

    //         if ($existingParticipation) {
    //             return response()->json([
    //                 'message' => 'User already paid the participation fee for this lot.',
    //                 'success' => true,
    //             ]);
    //         }

    //         $participateFee = $lotDetails->participate_fee;

    //         // Get the last balance of the customer
    //         $lastBalance = customerBalance::where('customerId', $newBid['customerId'])->orderBy('id', 'desc')->first();

    //         if ($lastBalance) {
    //             // Calculate the new final amount after deducting the participation fee
    //             $newFinalAmount = $lastBalance->finalAmount - $participateFee;

    //             if ($newFinalAmount >= 0) 
    //             {
    //                 // Create a new entry for the participation fee in customerBalance table
    //                 customerBalance::create([
    //                     'customerId' => $newBid['customerId'],
    //                     'balanceAmount' => $lastBalance->finalAmount,
    //                     'action' => 'Participate Fees',
    //                     'actionAmount' => $participateFee,
    //                     'finalAmount' => $newFinalAmount,
    //                     'lotid' => $newBid['lotid'],
    //                     'status' => 0,
    //                     'date' => Carbon::now(),
    //                 ]);

    //                 // Broadcast the message to all participants (including the new one)
    //                 broadcast(new winLotsEvent("Congratulations! You placed a new bid."))->toOthers();

    //                 // Prepare the response
    //                 $response = [
    //                     'message' => 'User can pay the participation fee successfully.',
    //                     'success' => true,
    //                 ];
    //             } else {
    //                 $response = [
    //                     'message' => 'User doesn\'t have enough balance for this participation.',
    //                     'success' => false,
    //                 ];
    //             }
    //         }
    //     } else {
    //         $response = [
    //             'message' => 'User is not available or User is blocked.',
    //             'success' => false,
    //         ];
    //     }

    //     return response()->json($response);
    // }

    

    // Participant on Expired Lot
    public function participeteOnExpireLot(Request $request)
    {
        $requestData = $request->validate([
            'customerId' => 'required',
            'lotid' => 'required',
            'bidPrice' => 'required',
        ]);
        $response = null;

        $lotDetails = lots::where('id', $requestData['lotid'])->first();
        $lastBid = BidsOfLots::where("lotId", $requestData['lotid'])->first();

        if (!$lastBid)
        {

            $response = $this->ParticipateOnLot($request);
            $participateOnLot = $response->original;
            if ($participateOnLot['sucess']) {
                $newBid =  BidsOfLots::create(
                    [
                        'customerId' => $requestData['customerId'],
                        'amount' => (int)$requestData['bidPrice'],
                        'lotId' => $requestData['lotid'],
                    ]
                );

                lots::where('id', $requestData['lotid'])->update(
                    [
                        'StartDate' => Carbon::now(),
                        'EndDate' => Carbon::now()->addMinutes(3),
                        // 'EndDate' => Carbon::parse($arry1[1]->EndDate)->addMinutes(3),
                        'lot_status' => 'Restart',
                    ]
                );
                $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` LEFT JOIN categories on categories.id  = lots.categoryId WHERE (date(lots.StartDate) = CURDATE() or date(lots.EndDate) = CURDATE()) and lots.lot_status IN ('live','ReStart','pause') ORDER BY LiveSequenceNumber;");

                $arry1 = array_slice($liveLots, 0, 2);
                $arry2 = array_slice($liveLots, 2);
                $newlotDetails = lots::where('id', $requestData['lotid'])->first()->attributesToArray();

                // $newlotDetails['ParticipateUsers'] = [0 => $requestData['customerId']];
                // $newlotDetails['lastBid'] =  BidsOfLots::where('lotId', $requestData['lotid'])->orderBy('id', 'DESC')->first()->attributesToArray();
                // $newlotDetails['lastBid'] = 0;

                // dd($newlotDetails);
                foreach ($arry2 as   $lot) {
                    lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes(3)]);
                }

                $newarray = [];
                $newarray = array_merge($newarray, $arry1, json_decode(json_encode([$newlotDetails]), FALSE), $arry2);
                // dump($arry1);


                $liveLotslist = [];
                foreach ($newarray as $lot) {
                    $templot = $lot;
                    $templot->ParticipateUsers = customerBalance::where([['lotId', $lot->id], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();

                    $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                    FROM bids_of_lots
                    LEFT JOIN customers on customers.id = bids_of_lots.customerId
                    WHERE lotId =' . $lot->id . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $lot->id . ') ORDER BY bids_of_lots.amount DESC  LIMIT 1;');
                    $templot->lastBid =  array_pop($lastBid);

                    $liveLotslist[$lot->id] = $templot;
                }

                // $firebase = (new Factory)
                //     ->withServiceAccount(json_encode([
                //         "type" => "service_account",
                //         "project_id" => "steel24-a898f",
                //         "private_key_id" => "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                //         "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                //         "client_email" => "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                //         "client_id" => "106107898058399972491",
                //         "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                //         "token_uri" => "https://oauth2.googleapis.com/token",
                //         "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                //         "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"

                //     ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');
                // $database = $firebase->createDatabase();
                // $database->getReference('TodaysLots/liveList/')->set($liveLotslist);

                $response = [
                    'message' => 'Lot Restart.',
                    'sucess' => true
                ];
            } else {
                return json_encode($participateOnLot);
            }
        } else {
            $response = [
                'message' => 'Lot is Not Expired.',
                'sucess' => true
            ];
        }
        return json_encode($response);
    }


    public static function newbidonlot(Request $request)
    {
        $newBid = $request->validate([
            'customerId' => 'required',
            'amount' => 'required',
            'lotId' => 'required',
        ]);

        $response = [];
        $customer = Customer::where('id', $newBid['customerId'])->first();
        $lotDtails = lots::where('id', $newBid['lotId'])->first();

        if ($customer && $customer->isApproved == 1) {
            $lastBid =  BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();
            if ($lastBid && $lastBid['amount'] < $newBid['amount'] && $lastBid['lotId'] == $newBid['lotId']) 
            {
                $lastBid = BidsOfLots::create($newBid);

                $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                FROM bids_of_lots
                LEFT JOIN customers on customers.id = bids_of_lots.customerId
                WHERE lotId =' . $newBid['lotId'] . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $newBid['lotId'] . ') ORDER BY bids_of_lots.amount DESC;');

                // $firebase = (new Factory)
                // ->withServiceAccount(json_encode([
                //     "type"=> "service_account",
                //     "project_id"=> "steel24-a898f",
                //     "private_key_id"=> "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                //     "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                //     "client_email"=> "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                //     "client_id"=> "106107898058399972491",
                //     "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
                //     "token_uri"=> "https://oauth2.googleapis.com/token",
                //     "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
                //     "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"
                    
                // ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');        $database = $firebase->createDatabase();
                // $database->getReference('TodaysLots/liveList/' . $newBid['lotId'] . '/lastBid')->set($lastBid[0]);

                $response = ["sucess" => true, 'LattestBid' => $lastBid, "userDetails" => $customer[0]];

                

            } elseif (!$lastBid && $lotDtails->Price < $newBid['amount']) 
            {
                $lastBid = BidsOfLots::create($newBid);
                // Have to Brodcast with

                $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                FROM bids_of_lots
                LEFT JOIN customers on customers.id = bids_of_lots.customerId
                WHERE lotId =' . $newBid['lotId'] . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $newBid['lotId'] . ') ORDER BY bids_of_lots.amount DESC;');

                // dd($lastBid);
                // $firebase = (new Factory)
                // ->withServiceAccount(json_encode([
                //     "type"=> "service_account",
                //     "project_id"=> "steel24-a898f",
                //     "private_key_id"=> "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
                //     "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
                //     "client_email"=> "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
                //     "client_id"=> "106107898058399972491",
                //     "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
                //     "token_uri"=> "https://oauth2.googleapis.com/token",
                //     "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
                //     "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"
                    
                // ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/');   $database = $firebase->createDatabase();
                // $database->getReference('TodaysLots/liveList/' . $newBid['lotId'] . '/lastBid')->set($lastBid[0]);


                $response = ["sucess" => true, 'LattestBid' => $lastBid, "userDetails" => $customer[0]];
            } else {
                $response = ["message" => 'Bid Amount is small then last bid.', 'sucess' => false];
            }
            // $bids = BidsOfLots::get()->toArray();
            // info($bids);
            // event(new MessageEvent($lastBid,  $customer));
        } else {
            $response = ["message" => 'User is not Availabel Or User is Blocked.', 'sucess' => false];
        }
        return $response;
    }


    // Add new bid to lot Updated by Z.R
    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);

    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDtails = lots::where('id', $newBid['lotId'])->first();

    //     if ($customer && $customer->isApproved == 1) {
    //         $lastBid =  BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();

    //         if ($lastBid && $lastBid['amount'] < $newBid['amount'] && $lastBid['lotId'] == $newBid['lotId']) 
    //         {
    //             $lastBid = BidsOfLots::create($newBid);
    //             $this->liveChangeOnfirbase($newBid['lotId'], $lotDtails['EndDate']);

    //             $response = ["sucess" => true, 'LattestBid' => $lastBid];

                
    //         } elseif (!$lastBid && $lotDtails->Price < $newBid['amount']) {
    //             $lastBid = BidsOfLots::create($newBid);
    //             // Have to Brodcast with

    //             $this->liveChangeOnfirbase($newBid['lotId'], $lotDtails['EndDate']);


    //             // $response = ["sucess" => true, 'LattestBid' => $lastBid, "userDetails" => $customer[0]];
    //         } else {
    //             $response = ["message" => 'Bid Amount is small then last bid.', 'sucess' => false];
    //         }
    //     } else {
    //         $response = ["message" => 'User is not Availabel Or User is Blocked.', 'sucess' => false];
    //     }
    //     return $response;
    // }




    // previous code working

    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);
    
    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDtails = lots::where('id', $newBid['lotId'])->first();
    
    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();
    
    //         // Calculate the next bid amount with an increment of 100
    //         $nextBidAmount = ceil($newBid['amount'] / 100) * 100;
    
    //         if ($newBid['amount'] % 100 !== 0) {
    //             $response = ["message" => 'Please Enter a multiple of 100', 'success' => false];
    //         } elseif ($lastBid && $lastBid['amount'] < $nextBidAmount && $lastBid['lotId'] == $newBid['lotId']) {
    //             $lastBid = BidsOfLots::create([
    //                 'customerId' => $newBid['customerId'],
    //                 'amount' => $nextBidAmount,
    //                 'lotId' => $newBid['lotId'],
    //             ]);
    //             $this->liveChangeOnfirbase($newBid['lotId'], $lotDtails['EndDate']);
    
    //             $response = ["success" => true, 'LatestBid' => $lastBid];
    //         } elseif (!$lastBid && $lotDtails->Price < $nextBidAmount) {
    //             $lastBid = BidsOfLots::create([
    //                 'customerId' => $newBid['customerId'],
    //                 'amount' => $nextBidAmount,
    //                 'lotId' => $newBid['lotId'],
    //             ]);
    
    //             $this->liveChangeOnfirbase($newBid['lotId'], $lotDtails['EndDate']);
    
    //             $response = ["success" => true, 'LatestBid' => $lastBid];
    //         } else {
    //             $response = ["message" => 'Bid Amount is small than the last bid or not in the allowed increment.', 'success' => false];
    //         }
    //     } else {
    //         $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    //     }
    //     return $response;
    // }

    // end the previous code 





    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);

    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDetails = lots::where('id', $newBid['lotId'])->first();

    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         // Calculate the next bid amount with an increment of 100
    //         $nextBidAmount = ceil($newBid['amount'] / 100) * 100;

    //         if ($newBid['amount'] % 100 !== 0) {
    //             $response = ["message" => 'Please Enter a multiple of 100', 'success' => false];
    //         } 
    //         else{
    //             $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();

    //             if ($lastBid && $lastBid['amount'] < $nextBidAmount && $lastBid['lotId'] == $newBid['lotId']) 
    //             {
    //                 // Check if the last bid was made within the last two minutes
    //                 $currentTime = Carbon::now();
    //                 $twoMinutesAgo = $currentTime->subMinutes(2);
    //                 $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);

    //                 if ($lastBidTime->greaterThan($twoMinutesAgo)) 
    //                 {
    //                     // Another bid was made within two minutes, create a new bid
    //                     $newBid = BidsOfLots::create([
    //                         'customerId' => $newBid['customerId'],
    //                         'amount' => $nextBidAmount,
    //                         'lotId' => $newBid['lotId'],
    //                     ]);

    //                     $response = ["message" => 'Congratulations! You placed a new bid.', 'success' => true, 'LatestBid' => $newBid];
    //                 }
    //                 else {
    //                     // No other bid within two minutes, the lot is won by the last bid
    //                     // Mark the lot as closed or do any necessary actions here

    //                     $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
    //                 }
    //             } 
    //             else {
    //                 $response = ["message" => 'Bid Amount is smaller than the last bid or not in the allowed increment.', 'success' => false];
    //             }
    //         }
    //     } else {
    //         $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    //     }

    //     return $response;
    // }

     // end the previous code 
      
     

    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);

    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDetails = lots::where('id', $newBid['lotId'])->first();

    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         // Calculate the next bid amount with an increment of 100
    //         $nextBidAmount = ceil($newBid['amount'] / 100) * 100;

    //         if ($newBid['amount'] % 100 !== 0) 
    //         {
    //             $response = ["message" => 'Please Enter a multiple of 100', 'success' => false];
    //         } else {
    //             $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();

    //             if ($lastBid && $lastBid['amount'] < $nextBidAmount && $lastBid['lotId'] == $newBid['lotId']) {
    //                 // Check if the last bid was made within the last two minutes
    //                 $currentTime = Carbon::now();
    //                 $twoMinutesAgo = $currentTime->subMinutes(2);
    //                 $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);

    //                 if ($lastBidTime->greaterThan($twoMinutesAgo)) {
    //                     // Another bid was made within two minutes, create a new bid
    //                     $newBid = BidsOfLots::create([
    //                         'customerId' => $newBid['customerId'],
    //                         'amount' => $nextBidAmount,
    //                         'lotId' => $newBid['lotId'],
    //                     ]);

    //                     // Dispatch event to notify participants about the new bid
    //                     event(new winLotsEvent('Congratulations! You placed a new bid.'));

    //                     // Pusher code to send notification to front-end
    //                     $pusher = new \Pusher\Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
    //                         'cluster' => env('PUSHER_APP_CLUSTER'),
    //                         'useTLS' => true,
    //                     ]);

    //                     $pusher->trigger('my-channel', 'my-event', [
    //                         'message' => 'Congratulations! You placed a new bid.',
    //                     ]);

    //                     $response = ["message" => 'Congratulations! You placed a new bid.', 'success' => true, 'LatestBid' => $newBid];
    //                 } else {
    //                     // No other bid within two minutes, the lot is won by the last bid
    //                     // Mark the lot as closed or do any necessary actions here

    //                     // Dispatch event to notify participants about the winner
    //                     event(new winLotsEvent('You are late! Sorry, another person won this lot.'));

    //                     // Return participation fee to the loser
    //                     $this->returnParticipationFee($lastBid);

    //                     // Send email notification to the winner
    //                     Mail::to($lastBid->customer->email)->send(new LotWinnerNotification($lastBid->customer->name));

    //                     // Send email notification to the loser
    //                     Mail::to($customer->email)->send(new LotLoserNotification($lotDetails->id, $customer->name));

    //                     // Pusher code to send notification to front-end
    //                     $pusher = new \Pusher\Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
    //                         'cluster' => env('PUSHER_APP_CLUSTER'),
    //                         'useTLS' => true,
    //                     ]);

    //                     $pusher->trigger('my-channel', 'my-event', [
    //                         'message' => 'You are late! Sorry, another person won this lot.',
    //                     ]);

    //                     $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
    //                 }
    //             } else {
    //                 $response = ["message" => 'Bid Amount is smaller than the last bid or not in the allowed increment.', 'success' => false];
    //             }
    //         }
    //     } else {
    //         $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    //     }

    //     return $response;
    // }

     
    //  private function returnParticipationFee($bid)
    //  {
    //      $customer = Customer::find($bid->customerId);
    //      if ($customer) {
    //          $lastBalance = customerBalance::where('customerId', $customer->id)->orderBy('id', 'desc')->first();
    //          if ($lastBalance) {
    //              $newFinalAmount = $lastBalance->finalAmount + $bid->amount;
    //              customerBalance::create([
    //                  'customerId' => $customer->id,
    //                  'balanceAmount' => $lastBalance->finalAmount,
    //                  'action' => 'Return Participation Fee',
    //                  'actionAmount' => $bid->amount,
    //                  'finalAmount' => $newFinalAmount,
    //                  'lotid' => $bid->lotId,
    //                  'status' => 1, // Status 1 for returned participation fee
    //                  'date' => Carbon::now(),
    //              ]);
    //          }
    //      }
    //  }



// *********** PREVIOUS CODE WORKING *************


    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);
    
    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDetails = lots::where('id', $newBid['lotId'])->first();

    //     if (!$lotDetails) {
    //         // Lot with the provided ID does not exist
    //         return response()->json(['message' => 'Sorry, you entered an invalid lot ID.', 'success' => true], 200);
    //     }
        
    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         $nextBidAmount = $newBid['amount'];
    
    //         $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();
    //         if ($lastBid!=null) 
    //         {
                
    //             // Check if the last bid was made within the last two minutes
    //             $currentTime = Carbon::now();
    //             $twoMinutesAgo = $currentTime->subMinutes(2);
    //             $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);
            

    //             //new code starts here
    //             // dd( $lastBidTime , $currentTime , $twoMinutesAgo);
    //              // date: 2023-07-27 12:33:50.
    //             // dd(date("Y-m-d H:i:s"));

    //             // current time : 12:30:00

    //             $lastBidTime = $lastBidTime->addMinutes(2);
    //             // dd($currentTime);
    //             // dd($lastBidTime->greaterThan($currentTime) , $lastBidTime , $currentTime);
    //             //new code ends here
                
    //             // if ($lastBidTime->greaterThan($twoMinutesAgo)) 
    //             if ($lastBidTime->greaterThan($currentTime)) 
    //             {
    //                 // Another bid was made within two minutes, create a new bid
    //                 $newBid = BidsOfLots::create([
    //                     'customerId' => $newBid['customerId'],
    //                     'amount' => $nextBidAmount,
    //                     'lotId' => $newBid['lotId'],
    //                     'created_at' => date('Y-m-d H:i:s'),
    //                     'updated_at' => date('Y-m-d H:i:s')
    //                 ]);
    
    //                 // Dispatch event to notify participants about the new bid
    //                 event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid,$customer,true));
                    
                    
    //                 $response = ["message" => 'Good Luck! You placed a new bid!', 'success' => true, 'LatestBid' => $newBid];
    //             } 
    //             else 
    //             {
    //                 // No other bid within two minutes, the lot is won by the last bid
    //                 // Mark the lot as closed or do any necessary actions here
    
    //                 // Dispatch event to notify participants about the winner
    //                 event(new winLotsEvent('You are late! Sorry, another person won this lot.', $lastBid,$customer,false));


    //                  // Return participation fee to the loser
    //                  $this->returnParticipationFee($lastBid);

    //                  // Send email notification to the winner
    //                  Mail::to($lastBid->customer->email)->send(new LotWinnerNotification($lastBid->customer->name));
 
    //                  // Send email notification to the loser
    //                  Mail::to($customer->email)->send(new LotLoserNotification($lotDetails->id, $customer->name));
    
    //                 $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
    //             }
    //         }
    //          else {
    //             $newBid = BidsOfLots::create([
    //                 'customerId' => $newBid['customerId'],
    //                 'amount' => $nextBidAmount,
    //                 'lotId' => $newBid['lotId'],
    //             ]);
    //             event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid,$customer,true));
    //             $response = ['LatestBid' => $newBid, 'success' => true];
    //         }
    //     } 
    //     else {
    //         $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    //     }
    
    //     return $response;
    // }
      

    public function addnewbidtolot(Request $request)
{
    $newBid = $request->validate([
        'customerId' => 'required',
        'amount' => 'required',
        'lotId' => 'required',
    ]);

    $response = [];
    $customer = Customer::where('id', $newBid['customerId'])->first();
    $lotDetails = lots::where('id', $newBid['lotId'])->first();

    if (!$lotDetails) {
        // Lot with the provided ID does not exist
        return response()->json(['message' => 'Sorry, you entered an invalid lot ID.', 'success' => true], 200);
    }

    if ($customer && $customer->isApproved == 1) {
        $nextBidAmount = $newBid['amount'];

        // Check if the customer has an auto-bid enabled
        $autoBid = AutoBid::where('customerId', $newBid['customerId'])
            ->where('lotId', $newBid['lotId'])
            ->first();

        if ($autoBid) {
            // If auto-bid is enabled, increase the bid amount by 100
            $nextBidAmount = $autoBid->amount + 100;
        }

        // Check if the last bid was made within the last two minutes
        $currentTime = Carbon::now();
        $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();

        if ($lastBid != null) {
            $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);
            $timeDifferenceInSeconds = $lastBidTime->diffInSeconds($currentTime);

            if ($timeDifferenceInSeconds <= 120) {
                // Another bid was made within two minutes, create a new bid
                $newBid = BidsOfLots::create([
                    'customerId' => $newBid['customerId'],
                    'amount' => $nextBidAmount,
                    'lotId' => $newBid['lotId'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Dispatch event to notify participants about the new bid
                event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true));

                $response = ["message" => 'Good Luck! You placed a new bid!', 'success' => true, 'LatestBid' => $newBid];
            } else {
                // No other bid within two minutes, the lot is won by the last bid
                // Mark the lot as closed or do any necessary actions here

                // Dispatch event to notify participants about the winner
                event(new winLotsEvent('You are late! Sorry, another person won this lot.', $lastBid, $customer, false));

                // Return participation fee to the loser
                $this->returnParticipationFee($lastBid);

                // Send email notification to the winner
                Mail::to($lastBid->customer->email)->send(new LotWinnerNotification($lastBid->customer->name));

                // Send email notification to the loser
                Mail::to($customer->email)->send(new LotLoserNotification($lotDetails->id, $customer->name));

                $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
            }
        } else {
            // First bid on the lot
            $newBid = BidsOfLots::create([
                'customerId' => $newBid['customerId'],
                'amount' => $nextBidAmount,
                'lotId' => $newBid['lotId'],
            ]);
            event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true));
            $response = ['LatestBid' => $newBid, 'success' => true];
        }
    } else {
        $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    }

    return $response;
}

    

    

    
    // *********** END PREVIOUS CODE WORKING *************


    // public function addnewbidtolot(Request $request)
    // {
    //     $newBid = $request->validate([
    //         'customerId' => 'required',
    //         'amount' => 'required',
    //         'lotId' => 'required',
    //     ]);
    
    //     $response = [];
    //     $customer = Customer::where('id', $newBid['customerId'])->first();
    //     $lotDetails = lots::where('id', $newBid['lotId'])->first();
    
    //     if (!$lotDetails) {
    //         // Lot with the provided ID does not exist
    //         return response()->json(['message' => 'Sorry, you entered an invalid lot ID.', 'success' => true], 200);
    //     }
    
    //     if ($customer && $customer->isApproved == 1) 
    //     {
    //         $nextBidAmount = $newBid['amount'];
    
    //         $lastBid = BidsOfLots::where('lotId', $newBid['lotId'])->orderBy('id', 'DESC')->first();
    //         if ($lastBid != null) {
    
    //             // Check if the last bid was made within the last two minutes
    //             $currentTime = Carbon::now();
    //             $twoMinutesAgo = $currentTime->subMinutes(2);
    //             $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);
    
    //             //new code starts here
    //             $lastBidTime = $lastBidTime->addMinutes(2);
    //             //new code ends here
    
    //             if ($lastBidTime->greaterThan($currentTime)) {
    //                 // Another bid was made within two minutes, create a new bid
    //                 $newBid = BidsOfLots::create([
    //                     'customerId' => $newBid['customerId'],
    //                     'amount' => $nextBidAmount,
    //                     'lotId' => $newBid['lotId'],
    //                     'created_at' => date('Y-m-d H:i:s'),
    //                     'updated_at' => date('Y-m-d H:i:s')
    //                 ]);
    
    //                 // Dispatch event to notify participants about the new bid
    //                 event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true, null));
    
    //                 $response = ["message" => 'Good Luck! You placed a new bid!', 'success' => true, 'LatestBid' => $newBid];
    //             } else {
    //                 // No other bid within two minutes, the lot is won by the last bid
    //                 // Mark the lot as closed or do any necessary actions here
    
    //                 // Dispatch event to notify participants about the winner
    //                 event(new winLotsEvent('You are late! Sorry, another person won this lot.', $lastBid, $customer, false, null));
    
    //                 // Return participation fee to the loser
    //                 $this->returnParticipationFee($lastBid);
    
    //                 // Send email notification to the winner
    //                 Mail::to($lastBid->customer->email)->send(new LotWinnerNotification($lastBid->customer->name));
    
    //                 // Send email notification to the loser
    //                 Mail::to($customer->email)->send(new LotLoserNotification($lotDetails->id, $customer->name));
    
    //                 $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
    //             }
    //         } else {
    //             // First bid on the lot, create a new bid with the given amount
    //             $newBid = BidsOfLots::create([
    //                 'customerId' => $newBid['customerId'],
    //                 'amount' => $nextBidAmount,
    //                 'lotId' => $newBid['lotId'],
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'updated_at' => date('Y-m-d H:i:s')
    //             ]);
    
    //             // Dispatch event to notify participants about the new bid
    //             event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true, null));
    //             $response = ['LatestBid' => $newBid, 'success' => true];
    //         }
    //     } else {
    //         $response = ["message" => 'User is not available or User is blocked.', 'success' => false];
    //     }
    
    //     return $response;
    // }








//     public function addnewbidtolot(Request $request)
// {
//     $newBid = $request->validate([
//         'customerId' => 'required|exists:customers,id',
//         'amount' => 'required|numeric',
//         'lotId' => 'required|exists:lots,id',
//     ]);

//     $response = [];
//     $customer = Customer::findOrFail($newBid['customerId']);
//     $lotDetails = lots::findOrFail($newBid['lotId']);

//     if ($customer->isApproved != 1) {
//         return response()->json(['message' => 'User is not available or User is blocked.', 'success' => false], 200);
//     }

//     // Check if the customer has an auto-bid enabled for the lot
//     $autoBid = BidsOfLots::where('customerId', $customer->id)
//         ->where('lotId', $lotDetails->id)
//         ->where('autoBid', true)
//         ->first();

//     // Determine if the bid is manual or auto
//     $isAutoBid = $autoBid ? true : false;

//     // Use the autoBid amount as the next bid amount if it's an auto bid
//     $nextBidAmount = $isAutoBid ? $autoBid->amount + 100 : $newBid['amount'];

//     $lastBid = BidsOfLots::where('lotId', $lotDetails->id)->orderBy('id', 'DESC')->first();
//     if ($lastBid != null) {
//         // Check if the last bid was made within the last two minutes
//         $currentTime = Carbon::now();
//         $twoMinutesAgo = $currentTime->subMinutes(2);
//         $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastBid->created_at);

//         if ($lastBidTime->greaterThan($currentTime)) {
//             // Another bid was made within two minutes, create a new bid
//             $newBid = BidsOfLots::create([
//                 'customerId' => $customer->id,
//                 'amount' => $nextBidAmount,
//                 'lotId' => $lotDetails->id,
//                 'autobid' => $isAutoBid ? 1 : 0, // Set the autoBid flag based on manual or auto bid
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'updated_at' => date('Y-m-d H:i:s')
//             ]);

//             // Dispatch event to notify participants about the new bid
//             event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true, null));

//             $response = ["message" => 'Good Luck! You placed a new bid!', 'success' => true, 'LatestBid' => $newBid];
//         } else {
//             // No other bid within two minutes, the lot is won by the last bid
//             // Mark the lot as closed or do any necessary actions here

//             // Dispatch event to notify participants about the winner
//             event(new winLotsEvent('You are late! Sorry, another person won this lot.', $lastBid, $customer, false, null));

//             // Return participation fee to the loser
//             $this->returnParticipationFee($lastBid);

//             // Send email notification to the winner
//             Mail::to($lastBid->customer->email)->send(new LotWinnerNotification($lastBid->customer->name));

//             // Send email notification to the loser
//             Mail::to($customer->email)->send(new LotLoserNotification($lotDetails->id, $customer->name));

//             $response = ["message" => 'You are late! Sorry, another person won this lot.', 'success' => false];
//         }
//     } else {
//         // First bid on the lot, create a new bid with the given amount
//         $newBid = BidsOfLots::create([
//             'customerId' => $customer->id,
//             'amount' => $nextBidAmount,
//             'lotId' => $lotDetails->id,
//             'autobid' => $isAutoBid ? 1 : 0, // Set the autoBid flag based on manual or auto bid
//             'created_at' => date('Y-m-d H:i:s'),
//             'updated_at' => date('Y-m-d H:i:s')
//         ]);

//         // Dispatch event to notify participants about the new bid
//         event(new winLotsEvent('Good Luck! You placed a new bid.', $newBid, $customer, true, null));
//         $response = ['LatestBid' => $newBid, 'success' => true];
//     }

//     return $response;
// }
    

    
    private function returnParticipationFee($bid)
    {
        $customer = Customer::find($bid->customerId);
        if ($customer) 
        {
            $lastBalance = CustomerBalance::where('customerId', $customer->id)->orderBy('id', 'desc')->first();
            if ($lastBalance) {
                $newFinalAmount = $lastBalance->finalAmount + $bid->amount;
                CustomerBalance::create([
                    'customerId' => $customer->id,
                    'balanceAmount' => $lastBalance->finalAmount,
                    'action' => 'Return Participation Fee',
                    'actionAmount' => $bid->amount,
                    'finalAmount' => $newFinalAmount,
                    'lotid' => $bid->lotId,
                    'status' => 1, // Status 1 for returned participation fee
                    'date' => Carbon::now(),
                ]);
            }
        }
    }

    // *********previous auto bid code***********

    // // createautobid API
    // public function createautobid(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);

    //     // Retrieve the customer and lot based on the provided IDs
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);

    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
    //         ->max('amount');

    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;

    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = AutoBid::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->first();

    //     if ($request->autobid === false) {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.

    //         if ($autoBid) 
    //         {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'autobid' => 1,
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         } 
    //         else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             AutoBid::create([
    //                 'customerId' => $customer->id,
    //                 'lotId' => $lot->id,
    //                 'autobid' => 1,
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         }
    //     }

    //     // Include the previous latest bid amount in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //     ];

    //     return response()->json($response);
    // }
      

    // public function createautobid(Request $request)
    // {
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);
    
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);
    
    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
    //         ->max('amount');
    
    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;
    
    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = AutoBid::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->first();
    
    //     if ($request->autobid === false) {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.
    
    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'autobid' => 1,
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         } else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             AutoBid::create([
    //                 'customerId' => $customer->id,
    //                 'lotId' => $lot->id,
    //                 'autobid' => 1,
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         }
    //     }
    
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //     ];
    
    //     return response()->json($response);
    // }
    

    
    

    // *********** end previous code auto bid*********



    // public function createautobid(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);
    
    //     // Retrieve the customer and lot based on the provided IDs
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);
    
    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
    //         ->max('amount');
    
    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;
    
    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = BidsOfLots::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->where('autoBid', true)
    //         ->first();
    
    //     if ($request->autobid === false) {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.
    
    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         } else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             BidsOfLots::create([
    //                 'customerId' => $customer->id,
    //                 'amount' => $autoBidAmount,
    //                 'lotId' => $lot->id,
    //                 'autoBid' => true,
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'updated_at' => date('Y-m-d H:i:s')
    //             ]);
    //         }
    
    //         // Trigger Pusher event for auto bid
    //         event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
    //     }
    
    //     // Include the customer details and auto bid amounts in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //         'autoBidAmount' => $autoBidAmount,
    //         'customer' => $customer,
    //     ];
    
    //     return response()->json($response);
    // }    







    //     public function createautobid(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);

    //     // Retrieve the customer and lot based on the provided IDs
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);

    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
    //         ->max('amount');

    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;

    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = BidsOfLots::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->where('autoBid', true)
    //         ->first();


    //     if ($request->autobid === false) {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.

    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         } else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             BidsOfLots::create([
    //                 'customerId' => $customer->id,
    //                 'amount' => $autoBidAmount,
    //                 'lotId' => $lot->id,
    //                 'autobid' => true,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }

    //         // Trigger Pusher event for auto bid
    //         event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
    //     }

    //     // Include the customer details and auto bid amounts in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //         'autoBidAmount' => $autoBidAmount,
    //         'customer' => $customer,
    //     ];

    //     return response()->json($response);
    // }


            public function createautobid(Request $request)
        {
            // Validate the incoming request data
            $request->validate([
                'customerId' => 'required|exists:customers,id',
                'lotId' => 'required|exists:lots,id',
                'autobid' => 'required|boolean',
            ]);

            // Retrieve the customer and lot based on the provided IDs
            $customer = Customer::findOrFail($request->customerId);
            $lot = lots::findOrFail($request->lotId);

            // Fetch the latest bid amount for the specified lot
            $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
                ->max('amount');

            // If there are no manual bids, set the previousLatestBidAmount to the starting price of the lot (lot price)
            $previousLatestBidAmount = $latestBidAmount ?: $lot->Price;

            // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
            $autoBidAmount = $previousLatestBidAmount + 100;

            // Check if the customer has already placed an auto bid for the lot
            $autoBid = BidsOfLots::where('customerId', $customer->id)
                ->where('lotId', $lot->id)
                ->where('autoBid', true)
                ->first();

            if ($request->autobid === false) {
                // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
                if ($autoBid) {
                    $autoBid->delete();
                }
            } else {
                // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.

                if ($autoBid) {
                    // If the auto bid record exists for the customer and lot, update the autobid status and amount
                    $autoBid->update([
                        'amount' => $autoBidAmount,
                    ]);
                } else {
                    // If the auto bid record does not exist, create a new one for the customer and lot
                    BidsOfLots::create([
                        'customerId' => $customer->id,
                        'amount' => $autoBidAmount,
                        'lotId' => $lot->id,
                        'autobid' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Trigger Pusher event for auto bid
                event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
            }

            // Include the customer details and auto bid amounts in the response
            $response = [
                'message' => 'Auto bid status updated successfully',
                'previousLatestBidAmount' => $previousLatestBidAmount,
                'autoBidAmount' => $autoBidAmount,
                'customer' => $customer,
            ];

            return response()->json($response);
        }







    //     public function createautobid(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);

    //     // Retrieve the customer and lot based on the provided IDs
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);

    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)
    //         ->max('amount');

    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;

    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = BidsOfLots::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->where('autoBid', true)
    //         ->first();

    //     if ($request->autobid === false) 
    //     {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) 
    //         {
    //             $autoBid->delete();
    //         }
    //     } 
    //     else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.

    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'amount' => $autoBidAmount,
    //             ]);
    //         } else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             BidsOfLots::create([
    //                 'customerId' => $customer->id,
    //                 'amount' => $autoBidAmount,
    //                 'lotId' => $lot->id,
    //                 'autobid' => true,
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'updated_at' => date('Y-m-d H:i:s')
    //             ]);
    //         }

    //         // Trigger Pusher event for auto bid
    //         event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
    //     }

    //     // Include the customer details and auto bid amounts in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //         'autoBidAmount' => $autoBidAmount,
    //         'customer' => $customer,
    //     ];

    //     return response()->json($response);
    // }










    // code working 
    // public function createautobid(Request $request)
    // {
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);
    
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);
    
    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)->max('amount');
    
    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;
    
    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = AutoBid::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         // ->where('autoBid', true)
    //         ->first();
    
    //     if ($request->autobid === false) 
    //     {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.
    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'autoBid' => true,
    //             ]);
    //         } else 
    //         {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             AutoBid::create([
    //                 'customerId' => $customer->id,
    //                 'lotId' => $lot->id,
    //                 'autobid' => true,
    //             ]);
    //         }
    
    //         // Trigger Pusher event for auto bid
    //         event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
    //     }
    
    //     // Include the customer details and auto bid amounts in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //         'autoBidAmount' => $autoBidAmount,
    //         'customer' => $customer,
    //     ];
    
    //     return response()->json($response);
    // }

    // end code working

    // public function createautobid(Request $request)
    // {
    //     $request->validate([
    //         'customerId' => 'required|exists:customers,id',
    //         'lotId' => 'required|exists:lots,id',
    //         'autobid' => 'required|boolean',
    //     ]);
    
    //     $customer = Customer::findOrFail($request->customerId);
    //     $lot = lots::findOrFail($request->lotId);
    
    //     // Fetch the latest bid amount for the specified lot
    //     $latestBidAmount = BidsOfLots::where('lotId', $request->lotId)->max('amount');
    
    //     // Calculate the new auto bid amount by adding 100 to the latest bid amount (if available) or use the starting price of the lot
    //     $autoBidAmount = $latestBidAmount ? $latestBidAmount + 100 : $lot->Price;
    
    //     // Check if the customer has already placed an auto bid for the lot
    //     $autoBid = AutoBid::where('customerId', $customer->id)
    //         ->where('lotId', $lot->id)
    //         ->first();
    
    //     if ($request->autobid === false) {
    //         // If the customer wants to disable auto bid (autobid = 0), we will remove the auto bid record if it exists.
    //         if ($autoBid) {
    //             $autoBid->delete();
    //         }
    //     } else {
    //         // If the customer wants to enable auto bid (autobid = 1), we will update the existing auto bid record or create a new one.
    //         if ($autoBid) {
    //             // If the auto bid record exists for the customer and lot, update the autobid status and amount
    //             $autoBid->update([
    //                 'autobid' => true,
    //                 'amount' => $autoBidAmount, // Update the auto bid amount
    //             ]);
    //         } else {
    //             // If the auto bid record does not exist, create a new one for the customer and lot
    //             AutoBid::create([
    //                 'customerId' => $customer->id,
    //                 'lotId' => $lot->id,
    //                 'autobid' => true,
    //                 'amount' => $autoBidAmount, // Set the auto bid amount for the new auto bid
    //             ]);
    //         }
    
    //         // Trigger Pusher event for auto bid
    //         event(new winLotsEvent('New Auto Bid Placed', $autoBid, $customer, true, $autoBidAmount));
    //     }
    
    //     // Include the customer details and auto bid amounts in the response
    //     $response = [
    //         'message' => 'Auto bid status updated successfully',
    //         'previousLatestBidAmount' => $latestBidAmount,
    //         'autoBidAmount' => $autoBidAmount,
    //         'customer' => $customer,
    //     ];
    
    //     return response()->json($response);
    // }
    

    
    
    

           // checking auto bid api 

            public function checkAutoBid(Request $request)
        {
            // Validate the incoming request data
            $request->validate([
                'customerId' => 'required|exists:customers,id',
                'lotId' => 'required|exists:lots,id',
            ]);

            $customerId = $request->input('customerId');
            $lotId = $request->input('lotId');

            // Check if the customer has placed an auto bid for the lot with autoBid value of 1
            $hasAutoBid = BidsOfLots::where('customerId', $customerId)
                ->where('lotId', $lotId)
                ->where('autoBid', 1)
                ->exists();

            // Prepare the response
            $response = [
                'customerId' => $customerId,
                'lotId' => $lotId,
                'hasAutoBid' => $hasAutoBid,
            ];

            return response()->json($response);
        }


        // delete ApI AUTO BID 

        public function deleteautobid(Request $request, $customerId, $lotId)
        {
            // Find the auto bids for the given customer and lot with autoBid value of 1
            $autoBids = BidsOfLots::where('customerId', $customerId)
                ->where('lotId', $lotId)
                ->where('autoBid', 1)
                ->get();
        
            if ($autoBids->isEmpty()) {
                // If no auto bids with autoBid value of 1 are found, return a not found response
                return response()->json(['message' => 'Auto bids not found', 'success' => false], 404);
            }
        
            // Delete all the auto bids found
            foreach ($autoBids as $autoBid) {
                $autoBid->delete();
            }
        
            // Return a success response indicating the auto bids were deleted
            return response()->json(['message' => 'Auto bids deleted successfully', 'success' => true]);
        }
        







    public function liveChangeOnfirbase($lotid, $endDate = null)
    {
        $starttime =   Carbon::now();
        $endtime =   Carbon::parse($endDate);

        $diff  = $starttime->diffInMinutes($endtime);
        if ($diff < 1) {

            $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` 
            LEFT JOIN categories on categories.id  = lots.categoryId
            WHERE (date(lots.EndDate) = CURDATE()) and lots.id  >= $lotid");

            foreach ($liveLots as $lot) 
            {
                $lot = lots::where('id', $lot->id)->update(['EndDate' => Carbon::parse($lot->EndDate)->addMinutes(1)]);
            }
        }
        $liveLots = DB::select("SELECT lots.* ,categories.title as categoriesTitle FROM `lots` LEFT JOIN categories on categories.id  = lots.categoryId WHERE (date(lots.StartDate) = CURDATE() or date(lots.EndDate) = CURDATE()) and lots.lot_status IN ('live','ReStart','pause') ORDER BY LiveSequenceNumber;");

        $liveLotslist = [];
        foreach ($liveLots as $lot) {
            $templot = $lot;
            $templot->ParticipateUsers = customerBalance::where([['lotId', $lot->id], ['status', '!=', '1']])->groupBy('customerId')->pluck('customerId')->toArray();

            $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
        FROM bids_of_lots
        LEFT JOIN customers on customers.id = bids_of_lots.customerId
        WHERE lotId =' . $lot->id . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $lot->id . ') ORDER BY bids_of_lots.amount DESC  LIMIT 1;');
            $templot->lastBid =  array_pop($lastBid);
            $liveLotslist[$lot->id] = $templot;
        }

        // $firebase = (new Factory)
        // ->withServiceAccount(json_encode([
        //     "type"=> "service_account",
        //     "project_id"=> "steel24-a898f",
        //     "private_key_id"=> "154e3c7d3ecb2b8fc1245ce9955d87ba8084ce77",
        //     "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJOy/8yIPhlpv2\nwGNffZVU8vTSNYwEqUDy6aHF/TLcJsHnoLfAcMdXvts0Cq4vIOw3FRRQrVq70bIa\n7dIQcyXkHvb8z/lsvvb/cI4VMpW3EgWQ+bo8m4DYY4kWDqGxs5stD119G14/q9iX\nCDhSiRbetygKGH5zxXJllsa5MFKAXNj9QD7mksJllEZV4Q/d+2z4HkfxggTK/KZy\nlEU1scb4P/U1mWukY4C/LgugJicQhIMtGDt6PaFHm5ZssQ2vZ2lumuMhnRIzQ/e9\nWkPbTJESeUoprZHJxPfSPbtRika9NYp3/YntHh1Y3aszGpzLx/VOeBmKo5SWIjOs\nSUj2gY3fAgMBAAECggEABsJfjrfhpw7gB7taKa3p2RFOdbwldWVQyaYwTaw3ARj3\nnA0Sf+wOJYhFC78q7S9V8zCam46uVWnyt9jW6/CAAUh1KeakhnKxf8tvdCPVs/qz\nQ3zJa4rNQdtFOUznMfWCwylqlWrvrXstY+MHwyj1c2raEgU61UD4bYCLsTtsFN5r\naXTa9NLEaExb+5PIaubtE9uJHESn/XJhTBXEhT8dt6YFr6jPBil7Ak4wJqsr+82l\nMILMSbPGl9F3Hd/LP+WRxPvjrHSU6U46vocZhEHCohMx08srMsl4NO6vt57Ty19h\nCaakYiM4PciJTwVl24yqrP4oszexdggIRzkT+0wJAQKBgQDACgw3LLMID+AP8tHZ\nnQwmxJ1YiWbhUliORGzCsontc9hz9N57BBE2GwqGAEmD+RaNFllbt1aGg+v3dFMl\nycKIc83Q4c+J85X2tNwXh94niQLF5Wl2lHgdioTSh8WNSZVh/0cekN7wh8zJBRKY\nkT59J7HM92gDrABYuGUDaofD/wKBgQC28AKCrQxb4EUpYsLC42fSMY066+5js3Tt\neguLyj4jNhIQ0nSe3TBBdWlvCJl+Drw8qKt1xM0RmNsqSLYBTK1PzkITLG6S4nvg\n7vf713Q116wipE5WX8FakMWL+cjlDdy3JAoBWHi4og4ZuqoMZr+JyM9dR2KH5UFl\nARuIIla2IQKBgQCC8BbmI98qNxDSLwEwfGlFobebH4x7Q5dH4ZW6pttugRdr8OEl\nRV+q4YMqXNXDWzoqFrv00iv36ckhXzo2QLwYJ8WEkALfD6wHm8eZb7VkhYHThxmC\nlbbUhZcMqTBkpnBpchJ+385yeFWEFqZYSmguE7uigmp0XnmaBJgzXRaW5wKBgBdf\nQKLbYwnV9GAeOw3VKe2D4SxW+kUIp3azsgfxFdE/1j0J9lZZohGq44aJDbs6PLhv\nQECynRSTd+TGF2LBHh9lFbIHajUf9H2/ajVlyHYckOR4I34Li9N7TZHdntoM1Fcd\npp2XZQ0Jv01wOMuO0QfUfRHIzgDYvGsgIhlZccShAoGAdctj7iBKhoTC3KecFUxy\nNHS1D+x/1VuZgUN1mKRhWjhQV1CyR8ao7O7om1at6IRJ8a3O7u2CZMdIB21pgBE2\n4lsMScCSl5Fr5UEMNuEELK3tMfuEVSt709HrtVrnzKU+LABQMJYrACWA3n6kUTRx\nFGWSMAzCBURToznCQEl88yc=\n-----END PRIVATE KEY-----\n",
        //     "client_email"=> "firebase-adminsdk-1jmgy@steel24-a898f.iam.gserviceaccount.com",
        //     "client_id"=> "106107898058399972491",
        //     "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri"=> "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1jmgy%40steel24-a898f.iam.gserviceaccount.com"
            
        // ]))->withDatabaseUri('https://steel24-a898f-default-rtdb.firebaseio.com/'); $database = $firebase->createDatabase();
        // // foreach ($liveLotslist as $lot) {
        // $database->getReference('TodaysLots/liveList/')->set($liveLotslist);
        // }
    }

    public function getlivebid(Request $request)
    {
        $response = ['sucess' => false];
        $response['EndDate'] = lots::where('id', $request->lotid)->pluck('EndDate')->first();
        $result = DB::select('SELECT lots.EndDate as lotEnd, bids_of_lots.*,customers.id as customerId,customers.name as customerName FROM `bids_of_lots` left JOIN lots on lots.id = bids_of_lots.lotId left JOIN customers on customers.id = bids_of_lots.customerId WHERE   bids_of_lots.lotId = ' . $request->lotid . ' and bids_of_lots.id >' . $request->lastBid . ' ORDER BY id  DESC LIMIT 1');
        if (count($result))
        {
            $response['sucess'] = true;
            $response['newbid'] = $result;
        }
        return json_encode($response);
    }


    //new code starts here
    public function customerBidding(Request $request)
    {
       try {
       $customerId = auth()->user()->id;
       $lotId = $request->lotId;
       $amount = $request->amount;

       
       //getting the lot information
       $lot = lots::with('autoBids' , 'bids.customer')->where('id' , $lotId)->first();
       $customer = Customer::find($customerId);


        //check wheather bid already sold or expired
        $status = $lot->lot_status;

        if(in_array($status , ['Sold' , 'Expired']))
        {
             $msg =   $status == 'Sold' ? "Lot Has Already Been Sold" : "Lot Has Been Expired"; 
             return response()->json(["success" => false , "msg" => $msg ]);
        }

       if($customer->isApproved)
       {
        // dd($lot->bids->isEmpty());      
        //if someone has bid against the lot
        if(!$lot->bids->isEmpty())
        {
            $lastRecordTime = $lot->bids()->latest()->first()->created_at;


            $currentTime = Carbon::now();
            
            $lastBidTime = Carbon::createFromFormat('Y-m-d H:i:s', $lastRecordTime);
            
            $timeDifferenceInSeconds = $lastBidTime->diffInSeconds($currentTime);
            
            if ($timeDifferenceInSeconds <= 120)
            {
                //when time is still remaingin add new bidding
                return $this->addNewBidding($customer , $amount , $lot , 0);
            }else{
                //when time is finished assigned lot to last bidder
                return $this->assignLastBidder($lot);
            }

        }else{
            //if no one has bid against the lot
            return $this->addNewBidding($customer , $amount , $lot , 0);
        }
    


       }
       else
       {
        
        return response()->json(["success" => false , "msg" => "User Is Not Allowed"]);
       
     }


    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong!" , "error" => $e->getMessage()]);
    }
        




    }

    function addNewBidding($customer , $amount , $lot , $bidType ){
        // dd("add new bidding");
        $currentPricing =  $lot->bids->count() > 0 ? $lot->bids->max('amount') : $lot->price;

        // $newPricing = $currentPricing + $amount;
        $newPricing = $amount;

        $manualBid = BidsOfLots::create([
            "customerId" => $customer->id,
            "amount" => $newPricing,
            "lotId" => $lot->id,
            "autoBid" => $bidType,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            
       ]);

       event(new winLotsEvent('Good Luck! You placed a new bid.', $manualBid, $customer, true));

       
       //checking wheather lot has auto bidders
        foreach($lot->autoBids as $autoBidder)
        {
                $newPricing = $newPricing + 100;
                $autoBid = BidsOfLots::create([
                    "customerId" => $autoBidder->customerId,
                    "amount" => $newPricing,
                    "lotId" => $lot->id,
                    "autoBid" => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

        $autoCustomer = Customer::where('id' , $autoBidder->customerId)->first();

        event(new winLotsEvent('Good Luck! You placed a new bid.', $autoBid, $autoCustomer, true));

        }

        return response()->json(['success' => true ,  'msg' =>'Your Bid Added Successfully' , 'bid' => $manualBid]);
    }

    public function assignLastBidder($lot)
    {
        // dd("assigning bidd");
        $lastBid = $lot->bids()->latest()->first();
        $latestBidCustomer = $lastBid->customer;
        
        $customerLot = CustomerLot::updateOrCreate(
            ['lot_id' => $lot->id],
            ['lot_id' => $lot->id  , 'customer_id' => $latestBidCustomer->id , 'created_at' => date('Y-m-d H:i:s')]
        );
        if( $customerLot ){
            $lot->lot_status = "Sold";
            $lot->save();
            
            dispatch(new LotJob($lot , $latestBidCustomer));
            //sending winner bidders email  
            event(new winLotsEvent('You are late! Sorry, another person won this lot.', $lastBid, $latestBidCustomer, false));

            $lastBid = BidsOfLots::where('lotId' , $lot->id)->latest()->first();

            $this->returnParticipationFee($lastBid);

            return response()->json(['success' => false ,  'msg' =>'Another Bidder Has Won Bidding You Are Too Late!' , 'bid' => $lastBid]);

        }

    }



    public function setCustomerAutobid(Request $request)
    {
        $lotId = $request->lotId;
        $customerId = auth()->user()->id;

        $lot = lots::with('bids.customer')->where('id' , $lotId)->first();
        $customer = Customer::find($customerId);

        $status = $lot->lot_status;

        if(in_array($status , ['Sold' , 'Expired']))
        {
             $msg =   $status == 'Sold' ? "Lot Has Already Been Sold" : "Lot Has Been Expired"; 
             return response()->json(["success" => false , "msg" => $msg ]);
        }

        $amount = $lot->bids->isEmpty() ? $lot->price : $lot->bids->max('amount');

        $amount += 100;

        if(!$lot->bids->isEmpty())
        {
            $lastBid = $lot->bids()->latest()->first();

            $lastBidTime = Carbon::createFromFormat( "Y-m-d H:i:s" , $lastBid->created_at);

            $currentTime = Carbon::now();

            $timeDifferenceInSeconds = $lastBidTime->diffInSeconds($currentTime);

            if($timeDifferenceInSeconds <= 120)
            {
                $createBid = BidsOfLots::create([
                                'customerId' => $customerId,
                                'amount' => $amount,
                                'lotId' => $lotId,
                                'autoBid' => 1
                            ]);

                if($createBid){
                    AutoBid::create([
                        'customerId' => $customerId,
                        'lotId' => $lotId,
                        'autoBid' => 1
                    ]);

                    event(new winLotsEvent('Another Bid Has Been Placed.', $createBid, $customer, true));

                    return response()->json(['success' => true , 'msg' => 'Autobid has been placed successfully']);

                }else{
                    return response()->json(['succes' => false , "msg" => "Something Went Wrong"]);
                }


                
            }else{
                return $this->assignLastBidder($lot);
            }

        }else{

            $createBid = BidsOfLots::create([
                'customerId' => $customerId,
                'amount' => $amount,
                'lotId' => $lotId,
                'autoBid' => 1
            ]);

            if($createBid){
                AutoBid::create([
                    'customerId' => $customerId,
                    'lotId' => $lotId,
                    'autoBid' => 1
                ]);

                event(new winLotsEvent('Bid Has Been Placed.', $createBid, $customer, true));

                return response()->json(['success' => true , 'msg' => 'Autobid has been placed successfully']);

            }

        }

    }

    //new code ends here

    public function stopAutoBid(Request $request)
    {
        try{
            $customerId = auth()->user()->id;
            $lotId = $request->lotId;
            
            AutoBid::where([
                'customerId' => $customerId,
                'lotId' => $lotId,
            ])->delete();

            return response()->json(['success' => true , 'msg' => 'Autobid Has Been Removed']);

        }catch(\Exception $e){

            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()]);
        
        }

    }


    public function checkCustomerAutobid(Request $request)
    {
        $customerId = auth()->user()->id;
        $lotId = $request->lotId;
        
        if(AutoBid::where(['lotId' => $lotId , 'customerId' => $customerId])->count())
        {
            return response()->json(['success' => true , 'msg' => 'An autobid has been placed against this customer' , 'autobid' => 1]);
        }else{
            return response()->json(['success' => false , 'msg' => 'There is no auto bid related to this customer' , 'autobid' => 0]);
        }

    }

}
