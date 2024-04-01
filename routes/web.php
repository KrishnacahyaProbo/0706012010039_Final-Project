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
    return view('dashboard');
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
        Route::get('/menu', 'MenuController@index')->name('menu.index');
        Route::post('/menu/store', 'MenuController@store')->name('menu.store');
        Route::get('/menu/data', 'MenuController@data')->name('menu.data');
        Route::get('/menu/detailMenu', 'MenuController@detailMenu')->name('menu.detailMenu');
        Route::delete('/menu/deleteMenu', 'MenuController@deleteMenu')->name('menu.deleteMenu');

        Route::post('/menu/addSchedule', 'MenuController@addSchedule')->name('menu.addSchedule');
    });
});
