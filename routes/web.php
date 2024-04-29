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

Route::get('/credit', function () {
    return view('pages.slicing.credit');
})->name('credit');
Route::get('/cart/checkout', function () {
    return view('pages.slicing.checkout');
})->name('checkout');
Route::get('/order', function () {
    return view('pages.slicing.order');
})->name('order');
Route::get('/testimony', function () {
    return view('pages.slicing.testimony');
})->name('testimony');

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
        Route::get('/menu/{vendorName}', 'VendorController@menu')->name('menu');
    });

    Route::prefix('carts')->name('cart.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'CartController@index')->name('index');
        Route::get('/data', 'CartController@data')->name('data');
        Route::post('/store', 'CartController@store')->name('store');
        Route::delete('/destroy', 'CartController@destroy')->name('destroy');
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
});
