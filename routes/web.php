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
    return view('pages.customer.vendor');
});

Route::prefix('vendors')->name('vendors.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/data', 'VendorController@data')->name('vendor.index');
});

Route::prefix('menu')->name('menu.')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/menuVendor/{vendor_id}', 'MenuController@menuVendor')->name('menuVendor');
    Route::get('/dataMenuVendor', 'MenuController@dataMenuVendor')->name('dataMenuVendor');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('users')->name('users.')->namespace('App\Http\Controllers')->group(function () {
        // Menu
        Route::get('/menu', 'MenuController@index')->name('menu.index');
        Route::post('/menu/store', 'MenuController@store')->name('menu.store');
        Route::get('/menu/data', 'MenuController@data')->name('menu.data');
        Route::get('/menu/show', 'MenuController@show')->name('menu.show');
        Route::delete('/menu/destroy', 'MenuController@destroy')->name('menu.destroy');

        // Schedule
        Route::post('/menu/addSchedule', 'MenuController@addSchedule')->name('menu.addSchedule');
        Route::post('/menu/updateSchedule', 'MenuController@updateSchedule')->name('menu.updateSchedule');
        Route::delete('/menu/destroySchedule', 'MenuController@destroySchedule')->name('menu.destroySchedule');

        // Setting
        Route::get('/settings', 'UserSettingController@index')->name('settings.index');
        Route::post('/settingsDelivery', 'DeliveryController@settingsDelivery')->name('delivery.store');
        Route::post('/settingsPemesanan', 'UserSettingController@settingsPemesanan')->name('settings.settingsPemesanan');
        Route::get('/getDataSettings', 'UserSettingController@getDataSettings')->name('settings.getDataSettings');

        Route::post('/balanceSettings', 'UserSettingController@balanceSettings')->name('users.balanceSettings');



    });
});
