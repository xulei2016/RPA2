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

        //FlowTestController
        Route::get('/flow/flowTest', 'FlowTextController@stores');
        Route::get('/flow/resend', 'FlowTextController@resend');
        Route::post('/flow/pass/{id}', 'FlowTextController@pass');
        Route::post('/flow/unpass/{id}', 'FlowTextController@unpass');

        //flow
        Route::get('/flow/flowList', 'FlowController@pagenation');
        Route::resource('/flow', 'FlowController');

        //flow designs
        Route::get('/flow/design/{id}', 'FlowController@design');
        Route::post('/flow/publish','FlowController@publish');

        //nodes
        Route::get('/node/attribute', 'NodeController@attribute');
        Route::post('node/condition','NodeController@condition');
        Route::post('node/begin','NodeController@setFirst');
        Route::post('node/stop','NodeController@setLast');
        Route::resource('/node', 'NodeController');

        //flowlink
        Route::get('/flowLink/auth/dept/{id}','FlowLinkController@dept');
        Route::get('/flowLink/auth/role/{id}','FlowLinkController@role');
        Route::get('/flowLink/auth/emp/{id}','FlowLinkController@emp');
        Route::post('/flowLink/{id}','FlowLinkController@update');

        //我的流程
        Route::get('/sys_flow_mine', 'FlowMineController@index');
        Route::get('/sys_flow_mine/list', 'FlowMineController@pagenation');
        Route::get('/sys_flow_mine/todoList', 'FlowMineController@todoList');
        Route::get('/sys_flow_mine/getMenuTree', 'FlowMineController@getMenuTree');
        Route::get('/sys_flow_mine/operation', 'FlowMineController@operation');
        Route::resource('/sys_flow_mine', 'FlowMineController');
    });


});