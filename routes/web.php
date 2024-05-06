<?php

use App\Models\BalanceNominal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $authDeliver = null;
    $balanceCustomer = null;

    if (Auth::check()) {
        $auth = User::where('id', Auth::user()->id)->with('Delivery')->first();
        $balanceCustomer = BalanceNominal::where('user_id', Auth::user()->id)->first();
        $authDeliver = $auth->Delivery;
    }

    $data = [
        'authDeliver' => $authDeliver,
        'balanceCustomer' => $balanceCustomer,
    ];

    return view('pages.home', $data);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('menus')->name('menu.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'MenuController@index')->name('index');
        Route::get('/data', 'MenuController@data')->name('data');
        Route::post('/store', 'MenuController@store')->name('store');
        Route::get('/show', 'MenuController@show')->name('show');
        Route::delete('/destroy', 'MenuController@destroy')->name('destroy');
        Route::get('/schedule', 'MenuController@schedule')->name('schedule');
    });

    Route::prefix('schedules')->name('schedule.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/{vendor_name}', 'ScheduleController@show')->name('vendor');
        Route::post('/store', 'ScheduleController@store')->name('store');
        Route::post('/update', 'ScheduleController@update')->name('update');
        Route::delete('/destroy', 'ScheduleController@destroy')->name('destroy');
    });

    Route::prefix('vendors')->name('vendor.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'VendorController@index')->name('index');
        Route::get('/data', 'VendorController@data')->name('data');
        Route::get('/{vendorName}', 'VendorController@detailVendor')->name('detail-vendor');
    });

    Route::middleware('customer')->prefix('carts')->name('cart.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'CartController@index')->name('index');
        Route::get('/data', 'CartController@data')->name('data');
        Route::post('/store', 'CartController@store')->name('store');
        Route::post('/update', 'CartController@update')->name('update');
        Route::delete('/destroy', 'CartController@destroy')->name('destroy');
        Route::post('/checkout', 'CheckoutController@checkout')->name('checkout');
        Route::post('/pay', 'CheckoutController@pay')->name('pay');
    });

    Route::prefix('orders')->name('order.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::get('/detail/{id}', 'OrderController@detailOrder')->name('detail');
        Route::get('/request-order', 'OrderController@requestOrder')->name('request-order');
        Route::get('/incoming-order', 'OrderController@incomingOrder')->name('incoming-order');
        Route::delete('/cancel-order', 'OrderController@cancelOrder')->name('cancel-order');
        Route::post('/process-order', 'OrderController@processOrder')->name('process-order');
        Route::post('/deliver-order', 'OrderController@deliverOrder')->name('deliver-order');
        Route::post('/receive-order', 'OrderController@receiveOrder')->name('receive-order');
        Route::post('/refund-reason', 'OrderController@refundReason')->name('refund-reason');
    });

    Route::prefix('testimonies')->name('testimony.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/{id}', 'TestimonyController@show')->name('show');
        Route::post('/store', 'TestimonyController@store')->name('store');
    });

    Route::prefix('settings')->name('setting.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'UserSettingController@index')->name('index');
        Route::get('/data', 'UserSettingController@data')->name('data');
        Route::post('/order', 'UserSettingController@order')->name('order');
        Route::post('/about', 'UserSettingController@about')->name('about');
    });
    Route::prefix('delivery')->name('delivery.')->namespace('App\Http\Controllers')->group(function () {
        Route::post('/settings', 'DeliveryController@deliverySetting')->name('delivery.settings');
    });
    Route::prefix('balance')->name('balance.')->namespace('App\Http\Controllers')->group(function () {
        Route::post('/settings', 'BalanceSettingController@settings')->name('balance.settings');
    });

    Route::prefix('credits')->name('credit.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'BalanceNominalController@index')->name('index');
        Route::post('/top-up', 'BalanceNominalController@topUp')->name('top-up');
        Route::post('/cash-out', 'BalanceNominalController@cashOut')->name('cash-out');
        Route::get('/{category}', 'BalanceNominalController@balanceCategory')->name('category');
    });
});
