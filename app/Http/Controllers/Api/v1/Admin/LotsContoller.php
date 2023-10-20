<?php

namespace App\Http\Controllers\Api\V1\Admin;

// use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BidsOfLots;
use App\Models\categories;
use App\Models\lot_materials;
use App\Models\lots;
use App\Models\FavLots;
use Illuminate\Support\Facades\DB;
use App\Models\lotTerms;
use App\Models\MaterialFiles;
use App\Models\materials;
use App\Models\CustomerLot;
use App\Models\productimage;
use App\Models\new_maerials_2;
use App\Models\newMaterial;
use App\Models\payments;
use App\Models\ExportUrl;
use App\Models\Customer;
use App\Models\customerBalance;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Events\{ winLotsEvent , NotificationEvent };
use Pusher\Pusher;
use App\Exports\LotsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Session;
use App\Exports\winlotexportapi;
use App\Models\ExportWinLots;
use App\Exports\ExportSpecificwin_lots;
use App\Models\Excel_specific_win_lots;
use App\Models\ExcelCategoryOfLots;
use App\Exports\ExcelCategoryofLot;
use App\Exports\favLotsExcelExport;
use App\Models\favlotsexcel_export;
use App\Exports\paymentexcelexportfile;
use App\Http\AppConst;
use App\Models\Excel_export_payment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\AdminNotification;






class LotsContoller extends Controller
{
    private $user, $defaultNumber;


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


    // STA Lots 
    public function stalots(Request $request)
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
            ->where('lot_status', 'LIKE', '%STA%')

            ->orderBy('StartDate', 'asc') 
            ->get();
    
