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

Route::prefix("supplier-categories")->group(function () {
    Route::get("", "SupplierCategoryController@index");
    Route::post("", "SupplierCategoryController@create");
    Route::put("", "SupplierCategoryController@update");
});

Route::prefix("suppliers")->group(function () {
    Route::get("", "SupplierController@index");
    Route::get("categories", "SupplierController@getCategories");
    Route::post("", "SupplierController@create");
    Route::put("", "SupplierController@update");
});