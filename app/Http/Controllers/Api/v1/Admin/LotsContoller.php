<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\BidsOfLots;
use App\Models\categories;
use App\Models\lot_materials;
use App\Models\lots;
use App\Models\FavLots;
use App\Models\lotTerms;
use App\Models\MaterialFiles;
use App\Models\materials;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use App\Models\Customer;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\DB;



class LotsContoller extends Controller
{
    private $user, $defaultNumber;

    // Get Soled Lots of Today.
    // public function getsoledlots()
    // {
    //     $lotList = DB::select("
    //     SELECT lots.*,payments.customerId,payments.customerVisible ,customers.name as customername,customers.compnyName as compnyName ,payments.total_amount as finalmount FROM `lots`
    //     LEFT JOIN payments on payments.lotId = lots.id
    //     LEFT JOIN customers on customers.id = payments.customerId 
    //     WHERE lots.lot_status = 'sold' and date(lots.EndDate) = CURDATE() GROUP by lots.id ORDER by lots.id DESC;");
    //     $newlotlist = [];
    //     foreach ($lotList as $lot) {

    //         array_push($newlotlist, [
    //             'lot' => $lot, 'lotTerms' => lotTerms::where('lotid', $lot->id)->first(), "materialilist" =>   new_maerials_2::where('lotid', $lot->id)->get()
    //         ]);
    //     }
    //     return json_encode([
    //         'lotList' => $newlotlist,
    //         'sucess' => true,
    //     ]);
    // }

    // Get Expired Lots of Today.
    // public function getexpiredlots()
    // {
    //     $lotList = DB::select("SELECT * FROM `lots` WHERE lot_status = 'expired' and date(EndDate) = CURDATE();");
    //     $newlotlist = [];
    //     foreach ($lotList as $lot) {
    //         array_push($newlotlist, ['lot' => $lot, 'lotTerms' => lotTerms::where('lotid', $lot->id)->first(), "materialilist" =>   new_maerials_2::where('lotid', $lot->id)->get()]);
    //     }
    //     return json_encode([
    //         'lotList' => $newlotlist,
    //         'sucess' => true,
    //     ]);
    // }

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

    // public function getActiveLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'active')
    //         ->get();
    
    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No active lots available',
    //             'success' => false,
    //         ]);
    //     }
    
    //     $activeLots = [];
    //     foreach ($lots as $lot) {
    //         $category = DB::table('categories')
    //             ->where('id', $lot->categoryId)
    //             ->select('id', 'title', 'description', 'parentcategory')
    //             ->get();
    
    //         $activeLots[] = [
    //             'lot' => $lot,
    //             'category' => $category,
    //         ];
    //     }
    
    //     return response()->json([
    //         'activeLots' => $activeLots,
    //         'success' => true,
    //     ]);
    // }

    // public function getActiveLots(Request $request)
    // {
    //     //NEW CODE STARTS HERE 

    //     $activeLots = DB::table('lots')

        
    //     ->join('categories' , 'lots.categoryId' , '=' , 'categories.id')
    //     ->leftJoin('user_lot','lots.id' , '=' , 'user_lot.lot_id')
    //     ->selectRaw('categories.id as cat_id , categories.title as category_title , categories.*, lots.id as l_id , lots.title as lot_title , lots.* , user_lot.id as fav_id , user_lot.* ')
    //     ->where('lots.lot_status' , 'Like' , '%live%')
    //     ->get();


    //     return response()->json([
    //         'activeLots' => $activeLots,
    //         'success' => true,
    //     ]);

