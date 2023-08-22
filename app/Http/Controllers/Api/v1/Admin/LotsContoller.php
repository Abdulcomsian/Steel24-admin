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
use Illuminate\Support\Facades\DB;
use App\Models\lotTerms;
use App\Models\MaterialFiles;
use App\Models\materials;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use App\Models\Customer;
use App\Models\customerBalance;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
// use \Illuminate\Support\Carbon;
use Carbon\Carbon;
// use \Illuminate\Support\Facades\DB;
use App\Events\winLotsEvent;
use Pusher\Pusher;


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

    // here start previous code backup

    // public function getActiveLots(Request $request)
    // {

    //     $customerId = $request->input('customer_id');

    //     // dd($customerId);

    //     $userLots = Lots::with('categories')
    //                     ->with(['customers' => function($query) use ($customerId)
    //                     {
    //                          $query->where('customers.id' , $customerId);
    //                     }])
    //                     ->where('lot_status' , 'LIKE' , '%live%')
    //                     ->get();

    //                     // dd($userLots);

    //                     if ($userLots->isEmpty()) 
    //                     {
    //                         return response()->json([
    //                             'message' => 'No active or favorite lots available for the customer',
    //                             'success' => false,
    //                         ], 200);
    //                     }
                    
    //                     return response()->json([
    //                         'userLots' => $userLots,
    //                         'success' => true,
    //                     ]);

    // }

    
    // public function getActiveLots(Request $request)
    // {
    //     $customerId = $request->customer_id;
    
    //     $lots = lots::with(['customerBalance' => function($query) use ($customerId)
    //     {
    //             $query->where('customerId', $customerId);
    //         }])
    //         ->with(['customers' => function($query) use ($customerId) 
    //         {
    //             $query->where('customer_id', $customerId);
    //         }])
    //         ->with('categories')
    //         ->where('lot_status', 'LIKE', '%live%')
    //         ->get();
    
    //     return response()->json(['userLots' => $lots, 'success' => true]);




    // public function getActiveLots(Request $request)
    // {
    //     $customerId = $request->customer_id;
    
    //     $lots = lots::with(['customerBalance' => function ($query) use ($customerId) {
    //             $query->where('customerId', $customerId);
    //         }])
    //         ->with(['customers' => function ($query) use ($customerId) {
    //             $query->where('customer_id', $customerId);
    //         }])
    //         ->with(['categories', 'bids'])
    //         ->where('lot_status', 'LIKE', '%live%')
    //         ->orderBy('created_at', 'asc')
    //         ->get();
    
    //     return response()->json(['userLots' => $lots, 'success' => true]);
    // }
      

    // public function getActiveLots(Request $request)
    // {
    //     $customerId = $request->customer_id;
    
    //     $lots = lots::with(['customerBalance' => function ($query) use ($customerId) {
    //             $query->where('customerId', $customerId);
    //         }])
    //         ->with(['customers' => function ($query) use ($customerId) {
    //             $query->where('customer_id', $customerId);
    //         }])
    //         ->with(['categories', 'bids' => function ($query) {
    //             $query->orderBy('created_at', 'desc')->take(1);
    //         }])
    //         ->where('lot_status', 'LIKE', '%live%')
    //         ->orderBy('created_at', 'asc')
    //         ->get();
    
    //     return response()->json(['userLots' => $lots, 'success' => true]);
    // }


    public function getActiveLots(Request $request)
    {
        $customerId = $request->customer_id;
    
        $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
        {
                $query->where('customerId', $customerId);
            }])
            ->with(['customers' => function ($query) use ($customerId) 
            {
                $query->where('customer_id', $customerId);
            }])
            ->with(['categories', 'bids' => function ($query) 
            {
                $query->orderBy('created_at', 'desc')->take(1);
            }])
            ->where('lot_status', 'LIKE', '%live%')

            ->orderBy('StartDate', 'asc') 
            ->get();
    
        return response()->json(['userLots' => $lots, 'success' => true]);
    }

    // Upcoming Lots API

    public function upcomingLots(Request $request)
    {
        $customerId = $request->customer_id;
    
        $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
        {
                $query->where('customerId', $customerId);
            }])
            ->with(['customers' => function ($query) use ($customerId) 
            {
                $query->where('customer_id', $customerId);
            }])
            ->with(['categories', 'bids' => function ($query) 
            {
                $query->orderBy('created_at', 'desc')->take(1);
            }])
            ->where('lot_status', 'LIKE', '%upcoming%')

            ->orderBy('StartDate', 'asc') 
            ->get();
    
        return response()->json(['userLots' => $lots, 'success' => true]);
    }
    
    
    
    
    


    
        // $customerId = $request->input('customer_id');

        // $userLots = lots::with('categories')
        //     ->with(['customers' => function ($query) use ($customerId) {
        //         $query->where('customers.id', $customerId);
        //     }])
        //     ->where('lot_status', 'LIKE', '%live%')
        //     ->get();

        // // Fetch the customer balance details
        // $customerBalance = customerBalance::where('customerId', $customerId)->first();

        // // dd($customerBalance);
        // if ($userLots->isEmpty()) 
        // {
        //     return response()->json([
        //         'message' => 'No active or favorite lots available for the customer',
        //         'success' => false,
        //     ], 200);
        // }

        // // Check if participate fee has been paid for each lot
        // foreach ($userLots as $lot) {
        //     // Assuming 'participate_fee' is the column name for the participate fee in the 'lots' table
        //     if ($customerBalance) 
        //     {
        //         $lot->isParticipated = $customerBalance->finalAmount >= $lot->participate_fee;
        //     } else 
        //     {
        //         $lot->isParticipated = false;
        //     }
        // }

        // return response()->json([
        //     'userLots' => $userLots,
        //     'success' => true,
        // ]);


        

                
        
        //new code ends here
        // $customerId = $request->input('customer_id');

        // $userLots = lots::with('categories')
        //     ->with(['customers' => function ($query) use ($customerId) 
        //     {
        //         $query->where('customers.id', $customerId);
        //     }])
        //     ->where('lot_status', 'LIKE', '%live%')
        //     ->get();

        // // Fetch the customer balance details
        // $customerBalance = customerBalance::where('customerId', $customerId)->first();

        // if ($userLots->isEmpty()) {
        //     return response()->json([
        //         'message' => 'No active or favorite lots available for the customer',
        //         'success' => false,
        //     ], 200);
        // }

        // // Check if participate fee has been paid for each lot
        // foreach ($userLots as $lot) 
        // {
        //     // Assuming 'participate_fee' is the column name for the participate fee in the 'lots' table
        //     $lot->isParticipated = ($customerBalance && $customerBalance->finalAmount >= $lot->participate_fee);
        // }

        // return response()->json([
        //     'userLots' => $userLots,
        //     'success' => true,
        // ]);


    // }
    



            
        // dd($userLots);



    //     $customer_id = $request->input('customer_id');

    //     $activeLots = DB::table('lots')
    //         ->join('categories', 'lots.categoryId', '=', 'categories.id')
    //         ->leftJoin('user_lot', function ($join) use ($customer_id) {
    //             $join->on('lots.id', '=', 'user_lot.lot_id')
    //                 ->where('user_lot.customer_id', $customer_id);
    //         })
    //         ->select('lots.*', 'categories.title as category_title', 'user_lot.id as fav_id')
    //         ->where('lots.lot_status', 'like', '%live%')
    //         ->orWhereNotNull('user_lot.id')
    //         ->get();
    
    //     if ($activeLots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No active or favorite lots available for the customer',
    //             'success' => false,
    //         ], 404);
    //     }
    
    //     return response()->json([
    //         'activeLots' => $activeLots,
    //         'success' => true,
    //     ]);
    
    // }

    // previous code backup


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

    // ***************new code here***********

    // public function getUpcomingLots()
    // {
    //     $upcomingLots = DB::table('lots')
    //         ->join('categories', 'lots.categoryId', '=', 'categories.id')
    //         ->leftJoin('user_lot', 'lots.id', '=', 'user_lot.lot_id')
    //         ->selectRaw('categories.id as cat_id, categories.title as category_title, categories.*, lots.id as l_id, lots.title as lot_title, lots.*, user_lot.id as fav_id, user_lot.*')
    //         ->where('lots.lot_status', 'upcoming')
    //         ->get();

    //     if ($upcomingLots->isEmpty()) 
    //     {
    //         return response()->json([
    //             'message' => 'No upcoming lots available',
    //             'success' => false,
    //         ]);
    //     }

    //     return response()->json([
    //         'upcomingLots' => $upcomingLots,
    //         'success' => true,
    //     ]);
    // }
      
    public function getActiveAndUpcomingLots(Request $request)
    {
        // $lots = DB::table('lots')
        //     ->join('categories', 'lots.categoryId', '=', 'categories.id')
        //     ->leftJoin('user_lot', 'lots.id', '=', 'user_lot.lot_id')
        //     ->selectRaw('categories.id as cat_id, categories.title as category_title, categories.*, lots.id as l_id, lots.title as lot_title, lots.*, user_lot.id as fav_id, user_lot.*')
        //     ->where(function ($query) {
        //         $query->where('lots.lot_status', 'live')
        //               ->orWhere('lots.lot_status', 'upcoming');
        //     })
        //     ->get();
    
        // if ($lots->isEmpty()) 
        // {
        //     return response()->json([
        //         'message' => 'No live or upcoming lots available',
        //         'success' => false,
        //     ]);
        // }
    
        // return response()->json([
        //     'lots' => $lots,
        //     'success' => true,
        // ]);


        $customerId = $request->customer_id;
    
        $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
        {
                $query->where('customerId', $customerId);
            }])
            ->with(['customers' => function ($query) use ($customerId) 
            {
                $query->where('customer_id', $customerId);
            }])
            ->with(['categories', 'bids' => function ($query) 
            {
                $query->orderBy('created_at', 'desc')->take(1);
            }])
            ->where('lot_status', 'LIKE', '%live%')
            ->orWhere('lot_status', 'LIKE', '%upcoming%')
            ->orderBy('StartDate', 'asc') 
            ->get();
    
        return response()->json(['userLots' => $lots,'success' => true]);
    }
    


    // ************** new code end ************



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


        

        // $customerId = $request->input('customer_id');
        // $expiredLots = Lots::with('categories')
        //                 ->with(['customers' => function($query) use ($customerId)
        //                 {
        //                      $query->where('customers.id' , $customerId);
        //                 }])
        //                 ->where('lot_status' , 'LIKE' , '%Expired%')
        //                 ->get();

        //                 // dd($userLots);

        //                 if ($expiredLots->isEmpty()) {
        //                     return response()->json([
        //                         'message' => 'No Expired lots available for the customer',
        //                         'success' => false,
        //                     ], 200);
        //                 }
                    
        //                 return response()->json([
        //                     'expiredLots' => $expiredLots,
        //                     'success' => true,
        //                 ]);



        $customerId = $request->customer_id;
    
        $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
        {
                $query->where('customerId', $customerId);
            }])
            ->with(['customers' => function ($query) use ($customerId) 
            {
                $query->where('customer_id', $customerId);
            }])
            ->with(['categories', 'bids' => function ($query) 
            {
                $query->orderBy('created_at', 'desc')->take(1);
            }])
            ->where('lot_status', 'LIKE', '%Expired%')

            ->orderBy('StartDate', 'asc') 
            ->get();
    
        return response()->json(['ExpiredLots' => $lots, 'success' => true]);
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

        // previous API


    //     $customerId = $request->input('customer_id');

    //     $soldLots = Lots::with('categories')
    //                     ->with(['customers' => function($query) use ($customerId)
    //                     {
    //                          $query->where('customers.id' , $customerId);
    //                     }])
    //                     ->where('lot_status' , 'LIKE' , '%Sold%')
    //                     ->get();

    //                     // dd($userLots);

    //                     if ($soldLots->isEmpty()) {
    //                         return response()->json([
    //                             'message' => 'No Sold lots available for the customer',
    //                             'success' => false,
    //                         ],200);
    //                     }
                    
    //                     return response()->json([
    //                         'soldLots' => $soldLots,
    //                         'success' => true,
    //                     ]);


    // }




            // $customerId = $request->customer_id;
            
            // $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
            // {
            //         $query->where('customerId', $customerId);
            //     }])
            //     ->with(['customers' => function ($query) use ($customerId) 
            //     {
            //         $query->where('customer_id', $customerId);
            //     }])
            //     ->with(['categories', 'bids' => function ($query) 
            //     {
            //         $query->orderBy('created_at', 'desc')->take(1);
            //     }])
            //     ->where('lot_status', 'LIKE', '%Sold%')

            //     ->orderBy('StartDate', 'asc') 
            //     ->get();

            // return response()->json(['userLots' => $lots, 'success' => true]);




            // $customerId = $request->customer_id;

            // $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
            // {
            //     $query->where('customerId', $customerId);
            // }])
            // ->with(['customers' => function ($query) use ($customerId)
            // {
            //     $query->where('customer_id', $customerId);
            // }])
            // ->with(['categories'])
            // ->with(['bids' => function ($query) 
            // {
            //     $query->select('lotId', DB::raw('MAX(amount) as max_bid'))->groupBy('lotId');
            // }])
            // ->where('lot_status', 'LIKE', '%Sold%')
            // ->orderBy('StartDate', 'asc') 
            // ->get();
        
            // return response()->json(['userLots' => $lots, 'success' => true]);


            $customerId = $request->customer_id;

            $lots = lots::with(['customerBalance' => function ($query) use ($customerId) 
            {
                $query->where('customerId', $customerId);
            }])
            ->with(['customers' => function ($query) use ($customerId)
            {
                $query->where('customer_id', $customerId);
            }])
            ->with(['categories'])
            ->with(['bids' => function ($query) 
            {
                $query->select('id', 'customerId', DB::raw('MAX(amount) as max_bid'), 'lotId', 'autoBid', 'created_at', 'updated_at')
                    ->groupBy('lotId'); // Retrieve the max_bid and group by lotId
            }])
            ->where('lot_status', 'LIKE', '%Sold%')
            ->orderBy('StartDate', 'asc') 
            ->get();

            return response()->json(['userLots' => $lots, 'success' => true]);

        }

    // end sold api

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

    // public function specificlotshow(Request $request, $lotId)
    // {
    //     $lot = lots::with('lotTerms', 'new_maerials_2', 'categories')->find($lotId);

    //     if (!$lot) 
    //     {
    //         return response()->json(['message' => 'Lot not found'], Response::HTTP_NOT_FOUND);
    //     }

    //     $paymentTerms = $lot->lotTerms;
    //     $materials = $lot->new_maerials_2;
    //     $categories = $lot->categories;

    //     $categoryData = $categories ? [
    //         'id' => $categories->id,
    //         'title' => $categories->title,
    //         'description' => $categories->description,
    //         'parentcategory' => $categories->parentcategory,
    //     ] : [];

    //     $data = [
    //         'lot' => $lot,
    //     ];

    //     return response()->json($data, Response::HTTP_OK);
    // }


    public function specificlotshow(Request $request, $lotId)
    {
        $lot = lots::with('lotTerms', 'new_maerials_2', 'categories')->find($lotId);

        if (!$lot) 
        {
            return response()->json(['message' => 'Lot not found'], Response::HTTP_NOT_FOUND);
        }

        // Get the maximum bid for the lot
        $maxBid = $lot->maxBid();

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
            'maxBid' => $maxBid, // Add the maximum bid object to the response data
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

    // public function showFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id from the database
    //     $favoritesLots = DB::table('user_lot')
    //         ->where('customer_id', $customer_id)
    //         ->join('lots', 'user_lot.lot_id', '=', 'lots.id')
    //         ->select('lots.*')
    //         ->get();

    //     // Check if there are any favorite lots available for the customer
    //     if ($favoritesLots->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Favorite lots not available for this customer',
    //             'success' => false,
    //         ]);
    //     }

    //     // Return the favorite lots as a response
    //     return response()->json([
    //         'message' => 'Favorite lots retrieved',
    //         'success' => true,
    //         'lots' => $favoritesLots,
    //     ]);
    // }




    //     public function showFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id along with their max_bid
    //     $favoritesLots = FavLots::where('customer_id', $customer_id)
    //         ->with(['lot' => function ($query) {
    //             $query->with(['bids' => function ($subQuery) {
    //                 $subQuery->orderBy('amount', 'desc')->take(1);
    //             }]);
    //         }])
    //         ->get();

    //     $result = [];

    //     foreach ($favoritesLots as $favoriteLot) {
    //         $lot = $favoriteLot->lot;

    //         if ($lot && $lot->bids->isNotEmpty()) 
    //         {
    //             $maxBid = $lot->bids->first()->amount;
    //             $result[] = [
    //                 'id' => $lot->id,
    //                 'customerId' => $customer_id,
    //                 'max_bid' => $maxBid,
    //                 'created_at' => $lot->bids->first()->created_at,
    //                 'updated_at' => $lot->bids->first()->updated_at,
    //             ];
    //         }
    //     }

    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite lots retrieved',
    //         'success' => true,
    //         'bids' => $result,
    //     ]);
    // }

    // public function showFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id
    //     $favoriteLots = FavLots::where('customer_id', $customer_id)->get();
    
    //     $result = [];
    
    //     foreach ($favoriteLots as $favoriteLot) {
    //         $lotId = $favoriteLot->lot_id;
            
    //         // Retrieve the maximum bid amount for the lot
    //         $maxBidAmount = DB::table('bids_of_lots')
    //             ->where('lotId', $lotId)
    //             ->max('amount');
    
    //         if ($maxBidAmount !== null) {
    //             $result[] = [
    //                 'id' => $lotId,
    //                 'customerId' => $customer_id,
    //                 'max_bid' => $maxBidAmount,
    //             ];
    //         }
    //     }
    
    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite lots retrieved',
    //         'success' => true,
    //         'bids' => $result,
    //     ]);
    // }

    // public function showFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id from the database
    //     $favoritesLots = DB::table('user_lot')
    //         ->where('customer_id', $customer_id)
    //         ->join('lots', 'user_lot.lot_id', '=', 'lots.id')
    //         ->select('lots.*')
    //         ->get();
    
    //     // Check if there are any favorite lots available for the customer
    //     if ($favoritesLots->isEmpty()) 
    //     {
    //         return response()->json([
    //             'message' => 'Favorite lots not available for this customer',
    //             'success' => false,
    //         ]);
    //     }
    
    //     // Retrieve the maximum bid amount for each favorite lot
    //     foreach ($favoritesLots as $favoriteLot) {
    //         $maxBidAmount = DB::table('bids_of_lots')
    //             ->where('lotId', $favoriteLot->id)
    //             ->max('amount');
    
    //         if ($maxBidAmount !== null) {
    //             $favoriteLot->max_bid = $maxBidAmount;
    //         }
    //     }
    
    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite lots retrieved',
    //         'success' => true,
    //         'lots' => $favoritesLots,
    //     ]);
    // }
    
    public function showFavorites($customer_id)
    {
        // Retrieve the favorite lots for the given customer_id
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();
    
        $result = [];
    
        foreach ($favoriteLots as $favoriteLot) {
            $lot = $favoriteLot->lot;
    
            if ($lot) {
                // Retrieve the maximum bid for the lot
                $maxBid = BidsOfLots::where('lotId', $lot->id)
                    ->orderBy('amount', 'desc')
                    ->first();
    
                $lotArray = $lot->toArray();
    
                if ($maxBid) {
                    $maxBidArray = [
                        'id' => $maxBid->id,
                        'customerId' => $maxBid->customerId,
                        'amount' => $maxBid->amount,
                        'lotId' => $maxBid->lotId,
                        'created_at' => $maxBid->created_at,
                        'updated_at' => $maxBid->updated_at,
                    ];
    
                    $lotArray['bids'] = [$maxBidArray];
                }
    
                $result[] = $lotArray;
            }
        }
    
        // Return the favorite lots with max_bid as a response
        return response()->json([
            'message' => 'Favorite lots retrieved',
            'success' => true,
            'Fav_lots' => $result,
        ]);
    }


    // Show Lots Categories
   
        public function showcategories()
    {
        // Retrieve all categories
        $categories = categories::all();

        return response()->json([
            'message' => 'Categories retrieved',
            'success' => true,
            'categories' => $categories,
        ]);
    }


    // show category with lots

    // public function showcategorieswithlot(Request $request)
    // {
    //     $customerId = $request->input('customerId');
    //     $categoryId = $request->input('categoryId');

    //     // Retrieve the category along with its lots
    //     $categoryWithLots = categories::with(['lot' => function ($query) use ($customerId) 
    //     {
    //         $query->with(['bids' => function ($subQuery) use ($customerId) {
    //             $subQuery->where('customerId', $customerId)->orderBy('amount', 'desc')->take(1);
    //         }]);
    //     }])
    //     ->find($categoryId);

    //     if (!$categoryWithLots) {
    //         return response()->json([
    //             'message' => 'Category not found',
    //             'success' => false,
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Category with lots retrieved',
    //         'success' => true,
    //         'category' => $categoryWithLots,
    //     ]);
    // }
    
    // public function showcategorieswithlot(Request $request)
    // {
    //     $customerId = $request->input('customerId');
    //     $categoryId = $request->input('categoryId');
    
    //     // Retrieve the category
    //     $category = categories::find($categoryId);
    
    //     if (!$category) {
    //         return response()->json([
    //             'message' => 'Category not found',
    //             'success' => false,
    //         ]);
    //     }
    
    //     // Retrieve live lots associated with the category
    //     $liveLots = $category->lot()->where('lot_status', 'live')->get();
    
    //     // Fetch max bid for each live lot
    //     $liveLotsWithMaxBids = [];
    //     foreach ($liveLots as $lot) {
    //         $maxBid = $lot->maxBid();
    //         $liveLotsWithMaxBids[] = [
    //             'lot' => $lot,
    //             'max_bid' => $maxBid,
    //         ];
    //     }
    
    //     return response()->json([
    //         'message' => 'Live lots for the category retrieved',
    //         'success' => true,
    //         'category' => $category,
    //         'live_lots' => $liveLotsWithMaxBids,
    //     ]);
    // }

    public function showcategorieswithlot(Request $request)
    {
        $customerId = $request->input('customerId');
        $categoryId = $request->input('categoryId');
    
        // Retrieve the category
        $category = categories::find($categoryId);
    
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'success' => false,
            ]);
        }
    
        // Retrieve live lots associated with the category
        $liveLots = lots::with([
            'customers' => function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            },
            'categories',
            'bids' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(1);
            }
        ])
        ->where('categoryId', $categoryId)
        ->where('lot_status', 'LIKE', '%live%')
        ->orderBy('StartDate', 'asc')
        ->get();
    
        return response()->json([
            'userLots' => $liveLots,
            'success' => true,
            // 'category' => $category,
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
    // public function getcustomerwinlots($customerId)
    // {
    //     // $winLots = payments::where('customerId', $customerId)->with('lotDetails')->orderBy('id', 'desc')->get();
    //     $winLots = DB::select('SELECT payments.* ,lots.*  from payments
    //     LEFT JOIN lots on  lots.id =  payments.lotId WHERE
    //     payments.id IN (SELECT MAX(payments.id) from payments GROUP BY payments.lotId)
    //     AND payments.customerId = ' . $customerId . ' ORDER By payments.id DESC;');
    //     foreach ($winLots as $lot) {
    //         // dd($lot->lotId);
    //         $lot->material =  new_maerials_2::where('lotid', $lot->lotId)->get()->toArray();
    //     }

    //     return response()->json(["lots" => $winLots, "sucess" => true]);
    // }


    // Previous CODE

    // public function getcustomerwinlots($customerId)
    // {
    //     $maxBid = BidsOfLots::where('customerId', $customerId)->max('amount');

    //     if ($maxBid !== null) 
    //     {
    //         $winLot = BidsOfLots::where('customerId', $customerId)
    //             ->where('amount', $maxBid)
    //             ->with('lotDetails')
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         if ($winLot) 
    //         {
    //             $winLot->material = new_maerials_2::where('lotid', $winLot->lotId)->get()->toArray();
    //             return response()->json(["lots" => [$winLot], "success" => true]);
    //         }
    //     }

    //     return response()->json(["message" => "No win lot found for the customer", "success" => false]);
    // }

    // public function getCustomerWinLots($customerId)
    // {
    //     // Get the maximum bid amount for the customer
    //     $maxBidAmount = BidsOfLots::where('customerId', $customerId)->max('amount');
    
    //     if ($maxBidAmount !== null) {
    //         // Retrieve the lot with the maximum bid amount for the customer
    //         $winLot = BidsOfLots::where('customerId', $customerId)
    //             ->where('amount', $maxBidAmount)
    //             ->with('lotDetails')
    //             ->orderBy('id', 'desc')
    //             ->first();
    
    //         if ($winLot) {
    //             // Load the related materials for the win lot
    //             $winLot->material = new_maerials_2::where('lotid', $winLot->lotId)->get()->toArray();
    //             return response()->json(["lots" => [$winLot], "success" => true]);
    //         }
    //     }
    
    //     return response()->json(["message" => "No win lot found for the customer", "success" => false]);
    // }




        public function getCustomerWinLots($customerId)
    {
        // Get distinct win lots for the customer
        $winLots = BidsOfLots::select('lotId')
            ->distinct()
            ->where('customerId', $customerId)
            ->orderBy('lotId', 'desc')
            ->get();

        if ($winLots->count() > 0) 
        {
            $winningLots = [];

            foreach ($winLots as $winLot) {
                $lot = BidsOfLots::where('customerId', $customerId)
                    ->where('lotId', $winLot->lotId)
                    ->orderBy('amount', 'desc')
                    ->with('lotDetails')
                    ->first();

                if ($lot) {
                    $lot->materials = new_maerials_2::where('lotid', $winLot->lotId)->get()->toArray();
                    $winningLots[] = $lot;
                }
            }

            return response()->json(["lots" => $winningLots, "success" => true]);
        }

        return response()->json(["message" => "No win lots found for the customer", "success" => false]);
    }

    
    

        

    
    

    // ENDED PREVOUS CODE


    // public function getcustomerwinlots($customerId)
    // {
    //     $maxBid = BidsOfLots::where('customerId', $customerId)->max('amount');
    
    //     if ($maxBid !== null) 
    //     {
    //         $winLot = BidsOfLots::where('customerId', $customerId)
    //             ->where('amount', $maxBid)
    //             ->with('lotDetails')
    //             ->orderBy('id', 'desc')
    //             ->first();
    
    //         if ($winLot) 
    //         {
    //             $winLot->material = new_maerials_2::where('lotid', $winLot->lotId)->get()->toArray();
    
    //             // Send notification to the winner
    //             $winnerMessage = 'Congratulations! You won this lot.';
    //             event(new winLotsEvent($winLot->customerId, $winnerMessage));
    
    //             // Send notification to other participants (losers)
    //             $losers = BidsOfLots::where('lotId', $winLot->lotId)->where('customerId', '!=', $customerId)->get();
    //             foreach ($losers as $loser) 
    //             {
    //                 $loserMessage = 'You lose this bid. Another person won this lot.';
    //                 event(new winLotsEvent($loser->customerId, $loserMessage));
    
    //                 // Return participation fee to the losers
    //                 $this->returnParticipationFee($loser);
    //             }
    
    //             return response()->json(["lots" => [$winLot], "success" => true]);
    //         }
    //     }
    
    //     return response()->json(["message" => "No win lot found for the customer", "success" => false]);
    // }

    // private function returnParticipationFee($bid)
    // {
    //     $customer = Customer::find($bid->customerId);
    //     if ($customer) {
    //         $lastBalance = customerBalance::where('customerId', $customer->id)->orderBy('id', 'desc')->first();
    //         if ($lastBalance) {
    //             $newFinalAmount = $lastBalance->finalAmount + $bid->amount;
    //             customerBalance::create([
    //                 'customerId' => $customer->id,
    //                 'balanceAmount' => $lastBalance->finalAmount,
    //                 'action' => 'Return Participation Fee',
    //                 'actionAmount' => $bid->amount,
    //                 'finalAmount' => $newFinalAmount,
    //                 'lotid' => $bid->lotId,
    //                 'status' => 1, // Status 1 for returned participation fee
    //                 'date' => Carbon::now(),
    //             ]);
    //         }
    //     }
    // }
    
    
}
