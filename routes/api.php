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
Route::post('login', 'API\PassportController@login');

Route::group(['namespace' => 'api'], function(){
    Route::middleware('auth:api')->post('/v1/zzy_sms', 'SysApiController@zzy_sms');
    Route::middleware('auth:api')->post('/v1/yx_sms', 'SysApiController@yx_sms');
    Route::middleware('auth:api')->post('/v1/message', 'SysApiController@message');
});