    public function getActiveLots(Request $request)
    {

        $customerId = $request->input('customer_id');

        // dd($customerId);

        $userLots = Lots::with('categories')
                        ->with(['customers' => function($query) use ($customerId){
                             $query->where('customers.id' , $customerId);
                        }])
                        ->where('lot_status' , 'LIKE' , '%live%')
                        ->get();

                        // dd($userLots);

                        if ($userLots->isEmpty()) 
                        {
                            return response()->json([
                                'message' => 'No active or favorite lots available for the customer',
                                'success' => false,
                            ], 200);
                        }
                    
                        return response()->json([
                            'userLots' => $userLots,
                            'success' => true,
                        ]);

            
        // dd($userLots);



        // $customer_id = $request->input('customer_id');

        // $activeLots = DB::table('lots')
        //     ->join('categories', 'lots.categoryId', '=', 'categories.id')
        //     ->leftJoin('user_lot', function ($join) use ($customer_id) {
        //         $join->on('lots.id', '=', 'user_lot.lot_id')
        //             ->where('user_lot.customer_id', $customer_id);
        //     })
        //     ->select('lots.*', 'categories.title as category_title', 'user_lot.id as fav_id')
        //     ->where('lots.lot_status', 'like', '%live%')
        //     ->orWhereNotNull('user_lot.id')
        //     ->get();
    
        // if ($activeLots->isEmpty()) {
        //     return response()->json([
        //         'message' => 'No active or favorite lots available for the customer',
        //         'success' => false,
        //     ], 404);
        // }
    
        // return response()->json([
        //     'activeLots' => $activeLots,
        //     'success' => true,
        // ]);
    
    }


        //NEW CODE ENDS HERE


        // foreach ($lots as $lot) {
        //     $category = DB::table('categories')
        //         ->where('id', $lot->categoryId)
        //         ->first();

        //     if (!$category) {
        //         continue; // Skip if category not found
        //     }
            
        //     $userLot = DB::table('user_lot')
        //         ->where('lot_id', $lot->id)
        //         ->where('user_id', $request->user_id)
        //         ->first();


            

        //     $lotData = [
        //         'lot' => $lot,
        //         'category' => $category,
        //         'favourite' => $userLot ? true : false
        //     ];

        //     $activeLots[] = $lotData;
        // }

        // return response()->json([
        //     'activeLots' => $activeLots,
        //     'success' => true,
        // ]);
    // }

    // Upcoming Live Lots API

