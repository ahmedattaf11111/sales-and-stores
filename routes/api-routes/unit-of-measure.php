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

Route::prefix("unit-of-measures")->group(function () {
    Route::get("", "UnitOfMeasureController@index");
    Route::post("", "UnitOfMeasureController@create");
    Route::put("", "UnitOfMeasureController@update");
    Route::delete("{id}", "UnitOfMeasureController@delete");
});
