<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
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
    // public function getcategorys()
    // {
    //     $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE date(lots.ReStartDate) <= CURDATE() GROUP by categories.id;");
    //     // $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE date(lots.StartDate) = CURDATE() OR date(lots.ReStartDate) = CURDATE() GROUP by categories.id;");
    //     return json_encode([
    //         'categoriList' => $categories,
    //         'sucess' => true,
    //     ]);
    // }

    // public function getcategorys()
    // {
    //     $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE date(lots.ReStartDate) <= CURDATE() GROUP by categories.id;");
    //     // $categories = DB::select("SELECT categories.id, categories.title FROM categories LEFT JOIN lots on lots.categoryId = categories.id WHERE date(lots.StartDate) = CURDATE() OR date(lots.ReStartDate) = CURDATE() GROUP by categories.id;");
    //     if (empty($categories)) {
    //         return json_encode([
    //             'message' => 'No Live Lots Available',
    //             'success' => false,
    //         ]);
    //     }
    //     return json_encode([
    //         'categoryList' => $categories,
    //         'success' => true,
    //     ]);
    // }

    public function getCategoriesAndLots()
    {
        $categories = DB::table('categories')
            ->leftJoin('lots', 'lots.categoryId', '=', 'categories.id')
            ->select('categories.id', 'categories.title As category_title', 'lots.*', 'lots.title AS lotTitle')
            ->groupBy('categories.id', 'lots.id')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories available',
                'success' => false,
            ]);
        }

        return response()->json([
            'categoriesAndLots' => $categories,
            'success' => true,
        ]);
    }


    // active lots api

    public function getActiveLots()
    {
        $lots = DB::table('lots')
            ->where('lot_status', 'active')
            ->get();

        if ($lots->isEmpty()) {
            return response()->json([
                'message' => 'No active lots available',
                'success' => false,
            ]);
        }

        return response()->json([
            'activeLots' => $lots,
            'success' => true,
        ]);
    }

    // Upcoming Live Lots API

    public function getUpcomingLots()
    {
        $lots = DB::table('lots')
            ->where('lot_status', 'upcoming')
            ->get();

        if ($lots->isEmpty()) {
            return response()->json([
                'message' => 'No upcoming lots available',
                'success' => false,
            ]);
        }

        return response()->json([
            'upcomingLots' => $lots,
            'success' => true,
        ]);
    }

    // Experied Lots API 

    public function ExpiredLots()
    {
        $lots = DB::table('lots')
            ->where('lot_status', 'Expired')
            ->get();

        if ($lots->isEmpty()) {
            return response()->json([
                'message' => 'No expired lots available',
                'success' => false,
            ]);
        }

        return response()->json([
            'expiredLots' => $lots,
            'success' => true,
        ]);
    }

    // Sold lots API
    public function SoldLots()
    {
        $lots = DB::table('lots')
            ->where('lot_status', 'Sold')
            ->get();

        if ($lots->isEmpty()) {
            return response()->json([
                'message' => 'No Sold lots available',
                'success' => false,
            ]);
        }

        return response()->json([
            'soldLots' => $lots,
            'success' => true,
        ]);
    }

    // Lots Details API

    public function lotsdetails(Request $request)
    {
        $lots = lots::with('lotTerms', 'new_maerials_2')->get();

        $data = [];
        foreach ($lots as $lot) {
            $paymentTerms = $lot->lotTerms;
            $materials = $lot->new_maerials_2;

            $data[] = [
                'lot' => $lot,
                // 'lotTerms' => $paymentTerms,
                // 'materials' => $materials,
            ];
        }

        return response()->json($data, Response::HTTP_OK);
    }


    // Show specfic lot

    public function specificlotshow(Request $request, $lotId)
    {
        $lot = lots::with('lotTerms', 'new_maerials_2')->find($lotId);
    
        if (!$lot) {
            return response()->json(['message' => 'Lot not found'], Response::HTTP_NOT_FOUND);
        }
    
        $paymentTerms = $lot->lotTerms;
        $materials = $lot->new_maerials_2;
    
        $data = [
            'lot' => $lot,
            // 'lotTerms' => $paymentTerms,
            // 'materials' => $materials,
        ];
    
        return response()->json($data, Response::HTTP_OK);
    }
    


    // add Favorites Lots in Lots 
    public function addFavorites(Request $request)
    {
        $user_id = $request->input('user_id');
        $lot_id = $request->input('lot_id');

        // Save the user_id and lot_id to the favorites table in the database
        DB::table('user_lot')->insert([
            'user_id' => $user_id,
            'lot_id' => $lot_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Lot added to Favorites Lots',
            'success' => true,
        ]);
    }

    // show Favorites Lots in Lots 
    public function showFavorites($user_id)
    {
        // Retrieve the favorite lots for the given user_id from the database
        $favoritesLots = DB::table('user_lot')
            ->where('user_id', $user_id)
            ->join('lots', 'user_lot.lot_id', '=', 'lots.id')
            ->select('lots.*')
            ->get();

        // Check if there are any favorite lots available for the user
        if ($favoritesLots->isEmpty()) {
            return response()->json([
                'message' => 'Favorites lots not available for this user',
                'success' => false,
            ]);
        }

        // Return the favorite lots as a response
        return response()->json([
            'message' => 'Favorite lots retrieved',
            'success' => true,
            'lots' => $favoritesLots,
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
