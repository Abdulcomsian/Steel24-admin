<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\MessageEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Carbon;

use App\Models\BidsOfLots;
use App\Models\Customer;
use App\Models\lots;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;

class BidsOfLotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
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
            if ($lastBid && $lastBid['amount'] < $newBid['amount'] && $lastBid['lotId'] == $newBid['lotId']) {
                $lastBid = BidsOfLots::create($newBid);

                $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                FROM bids_of_lots
                LEFT JOIN customers on customers.id = bids_of_lots.customerId
                WHERE lotId =' . $newBid['lotId'] . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $newBid['lotId'] . ') ORDER BY bids_of_lots.amount DESC;');

                $firebase = (new Factory)
                    ->withServiceAccount(__DIR__ . './lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
                    ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
                $database = $firebase->createDatabase();
                $database->getReference('TodaysLots/liveList/' . $newBid['lotId'] . '/lastBid')->set($lastBid[0]);

                $response = ["sucess" => true, 'LattestBid' => $lastBid, "userDetails" => $customer[0]];
            } elseif (!$lastBid && $lotDtails->Price < $newBid['amount']) {
                $lastBid = BidsOfLots::create($newBid);
                // Have to Brodcast with

                $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                FROM bids_of_lots
                LEFT JOIN customers on customers.id = bids_of_lots.customerId
                WHERE lotId =' . $newBid['lotId'] . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $newBid['lotId'] . ') ORDER BY bids_of_lots.amount DESC;');

                // dd($lastBid);
                $firebase = (new Factory)
                    ->withServiceAccount(__DIR__ . './lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
                    ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
                $database = $firebase->createDatabase();
                $database->getReference('TodaysLots/liveList/' . $newBid['lotId'] . '/lastBid')->set($lastBid[0]);


                $response = ["sucess" => true, 'LattestBid' => $lastBid, "userDetails" => $customer[0]];
            } else {
                $response = ["message" => 'Bid Aount is small then last bid.', 'sucess' => false];
            }
            // $bids = BidsOfLots::get()->toArray();
            // info($bids);
            // event(new MessageEvent($lastBid,  $customer));
        } else {
            $response = ["message" => 'User is not Availabel Or User is Blocked.', 'sucess' => false];
        }
        return $response;
    }


    function lastBid($lotid)
    {
        $lastBid =  DB::select('SELECT bids_of_lots.*, customers.id as customerId,customers.name as customerName       
                FROM bids_of_lots
                LEFT JOIN customers on customers.id = bids_of_lots.customerId
                WHERE lotId =' . $lotid . ' and bids_of_lots.id =  (SELECT max(id) from bids_of_lots WHERE lotid = ' . $lotid . ') ORDER BY bids_of_lots.amount DESC;');

        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . './lotbids-7751a-firebase-adminsdk-2kxk6-5db00e2535.json')
            ->withDatabaseUri('https://lotbids-7751a-default-rtdb.europe-west1.firebasedatabase.app/');
        $database = $firebase->createDatabase();
        $database->getReference('TodaysLots/liveList/' . $lotid . '/lastBid')->set($lastBid[0]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidsOfLots  $bidsOfLots
     * @return \Illuminate\Http\Response
     */
    public function show(BidsOfLots $bidsOfLots)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidsOfLots  $bidsOfLots
     * @return \Illuminate\Http\Response
     */
    public function edit(BidsOfLots $bidsOfLots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidsOfLots  $bidsOfLots
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BidsOfLots $bidsOfLots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidsOfLots  $bidsOfLots
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidsOfLots $bidsOfLots)
    {
        //
    }
}
