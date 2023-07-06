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
        Route::post('/addnewbidtolot', 'AuctionContoller@addnewbidtolot');

        // Route::get('materials', 'MaterialController@getMaterials');
        // Route::post('lotpayment', 'AuctionContoller@orderIdGenerate');

        Route::get('getlots', 'LotsContoller@getlots');


        
        Route::get('getsoledlots', 'LotsContoller@getsoledlots');
        Route::get('getsoledlots', 'LotsContoller@getsoledlots');
        Route::get('getexpiredlots', 'LotsContoller@getexpiredlots');
        Route::get('getcustimerwinlots/{customerId}', 'LotsContoller@getcustimerwinlots');
        Route::get('getcustimerparticipatelots/{customerId}', 'LotsContoller@getcustimerparticipatelots');
        Route::post('participateonlot', 'AuctionContoller@participateOnLot');
        Route::post('participeteonexpirelot', 'AuctionContoller@participeteOnExpireLot');
    });

    Route::get('getlivebid', 'App\Http\Controllers\Api\v1\Admin\AuctionContoller@getlivebid');
    Route::get('requestativateaccount/{uid}', [AccountNotificationController::class, 'requestativateaccount']);
    
    // Route::group(['middleware' => 'jwt.auth'], function () {
    //     Route::get('user', 'Auth\LoginController@getAuthUser');
    // });
});

// // Get Categorys API
// Route::get('getcategorys', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getcategorys');

// Get Categorys API
Route::get('getCategoriesAndLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getCategoriesAndLots');


// live lots 
Route::get('activeLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getActiveLots');
// Upcoming lots 
Route::get('upcomingLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@getUpcomingLots');
// Experied lots
Route::get('expiredLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@ExpiredLots');
//Sold lots
Route::get('soldLots', 'App\Http\Controllers\Api\v1\Admin\LotsContoller@SoldLots');

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
