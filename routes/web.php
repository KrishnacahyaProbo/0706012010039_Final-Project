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

Route::prefix('vendors')->name('vendors.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/', 'VendorController@index')->name('index');
    Route::get('/data', 'VendorController@data')->name('data');
    Route::get('/{name}', 'VendorController@show')->name('show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('menus')->name('menu.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'MenuController@index')->name('index');
        Route::get('/data', 'MenuController@data')->name('data');
        Route::post('/store', 'MenuController@store')->name('store');
        Route::get('/show', 'MenuController@show')->name('show');
        Route::delete('/destroy', 'MenuController@destroy')->name('destroy');
    });

    Route::prefix('schedules')->name('schedule.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/{vendor_name}', 'ScheduleController@show')->name('vendor');
        Route::post('/store', 'ScheduleController@store')->name('store');
        Route::post('/update', 'ScheduleController@update')->name('update');
        Route::delete('/destroy', 'ScheduleController@destroy')->name('destroy');
    });

    Route::prefix('settings')->name('setting.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'UserSettingController@index')->name('index');
        Route::post('/settingsPemesanan', 'DeliveryController@settingsPemesanan')->name('delivery.settingsPemesanan');
    });

    Route::prefix('delivery')->name('delivery.')->namespace('App\Http\Controllers')->group(function () {
        Route::post('/settings', 'DeliveryController@settingsDelivery')->name('delivery.settings');
    });



    // Route::prefix('users')->name('users.')->namespace('App\Http\Controllers')->group(function () {

    //     // Setting
    //     Route::post('/settingsPemesanan', 'UserSettingController@settingsPemesanan')->name('settings.settingsPemesanan');
    //     Route::get('/getDataSettings', 'UserSettingController@getDataSettings')->name('settings.getDataSettings');

    //     Route::post('/balanceSettings', 'UserSettingController@balanceSettings')->name('users.balanceSettings');
    // });
});
