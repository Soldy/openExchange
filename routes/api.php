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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/exchange/info', 'ExchangeController@getInfo');
Route::get('/exchange/cache/clear', 'ExchangeController@getCacheClear');
Route::get('/exchange/{amount}/{from}/{to}', 'ExchangeController@getExchange');

Route::get('/country/list', 'ExCountryController@list');
Route::get('/currency/list', 'ExCurrencyController@list');
Route::get('/currency/status', 'ExCurrencyController@status');
Route::get('/currency/history/{form}/{to}/{period}', 'ExCurrencyController@history');
