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
Route::group(['prefix' => 'call_center', 'namespace' => 'Admin\Base\CallCenter'], function(){
    Route::post('/login', 'CustomerController@doLogin');
    Route::post('/logout', 'CustomerController@logout');
    Route::post('/leave', 'ManagerController@leave');

    Route::post("/send", 'CustomerController@sendByCustomer');
    Route::post("/updateOne", 'ManagerController@updateOne');
    Route::post("/sendByManager", 'ManagerController@sendByManager');
    Route::get("/getOnlineCustomerList", 'CustomerController@getOnlineCustomerList');
    Route::get("/getOnlineManagerList", 'ManagerController@getOnlineManagerList');
    Route::get("/getRecordById", 'RecordDetailController@getRecordById');
    Route::get("/getRecordList", 'RecordDetailController@getRecordList');
    Route::post('/getCustomerInfo', 'CustomerController@getById');
    Route::post("/connect", 'ManagerController@connect');
    Route::post("/transfer", 'ManagerController@transfer');
    //图片上传
    Route::post("/upload", 'BaseController@upload');
    // 模板请求
    Route::get("/template_list", 'TemplateController@getList');
    Route::get("/template_list_background", 'TemplateController@getTemplateList');

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
