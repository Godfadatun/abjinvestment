<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::resource('package', 'PackageController');
    Route::resource('payment', 'PaymentController');
    Route::resource('transaction', 'TransactionController');
    Route::resource('investment', 'InvestmentController');
    Route::resource('dailyRoi', 'ROIController');
});