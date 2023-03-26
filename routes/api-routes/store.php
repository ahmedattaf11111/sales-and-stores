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

Route::prefix("stores")->group(function () {
    Route::get("", "StoreController@index");
    Route::post("", "StoreController@create");
    Route::put("", "StoreController@update");
    Route::delete("{id}", "StoreController@delete");
});
