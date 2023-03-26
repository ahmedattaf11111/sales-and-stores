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

Route::prefix("admins")->group(function () {
    Route::get("", "AdminController@index");
    Route::post("", "AdminController@create");
    Route::put("", "AdminController@update");
    Route::get("treasuries", "AdminController@getAllTreasuries");
});
