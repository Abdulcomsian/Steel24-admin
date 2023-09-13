<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\LotsController;
use App\Http\Controllers\Admin\MaterialsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\LiveLotsController;
use App\Http\Controllers\Admin\AccountNotificationController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\Admin\ProductImageController;

// Dashboard
Route::get('/', 'HomeController@index')->name('home');

// Login
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Register
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Reset Password
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Confirm Password
// Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

Route::resource('users', 'UserController');
Route::get('/users/destroy/{user}', 'UserController@destroy')->name('delete');

Route::resource('customers', 'CustomerController');
Route::get('/customers/edit/{customer}', 'CustomerController@edit')->name('edit');
Route::get('/customers/destroy/{customer}', 'CustomerController@destroy')->name('delete');
Route::get('/customers/balancehistory/{customer}', 'CustomerController@customersbalancehistory');
Route::resource('payments', 'PaymentsController');
Route::post('createPayment', [LotsController::class, 'createPayment']);
Route::resource('materialFiles', 'MaterialFilesController');



// Route::get('/lots-dashboard', [LotsController::class, 'index'])->name('home');
Route::get('/lots', [LotsController::class, 'index'])->name('home');


// Materials Routes
// Route::get('/materials', [MaterialsController::class, 'index'])->name('materials');
// Route::get('/materials/create', [MaterialsController::class, 'create']);
// Route::post('/newmaterials', [MaterialsController::class, 'store']);

// Route::get('/materials/{materials}', [MaterialsController::class, 'show']);
// Route::get('/materials/{materials}/edit', [MaterialsController::class, 'edit']);
// Route::patch('/materials/{materials}', [MaterialsController::class, 'update']);
// // Route::delete('/materials/destroy/{materials}', [MaterialsController::class, 'destroy']);
// Route::get('/materials/destroy/{materials}', [MaterialsController::class, 'destroy']);

// Categories Routes
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
Route::get('/categories/create', [CategoriesController::class, 'create']);
Route::get('/categories/show/{categories}', [CategoriesController::class, 'show']);
Route::post('/categories/store', [CategoriesController::class, 'store']);
Route::get('/categories/{categories}/edit', [CategoriesController::class, 'edit']);
Route::patch('/categories/{categories}', [CategoriesController::class, 'update']);
Route::get('/categories/destroy/{categories}', [CategoriesController::class, 'destroy']);

// Product Image Routes
Route::get('/productimage', [ProductImageController::class, 'index'])->name('productimages');
Route::get('/productimages/create', [ProductImageController::class, 'create']);
Route::get('/productimages/show/{productimages}', [ProductImageController::class, 'show']);
Route::post('/productimages/store', [ProductImageController::class, 'store']);
Route::get('/productimages/{productimage}/edit', [ProductImageController::class, 'edit']);
Route::post('/productimages/{productimage}', [ProductImageController::class, 'update']);
Route::post('/productimages/destroy/{productimage}', [ProductImageController::class, 'destroy'])->name('productimages.destroy');


// Lots Routes
Route::get('/lots', [LotsController::class, 'index'])->name('lots');

Route::get('/lots/create', [LotsController::class, 'create'])->name('create');
// import lots
Route::post('/lots/import-csv', [CsvImportController::class, 'import']);
Route::get('/lots/import-csv/', [CsvImportController::class, 'showForm'])->name('import.showForm');


// Route::get('import-csv', [CsvImportController::class, 'showForm'])->name('import.showForm');
// Route::post('/lots/import-csv', [CsvImportController::class, 'import'])->name('import.csv');

Route::post('/newlots', [LotsController::class, 'store']);

Route::get('/lots/{lots}', [LotsController::class, 'show']);
Route::get('/lots/edit/{lots}', [LotsController::class, 'edit']);

// Route::get('/lots/edit/{lots}/live', [LotsController::class, 'editLive']);
Route::patch('/lots/{lots}', [LotsController::class, 'update']);
Route::get('/lots/remove/{lots}', [LotsController::class, 'destroy']);

Route::get('/addmaterialslots/{lots}', [LotsController::class, 'creatematerialslots']);
Route::get('/materialslots/{lots}', [LotsController::class, 'materialslots']);

Route::post('/addmaterialslots/{lots}', [LotsController::class, 'addmaterialslots']);
Route::patch('/materialslots/{lots}', [LotsController::class, 'updatematerialslots']);

// Route::get('/payment_plan', [LotsController::class, 'createlotsterms']);
// Route::post('/addlotsterms', [LotsController::class, 'storelotsterms']);
// Route::get('/addlotsterms/{lots}', [LotsController::class, 'createlotsterms']);
Route::get('/payment_plan', [LotsController::class, 'payment_plan']);
Route::get('/addpayment_plan', [LotsController::class, 'createLotTerms']);
Route::post('/addpayment_plan', [LotsController::class, 'storepaymentplan']);



Route::get('/lotsterms/{lots}', [LotsController::class, 'lotsterms']);
Route::patch('/lotsterms/{lots}', [LotsController::class, 'updatelotsterms']);

Route::get('/complete_lots', [LotsController::class, 'complete_index'])->name('expirelots');
Route::get('/livelots/{lots}', [LotsController::class, 'liveLotDetails']);
Route::get('/livelotstatus/{id}/{status}', [LotsController::class, 'livelotstatus']);
Route::post('/users-send-email', [UserController::class, 'sendEmail'])->name('send.email');


Route::get('/live_lots', [LiveLotsController::class, 'live_index'])->name('livelots');
Route::get('/live_lots_bids/{lots}', [LiveLotsController::class, 'liveLotBids']);

Route::get('/startlot/{id}', [LiveLotsController::class, 'startLots']);
Route::get('/poselot/{id}', [LiveLotsController::class, 'poseLots']);
Route::get('/endlot/{id}', [LiveLotsController::class, 'endlot']);
Route::post('/reStartExpirelot', [LiveLotsController::class, 'reStartExpirelot']);
Route::get('/pushonfirbase', [LiveLotsController::class, 'pushonfirbase']);
Route::post('/livelotsequencechange', [LiveLotsController::class, 'LiveLotSequenceChange']);
Route::get('/live_lots/categorie/{id}', [LiveLotsController::class, 'categorieLots']);
Route::get('/expireLot/{id}', [LiveLotsController::class, 'expireLot']);

Route::get('/completelotbids/{lots}', [LotsController::class, 'completelotbids']);
Route::get('/addtimeinlive/{lots}', [LotsController::class, 'addTimeInLive']);
Route::get('/notification', [AccountNotificationController::class, 'index']);


Route::post('/analyze-url', [LotsController::class, 'analyzeUrl'])->name('analyze-url');
// Verify Email
// Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
// Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get("coupon/status/{id}/{type}", 'UserController@status');
Route::get("customer/status/{id}/{type}", 'CustomerController@status');
Route::get("activecustomers/{id}", 'CustomerController@activecustomers');
Route::get("activecustomers/{id}", 'CustomerController@activecustomers');


// Route::get('/payments', [PaymentsController::class, 'index'])->name('payments');
