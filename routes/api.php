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
//    系统
    Route::middleware('auth:api')->post('/v1/zzy_sms', 'NoticeApiController@sms');
    Route::middleware('auth:api')->post('/v1/mail', 'NoticeApiController@mail');
    Route::middleware('auth:api')->post('/v1/task_notice', 'NoticeApiController@task_notice');
    Route::middleware('auth:api')->post('/v1/message', 'NoticeApiController@message');
    Route::middleware('auth:api')->post('/v1/sms_tpl', 'NoticeApiController@sms_tpl');
    Route::middleware('auth:api')->post('/v1/tpl_send', 'NoticeApiController@tpl_send');
//    rpa系统
    Route::group(['namespace' => 'rpa'], function(){
//        rpa相关
        Route::middleware('auth:api')->post('/v1/punch_card', 'RpaApiController@punch_card');
        Route::middleware('auth:api')->post('/v1/get_card', 'RpaApiController@get_card');
        Route::middleware('auth:api')->post('/v1/release_task', 'RpaApiController@release_task');
        Route::middleware('auth:api')->post('/v1/dtu_save_sms', 'RpaApiController@dtu_save_sms');
        Route::middleware('auth:api')->post('/v1/dtu_get_sms', 'RpaApiController@dtu_get_sms');
//        插件相关
        Route::middleware('auth:api')->post('/v1/customerPDF', 'PluginApiController@customerPDF');
        Route::middleware('auth:api')->post('/v1/customer_review', 'PluginApiController@customer_review');
        Route::middleware('auth:api')->post('/v1/oa_flow_save', 'PluginApiController@oa_flow_save');
        Route::middleware('auth:api')->post('/v1/oa_get_sign', 'PluginApiController@oa_get_sign');
        Route::middleware('auth:api')->post('/v1/crm_flow_save', 'PluginApiController@crm_flow_save');
        Route::middleware('auth:api')->post('/v1/crm_get_sign', 'PluginApiController@crm_get_sign');
        Route::middleware('auth:api')->post('/v1/credit', 'PluginApiController@credit');
        Route::middleware('auth:api')->post('/v1/mediator_info', 'PluginApiController@mediator_info');
        Route::middleware('auth:api')->post('/v1/mediator_info2', 'PluginApiController@mediator_info2');
        Route::middleware('auth:api')->post('/v1/save_customer_info', 'PluginApiController@save_customer_info');
//        crm相关
        Route::middleware('auth:api')->post('/v1/investor_password', 'CrmApiController@save_customer_info');
        Route::middleware('auth:api')->post('/v1/get_customer_info', 'CrmApiController@get_customer_info');
        Route::middleware('auth:api')->post('/v1/get_customer_kyzj', 'CrmApiController@get_customer_kyzj');
        Route::middleware('auth:api')->post('/v1/open_history', 'CrmApiController@open_history');
        Route::middleware('auth:api')->post('/v1/sync_data', 'CrmApiController@sync_data');
        Route::middleware('auth:api')->post('/v1/mediator_flow', 'CrmApiController@mediator_flow');
        Route::middleware('auth:api')->post('/v1/get_customer_relation', 'CrmApiController@get_customer_relation');
        Route::middleware('auth:api')->post('/v1/get_mediator_relation', 'CrmApiController@get_mediator_relation');
        Route::middleware('auth:api')->post('/v1/fxq', 'CrmApiController@fxq');


    });
//   掌上营业厅


    Route::group(['namespace' => 'Base'], function(){
        Route::get('/v1/011', 'NoticeApiController@sms');
    });
});


