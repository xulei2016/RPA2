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

//    系统
    Route::group(['namespace' => 'Base'], function(){
        Route::middleware('auth:api')->post('/v1/sms', 'NoticeApiController@sms');
        Route::middleware('auth:api')->post('/v1/mail', 'NoticeApiController@mail');
        Route::middleware('auth:api')->post('/v1/test_notice', 'NoticeApiController@test_notice');
        Route::middleware('auth:api')->post('/v1/task_notice', 'NoticeApiController@task_notice');
        Route::middleware('auth:api')->post('/v1/message', 'NoticeApiController@message');
        Route::middleware('auth:api')->post('/v1/sms_tpl', 'NoticeApiController@sms_tpl');
        Route::middleware('auth:api')->post('/v1/tpl_send', 'NoticeApiController@tpl_send');
        Route::middleware('auth:api')->post('/v1/code_distinguish', 'NoticeApiController@code_distinguish');

        //账户系统
        Route::middleware('auth:api')->post('/v1/getSdx', 'accountSysController@getSdx');
    });
//    rpa系统
    Route::group(['namespace' => 'rpa'], function(){
//        rpa相关
        Route::middleware('auth:api')->post('/v1/punch_card', 'RpaApiController@punch_card');
        Route::middleware('auth:api')->post('/v1/get_card', 'RpaApiController@get_card');
        Route::middleware('auth:api')->post('/v1/release_task', 'RpaApiController@release_task');
        Route::middleware('auth:api')->post('/v1/dtu_save_sms', 'RpaApiController@dtu_save_sms');
        Route::middleware('auth:api')->post('/v1/dtu_get_sms', 'RpaApiController@dtu_get_sms');
        Route::middleware('auth:api')->post('/v1/investor_password', 'RpaApiController@investor_password');
        Route::middleware('auth:api')->post('/v1/get_customer_info', 'RpaApiController@get_customer_info');
        Route::middleware('auth:api')->post('/v1/get_customer_by_khh', 'RpaApiController@get_customer_by_khh');
        Route::middleware('auth:api')->post('/v1/get_customer_kyzj', 'RpaApiController@get_customer_kyzj');
        Route::middleware('auth:api')->post('/v1/gg_sms', 'RpaApiController@gg_sms');
        Route::middleware('auth:api')->post('/v1/flow', 'RpaApiController@flow');
        Route::middleware('auth:api')->post('/v1/gw_fee', 'RpaApiController@gw_fee');
        Route::middleware('auth:api')->post('/v1/monitor_kh', 'RpaApiController@monitor_kh');
        Route::middleware('auth:api')->post('/v1/crm_connection', 'RpaApiController@crm_connection');
        Route::middleware('auth:api')->post('/v1/simulation', 'RpaApiController@simulation');
        Route::middleware('auth:api')->post('/v1/simulation_open', 'RpaApiController@simulation_open');
//        官网
        Route::post('/v1/release_task2', 'OfficialApiController@release_task2');
        Route::post('/v1/get_trading_flow', 'OfficialApiController@get_trading_flow');
        Route::post('/v1/get_trading_flow_test', 'OfficialApiController@get_trading_flow_test');
        Route::post('/v1/release_task2_result', 'OfficialApiController@release_task2_result');
        Route::post('/v1/get_mediator_by_number', 'OfficialApiController@get_mediator_by_number');
        Route::post('/v1/get_profession', 'OfficialApiController@get_profession');
        Route::post('/v1/change_profession', 'OfficialApiController@change_profession');
        Route::post('/v1/get_customer_img', 'OfficialApiController@get_customer_img');
//        插件相关
        Route::middleware('auth:api')->post('/v1/oa_flow_save', 'PluginApiController@oa_flow_save');
        Route::middleware('auth:api')->post('/v1/oa_get_sign', 'PluginApiController@oa_get_sign');
        Route::middleware('auth:api')->post('/v1/crm_flow_save', 'PluginApiController@crm_flow_save');
        Route::middleware('auth:api')->post('/v1/crm_get_sign', 'PluginApiController@crm_get_sign');
        Route::middleware('auth:api')->post('/v1/credit', 'PluginApiController@credit');
        Route::middleware('auth:api')->post('/v1/save_customer_info', 'PluginApiController@save_customer_info');
        Route::middleware('auth:api')->post('/v1/customer_review', 'PluginApiController@customer_review');
        Route::middleware('auth:api')->post('/v1/open_history', 'PluginApiController@open_history');
        Route::middleware('auth:api')->post('/v1/mediator_info', 'PluginApiController@mediator_info');
        Route::middleware('auth:api')->post('/v1/mediator_info2', 'PluginApiController@mediator_info2');
        Route::post('/v1/fxq', 'PluginApiController@fxq');
        Route::middleware('auth:api')->post('/v1/sync_data', 'PluginApiController@sync_data');
        Route::middleware('auth:api')->post('/v1/get_entry_by_pihao', 'PluginApiController@get_entry_by_pihao');
        Route::middleware('auth:api')->post('/v1/offline_training_records', 'PluginApiController@offline_training_records');

//        其他
        Route::middleware('auth:api')->post('/v1/mediator_flow', 'OtherApiController@mediator_flow');
        Route::middleware('auth:api')->post('/v1/get_customer_relation', 'OtherApiController@get_customer_relation');
        Route::middleware('auth:api')->post('/v1/get_mediator_relation', 'OtherApiController@get_mediator_relation');
        Route::middleware('auth:api')->post('/v1/test', 'OtherApiController@test');

//        线下视频上传
        Route::post('/v1/login', 'VideoCollectApiController@login');
        Route::post('/v1/customer', 'VideoCollectApiController@customer');
        Route::post('/v1/upload', 'VideoCollectApiController@upload');
        Route::post('/v1/history', 'VideoCollectApiController@history');
        Route::post('/v1/getRemark', 'VideoCollectApiController@getRemark');

//        账户分析
        Route::middleware('auth:api')->post('/v1/get_customer_jyls', 'TradeApiController@get_customer_jyls');
        Route::middleware('auth:api')->post('/v1/get_jyr', 'TradeApiController@get_jyr');
        Route::middleware('auth:api')->post('/v1/get_code_table', 'TradeApiController@get_code_table');
        Route::middleware('auth:api')->post('/v1/get_trade_version', 'TradeApiController@get_trade_version');
        Route::middleware('auth:api')->post('/v1/loginRecord', 'TradeApiController@loginRecord');
        Route::middleware('auth:api')->post('/v1/update_version', 'TradeApiController@update_version');

        //  V2  
        Route::group(['namespace' => 'v2'], function(){
            Route::middleware('auth:api')->post('/v2/credit', 'PluginApiController@credit');
        });
    });
//   掌上营业厅