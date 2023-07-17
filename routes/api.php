<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductController;
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

Route::prefix('v1')->namespace('Api')->group(function(){
    
    //authentication
    Route::post('/login','Auth\\LoginJwtController@login')->name('login');
    Route::get('logout','Auth\\LoginJwtController@logout')->name('logout');
    Route::get('refresh','Auth\\LoginJwtController@refresh')->name('logout');

    //new user
    Route::post('/users','UserController@store');

    //protected routes
    Route::group(['middleware'=>['jwt.auth']], function(){

        //users
        Route::prefix('users')->group(function(){
            Route::get('/','UserController@index');
            Route::get('/{id}','UserController@show');
            //Route::post('/','UserController@store');
            Route::put('/{id}','UserController@update');
            Route::delete('/{id}','UserController@destroy');
        });

        //products
        Route::prefix('products')->group(function(){
            Route::get('/','ProductController@index');
            Route::get('/{id}','ProductController@show');
            Route::post('/','ProductController@store');
            Route::put('/{id}','ProductController@update');
            Route::delete('/{id}','ProductController@destroy');
        });

    });
    
   
});

