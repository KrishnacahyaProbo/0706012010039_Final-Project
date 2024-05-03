<?php

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
    return view('pages.home');
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
        Route::get('/{vendorName}', 'VendorController@menu')->name('menu');
    });

    Route::prefix('carts')->name('cart.')->namespace('App\Http\Controllers')->group(function () {
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
        Route::get('/data', 'OrderController@data')->name('data');
        Route::post('/store', 'OrderController@store')->name('store');
        Route::get('/detail/{id}', 'OrderController@detailOrder');
        Route::delete('/cancel-order', 'OrderController@cancelOrder')->name('cancelOrder');
        Route::post('/receive-order', 'OrderController@receiveOrder')->name('receiveOrder');
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
        Route::get('/{category}', 'BalanceNominalController@balanceCategory');
    });
});
