<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 流程管理路由
Route::group(['middleware' => ['auth.admin:admin','web']], function(){


    //Flow
    Route::group(['namespace' => 'Base\Flow'], function(){

        //flow
        Route::get('/flow/flowList', 'FlowController@pagenation');
        Route::resource('/flow', 'FlowController');

        //flow designs
        Route::get('/flow/design/{id}', 'FlowController@design');

    });


});