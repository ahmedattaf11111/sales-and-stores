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

Route::prefix("invoice-categories")->group(function () {
    Route::get("", "InvoiceCategoryController@index");
    Route::post("", "InvoiceCategoryController@create");
    Route::put("", "InvoiceCategoryController@update");
    Route::delete("{id}", "InvoiceCategoryController@delete");
});
