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

Route::prefix("sales-returns-invoices")->group(function () {
    Route::get("", "SaleReturnInvoiceController@index");
    Route::post("", "SaleReturnInvoiceController@createSaleInvoice");
    Route::get("customers", "SaleReturnInvoiceController@getCustomers");
    Route::get("delegates", "SaleReturnInvoiceController@getDelegates");
    Route::get("items", "SaleReturnInvoiceController@getItems");
    Route::get("stores", "SaleReturnInvoiceController@getStores");
    Route::get("invoice-categories", "SaleReturnInvoiceController@getInvoiceCategories");
    Route::put("", "SaleReturnInvoiceController@update");
    Route::put("tax", "SaleReturnInvoiceController@updateTax");
    Route::put("discount", "SaleReturnInvoiceController@updateDiscount");
    Route::delete("{id}", "SaleReturnInvoiceController@delete");
});

Route::prefix("sales-returns-invoice-items")->group(function () {
    Route::get("{saleInvoiceId}", "SaleReturnInvoiceItemController@getInvoiceItems");
    Route::post("delete", "SaleReturnInvoiceItemController@deleteItem");
    Route::post("", "SaleReturnInvoiceItemController@create");
    Route::get("batches/{storeId}/{itemId}", "SaleReturnInvoiceItemController@getBatches");
});
