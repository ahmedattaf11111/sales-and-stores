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

Route::prefix("supplier_account_statement")->group(function () {
    Route::get("suppliers", "SupplierReportController@getSuppliers");
    Route::get("total-report", "SupplierReportController@getTotalAccountStatementReport");
    Route::get("purchase-invoice-report", "SupplierReportController@getPurchaseInvoiceReport");
    Route::get("purchase-return-invoice-report", "SupplierReportController@getPurchaseReturnInvoiceReport");
    Route::get("collect-exchange-report", "SupplierReportController@getCollectExchangeReport");
});
