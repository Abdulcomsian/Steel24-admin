<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BidsOfLots;
use App\Models\categories;
use App\Models\lot_materials;
use App\Models\lots;
use App\Models\lotTerms;
use App\Models\MaterialFiles;
use App\Models\materials;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\DB;

class LotsContoller extends Controller
{
    private $user, $defaultNumber;

    // Get Soled Lots of Today.
    public function getsoledlots()
    {
        $lotList = DB::select("
        SELECT lots.*,payments.customerId,payments.customerVisible ,customers.name as customername,customers.compnyName as compnyName ,payments.total_amount as finalmount FROM `lots`
        LEFT JOIN payments on payments.lotId = lots.id
        LEFT JOIN customers on customers.id = payments.customerId 
        WHERE lots.lot_status = 'sold' and date(lots.EndDate) = CURDATE() GROUP by lots.id ORDER by lots.id DESC;");
        $newlotlist = [];
        foreach ($lotList as $lot) {

            array_push($newlotlist, [
                'lot' => $lot, 'lotTerms' => lotTerms::where('lotid', $lot->id)->first(), "materialilist" =>   new_maerials_2::where('lotid', $lot->id)->get()
            ]);
        }
        return json_encode([
            'lotList' => $newlotlist,
            'sucess' => true,
        ]);
    }

    // Get Expired Lots of Today.
    public function getexpiredlots()
    {
        $lotList = DB::select("SELECT * FROM `lots` WHERE lot_status = 'expired' and date(EndDate) = CURDATE();");
        $newlotlist = [];
        foreach ($lotList as $lot) {
            array_push($newlotlist, ['lot' => $lot, 'lotTerms' => lotTerms::where('lotid', $lot->id)->first(), "materialilist" =>   new_maerials_2::where('lotid', $lot->id)->get()]);
        }
        return json_encode([
            'lotList' => $newlotlist,
            'sucess' => true,
        ]);
    }

    // Get Categorys of Today.
    public function getcategorys()
    {
        $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE date(lots.StartDate) = CURDATE() OR date(lots.ReStartDate) = CURDATE() GROUP by categories.id;");
        return json_encode([
            'categoriList' => $categories,
            'sucess' => true,
        ]);
    }

    // Get Custimer Participated lots.
    public function getcustimerparticipatelots($customerId)
    {
        // $customerLots = BidsOfLots::where('customerId', $customerId)->with('lotDetails')->orderBy('id', 'desc')->get();

        $customerLots = DB::select('SELECT bids_of_lots.* ,lots.*  from bids_of_lots
        LEFT JOIN lots on  lots.id =  bids_of_lots.lotId WHERE
        bids_of_lots.id IN (SELECT MAX(bids_of_lots.id) from bids_of_lots GROUP BY bids_of_lots.lotId)
        AND bids_of_lots.customerId = ' . $customerId . ' ORDER By bids_of_lots.id DESC');

        return response()->json(["lots" => $customerLots, "sucess" => true]);
    }

    // Get Custimer Win Lots.
    public function getcustimerwinlots($customerId)
    {
        // $winLots = payments::where('customerId', $customerId)->with('lotDetails')->orderBy('id', 'desc')->get();
        $winLots = DB::select('SELECT payments.* ,lots.*  from payments
        LEFT JOIN lots on  lots.id =  payments.lotId WHERE
        payments.id IN (SELECT MAX(payments.id) from payments GROUP BY payments.lotId)
        AND payments.customerId = ' . $customerId . ' ORDER By payments.id DESC;');
        foreach ($winLots as $lot) {
            // dd($lot->lotId);
            $lot->material =  new_maerials_2::where('lotid', $lot->lotId)->get()->toArray();;
        }

        return response()->json(["lots" => $winLots, "sucess" => true]);
    }
}
