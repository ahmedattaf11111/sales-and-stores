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

Route::prefix("admin-panel-settings")->group(function () {
    Route::get("", "AdminPanelSettingController@index");
    Route::get("general-accounts", "AdminPanelSettingController@getGeneralAccounts");
    Route::post("", "AdminPanelSettingController@update");
});
