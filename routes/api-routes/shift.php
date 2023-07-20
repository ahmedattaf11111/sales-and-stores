<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix("shifts")->group(function () {
    Route::get("", "ShiftController@index");
    Route::get("admin-treasuries", "ShiftController@getAdminTreasuries");
    Route::get("current-shift", "ShiftController@getCurrentShift");
    Route::post("", "ShiftController@create");
    Route::get("close", "ShiftController@close");
});