        return response()->json(['STA_Lots' => $lots, 'success' => true]);
    }


    // STA Fav lots API

    public function stafavlots(Request $request)
    {
        $customerId = $request->customer_id;

        $favoriteLotIds = FavLots::where('customer_id', $customerId)->pluck('lot_id');
    
        $lots = lots::with(['customers' => function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        }])
        ->with(['bids' => function ($query) {
            $query->orderBy('created_at', 'desc')->take(1);
        }])
        ->with(['categories', 'bids' => function ($query) 
            {
                $query->orderBy('created_at', 'desc')->take(1);
        }])
        ->whereIn('id', $favoriteLotIds)
        ->where('lot_status', 'STA')
        ->orderBy('StartDate', 'asc')
        ->get();
    
        return response()->json(['STA_Fav_Lots' => $lots, 'success' => true]);
    }
    


    



        
        
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




        // Show All Expired Lots working

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
        //     ->where('lot_status', 'LIKE', '%Expired%')

        //     ->orderBy('StartDate', 'asc') 
        //     ->get();
    
        // return response()->json(['ExpiredLots' => $lots, 'success' => true]);



        // Show Expired Lots current date(Today date)

        $customerId = $request->customer_id;

        // Get the current date
        $currentDate = now()->toDateString();

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
        ->whereDate('EndDate', $currentDate) // Filter by lots that have expired (StartDate is earlier than the current date)
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


        // Show Sold All Lots

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
            //     $query->select('id', 'customerId', DB::raw('MAX(amount) as max_bid'), 'lotId', 'autoBid', 'created_at', 'updated_at')
            //         ->groupBy('lotId'); // Retrieve the max_bid and group by lotId
            // }])
            // ->where('lot_status', 'LIKE', '%Sold%')
            // ->orderBy('StartDate', 'asc') 
            // ->get();

            // return response()->json(['userLots' => $lots, 'success' => true]);




            // Sold lots show on current Date (Today Sold Lots)

            $customerId = $request->customer_id;
        
            // Get the current date
            $currentDate = now()->toDateString();

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
                ->whereDate('EndDate', $currentDate) // Filter by the current date
                ->orderBy('StartDate', 'asc')
                ->get();

            return response()->json(['SoldLots' => $lots, 'success' => true]);

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


    // ********* Start 4 LOT API INTO FAV **********
     
    // 1 add Favorites Lots in Lots

    public function addLiveLotsFavorites(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $lot_id = $request->input('lot_id');

        // Check if the favorite lot already exists for the given customer and lot_id
        $existingFavorite = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->where('lot_id', $lot_id)
            ->first();

        // If the favorite lot already exists, return an error response
        if ($existingFavorite) 
        {
            return response()->json([
                'message' => 'Favorite Live lot already exists for this customer',
                'success' => false,
            ]);
        }

        // Check if the lot is live before adding to favorites
        $lot = DB::table('lots')
            ->where('id', $lot_id)
            ->where('lot_status', 'live') // Assuming 'lot_status' is a column in your 'lots' table
            ->first();

        if (!$lot) {
            return response()->json([
                'message' => 'Cannot add lot to favorites. Lot is not live.',
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
            'message' => 'Live Lot added to Favorites Lots',
            'success' => true,
        ]);
    }



    // 2 add Fav lots into Upcoming

    public function addUpcomingLotsFavorites(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $lot_id = $request->input('lot_id');

        // Check if the favorite lot already exists for the given customer and lot_id
        $existingFavorite = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->where('lot_id', $lot_id)
            ->first();

        // If the favorite lot already exists, return an error response
        if ($existingFavorite) 
        {
            return response()->json([
                'message' => 'Favorite Upcoming lot already exists for this customer',
                'success' => false,
            ]);
        }

        // Check if the lot is live before adding to favorites
        $lot = DB::table('lots')
            ->where('id', $lot_id)
            ->where('lot_status', 'upcoming') // Assuming 'lot_status' is a column in your 'lots' table
            ->first();

        if (!$lot) {
            return response()->json([
                'message' => 'Cannot add lot to favorites. Lot is not Upcoming.',
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
            'message' => 'Upcoming Lot added to Favorites Lots',
            'success' => true,
        ]);
    }



    // 3 add Fav lots into Sold

    public function addSoldLotsFavorites(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $lot_id = $request->input('lot_id');

        // Check if the favorite lot already exists for the given customer and lot_id
        $existingFavorite = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->where('lot_id', $lot_id)
            ->first();

        // If the favorite lot already exists, return an error response
        if ($existingFavorite) 
        {
            return response()->json([
                'message' => 'Favorite Sold lot already exists for this customer',
                'success' => false,
            ]);
        }

        // Check if the lot is live before adding to favorites
        $lot = DB::table('lots')
            ->where('id', $lot_id)
            ->where('lot_status', 'Sold')
            ->first();

        if (!$lot) {
            return response()->json([
                'message' => 'Cannot add lot to favorites. Lot is not Sold.',
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
            'message' => 'Sold Lot added to Favorites Lots',
            'success' => true,
        ]);
    }


     // 4 add Fav lots into Expired

    public function addExpiredLotsFavorites(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $lot_id = $request->input('lot_id');

        // Check if the favorite lot already exists for the given customer and lot_id
        $existingFavorite = DB::table('user_lot')
            ->where('customer_id', $customer_id)
            ->where('lot_id', $lot_id)
            ->first();

        // If the favorite lot already exists, return an error response
        if ($existingFavorite) 
        {
            return response()->json([
                'message' => 'Favorite Expired lot already exists for this customer',
                'success' => false,
            ]);
        }

        // Check if the lot is live before adding to favorites
        $lot = DB::table('lots')
            ->where('id', $lot_id)
            ->where('lot_status', 'Expired')
            ->first();

        if (!$lot) {
            return response()->json([
                'message' => 'Cannot add lot to favorites. Lot is not Expired.',
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
            'message' => 'Expired Lot added to Favorites Lots',
            'success' => true,
        ]);
    }


    // ********* End 4 LOT API INTO FAV **********






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
        // Retrieve the favorite lots for the given customer_id
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();
    
        $result = [];
    
        foreach ($favoriteLots as $favoriteLot) {
            $lot = $favoriteLot->lot;
    
            if ($lot) 
            {
                // Retrieve the maximum bid for the lot
                $maxBid = BidsOfLots::where('lotId', $lot->id)
                    ->orderBy('amount', 'desc')
                    ->first();
    
                $lotArray = $lot->toArray();
    
                if ($maxBid) 
                {
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


    // Show notification in Admin Side 

    public function resetLotRequest(Request $request)
    {
        $requestData = $request->validate([
            'customerId' => 'required',
            'lotId' => 'required',
        ]);

    
        // Check if the customer exists
        $customer = Customer::find($requestData['customerId']);
    
        if (!$customer) 
        {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $previousNotificationCount = AdminNotification::where('customerId' , $request->customerId)
                                                        ->where('lotId' , $request->lotId)
                                                        ->whereNull('notification_status')
                                                        ->count();

        if($previousNotificationCount){
            return response()->json(['message' => 'Already Your Notication Has Been Send To Admin'], 200);
        }


    
        // Create a new admin notification record
        AdminNotification::create([
            'customername' => $customer->name,
            'customerId' => $requestData['customerId'],
            'lotId' => $requestData['lotId'],
            'notification_status' => AppConst::NOTIFICATION_PENDING
        ]);

        event(new NotificationEvent($request->customerId));
    
        return response()->json(['message' => 'Notification saved successfully'], 200);
    }


    // Start Show 4 APIs live, Upcoming, Sold and Expired

    // Show live lots Fav

    // public function showLiveLotsFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id
    //     $favoriteLots = FavLots::where('customer_id', $customer_id)
    //         ->with('lot')
    //         ->get();

    //     $result = [];

    //     foreach ($favoriteLots as $favoriteLot) {
    //         $lot = $favoriteLot->lot;

    //         if ($lot && $lot->lot_status === 'live') 
    //         {
    //             // Retrieve the maximum bid for the lot
    //             $maxBid = BidsOfLots::where('lotId', $lot->id)
    //                 ->orderBy('amount', 'desc')
    //                 ->first();

    //             $lotArray = $lot->toArray();

    //             if ($maxBid) {
    //                 $maxBidArray = [
    //                     'id' => $maxBid->id,
    //                     'customerId' => $maxBid->customerId,
    //                     'amount' => $maxBid->amount,
    //                     'lotId' => $maxBid->lotId,
    //                     'created_at' => $maxBid->created_at,
    //                     'updated_at' => $maxBid->updated_at,
    //                 ];

    //                 $lotArray['bids'] = [$maxBidArray];
    //             }

    //             $result[] = $lotArray;
    //         }
    //     }

    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite live lots retrieved',
    //         'success' => true,
    //         'Fav_lots' => $result,
    //     ]);
    // }



    // public function showLiveLotsFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id
    //     $favoriteLots = FavLots::where('customer_id', $customer_id)
    //         ->with('lot')
    //         ->get();
    
    //     $result = [];
    //     $currentDate = now()->toDateString();
    
    //     foreach ($favoriteLots as $favoriteLot) 
    //     {
    //         $lot = $favoriteLot->lot;
    
    //         if ($lot && $lot->lot_status === 'live' && $lot->EndDate->toDateString() === $currentDate) 
    //         {
    //             // Retrieve the maximum bid for the lot
    //             $maxBid = BidsOfLots::where('lotId', $lot->id)
    //                 ->orderBy('amount', 'desc')
    //                 ->first();
    
    //             $lotArray = $lot->toArray();
    
    //             if ($maxBid) {
    //                 $maxBidArray = [
    //                     'id' => $maxBid->id,
    //                     'customerId' => $maxBid->customerId,
    //                     'amount' => $maxBid->amount,
    //                     'lotId' => $maxBid->lotId,
    //                     'created_at' => $maxBid->created_at,
    //                     'updated_at' => $maxBid->updated_at,
    //                 ];
    
    //                 $lotArray['bids'] = [$maxBidArray];
    //             }
    
    //             $result[] = $lotArray;
    //         }
    //     }
    
    //     // Return the favorite live lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Today Favorite live lots retrieved',
    //         'success' => true,
    //         'Fav_lots' => $result,
    //     ]);
    // }


    public function showLiveLotsFavorites($customer_id)
    {
        // Retrieve the favorite lots for the given customer_id
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();
    
        $result = [];
        // $currentDate = now()->toDateString();
        $currentDate = \Carbon\Carbon::now();
    
        foreach ($favoriteLots as $favoriteLot) 
        {
            $lot = $favoriteLot->lot;
    
            if ($lot && ($lot->lot_status === 'live' || $lot->lot_status === 'Live')) 
            {
                // Retrieve the maximum bid for the lot
                $maxBid = BidsOfLots::where('lotId', $lot->id)
                    ->orderBy('amount', 'desc')
                    ->first();
    
                $lotArray = $lot->toArray();
    
                if ($maxBid) 
                {
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
    
        // Return the favorite live lots with max_bid as a response
        return response()->json([
            'message' => 'Favorite live lots retrieved',
            'success' => true,
            'Fav_lots' => $result,
        ]);
    }
    
    



    // Show Upcoming lots Fav

    public function showUpcomingLotsFavorites($customer_id)
    {
        // Retrieve the favorite lots for the given customer_id
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();

        $result = [];

        foreach ($favoriteLots as $favoriteLot) {
            $lot = $favoriteLot->lot;

            if ($lot && $lot->lot_status === 'Upcoming') 
            {
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
            'message' => 'Favorite Upcoming lots retrieved',
            'success' => true,
            'Fav_lots' => $result,
        ]);
    }


    // Show Sold lots Fav 

    // public function showSoldLotsFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id
    //     $favoriteLots = FavLots::where('customer_id', $customer_id)
    //         ->with('lot')
    //         ->get();

    //     $result = [];

    //     foreach ($favoriteLots as $favoriteLot) {
    //         $lot = $favoriteLot->lot;

    //         if ($lot && $lot->lot_status === 'Sold') 
    //         {
    //             // Retrieve the maximum bid for the lot
    //             $maxBid = BidsOfLots::where('lotId', $lot->id)
    //                 ->orderBy('amount', 'desc')
    //                 ->first();

    //             $lotArray = $lot->toArray();

    //             if ($maxBid) 
    //             {
    //                 $maxBidArray = [
    //                     'id' => $maxBid->id,
    //                     'customerId' => $maxBid->customerId,
    //                     'amount' => $maxBid->amount,
    //                     'lotId' => $maxBid->lotId,
    //                     'created_at' => $maxBid->created_at,
    //                     'updated_at' => $maxBid->updated_at,
    //                 ];

    //                 $lotArray['bids'] = [$maxBidArray];
    //             }

    //             $result[] = $lotArray;
    //         }
    //     }

    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite Sold lots retrieved',
    //         'success' => true,
    //         'Fav_lots' => $result,
    //     ]);
    // }

    public function showSoldLotsFavorites($customer_id)
    {
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();
    
        $result = [];

        $currentDate = now()->toDateString();
    
        foreach ($favoriteLots as $favoriteLot) 
        {
            $lot = $favoriteLot->lot;
    
            if ($lot && $lot->lot_status === 'Sold' && $lot->EndDate->toDateString() === $currentDate) 
            {
                $maxBid = BidsOfLots::where('lotId', $lot->id)
                    ->orderBy('amount', 'desc')
                    ->first();
    
                $lotArray = $lot->toArray();
    
                if ($maxBid) 
                {
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

        return response()->json([
            'message' => 'Today Favorite Sold lots retrieved',
            'success' => true,
            'Fav_lots' => $result,
        ]);
    }
    

     // Show Expired lots Fav 

    // public function showExpiredLotsFavorites($customer_id)
    // {
    //     // Retrieve the favorite lots for the given customer_id
    //     $favoriteLots = FavLots::where('customer_id', $customer_id)
    //         ->with('lot')
    //         ->get();

    //     $result = [];

    //     foreach ($favoriteLots as $favoriteLot) 
    //     {
    //         $lot = $favoriteLot->lot;

    //         if ($lot && $lot->lot_status === 'Expired') 
    //         {
    //             // Retrieve the maximum bid for the lot
    //             $maxBid = BidsOfLots::where('lotId', $lot->id)
    //                 ->orderBy('amount', 'desc')
    //                 ->first();

    //             $lotArray = $lot->toArray();

    //             if ($maxBid) 
    //             {
    //                 $maxBidArray = [
    //                     'id' => $maxBid->id,
    //                     'customerId' => $maxBid->customerId,
    //                     'amount' => $maxBid->amount,
    //                     'lotId' => $maxBid->lotId,
    //                     'created_at' => $maxBid->created_at,
    //                     'updated_at' => $maxBid->updated_at,
    //                 ];

    //                 $lotArray['bids'] = [$maxBidArray];
    //             }

    //             $result[] = $lotArray;
    //         }
    //     }

    //     // Return the favorite lots with max_bid as a response
    //     return response()->json([
    //         'message' => 'Favorite Expired lots retrieved',
    //         'success' => true,
    //         'Fav_lots' => $result,
    //     ]);
    // }

    public function showExpiredLotsFavorites($customer_id)
    {
        // Retrieve the favorite lots for the given customer_id
        $favoriteLots = FavLots::where('customer_id', $customer_id)
            ->with('lot')
            ->get();
    
        $result = [];
        $currentDate = now()->toDateString();
    
        foreach ($favoriteLots as $favoriteLot) 
        {
            $lot = $favoriteLot->lot;
    
            if ($lot && $lot->lot_status === 'Expired' && $lot->EndDate->toDateString() === $currentDate) 
            {
                // Retrieve the maximum bid for the lot
                $maxBid = BidsOfLots::where('lotId', $lot->id)
                    ->orderBy('amount', 'desc')
                    ->first();
    
                $lotArray = $lot->toArray();
    
                if ($maxBid) 
                {
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
    
        // Return the favorite Expired lots with max_bid as a response
        return response()->json([
            'message' => 'Today Favorite Expired lots retrieved',
            'success' => true,
            'Fav_lots' => $result,
        ]);
    }
    


    // End Show 4 APIs live, Upcoming, Sold and Expired
    


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

    public function showcategorieswithlot(Request $request)
    {
        $customerId = $request->input('customerId');
        $categoryId = $request->input('categoryId');
        $status = $request->input('status');

        // Retrieve the category
        $category = categories::find($categoryId);

        if (!$category) 
        {
            return response()->json([
                'message' => 'Category not found',
                'success' => false,
            ]);
        }

        $currentDate = now()->toDateString();

        // Define the lot_status values for which you want to show current date lots
        $currentDateStatuses = ['Sold', 'Expired'];

        // Check if the status is one of the current date statuses
        if (in_array($status, $currentDateStatuses)) 
        {
            // Retrieve lots based on current date
            $lots = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) 
                {
                    $query->orderBy('created_at', 'desc')->take(1);
                }
            ])
                ->where('categoryId', $categoryId)
                ->where('lot_status', $status)
                ->whereDate('EndDate', $currentDate) // Filter by current date
                ->orderBy('StartDate', 'asc')
                ->get();
        } 

        elseif($status === 'live' || $status === 'Live')
        {
            // Retrieve lots based on the live status and date comparison
            $lots = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) 
                {
                    $query->orderBy('created_at', 'desc')->take(1);
                }
            ])
                ->where('categoryId', $categoryId)
                ->whereIn('lot_status', ['live' , 'Live'])
                // ->where('StartDate', '<=', $currentDate)
                // ->where('EndDate', '>=', $currentDate) 
                ->orderBy('StartDate', 'asc')
                ->get();
        }

        else 
        {
            // For "Upcoming" status, show normal behavior
            $lots = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) 
                {
                    $query->orderBy('created_at', 'desc')->take(1);
                }
            ])
                ->where('categoryId', $categoryId)
                ->where('lot_status', $status)
                ->orderBy('StartDate', 'asc')
                ->get();
        }

        return response()->json([
            'userLots' => $lots,
            'success' => true,
        ]);
    }


    // Excel Export using category of lots API


    public function excelcategoryoflots(Request $request)
    {
        $customerId = $request->input('customerId');
        $categoryId = $request->input('categoryId');
        $status = $request->input('status');
    
        $currentDate = now()->toDateString();
    
        // $soldExpiredStatuses = ['Sold', 'Expired'];
        $soldlots = 'Sold';
        $expiredlots = 'Expired';
        $liveStatus = 'live';
    
        $allLots = [];

        if($status === $soldlots)
        {
            $lotsSold = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) {
                    $query->orderBy('created_at', 'desc')->take(1);
                },
            ])
                ->where('categoryId', $categoryId)
                ->where('lot_status', $soldlots)
                ->whereDate('EndDate', $currentDate)
                ->orderBy('StartDate', 'asc')
                ->get();
    
            $allLots = array_merge($allLots, $lotsSold->toArray());

        }

        elseif($status === $expiredlots)
        {
            $lotsexpired = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) {
                    $query->orderBy('created_at', 'desc')->take(1);
                },
            ])

                ->where('categoryId', $categoryId)
                ->where('lot_status', $expiredlots)
                ->whereDate('EndDate', $currentDate)
                ->orderBy('StartDate', 'asc')
                ->get();
    
            $allLots = array_merge($allLots, $lotsexpired->toArray());

        }
    
        elseif ($status === $liveStatus) 
        {
            $lotsLive = lots::with([
                'customers' => function ($query) use ($customerId) 
                {
                    $query->where('customer_id', $customerId);
                },
                'categories',
                'bids' => function ($query) 
                {
                    $query->orderBy('created_at', 'desc')->take(1);
                },
            ])
                ->where('categoryId', $categoryId)
                ->where('lot_status', $liveStatus)
                ->where('StartDate', '<=', $currentDate)
                ->where('EndDate', '>=', $currentDate)
                ->orderBy('StartDate', 'asc')
                ->get();
    
            $allLots = array_merge($allLots, $lotsLive->toArray());
        } else {
                $lotsUpcoming = lots::with([
                    'customers' => function ($query) use ($customerId) {
                        $query->where('customer_id', $customerId);
                    },
                    'categories',
                    'bids' => function ($query) {
                        $query->orderBy('created_at', 'desc')->take(1);
                    },
                ])
                    ->where('categoryId', $categoryId)
                    ->where('lot_status', $status)
                    ->orderBy('StartDate', 'asc')
                    ->get();
        
                $allLots = array_merge($allLots, $lotsUpcoming->toArray());
            }
    
        // Create a new export instance
        $export = new ExcelCategoryofLot($allLots);

        // Generate and store the Excel file
        $timestamp = now()->format('Ymd_His');
        $fileName = 'categoryoflots_' . $timestamp . '.xlsx';
        $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;
    
        Excel::store($export, $fileName, 'ExcelLots');
    
        // Full local file path
        $localFilePath = $filePath;
    
        // Generate live URL
        $liveUrl = url('ExcelLots/' . $fileName);
    
        // Save the URL in the database
        ExcelCategoryOfLots::create([
            'url' => $localFilePath,
        ]);
    
        return response()->json([
            'message' => 'Excel file generated, saved, and URL recorded successfully.',
            'file_url' => $liveUrl,
        ]);
    }


    // Fav lot Excel Export 

    public function favlotsexcelexport(Request $request)
    {
        try {
            $customer_id = $request->input('customer_id');
            $status = $request->input('status');

            $currentDate = now()->toDateString();

            $lots = FavLots::where('customer_id', $customer_id)
                ->with(['lot.materials'])
                ->whereHas('lot', function ($query) use ($status, $currentDate) 
                {
                    if ($status === 'live') 
                    {
                        $query->where('lot_status', $status)
                            ->where('StartDate', '<=', $currentDate)
                            ->where('EndDate', '>=', $currentDate);
                    } elseif ($status === 'Sold') 
                    {
                        $query->where('lot_status', $status)
                            ->whereDate('EndDate', $currentDate);
                    } elseif ($status === 'Expired') 
                    {
                        $query->where('lot_status', $status)
                            ->whereDate('EndDate', $currentDate);
                    } elseif ($status === 'Upcoming') 
                    {
                        $query->where('lot_status', $status);
                    } elseif ($status === 'STA') 
                    {
                        $query->where('lot_status', $status);
                    }
                })
                ->get();

            $export = new favLotsExcelExport($lots);

            // Generate a unique filename using a timestamp
            $timestamp = now()->format('Ymd_His');
            $fileName = 'Favlots_' . $timestamp . '.xlsx';
            $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

            // Generate and store the Excel file on the local filesystem
            Excel::store($export, $fileName, 'ExcelLots');

            // Full local file path
            $localFilePath = $filePath;

            // Generate live URL
            $liveUrl = url('ExcelLots/' . $fileName);

            // Save the local file path in the 'export_urls' table
            favlotsexcel_export::create([
                'url' => $localFilePath,
            ]);

            // Return a JSON response with the success message and live URL
            return response()->json([
                'message' => 'Excel file generated, saved, and URL recorded successfully.',
                'file_url' => $liveUrl,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error occurred during export: ' . $e->getMessage(),
            ], 500);
        }
    }


    // Payment Excel export API

    public function paymentexcelexport(Request $request)
    {
        try {
            
            $dynamicData = $request->input('data');

            $collection = collect($dynamicData);

            $export = new paymentexcelexportfile($collection);

            // Generate a unique filename using a timestamp
            $timestamp = now()->format('Ymd_His');
            $fileName = 'Payments_' . $timestamp . '.xlsx';
            $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

            // Generate and store the Excel file on the local filesystem
            Excel::store($export, $fileName, 'ExcelLots');

            // Full local file path
            $localFilePath = $filePath;

            // Generate live URL
            $liveUrl = url('ExcelLots/' . $fileName);

            // Return a JSON response with the success message and live URL
            return response()->json([
                'message' => 'Excel file generated and saved successfully.',
                'file_url' => $liveUrl,
            ]);
        } catch (\Throwable $e) 
        {
            // Handle exceptions gracefully, log errors, and return an error response
            Log::error('Error occurred during export: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error occurred during export: ' . $e->getMessage(),
            ], 500);
        }
    }



    

   








    


      
        // Show all product Images
        // public function showProductImages()
        // {
        //     $productImages = productimage::all();

        //     $productImagesWithUrls = $productImages->map(function ($image) {
        //         $imageUrl = asset($image->image); // Assuming image field contains the relative path
        //         $image->image_url = $imageUrl;
        //         return $image;
        //     });

        //     return response()->json(['productImages' => $productImagesWithUrls]);
        // }

        public function showProductImages()
        {
            $productImages = productimage::all();
            
            $productImagesWithUrls = $productImages->map(function ($image) 
            {
                $imagePath = url('/' . $image->image);
                $image->image = $imagePath;
                unset($image->image_url);
                return $image;
            });
            
            return response()->json(['productImages' => $productImagesWithUrls]);
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

    // Generate Excel File OF win Lots Against the Customer_id, start_date and end_date

    public function exportLotsToExcel(Request $request)
    {
        try {
            $status = $request->input('status');

                $lots = lots::with('new_maerials_2', 'categories')
                ->where('lot_status', $status)
                ->get();

            // Create a new LotsExport instance and pass the fetched lots
            $export = new LotsExport($lots);

            // Generate a unique filename using a timestamp
            $timestamp = now()->format('Ymd_His');
            $fileName = 'lots_' . $timestamp . '.xlsx';
            $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

            // Generate and store the Excel file on the local filesystem
            Excel::store($export, $fileName, 'ExcelLots');

            // Full local file path
            $localFilePath = $filePath;

            // Generate live URL
            $liveUrl = url('ExcelLots/' . $fileName);

            // Save the local file path in the 'export_urls' table
            ExportUrl::create([
                'url' => $localFilePath,
            ]);

            // Return a JSON response with the success message and live URL
            return response()->json([
                'message' => 'Excel file generated, saved, and URL recorded successfully.',
                'file_url' => $liveUrl,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error occurred during export: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ENDED PREVOUS CODE

    // Fetch win Lots Against the Customer_id, start_date and end_date

    public function winLotsShow(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $customerId = $request->input('customer_id');
    
        // $customerLots = CustomerLot::with(['lotDetail.materials', 'lot.materials'])
        //     ->whereBetween(DB::raw('Date(customer_lots.created_at)'), [$startDate, $endDate])
        //     ->where('customer_id', $customerId)
        //     ->get();

        $customerLots = CustomerLot::query();

        $customerLots->with('lotDetail.materials');

        $customerLots->whereHas('lotDetail' , function($query){
            $query->whereIn('lot_status' , ['Sold' , 'sold' , 'sta', 'STA'] );
        });

        $customerLots->when(isset($startDate) && !is_null($startDate) , function($query) use ($startDate){
            $query->where('created_at' , '>=' , $startDate);
        });

  

        $customerLots->when(isset($endDate) && !is_null($endDate) , function($query) use ($endDate){
            $query->where(DB::raw('DATE(created_at)') , '<=' , $endDate);
        });

        $customerLots->where('customer_id' , $customerId);

        $winningLots = $customerLots->get();

    
        if ($winningLots->isEmpty()) {
            $message = 'Sorry, No Win lots against this Customer.';
        } else {
            $message = 'Win Lot Retrieved Successfully.';
        }
    
        // $winningLots = [];
    
        // foreach ($customerLots as $customerLot) 
        // {
        //     $lot = $customerLot->lotDetail;
    
        //     if ($lot->lot_status === 'Sold') 
        //     {
        //         $winningLots[] = [
        //             'id' => $customerLot->id,
        //             'customer_id' => $customerLot->customer_id,
        //             'lot_id' => $customerLot->lot_id,
        //             'created_at' => $customerLot->created_at,
        //             'updated_at' => $customerLot->updated_at,
        //             'lot_details' => $lot,
        //         ];
        //     }
        // }
    
        $response = [
            'message' => $message,
            'win_lots' => $winningLots,
        ];
    
        return response()->json($response);
    }
    

    // Win lot against the customer_id to show Excel Export API

    // public function winExcelLotExport(Request $request)
    // {
    //     $customerId = $request->input('customer_id');

    //     $winningLotsData = CustomerLot::with(['lot', 'lot.materials'])
    //         ->where('customer_id', $customerId)
    //         ->get();

    //     // Create a new export instances
    //     $export = new winlotexportapi($winningLotsData);

    //     $timestamp = now()->format('Ymd_His');
    //     $fileName = 'winlots_' . $timestamp . '.xlsx';
    //     // $filePath = 'ExcelLots' . DIRECTORY_SEPARATOR . $fileName;
    //     $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

    //     // Generate and store the Excel files
    //     Excel::store($export, $fileName, 'ExcelLots');

    //     // Full local file path
    //     $localFilePath = $filePath;

    //     // Generate live URL
    //     $liveUrl = url('ExcelLots/' . $fileName);

    //     // Save the URL in the database
    //     ExportWinLots::create([
    //         'url' => $localFilePath, 
    //     ]);

    //      return response()->json([
    //         'message' => 'Excel file generated, saved, and URL recorded successfully.',
    //         'file_url' => $liveUrl,
    //     ]);

    // }

    public function winExcelLotExport(Request $request)
    {
        $customerId = $request->input('customer_id');
    
        $winningLotsData = CustomerLot::whereHas('lot', function ($query) 
        {
            $query->where('lot_status', 'Sold');
        })
        ->with(['lot', 'lotDetail.materials'])
        ->where('customer_id', $customerId) 
        ->get();

        // dd($winningLotsData);
    
        // Create a new export instance
        $export = new winlotexportapi($winningLotsData);
    
        $timestamp = now()->format('Ymd_His');
        $fileName = 'winlots_' . $timestamp . '.xlsx';
        $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;
    
        // Generate and store the Excel file
        Excel::store($export, $fileName, 'ExcelLots');
    
        // Full local file path
        $localFilePath = $filePath;
    
        // Generate live URL
        $liveUrl = url('ExcelLots/' . $fileName);
    
        // Save the URL in the database
        ExportWinLots::create([
            'url' => $localFilePath, 
        ]);
    
        return response()->json([
            'message' => 'Excel file generated, saved, and URL recorded successfully.',
            'file_url' => $liveUrl,
        ]);
    }
    



    // Win Lots Excel Export using start date, end date and Customer_id 

    // public function winspecificdateExcel(Request $request)
    // {
    //     $customerId = $request->input('customer_id');
    //     $startDate  = $request->input('start_date');
    //     $endDate    = $request->input('end_date');

    //         $lots = CustomerLot::with(['lotDetail', 'new_maerials_2', 'categories'])
    //         ->where('customer_id', $customerId)
    //         ->where(DB::raw('Date(customer_lots.created_at)'), '>=', $startDate)
    //         ->where(DB::raw('Date(customer_lots.created_at)'), '<=', $endDate)
    //         ->get();


    //     // Create a new export instances
    //     $export = new ExportSpecificwin_lots($lots);

    //     $timestamp = now()->format('Ymd_His');
    //     $fileName = 'winspecificlots_' . $timestamp . '.xlsx';
    //     // $filePath = 'ExcelLots' . DIRECTORY_SEPARATOR . $fileName;
    //     $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

    //     // Generate and store the Excel files
    //     Excel::store($export, $fileName, 'ExcelLots');

    //     // Full local file path
    //     $localFilePath = $filePath;

    //     // Generate live URL
    //     $liveUrl = url('ExcelLots/' . $fileName);

    //     // Save the URL in the database
    //     Excel_specific_win_lots::create([
    //         'url' => $localFilePath, 
    //     ]);

    //      return response()->json([
    //         'message' => 'Excel file generated, saved, and URL recorded successfully.',
    //         'file_url' => $liveUrl,
    //     ]);

    // }

      
    public function winspecificdateExcel(Request $request)
    {
        $customerId = $request->input('customer_id');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $lots = CustomerLot::with(['lotDetail', 'new_maerials_2', 'categories'])
            ->where('customer_id', $customerId)
            ->where(DB::raw('Date(customer_lots.created_at)'), '>=', $startDate)
            ->where(DB::raw('Date(customer_lots.created_at)'), '<=', $endDate)
            ->get();

        // Filter lots to include only the Sold lots
        $soldLots = $lots->filter(function ($lot) 
        {
            return $lot->lotDetail->lot_status === 'Sold';
        });

        // Create a new export instance with the filtered Sold lots
        $export = new ExportSpecificwin_lots($soldLots);

        $timestamp = now()->format('Ymd_His');
        $fileName = 'winspecificlots_' . $timestamp . '.xlsx';
        $filePath = public_path('ExcelLots') . DIRECTORY_SEPARATOR . $fileName;

        Excel::store($export, $fileName, 'ExcelLots');

        $localFilePath = $filePath;
        $liveUrl = url('ExcelLots/' . $fileName);

        Excel_specific_win_lots::create([
            'url' => $localFilePath, 
        ]);

        return response()->json([
            'message' => 'Excel file generated, saved, and URL recorded successfully.',
            'file_url' => $liveUrl,
        ]);
    }



    // customer balance using start_date, End_Date and customer_Id

    public function showcustomerbalnace(Request $request)
    {
        // Define validation rules
        $rules = [
            'customer_id' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ];

        // Define custom error messages
        $messages = [
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'start_date.date_format' => 'Please provide the date in YYYY-MM-DD format.',
            'end_date.date_format' => 'Please provide the date in YYYY-MM-DD format for end date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) 
        {
            return response()->json(['errors' => $validator->errors()],200);
        }

        // $customerId = $request->input('customer_id');
        // $startDate = $request->input('start_date');
        // $endDate = $request->input('end_date');

        // $customerBalances = CustomerBalance::with('lots') 
        //     ->where('customerId', $customerId)
        //     ->whereBetween('date', [$startDate, $endDate])
        //     ->get()
        //     ->map(function ($item) 
        //     {
        //         $lotTitle = optional($item->lots)->title;
        //         return [
        //             'id' => $item->id,
        //             'customerId' => $item->customerId,
        //             'balanceAmount' => $item->balanceAmount,
        //             'action' => $item->action,
        //             'actionAmount' => $item->actionAmount,
        //             'finalAmount' => $item->finalAmount,
        //             'date' => $item->date,
        //             'status' => $item->status,
        //             'lot_title' => $lotTitle, 
        //             'created_at' => $item->created_at,
        //             'updated_at' => $item->updated_at,
        //             'lotid' => $item->lotid,
        //         ];
        //     });

        // $data = [
        //     'Customer_Balances' => $customerBalances,
        // ];

        // return response()->json($data);


        $customerId = $request->input('customer_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate === $endDate) 
        {
            // Fetch data for the same date
            $customerBalances = CustomerBalance::with('lots')
                ->where('customerId', $customerId)
                ->whereDate('date', $startDate)
                ->get()
                ->map(function ($item) 
                {
                    $lotTitle = optional($item->lots)->title;
                    return [
                        'id' => $item->id,
                        'customerId' => $item->customerId,
                        'balanceAmount' => $item->balanceAmount,
                        'action' => $item->action,
                        'actionAmount' => $item->actionAmount,
                        'finalAmount' => $item->finalAmount,
                        'date' => $item->date,
                        'status' => $item->status,
                        'lot_title' => $lotTitle, 
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'lotid' => $item->lotid,
                    ];
                });
        } 
        else 
        {
            // Fetch data within the date range
            $customerBalances = CustomerBalance::with('lots')
                ->where('customerId', $customerId)
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->map(function ($item) 
                {
                    $lotTitle = optional($item->lots)->title;
                    return [
                        'id' => $item->id,
                        'customerId' => $item->customerId,
                        'balanceAmount' => $item->balanceAmount,
                        'action' => $item->action,
                        'actionAmount' => $item->actionAmount,
                        'finalAmount' => $item->finalAmount,
                        'date' => $item->date,
                        'status' => $item->status,
                        'lot_title' => $lotTitle, 
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'lotid' => $item->lotid,
                    ];
                });
        }

        $data = [
            'Customer_Balances' => $customerBalances,
        ];

        return response()->json($data);

    }
    
}
