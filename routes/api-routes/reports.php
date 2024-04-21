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

Route::prefix("customer_account_statement")->group(function () {
    Route::get("customers", "CustomerReportController@getCustomers");
    Route::get("total-report", "CustomerReportController@getTotalAccountStatementReport");
    Route::get("sale-invoice-report", "CustomerReportController@getSaleInvoiceReport");
    Route::get("sale-return-invoice-report", "CustomerReportController@getSaleReturnInvoiceReport");
    Route::get("collect-exchange-report", "CustomerReportController@getCollectExchangeReport");
});

Route::prefix("delegate_account_statement")->group(function () {
    Route::get("delegates", "DelegateReportController@getDelegates");
    Route::get("total-report", "DelegateReportController@getTotalAccountStatementReport");
    Route::get("sale-invoice-report", "DelegateReportController@getSaleInvoiceReport");
    Route::get("sale-return-invoice-report", "DelegateReportController@getSaleReturnInvoiceReport");
    Route::get("collect-exchange-report", "DelegateReportController@getCollectExchangeReport");
});