    // public function getUpcomingLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'upcoming')
    //         ->get();

    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No upcoming lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     return response()->json([
    //         'upcomingLots' => $lots,
    //         'success' => true,
    //     ]);
    // }

    // public function getUpcomingLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'upcoming')
    //         ->get();

    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No upcoming lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     $upcomingLots = [];
    //     foreach ($lots as $lot) {
    //         $categories = DB::table('categories')
    //             ->where('id', $lot->categoryId)
    //             ->select('id', 'title', 'description', 'parentcategory')
    //             ->get();

    //         $upcomingLots[] = [
    //             'lot' => $lot,
    //             'categories' => $categories,
    //         ];
    //     }

    //     return response()->json([
    //         'upcomingLots' => $upcomingLots,
    //         'success' => true,
    //     ]);
    // }

    public function getUpcomingLots()
    {
        $upcomingLots = DB::table('lots')
            ->join('categories', 'lots.categoryId', '=', 'categories.id')
            ->leftJoin('user_lot', 'lots.id', '=', 'user_lot.lot_id')
            ->selectRaw('categories.id as cat_id, categories.title as category_title, categories.*, lots.id as l_id, lots.title as lot_title, lots.*, user_lot.id as fav_id, user_lot.*')
            ->where('lots.lot_status', 'upcoming')
            ->get();

        if ($upcomingLots->isEmpty()) {
            return response()->json([
                'message' => 'No upcoming lots available',
                'success' => false,
            ]);
        }

        return response()->json([
            'upcomingLots' => $upcomingLots,
            'success' => true,
        ]);
    }



    // Experied Lots API 

    // public function ExpiredLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'Expired')
    //         ->get();

    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No expired lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     return response()->json([
    //         'expiredLots' => $lots,
    //         'success' => true,
    //     ]);
    // }
    
    // public function ExpiredLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'Expired')
    //         ->get();

    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No expired lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     $expiredLots = [];
    //     foreach ($lots as $lot) {
    //         $categories = DB::table('categories')
    //             ->where('id', $lot->categoryId)
    //             ->select('id', 'title', 'description', 'parentcategory')
    //             ->get();

    //         $expiredLots[] = [
    //             'lot' => $lot,
    //             'categories' => $categories,
    //         ];
    //     }

    //     return response()->json([
    //         'expiredLots' => $expiredLots,
    //         'success' => true,
    //     ]);
    // }
    
    public function getexpiredlots(Request $request)
    {
    
        // $expiredLots = DB::table('lots')
        // ->join('categories' , 'lots.categoryId' , '=' , 'categories.id')
        // ->leftJoin('user_lot','lots.id' , '=' , 'user_lot.lot_id')
        // ->selectRaw('categories.id as cat_id , categories.title as category_title , categories.*, lots.id as l_id , lots.title as lot_title , lots.* , user_lot.id as fav_id , user_lot.*')
        // ->where('lots.lot_status' , '=' , 'Expired')
        // ->get();

        // return response()->json([
        //     'expiredLots' => $expiredLots,
        //     'success' => true,
        // ]);
        

        $customerId = $request->input('customer_id');

        $expiredLots = Lots::with('categories')
                        ->with(['customers' => function($query) use ($customerId){
                             $query->where('customers.id' , $customerId);
                        }])
                        ->where('lot_status' , 'LIKE' , '%Expired%')
                        ->get();

                        // dd($userLots);

                        if ($expiredLots->isEmpty()) {
                            return response()->json([
                                'message' => 'No Expired lots available for the customer',
                                'success' => false,
                            ], 200);
                        }
                    
                        return response()->json([
                            'expiredLots' => $expiredLots,
                            'success' => true,
                        ]);
    }
    



    // Sold lots API
    // public function SoldLots()
    // {
    //     $lots = DB::table('lots')
    //         ->where('lot_status', 'Sold')
    //         ->get();

    //     if ($lots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No Sold lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     return response()->json([
    //         'soldLots' => $lots,
    //         'success' => true,
    //     ]);
    // }
    public function SoldLots(Request $request)
    {
        // {
        //     $soldLots = DB::table('lots')
        //     ->join('categories' , 'lots.categoryId' , '=' , 'categories.id')
        //     ->leftJoin('user_lot','lots.id' , '=' , 'user_lot.lot_id')
        //     ->selectRaw('categories.id as cat_id , categories.title as category_title , categories.*, lots.id as l_id , lots.title as lot_title , lots.* , user_lot.id as fav_id , user_lot.*')
        //     ->where('lots.lot_status' , '=' , 'Sold')
        //     ->get();
    
        //     return response()->json([
        //         'soldLots' => $soldLots,
        //         'success' => true,
        //     ]);
        // }


        $customerId = $request->input('customer_id');

        $soldLots = Lots::with('categories')
                        ->with(['customers' => function($query) use ($customerId)
                        {
                             $query->where('customers.id' , $customerId);
                        }])
                        ->where('lot_status' , 'LIKE' , '%Sold%')
                        ->get();

                        // dd($userLots);

                        if ($soldLots->isEmpty()) {
                            return response()->json([
                                'message' => 'No Sold lots available for the customer',
                                'success' => false,
                            ],200);
                        }
                    
                        return response()->json([
                            'soldLots' => $soldLots,
                            'success' => true,
                        ]);


    }

    // Lots Details API

    // public function lotsdetails(Request $request)
    // {
    //     $lots = lots::with('lotTerms', 'new_maerials_2')->get();

    //     $data = [];
    //     foreach ($lots as $lot) {
    //         $paymentTerms = $lot->lotTerms;
    //         $materials = $lot->new_maerials_2;

    //         $data[] = [
    //             'lot' => $lot,
    //         ];
    //     }

    //     return response()->json($data, Response::HTTP_OK);
    // }

    public function lotsdetails(Request $request)
    {
        $lots = lots::with('lotTerms', 'new_maerials_2', 'categories')->get();

        $data = [];
        foreach ($lots as $lot) {
            $paymentTerms = $lot->lotTerms;
            $materials = $lot->new_maerials_2;
            $categories = $lot->categories;

            $categoryData = $categories ? [
                'id' => $categories->id,
                'title' => $categories->title,
                'description' => $categories->description,
                'parentcategory' => $categories->parentcategory,
            ] : null;

            $data[] = [
                'lot' => $lot,
            ];
        }

        return response()->json($data, Response::HTTP_OK);
    }



    // Show specfic lot

    // public function specificlotshow(Request $request, $lotId)
    // {
    //     $lot = lots::with('lotTerms', 'new_maerials_2')->find($lotId);
    
    //     if (!$lot) {
    //         return response()->json(['message' => 'Lot not found'], Response::HTTP_NOT_FOUND);
    //     }
    
    //     $paymentTerms = $lot->lotTerms;
    //     $materials = $lot->new_maerials_2;
    
    //     $data = [
    //         'lot' => $lot,
    //     ];
    
    //     return response()->json($data, Response::HTTP_OK);
    // }

    public function specificlotshow(Request $request, $lotId)
    {
        $lot = lots::with('lotTerms', 'new_maerials_2', 'categories')->find($lotId);

        if (!$lot) {
            return response()->json(['message' => 'Lot not found'], Response::HTTP_NOT_FOUND);
        }

        $paymentTerms = $lot->lotTerms;
        $materials = $lot->new_maerials_2;
        $categories = $lot->categories;

        $categoryData = $categories ? [
            'id' => $categories->id,
            'title' => $categories->title,
            'description' => $categories->description,
            'parentcategory' => $categories->parentcategory,
        ] : [];

        $data = [
            'lot' => $lot,
        ];

        return response()->json($data, Response::HTTP_OK);
    }
    

    // add Favorites Lots in Lots 

    public function addFavorites(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $lot_id = $request->input('lot_id');

        // Check if the favorite lot already exists for the given customer and lot_id
        $existingFavorite = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->where('lot_id', $lot_id)
            ->first();

        // If the favorite lot already exists, return an error response
        if ($existingFavorite) {
            return response()->json([
                'message' => 'Favorite lot already exists for this customer',
                'success' => false,
            ]);
        }

        // Save the customer_id and lot_id to the favorites table in the database
        DB::table('user_lot')->insert([
            'customer_id' => $customer_id,
            'lot_id' => $lot_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Lot added to Favorites Lots',
            'success' => true,
        ]);
    }



        // delete favlot Api

        public function deleteFavorite(Request $request)
        {
            $customer_id = $request->input('customer_id');
            $lot_id = $request->input('lot_id');
        
            $deleted = DB::table('user_lot')
                ->where('customer_id', $customer_id)
                ->where('lot_id', $lot_id)
                ->delete();
        
            if ($deleted) {
                return response()->json([
                    'message' => 'Lot deleted from Favorites Lots',
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to delete the lot from Favorites Lots',
                    'success' => false,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        


    


     // show Favorites Lots in Lots 

    public function showFavorites($customer_id)
    {
        // Retrieve the favorite lots for the given customer_id from the database
        $favoritesLots = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->join('lots', 'user_lot.lot_id', '=', 'lots.id')
            ->select('lots.*')
            ->get();

        // Check if there are any favorite lots available for the customer
        if ($favoritesLots->isEmpty()) {
            return response()->json([
                'message' => 'Favorite lots not available for this customer',
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

    // Previous code -> Get Custimer Win Lots.
    public function getcustomerwinlots($customerId)
    {
        // $winLots = payments::where('customerId', $customerId)->with('lotDetails')->orderBy('id', 'desc')->get();
        $winLots = DB::select('SELECT payments.* ,lots.*  from payments
        LEFT JOIN lots on  lots.id =  payments.lotId WHERE
        payments.id IN (SELECT MAX(payments.id) from payments GROUP BY payments.lotId)
        AND payments.customerId = ' . $customerId . ' ORDER By payments.id DESC;');
        foreach ($winLots as $lot) {
            // dd($lot->lotId);
            $lot->material =  new_maerials_2::where('lotid', $lot->lotId)->get()->toArray();
        }

        return response()->json(["lots" => $winLots, "sucess" => true]);
    }

    //  public function getcustomerwinlots($customerId)
    // {
    //     // Subquery to get the maximum bid amount for each lot
    //     $subquery = DB::table('bids_of_lots')
    //         ->select('lotId', DB::raw('MAX(amount) as maxBidAmount'))
    //         ->groupBy('lotId');

    //     // Get the winLots with the highest bid amount
    //     $winLots = DB::table('lots')
    //         ->join('payments', 'lots.id', '=', 'payments.lotId')
    //         ->joinSub($subquery, 'max_bids', function ($join) {
    //             $join->on('lots.id', '=', 'max_bids.lotId');
    //         })
    //         ->where('payments.customerId', $customerId)
    //         ->orderBy('payments.id', 'desc')
    //         ->get();

    //     // Fetch the associated material for each lot
    //     foreach ($winLots as $lot) {
    //         $lot->material = new_maerials_2::where('lotid', $lot->lotId)->get()->toArray();
    //     }

    //     return response()->json(["lots" => $winLots, "success" => true]);
    // }


    
}
