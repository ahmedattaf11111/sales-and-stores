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

Route::prefix("accounts")->group(function () {
    Route::get("", "AccountController@index");
    Route::get("all", "AccountController@getAllAccounts");
    Route::get("types", "AccountController@getTypes");
    Route::post("", "AccountController@create");
    Route::put("", "AccountController@update");
});
