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
        // Route::middleware('auth:api')->post('/v1/test_notice', 'NoticeApiController@test_notice');
        Route::middleware('auth:api')->post('/v1/task_notice', 'NoticeApiController@task_notice');
        Route::middleware('auth:api')->post('/v1/message', 'NoticeApiController@message');
        Route::middleware('auth:api')->post('/v1/sms_tpl', 'NoticeApiController@sms_tpl');
        Route::middleware('auth:api')->post('/v1/tpl_send', 'NoticeApiController@tpl_send');
        Route::middleware('auth:api')->post('/v1/code_distinguish', 'NoticeApiController@code_distinguish');
        Route::middleware('auth:api')->post('/v1/edit_password', 'CommonApiController@edit_password');

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
        Route::middleware('auth:api')->post('/v1/get_mediator_by_account', 'RpaApiController@get_mediator_by_account');
        Route::middleware('auth:api')->post('/v1/getUnlineMediatorFile', 'RpaApiController@getUnlineMediatorFile');
        Route::middleware('auth:api')->post('/v1/getSecondFinance', 'RpaApiController@getSecondFinance');
        Route::middleware('auth:api')->post('/v1/saveJkzxImage', 'RpaApiController@saveJkzxImage');

//        官网
        Route::post('/v1/release_task2', 'OfficialApiController@release_task2');
        Route::post('/v1/get_trading_flow', 'OfficialApiController@get_trading_flow');
        Route::post('/v1/get_trading_flow_test', 'OfficialApiController@get_trading_flow_test');
        Route::post('/v1/release_task2_result', 'OfficialApiController@release_task2_result');
        Route::post('/v1/get_mediator_by_number', 'OfficialApiController@get_mediator_by_number');
        Route::post('/v1/get_profession', 'OfficialApiController@get_profession');
        Route::post('/v1/change_profession', 'OfficialApiController@change_profession');
        Route::post('/v1/get_customer_img', 'OfficialApiController@get_customer_img');
        
        Route::post('/v1/contract_cost_change_remind', 'RpaApiController@contract_cost_change_remind');

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
        Route::middleware('auth:api')->post('/v1/sync_data2', 'PluginApiController@sync_data2');
        Route::middleware('auth:api')->post('/v1/get_entry_by_pihao', 'PluginApiController@get_entry_by_pihao');
        Route::middleware('auth:api')->post('/v1/offline_training_records', 'PluginApiController@offline_training_records');
        Route::middleware('auth:api')->post('/v1/getCustomerFrom', 'PluginApiController@getCustomerFrom');
        Route::middleware('auth:api')->post('/v1/checkRelations', 'PluginApiController@checkRelations');
//        其他
        Route::middleware('auth:api')->post('/v1/get_customer_relation', 'OtherApiController@get_customer_relation');
        Route::middleware('auth:api')->post('/v1/get_mediator_relation', 'OtherApiController@get_mediator_relation');
        Route::middleware('auth:api')->post('/v1/test', 'OtherApiController@test');
        Route::middleware('auth:api')->post('/v1/check_mediator', 'OtherApiController@check_mediator');

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
            Route::middleware('auth:api')->post('/v2/credit2', 'PluginApiController@credit2');
            Route::middleware('auth:api')->post('/v2/setPreOpenAccount', 'PluginApiController@setPreOpenAccount');
            Route::middleware('auth:api')->post('/v2/getPreOpenAccount', 'PluginApiController@getPreOpenAccount');
            Route::middleware('auth:api')->post('/v2/sync_data', 'PluginApiController@sync_data');
            Route::middleware('auth:api')->post('/v2/sync_data2', 'PluginApiController@sync_data2');
            Route::middleware('auth:api')->post('/v2/save_customer_info', 'PluginApiController@save_customer_info');
        });
        
        //开户客户列表同步
        Route::get('/v1/syncCustomerByDay', 'SyncCustomerController@syncCustomerByDay');
        Route::get('/v1/syncHistoryCustomer', 'SyncCustomerController@syncHistoryCustomer');
        Route::get('/v1/syncOfflineCustomer', 'SyncCustomerController@syncOfflineCustomer');
        //同步CRM单客户信息
        Route::get('/v1/syncCrmCustomer', 'SyncCustomerController@syncCrmCustomer');
        //同步指定客户关系数据
        Route::post('/v1/syncAppointCustomer', 'SyncCustomerController@syncAppointCustomer');
    });
//    居间
    Route::group(['namespace' => 'Mediator'], function(){
        Route::post('/v1/mediator_flow', 'MediatorApiController@mediator_flow');
        Route::post('/v1/testXy', 'MediatorApiController@getXy');
        Route::post('/v1/mediator_data', 'DataSyncController@mediator_data');
        
    });
//   掌上营业厅
    //客户回访
    Route::group(['namespace' => 'zt'], function () {
        Route::post('/zt/sendCode', 'LoginController@sendCode');
        Route::get('/zt/sendCode', 'LoginController@sendCode');
        Route::post('/zt/doLogin', 'LoginController@doLogin');
        Route::post('/zt/test', 'LoginController@test');
    });

    Route::group(['namespace' => 'Revisit\customer', ['middleware' => 'auth:api']], function () {
        Route::post('/customer/getRevisitList', 'CustomerRevisitController@getRevisitList');
        Route::post('/customer/getDetail', 'CustomerRevisitController@getCustomerDetail');
        Route::post('/customer/tables', 'CustomerRevisitController@getTables');
        Route::post('/customer/mark', 'CustomerRevisitController@mark');
        Route::post('/customer/uploadRecords', 'CustomerRevisitController@uploadRecords');
    });

    //定时任务
    Route::group(['namespace' => 'TimeTasks'], function(){
        Route::get('/v1/syncTrainingDuration', 'IndexController@syncTrainingDuration');
        Route::post('/v1/mediatorTask', 'IndexController@mediatorTask');
        Route::get('/v1/syncCancelMediator', 'IndexController@syncCancelMediator');
        Route::get('/v1/completeOpenCustomer', 'CustomerController@completeOpenCustomer');
        Route::get('/v1/test', 'CustomerController@test');
        Route::post('/v1/getLoseCreditByIdCard', 'MediatorController@getLoseCreditByIdCard');
        Route::get('/v1/checkMysqlSyncStatus', 'ServerController@checkMysqlSyncStatus');

        Route::get('/v1/syncMediatorTrainingDurationToRpa', 'MediatorController@syncMediatorTrainingDurationToRpa');
    });
