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

Route::prefix("purchase-invoices")->group(function () {
    Route::get("", "PurchaseInvoiceController@index");
    Route::get("suppliers", "PurchaseInvoiceController@getSuppliers");
    Route::get("stores", "PurchaseInvoiceController@getStores");
    Route::get("current-shift", "PurchaseInvoiceController@getCurrentShift");
    Route::post("", "PurchaseInvoiceController@create");
    Route::put("", "PurchaseInvoiceController@update");
    Route::put("approve", "PurchaseInvoiceController@approve");
    Route::delete("{id}", "PurchaseInvoiceController@delete");
});

Route::prefix("purchase-return-invoices")->group(function () {
    Route::get("", "PurchaseReturnInvoiceController@index");
    Route::get("suppliers", "PurchaseReturnInvoiceController@getSuppliers");
    Route::get("stores", "PurchaseReturnInvoiceController@getStores");
    Route::get("current-shift", "PurchaseReturnInvoiceController@getCurrentShift");
    Route::post("", "PurchaseReturnInvoiceController@create");
    Route::put("", "PurchaseReturnInvoiceController@update");
    Route::put("approve", "PurchaseReturnInvoiceController@approve");
    Route::delete("{id}", "PurchaseReturnInvoiceController@delete");
});

Route::prefix("purchase-return-invoice-items")->group(function () {
    Route::get("batches", "PurchaseReturnInvoiceItemController@getBatches");
    Route::get("items", "PurchaseReturnInvoiceItemController@getItems");
    Route::get("{purchaseInvoiceId}", "PurchaseInvoiceItemController@index");
    Route::post("", "PurchaseReturnInvoiceItemController@create");
    Route::delete("{id}", "PurchaseReturnInvoiceItemController@delete");
    Route::get("is-purchase-invoice-approved/{id}","PurchaseReturnInvoiceItemController@isPurchaseInvoiceApproved");
});

Route::prefix("purchase-invoice-items")->group(function () {
    Route::get("items", "PurchaseInvoiceItemController@getItems");
    Route::get("{purchaseInvoiceId}", "PurchaseInvoiceItemController@index");
    Route::post("", "PurchaseInvoiceItemController@create");
    Route::put("", "PurchaseInvoiceItemController@update");
    Route::delete("{id}", "PurchaseInvoiceItemController@delete");
    Route::get("is-purchase-invoice-approved/{id}","PurchaseInvoiceItemController@isPurchaseInvoiceApproved");
});

