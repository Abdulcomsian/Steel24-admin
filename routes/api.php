<?php

use App\Http\Controllers\Admin\AccountNotificationController;
use App\Http\Controllers\Api\V1\Admin\AuctionContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\AuctionController;
use App\Http\Controllers\Api\v1\Admin\LotsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    });
    $api = app('Dingo\Api\Routing\Router');
    $api->version('v1', ['namespace' => 'App\Http\Controllers\Api\v1\Admin'], function ($api) {

    $api->post('auth/loginWithGoolge', 'Auth\SignUpController@loginWithGoolge');
    $api->post('auth/signUp', 'Auth\SignUpController@signUp');
    $api->post('auth/updateUser/{id}', 'Auth\SignUpController@updateUser');
    $api->post('auth/login', 'Auth\SignUpController@login');
    $api->get('open', 'Auth\**@open');


    Route::group([
        'middleware' => 'jwt.auth',
        'namespace' => 'App\Http\Controllers\Api\v1\Admin',
        // 'middleware' => ['api', 'cors'],
    ], function () {
        Route::get('user', 'Auth\SignUpController@getAuthenticatedUser');
        Route::get('closed', 'Auth\DemoController@closed');
        Route::get('lots', 'LotsContoller@getLots');

        Route::get('requestAction', 'AuctionContoller@auction');
        Route::post('/newbidonlot',  'AuctionContoller@newbidonlot');

        Route::get('/lotdetails/{lotid}', 'AuctionContoller@lotDetails');
        Route::get('/getcustomerbalance/{id}', 'AuctionContoller@getCustomerBalance');
        
        Route::post('/getidforbalance', 'AuctionContoller@addBalanceOrderIdGenerate');
        Route::post('/addcustomerbalance', 'AuctionContoller@addcustomerbalance');
        Route::post('/getlotpaymentid', 'AuctionContoller@getLotPaymentId');
        Route::post('/completelotpayment', 'AuctionContoller@completelotpayment');

        // new APIS's by zeshan rabnawaz

        Route::post('/addnewbidtolot', 'AuctionContoller@addnewbidtolot');

        //create auto lot
        Route::post('/createautobid', 'AuctionContoller@createautobid');

        // check auto bid
        Route::post('/autobidchecking', 'AuctionContoller@checkAutoBid');

        // auto bid delete
        Route::delete('/deleteautobid/{customerId}/{lotId}', 'AuctionContoller@deleteautobid');

        // End new APIS's by zeshan rabnawaz



        // Route::get('materials', 'MaterialController@getMaterials');
        // Route::post('lotpayment', 'AuctionContoller@orderIdGenerate');

        Route::get('getlots', 'LotsContoller@getlots');
        Route::get('getsoledlots', 'LotsContoller@getsoledlots');
        Route::get('getsoledlots', 'LotsContoller@getsoledlots');
        Route::get('getexpiredlots', 'LotsContoller@getexpiredlots');

        // new APIS's by zeshan rabnawaz

        Route::get('getcustomerwinlots/{customerId}', 'LotsContoller@getcustomerwinlots');

        Route::get('getcustimerparticipatelots/{customerId}', 'LotsContoller@getcustimerparticipatelots');

        Route::post('participateonlot', 'AuctionContoller@participateOnLot');

        Route::post('participeteonexpirelot', 'AuctionContoller@participeteOnExpireLot');

        // Define the route to manually process the return of the participation fee
       Route::post('returnParticipationFee/{bidId}', 'AuctionContoller@returnParticipationFee');

        //nouman route starts here
        Route::post('customer-bid' ,  'AuctionContoller@customerBidding' );

        Route::post('set-customer-autobid' , 'AuctionContoller@setCustomerAutobid');

        Route::post('check-customer-autobid' , 'AuctionContoller@checkCustomerAutobid');

        Route::post('stop-customer-autobid' , 'AuctionContoller@stopAutoBid');
        //nouman route ends here
    });

    Route::get('getlivebid', 'App\Http\Controllers\Api\v1\Admin\AuctionContoller@getlivebid');
    Route::get('requestativateaccount/{uid}', [AccountNotificationController::class, 'requestativateaccount']);
    
    // Route::group(['middleware' => 'jwt.auth'], function () {
    //     Route::get('user', 'Auth\LoginController@getAuthUser');
    // });
});


// new APIS's by zeshan rabnawaz

// Get Categorys API
Route::get('getCategoriesAndLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getCategoriesAndLots');

// live lots 
Route::post('activeLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getActiveLots');

// Upcoming Lots 
Route::post('upcomingLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@upcomingLots');

// Active And Upcoming Lots
Route::post('getactiveupcominglots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getActiveAndUpcomingLots');

// Experied lots
Route::post('expiredLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getexpiredlots');

//Sold lots
Route::post('soldLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@SoldLots');

// details Lot
Route::get('lotsdetails', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@lotsdetails');

// show specific one lot
Route::get('lotsdetails/{lotId}', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@specificlotshow');

// Favorites lots add and show API
Route::post('addFavorites', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@addFavorites');

// delete fav lot 
Route::post('deleteFavorite', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@deleteFavorite');

// show fav lot
Route::get('showFavorites/{customer_id}', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@showFavorites');

// new APIS's by zeshan rabnawaz



// Route::group([

//     'middleware' => 'api',
//     'namespace' => 'App\Http\Controllers',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'Auth\SignUpController@signUp');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });


// Route::get('test' , function()
// {
//    $currentTime = Carbon\Carbon::now();
//    $twoMinutesAgo = $currentTime->subMinutes(2);
//    $workingLots = App\Models\lots::where('lot_status' , 'live')
//               ->whereHas('bids' , function($query) use ($twoMinutesAgo) 
//               {
//                     $query->latest('created_at')->where('created_at' , '<=' , $twoMinutesAgo);
//                     ->update([
//                         'lot_status' => 'Sold'
//                     ]);
//               })->get();

//             //   dd($workingLots);
//             // $workingLots->update(['lot_status' => 'Sold']);
// });


// crone job on testing
    // Route::get('test', function () 
    // {

    //     $currentTime = Carbon\Carbon::now();
    //     $twoMinutesAgo = $currentTime->subMinutes(2);

    //     // Update the lot_status to 'Sold' and get the number of lots updated
    //     $numUpdatedLots = App\Models\lots::where('lot_status', 'live')
    //         ->whereHas('bids', function ($query) use ($twoMinutesAgo) 
    //         {
    //             $query->latest('created_at')->where('created_at', '<=', $twoMinutesAgo);
    //         })
    //         ->update(['lot_status' => 'Sold']);

    //     // Fetch the lots that were updated
    //     $updatedLots = App\Models\lots::where('lot_status', 'Sold')
    //         ->whereHas('bids', function ($query) use ($twoMinutesAgo) 
    //         {
    //             $query->latest('created_at')->where('created_at', '<=', $twoMinutesAgo);
    //         })
    //         ->get();

    //     // dd($updatedLots);
    // });




