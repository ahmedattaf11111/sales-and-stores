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

Route::prefix("sales-invoices")->group(function () {
    Route::get("", "SaleInvoiceController@index");
    Route::post("", "SaleInvoiceController@createSaleInvoice");
    Route::get("customers", "SaleInvoiceController@getCustomers");
    Route::get("delegates", "SaleInvoiceController@getDelegates");
    Route::get("items", "SaleInvoiceController@getItems");
    Route::get("stores", "SaleInvoiceController@getStores");
    Route::get("invoice-categories", "SaleInvoiceController@getInvoiceCategories");
    Route::put("", "SaleInvoiceController@update");
    Route::put("tax", "SaleInvoiceController@updateTax");
    Route::put("discount", "SaleInvoiceController@updateDiscount");
    Route::delete("{id}", "SaleInvoiceController@delete");
});

Route::prefix("sales-invoice-items")->group(function () {
    Route::get("{saleInvoiceId}", "SaleInvoiceItemController@getInvoiceItems");
    Route::post("delete", "SaleInvoiceItemController@deleteItem");
    Route::post("", "SaleInvoiceItemController@create");
    Route::get("batches/{storeId}/{itemId}", "SaleInvoiceItemController@getBatches");
});
