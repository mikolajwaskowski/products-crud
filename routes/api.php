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

Route::get('/products', 'ProductsController@getAll');
Route::get('/products/{product}', 'ProductsController@getSpecified');
Route::post('/products', 'ProductsController@store');
Route::put('/products', 'ProductsController@update');
Route::delete('/products/{product}', 'ProductsController@destroy');

