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

//客服中心前台  (*************需要修复namespace**************)
Route::group(['prefix' => 'call_center', 'namespace' => 'CallCenter'], function(){
    Route::post('/login', 'CustomerController@doLogin');
    Route::post('/logout', 'CustomerController@logout');
    Route::post('/leave', 'ManagerController@leave');

    Route::post("/send", 'CustomerController@sendByCustomer');


    //图片上传
    Route::post("/upload", 'BaseController@upload');
    //模板
    Route::get("/template_list", 'TemplateController@getList');

    // 客户部分三个界面
    Route::get('/', 'CustomerController@login');
    Route::get('/login', 'CustomerController@login');
    Route::get('/forget', 'CustomerController@forget');
    Route::get('/chat', 'CustomerController@chat');
});

//视频上传
Route::group(['prefix' => 'uploadVideo'], function(){
    Route::get('/', 'VideoCollectController@index');
    Route::get('/client', 'VideoCollectController@client');
    Route::get('/upload', 'VideoCollectController@upload');
    Route::get('/record', 'VideoCollectController@record');
});

Route::group(['prefix' => 'mediator'], function(){
    Route::get('/getCode', 'mediatorController@getCode');
    Route::get('/login', 'mediatorController@login');
    Route::get('/', 'MediatorController@index');
	Route::get('/getImageCode', 'MediatorController@getImageCode');
	Route::get('/login', 'MediatorController@login');
	Route::post('/sendCode', 'MediatorController@sendCode');
	Route::get('/IDCard', 'MediatorController@IDCard');
	Route::get('/perfectInformation', 'MediatorController@perfectInformation');
	Route::get('/sign', 'MediatorController@sign');
	Route::get('/bankCard', 'MediatorController@bankCard');
	Route::get('/handIdCard', 'MediatorController@handIdCard');
	Route::get('/agreement', 'MediatorController@agreement');
	Route::get('/rate', 'MediatorController@rate');
	Route::get('/video', 'MediatorController@video');
	Route::get('/review', 'MediatorController@review');

});


Route::group([], function(){
    Route::get('/login', 'AuthController@login');
});
