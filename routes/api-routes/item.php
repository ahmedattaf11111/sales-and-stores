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

Route::prefix("items")->group(function () {
    Route::get("", "ItemController@index");
    Route::post("", "ItemController@create");
    Route::post("update", "ItemController@update");
    Route::delete("{id}", "ItemController@delete");
    Route::get("categories", "ItemController@getItemCategories");
    Route::get("unit-of-measures", "ItemController@getUnitOfMeasures");
});
