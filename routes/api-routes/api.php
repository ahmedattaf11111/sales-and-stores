<?php

use Illuminate\Http\Request;
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

Route::prefix("hellos")->group(function () {
    Route::post("", "HelloController@createSaleInvoice");
    Route::post("update", "HelloController@update");
    Route::delete("{id}", "HelloController@delete");
    Route::get("", "HelloController@index");
});