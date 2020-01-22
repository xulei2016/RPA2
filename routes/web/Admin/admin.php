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

/**
 * resource
 * GET	/photos	index	photos.index
 * GET	/photos/create	create	photos.create
 * POST	/photos	store	photos.store
 * GET	/photos/{photo}	show	photos.show
 * GET	/photos/{photo}/edit	edit	photos.edit
 * PUT/PATCH	/photos/{photo}	update	photos.update
 * DELETE	/photos/{photo}     destory     photos.destory
 */


// 后台路由管理
Route::group(['middleware' => ['auth.admin:admin','web'], ], function(){
    
    //错误日志
    Route::get('/sys_error_logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('permission:sys_error_logs');

    //Base
    Route::group(['namespace' => 'Base'], function(){
        //Bugs
        Route::resource('/Bugs', 'BugsController');

        //意见
        Route::resource('/Improvement', 'ImprovementController');

        // 首页
        Route::get('/', 'SysController@index');
        Route::get('/index', 'SysController@index')->name('index');
        Route::get('/closeAlert/{id}', 'SysController@closeAlert');
        Route::post('/index', 'SysController@index');
        Route::post('/clearCache', 'SysController@clearCache');
        Route::post('/dashboard', 'SysController@get_index');

        //导航管理
        Route::group(['middleware' => ['permission:sys_nav']], function () {
            //菜单
            Route::get('/sys_icon', 'MenuController@sys_icon')->middleware('permission:sys_icon');
            Route::resource('/sys_menu', 'MenuController')->middleware('permission:sys_menu');
            Route::post('/sys_menu/order', 'MenuController@orderUpdate');
        });
        //版本更新查看单个
        Route::get('/sys_version_update/{id}', 'VersionUpdateController@show');

        //控制面板
        Route::group(['middleware' => ['permission:sys_board']], function () {
            //用户管理
            Route::group(['middleware' => ['permission:sys_admin']], function () {
                Route::get('/sys_admin/getById', 'AdminController@getById');
                Route::get('/sys_admin/export', 'AdminController@export')->middleware('permission:sys_admin_export');
                Route::get('/sys_admin/list', 'AdminController@pagenation');
                Route::get('/sys_admin', 'AdminController@index');
                Route::get('/sys_admin/{id}', 'AdminController@show');
                Route::get('/sys_admin/create', 'AdminController@create')->middleware('permission:sys_admin_add');
                Route::post('/sys_admin', 'AdminController@store')->middleware('permission:sys_admin_add');
                Route::get('/sys_admin/{id}/edit', 'AdminController@edit')->middleware('permission:sys_admin_edit');
                Route::patch('/sys_admin/{id}', 'AdminController@update')->middleware('permission:sys_admin_edit');
                Route::post('/sys_admin/clearCount', 'AdminController@clearCount')->middleware('permission:sys_admin_edit');
                Route::delete('/sys_admin/{id}', 'AdminController@destroy')->middleware('permission:sys_admin_delete');
            });

            //角色
            Route::group(['middleware' => ['permission:sys_role']], function () {
                Route::get('/sys_role/export', 'RoleController@export')->middleware('permission:sys_role_export');;
                Route::get('/sys_role/list', 'RoleController@pagenation');
                Route::get('/sys_role/{id}/getPermission', 'RoleController@getPermission');
                Route::post('/sys_role/{id}/getCheckPermission', 'RoleController@getCheckPermission');
                Route::post('/sys_role/{id}/roleHasPermission', 'RoleController@roleHasPermission');
                Route::resource('/sys_role', 'RoleController');
            });

            //权限模型
            Route::group(['middleware' => ['permission:sys_permission']], function () {
                Route::post('/sys_permission/getTree', 'PermissionController@getTree');
                Route::post('/sys_permission/order', 'PermissionController@orderUpdate');
                Route::resource('/sys_permission', 'PermissionController');
            });
            //系统设置
            Route::get('sys_config', 'SysController@setting')->middleware('permission:sys_config');
            Route::post('sys_config_update', 'SysController@update_config');
            //版本更新
            Route::group(['middleware' => ['permission:sys_version_update']], function () {
                Route::get('/sys_version_update', 'VersionUpdateController@index');
                Route::get('/sys_version_update/list', 'VersionUpdateController@pagination');
                Route::resource('/sys_version_update', 'VersionUpdateController');
            });
        });
            //统计中心
            Route::group([ 'namespace' => 'Chart'], function(){
            Route::post('/sys_chart/disk', 'ChartController@disk');
            Route::post('/sys_chart/footprint', 'ChartController@footprint');
        });
        //通知中心
        Route::group(['middleware' => ['permission:sys_notice']], function () {
            //通知列表
            Route::get('/sys_message_list', 'MessageController@index')->middleware('permission:sys_message_list');
            Route::get('/sys_message_list/view/{nid}', 'MessageController@view');
            Route::get('/sys_message_list/message_list', 'MessageController@pagination');
            Route::get('/sys_message_send', 'MessageController@sendMessage')->middleware('permission:sys_message_send');
            Route::post('/sys_message_list/send', 'MessageController@send');
            //历史通知
            Route::get('/sys_message_history', 'MessageController@history_list')->middleware('permission:sys_message_history');
            Route::get('/sys_message_history/view/{id}', 'MessageController@history_view');
            Route::get('/sys_message_history/message_list', 'MessageController@history_pagination');
        });
        Route::group(['middleware' => ['permission:sys_system']], function () {
            //个人中心
            Route::group(['middleware' => ['permission:sys_profile']], function () {
                Route::get('/sys_profile', 'AdminController@userCenter');
                Route::post('/sys_profile', 'AdminController@updateUser');
                Route::post('/sys_profile_head_img', 'AdminController@updateUser');
            });
            //短信记录
            Route::get('/sys_sms', 'MessageController@sms_list');
            Route::get('/sys_sms/list', 'MessageController@sms_pagination');
            //邮件管理
            Route::group(['middleware' => ['permission:sys_mail']], function () {
                Route::get('/sys_mail/list', 'MailController@pagenation');
                Route::get('/sys_mail/export', 'MailController@export');
                Route::get('/sys_mail/create', 'MailController@create')->middleware('permission:sys_mail_create');
                Route::post('/sys_mail/send', 'MailController@send');
                Route::post('/sys_mail/draft', 'MailController@draft');
                Route::post('/sys_mail/reSend', 'MailController@reSend');
                Route::resource('/sys_mail', 'MailController', ['except' => ['create', 'update']]);
            });
            //操作日志
            Route::group(['middleware' => ['permission:sys_logs']], function () {
                Route::get('/sys_logs/list', 'logController@pagenation');
                Route::get('/sys_logs/export', 'logController@export')->middleware('permission:sys_logs_export');
                Route::get('/sys_logs', 'logController@index');
                Route::get('/sys_logs/{id}', 'logController@show');
                Route::delete('/sys_logs/{id}', 'logController@destroy')->middleware('permission:sys_logs_delete');
            });
            //错误日志
        });
        //文档中心
        Route::group(['namespace' => 'Document','middleware' => ['permission:sys_document']], function () {
            Route::get("/sys_document/getAllMenus",'DocumentController@getAllMenus');
            Route::get("/sys_document/getDoc/{id}",'DocumentController@getDoc');
            Route::post("/sys_document/uploadFile",'DocumentController@uploadFiles');
            Route::post("/sys_document/{id}/deleteDoc",'DocumentController@deleteDoc');
            Route::resource('/sys_document', 'DocumentController');
        });
        //api插件
        Route::group(['middleware' => ['permission:sys_api_config']], function () {
            Route::get('/sys_api/list', 'ApiController@pagination');
            Route::resource('/sys_api', 'ApiController');
        });

        //公司组织架构
        Route::group(['namespace' => 'Organization','middleware' => ['permission:sys_dept']], function () {
            //部门
            Route::group([], function () {
                Route::get('/sys_dept/detail', 'DeptController@detail');
                Route::get('/sys_dept/getDetailById', 'DeptController@getDetailById');
                Route::get('/sys_dept/getMenus', 'DeptController@getMenus');
                Route::get('/sys_dept/searchAdmins', 'DeptController@searchAdmins');
                Route::resource('/sys_dept', 'DeptController');
            });
            //岗位
            Route::group([], function (){
                Route::post('/sys_post/getPostsByDeptId', 'PostController@getPostsByDeptId');
                Route::resource('/sys_post', 'PostController');
            });
            //部门岗位中间表
            Route::group([], function (){
                Route::post('/sys_dept_post_relation/getByDeptId', 'DeptPostRelationController@getByDeptId');
                Route::resource('/sys_dept_post_relation', 'DeptPostRelationController');
            });
        });


    });
    //RPA 任务中心
    Route::group(['namespace' => 'Rpa'], function(){
        //rpa 任务管理中心
        Route::group(['middleware' => ['permission:rpa_center']], function () {
            //任务队列
            Route::get('/rpa_center/queue','RpaController@queue');
            Route::get('/rpa_center/editQueue/{id}','RpaController@editQueue');
            Route::post('/rpa_center/updateQueue','RpaController@updateQueue');
            Route::post('/rpa_center/deleteQueue','RpaController@deleteQueue');
            Route::get('/rpa_center/rpa_queueList', 'RpaController@queuePagination');
            //任务总览
            Route::get('/rpa_center/taskList','RpaController@taskList');
            Route::post('/rpa_center/immedtask','RpaController@immedtasks');
            Route::get('/rpa_center/rpa_taskList', 'RpaController@taskPagination');
            //任务管理中心
            Route::get('/rpa_center/list', 'RpaController@pagenation');
            Route::post('/rpa_center/getAccepter', 'RpaController@getAccepter');
            Route::resource('/rpa_center', 'RpaController');
        });
        //朝闻天下
        Route::group(['middleware' => ['permission:rpa_news']], function () {
            Route::get('/rpa_news/list', 'NewsController@pagenation');
            Route::get('/rpa_news/immedtasks/{id?}', 'NewsController@immedtasks');
            Route::post('/rpa_news/insertImmedtasks', 'NewsController@insertImmedtasks');
            Route::resource('/rpa_news', 'NewsController');
        });
        //失信查询
        Route::group(['middleware' => ['permission:rpa_discredit']], function () {
            Route::get('/rpa_discredit/list', 'discreditController@pagenation');
            Route::get('/rpa_discredit/immedtasks/{id?}', 'discreditController@immedtasks');
            Route::post('/rpa_discredit/insertImmedtasks', 'discreditController@insertImmedtasks');
            Route::resource('/rpa_discredit', 'discreditController');
        });
        //投资者密码
        Route::group(['middleware' => ['permission:rpa_investorPWD']], function () {
            Route::get('/rpa_investorPWD/list', 'investorPWDController@pagenation');
            Route::get('/rpa_investorPWD/immedtasks/{id?}', 'investorPWDController@immedtasks');
            Route::post('/rpa_investorPWD/insertImmedtasks', 'investorPWDController@insertImmedtasks');
            Route::resource('/rpa_investorPWD', 'investorPWDController');
        });
        //客户分组
        Route::group(['middleware' => ['permission:rpa_customerGrouping']], function () {
            Route::get('/rpa_customerGrouping/list', 'customerGroupingController@pagenation');
            Route::get('/rpa_customerGrouping/immedtasks/{id?}', 'customerGroupingController@immedtasks');
            Route::post('/rpa_customerGrouping/insertImmedtasks', 'customerGroupingController@insertImmedtasks');
            Route::resource('/rpa_customerGrouping', 'customerGroupingController');
        });
        //居间人影像
        Route::group(['middleware' => ['permission:rpa_jjr_image']], function () {
            Route::get('/rpa_jjr_image/list', 'JJRImageController@pagenation');
            Route::get('/rpa_jjr_image/immedtasks/{id?}', 'JJRImageController@immedtasks');
            Route::post('/rpa_jjr_image/insertImmedtasks', 'JJRImageController@insertImmedtasks');
            Route::resource('/rpa_jjr_image', 'JJRImageController');
        });
        //居间人回访分配
        Route::group(['middleware' => ['permission:rpa_jjr_distribution']], function () {
            Route::get('/rpa_jjr_distribution/list', 'JJRVisController@pagenation');
            Route::get('/rpa_jjr_distribution/immedtasks/{id?}', 'JJRVisController@immedtasks');
            Route::post('/rpa_jjr_distribution/insertImmedtasks', 'JJRVisController@insertImmedtasks');
            Route::resource('/rpa_jjr_distribution', 'JJRVisController');
        });
        //开户云回访分配
        Route::group(['middleware' => ['permission:rpa_khy_distribution']], function () {
            Route::get('/rpa_khy_distribution/list', 'KHYVisController@pagenation');
            Route::get('/rpa_khy_distribution/immedtasks/{id?}', 'KHYVisController@immedtasks');
            Route::post('/rpa_khy_distribution/insertImmedtasks', 'KHYVisController@insertImmedtasks');
            Route::resource('/rpa_khy_distribution', 'KHYVisController');
        });
        //问卷录入
        Route::group(['middleware' => ['permission:rpa_questionnaire']], function () {
            Route::get('/rpa_questionnaire/list', 'QuestionnaireController@pagenation');
            Route::get('/rpa_questionnaire/immedtasks/{id?}', 'QuestionnaireController@immedtasks');
            Route::post('/rpa_questionnaire/insertImmedtasks', 'QuestionnaireController@insertImmedtasks');
            Route::resource('/rpa_questionnaire', 'QuestionnaireController');
        });
        //客户开户视频收集
        Route::group(['middleware' => ['permission:rpa_rtc_collect']], function () {
            Route::get('/rpa_rtc_collect/list', 'RTCController@pagenation');
            Route::get('/rpa_rtc_collect/immedtasks/{id?}', 'RTCController@immedtasks');
            Route::post('/rpa_rtc_collect/insertImmedtasks', 'RTCController@insertImmedtasks');
            Route::resource('/rpa_rtc_collect', 'RTCController');
        });
        //官网手续费
        Route::group(['middleware' => ['permission:rpa_SettlementFee']], function () {
            Route::get('/rpa_SettlementFee/list', 'SettlementFeeController@pagenation');
            Route::get('/rpa_SettlementFee/immedtasks/{id?}', 'SettlementFeeController@immedtasks');
            Route::post('/rpa_SettlementFee/insertImmedtasks', 'SettlementFeeController@insertImmedtasks');
            Route::resource('/rpa_SettlementFee', 'SettlementFeeController');
        });
        //客户资金查询任务
        Route::group(['middleware' => ['permission:rpa_oabreminding']], function () {
            Route::get('/rpa_oabreminding/list', 'OabremindingController@pagenation');
            Route::get('/rpa_oabreminding/immedtasks/{id?}', 'OabremindingController@immedtasks');
            Route::post('/rpa_oabreminding/insertImmedtasks', 'OabremindingController@insertImmedtasks');
            Route::resource('/rpa_oabreminding', 'OabremindingController');
        });
        //客户PDF下载
        Route::group(['middleware' => ['permission:rpa_downloadPDF']], function () {
            Route::get('/rpa_downloadPDF/list', 'downloadPDFController@pagenation');
            Route::get('/rpa_downloadPDF/immedtasks/{id?}', 'downloadPDFController@immedtasks');
            Route::post('/rpa_downloadPDF/insertImmedtasks', 'downloadPDFController@insertImmedtasks');
            Route::resource('/rpa_downloadPDF', 'downloadPDFController');
        });
        //职业变更
        Route::group(['middleware' => ['permission:rpa_profession_change_task']], function () {
            Route::get('/rpa_profession_change_task/list', 'ProfessionChangeController@pagenation');
            Route::get('/rpa_profession_change_task/immedtasks/{id?}', 'ProfessionChangeController@immedtasks');
            Route::post('/rpa_profession_change_task/insertImmedtasks', 'ProfessionChangeController@insertImmedtasks');
            Route::resource('/rpa_profession_change_task', 'ProfessionChangeController');
        });
        //rpa任务运行日志
        Route::get('/rpa_logs/log','StatisticsController@pagination');
        Route::get('/rpa_logs/show/{id}','StatisticsController@show');
        Route::get('/rpa_logs','StatisticsController@rpa_log');
        //数据统计
        Route::post('/rpa_statistics/getData','StatisticsController@getData');
        Route::get('/rpa_statistics','StatisticsController@index');
    });
    //RPA 功能中心
    Route::group(['namespace' => 'Func'],function(){
        //居间人回访
        Route::group(['middleware' => ['permission:rpa_jjr_records']], function () {
            Route::get('/rpa_jjr_records/edit/{id}', 'JJRVisFuncController@edit');
            Route::patch('/rpa_jjr_records/{id}', 'JJRVisFuncController@update');
            Route::post('/rpa_jjr_records/typeChange', 'JJRVisFuncController@typeChange');
            Route::get('/rpa_jjr_records/rpa_list', 'JJRVisFuncController@JJRpagination');
            Route::get('/rpa_jjr_records', 'JJRVisFuncController@index');
        });

        //开户云回访
        Route::group(['middleware' => ['permission:rpa_cloud_distribution']], function () {
            Route::get('/rpa_cloud_distribution/edit/{id}', 'ReviewtableController@edit');
            Route::patch('/rpa_cloud_distribution/{id}', 'ReviewtableController@update');
            Route::get('/rpa_cloud_distribution/rpa_list', 'ReviewtableController@JJRpagination');
            Route::get('/rpa_cloud_distribution', 'ReviewtableController@index');
            Route::get('/rpa_cloud_distribution/report', 'ReviewtableController@report');
            Route::get('/rpa_cloud_distribution/report_list', 'ReviewtableController@reportpagination');
        });

        //客户资金查询
        Route::group(['middleware' => ['permission:rpa_customer_funds_search']], function () {
            //客户
            Route::get('/rpa_customer_funds_search', 'OabremindingFuncController@oabIndex');
            Route::get('/rpa_customer_funds_search/add','OabremindingFuncController@oabAdd');
            Route::post('/rpa_customer_funds_search/insert','OabremindingFuncController@oabInsert');
            Route::post('/rpa_customer_funds_search/delete','OabremindingFuncController@oabDelete');
            Route::post('/rpa_customer_funds_search/typeChange', 'OabremindingFuncController@oabTypeChange');
            Route::get('/rpa_customer_funds_search/rpa_list', 'OabremindingFuncController@oabPagination');
            Route::post('/rpa_customer_funds_search/getCustomerName', 'OabremindingFuncController@getCustomerName');

            //品种
            Route::get('/rpa_customer_funds_search/varietyset','OabremindingFuncController@varietyList');
            Route::get('/rpa_customer_funds_search/varietyadd','OabremindingFuncController@varietyAdd');
            Route::get('/rpa_customer_funds_search/varietyedit/{id}','OabremindingFuncController@varietyEdit');
            Route::post('/rpa_customer_funds_search/varietyinsert','OabremindingFuncController@varietyInsert');
            Route::post('/rpa_customer_funds_search/varietyupdate','OabremindingFuncController@varietyUpdate');
            Route::post('/rpa_customer_funds_search/varietydelete','OabremindingFuncController@varietyDelete');
            Route::get('/rpa_customer_funds_search/varietyList','OabremindingFuncController@varietyPagination');
        });

        //棉花仓单
        Route::group(['middleware' => ['permission:rpa_cotton']], function () {
            Route::get('/rpa_cotton','CottonController@index');
            Route::get('/rpa_cotton/rpa_list', 'CottonController@pagination');
            Route::get('/rpa_cotton/add', 'CottonController@add');
            Route::post('/rpa_cotton/adddata', 'CottonController@adddata');
            Route::post('/rpa_cotton/isanalysis', 'CottonController@isanalysis');
            Route::post('/rpa_cotton/checkdata', 'CottonController@checkdata');
            Route::any('/rpa_cotton/download/{id?}', 'CottonController@download');
            Route::post('/rpa_cotton/delete', 'CottonController@delete');
            Route::get('/rpa_cotton/detail/{id}', 'CottonController@detail');
            Route::post('/rpa_cotton/save', 'CottonController@save');
            Route::post('/rpa_cotton/changePack', 'CottonController@changePack');
            Route::post('/rpa_cotton/immedtask', 'CottonController@immedtask');
            Route::get('/rpa_cotton/official', 'CottonController@official');
            Route::get('/rpa_cotton/official_detail/{id}', 'CottonController@official_detail');
        });

        //仿真开户 rpa_simulation_account_business
        Route::group(['middleware' => ['permission:rpa_simulation_account_business']], function () {
            Route::get("/rpa_simulation_account_business/list", 'SimulationController@pagination');
            Route::get("/rpa_simulation_account_business", 'SimulationController@index');
            Route::get("/rpa_simulation_account_business/export", 'SimulationController@export');
            Route::post("/rpa_simulation_account_business/ctp", 'SimulationController@ctp');
            Route::post("/rpa_simulation_account_business/setZjzh", 'SimulationController@setZjzh');
        });

        //开户证件识别 rpa_cloud_recognition
        Route::group(['middleware' => ['permission:rpa_cloud_recognition']], function () {
            Route::get("/rpa_cloud_recognition/list", 'CloudRecignitionController@pagination');
            Route::get("/rpa_cloud_recognition/export", 'CloudRecignitionController@export');
            Route::resource("/rpa_cloud_recognition", 'CloudRecignitionController');
        });

        //开户客户信息
        Route::group(['middleware' => ['permission:rpa_customer']], function () {
            Route::get('/rpa_customer','CustomerController@index');
            Route::get("/rpa_customer/export", 'CustomerController@export');
            Route::get('/rpa_customer/add', 'CustomerController@add')->middleware('permission:rpa_customer_add');
            Route::post('/rpa_customer/adddata', 'CustomerController@adddata')->middleware('permission:rpa_customer_add');
            Route::get('/rpa_customer/edit/{id}', 'CustomerController@edit');
            Route::patch('/rpa_customer/editdata/{id}', 'CustomerController@editdata');
            Route::get('/rpa_customer/list', 'CustomerController@pagination');
            Route::post('/rpa_customer/delete','CustomerController@delete')->middleware('permission:rpa_customer_delete');
        });

        //线下客户档案收集
        Route::group([], function () {
            Route::post("/rpa_archives/selectVideo","ArchivesController@selectVideo");
            Route::get("/rpa_archives/list","ArchivesController@pagenation");
            Route::post("/rpa_archives/credit","ArchivesController@credit");
            Route::post("/rpa_archives/selectSdxLevel","ArchivesController@selectSdxLevel");
            Route::post("/rpa_archives/selectZJZH","ArchivesController@selectZJZH");
            Route::post("/rpa_archives/uploadEnclosure","ArchivesController@uploadEnclosure");
            Route::post("/rpa_archives/uploadAudio","ArchivesController@uploadAudio");
            Route::post("/rpa_archives/changeStep","ArchivesController@changeStep");
            Route::resource("/rpa_archives", 'ArchivesController');
        });

        //线下客户视频收集
        Route::group([], function () {
            Route::get('/rpa_customer_video_collect/export', 'VideoCollectController@export');
            Route::get('/rpa_customer_video_collect/list', 'VideoCollectController@pagenation');
            Route::resource('/rpa_customer_video_collect', 'VideoCollectController');
        });

        //监控中心密码
        Route::group(['middleware' => ['permission:rpa_jkzxPwd']], function () {
            Route::get('/rpa_jkzxPwd/send/{id}', 'JKZXPwdController@send');
            Route::get('/rpa_jkzxPwd/resend/{id}', 'JKZXPwdController@resend');
            Route::post('/rpa_jkzxPwd/senddata', 'JKZXPwdController@senddata');
            Route::post('/rpa_jkzxPwd/yjsend', 'JKZXPwdController@yjsend');
            Route::get('/rpa_jkzxPwd/list', 'JKZXPwdController@pagination');
            Route::resource("/rpa_jkzxPwd", 'JKZXPwdController');
        });

        //身份证地址识别上报
        Route::group(['middleware' => ['permission:rpa_address_recognition']], function () {
            Route::get('/rpa_address_recognition/list', 'RecognitionController@pagination');
            Route::get('/rpa_address_recognition/review/{id}', 'RecognitionController@review');
            Route::patch('/rpa_address_recognition/reviewdata/{id}', 'RecognitionController@reviewdata');
            Route::get("/rpa_address_recognition/export", 'RecognitionController@export');
            Route::resource("/rpa_address_recognition", 'RecognitionController');
        });

        /**rpa_customer
         * 插件下载
         */
        Route::group([], function(){
            Route::get('/rpa_download_plugin', 'DownloadPluginController@index');
            Route::get('/rpa_download_plugin/document', 'DownloadPluginController@document');
            Route::post('/rpa_download_plugin/apply', 'DownloadPluginController@apply');
            Route::get('/rpa_download_plugin/download/{id}', 'DownloadPluginController@download');
            Route::resource('/rpa_download_plugin', 'DownloadPluginController');
        });

        //插件管理
        Route::group(['namespace' => 'Plugin','middleware' => ['permission:plugin']], function(){

            // 插件管理
            Route::group(['middleware' => ['permission:rpa_plugin']], function(){
                Route::get("/rpa_plugin/list", 'PluginController@pagination');
                Route::get("/rpa_plugin/export", 'PluginController@export');
                Route::resource("/rpa_plugin", 'PluginController');
            });
            //插件申请
            Route::group(['middleware' => ['permission:rpa_plugin_apply']], function(){
                Route::get("/rpa_plugin_apply/list", 'ApplyController@pagination');
                Route::resource("/rpa_plugin_apply", 'ApplyController');
                Route::post("/rpa_plugin_apply/confirm", 'ApplyController@confirm');
            });    
            // 版本管理
            Route::group(['middleware' => ['permission:rpa_plugin_version']], function(){
                Route::get("/rpa_plugin_version/list", 'VersionController@pagination');
                Route::get("/rpa_plugin_version/plugin/{id}", 'VersionController@getByPlugin');
                Route::get("/rpa_plugin_version/export", 'VersionController@export');
                Route::post("/rpa_plugin_version/upload", 'VersionController@upload');
                Route::resource("/rpa_plugin_version", 'VersionController');
            });
                //插件下载
                Route::group([], function(){
                Route::get("/rpa_plugin_download/list", 'DownloadController@pagination');
                Route::get("/rpa_plugin_download", 'DownloadController@index');
            });

        });

        //手续费和保证金
        Route::group([], function () {
            Route::get('/rpa_bzj', 'BzjController@index');
            Route::get('/rpa_bzj/list', 'BzjController@pagination');
            Route::get('/rpa_sxf', 'SxfController@index');
            Route::get('/rpa_sxf/list', 'SxfController@pagination');
        });

        //职业变更
        Route::get('/rpa_profession_change','ProfessionChangeController@index');
        Route::get('/rpa_profession_change/list','ProfessionChangeController@pagination');
        Route::get('/rpa_profession_change/export','ProfessionChangeController@export');
        Route::post('/rpa_profession_change/confirmOne','ProfessionChangeController@confirmOne');

        //风险指标
        // Route::group(['middleware' => ['permission:rpa_risk_center']], function () {    
        //     Route::get('/rpa_risk','FuncRiskDegreeController@index');
        //     Route::get('/rpa_risk/list','FuncRiskDegreeController@pagination');
        //     Route::get('/rpa_risk/export','FuncRiskDegreeController@export');
        //     Route::post('/rpa_risk/getData','FuncRiskDegreeController@getData');
        //     Route::get('/rpa_risk/getQueryDay','FuncRiskDegreeController@getQueryDay');
        // });

        //合约提醒
        Route::group(['middleware' => ['permission:rpa_contract_detail'], 'namespace' => 'Contract'], function () {
            //交易所管理
            Route::get('/rpa_contract_jys/list','JysController@pagination');
            Route::resource("/rpa_contract_jys", 'JysController');
            //接收者管理
            Route::get('/rpa_contract_receiver/list','ReceiverController@pagination');
            Route::post('/rpa_contract_receiver/email','ReceiverController@email');
            Route::resource("/rpa_contract_receiver", 'ReceiverController');
            //品种管理
            Route::get('/rpa_contract_pz/list','PzController@pagination');
            Route::get('/rpa_contract_pz/getByJys','PzController@getByJys');
            Route::resource("/rpa_contract_pz", 'PzController');
            //合约详细
            Route::get('/rpa_contract_detail/list','DetailController@pagination');
            Route::resource("/rpa_contract_detail", 'DetailController');
            Route::post('/rpa_contract_detail/updateAll','DetailController@updateAll');
            //配置信息
            Route::resource("/rpa_contract_dict", 'DictController');
            //推送信息
            Route::get('/rpa_contract_publish/list','PublishController@pagination');
            Route::get('/rpa_contract_publish/getByDate','PublishController@getByDate');
            Route::resource("/rpa_contract_publish", 'PublishController');
            //指定上市日期
            Route::get('/rpa_contract_extra/list','ExtraController@pagination');
            Route::resource("/rpa_contract_extra", 'ExtraController');
        });
        
    });

    //辅助功能
    Route::group(['namespace' => 'Auxiliary'],function(){
        //反洗钱查询
        Route::get('/rpa_fxq','FxqController@index')->middleware('permission:rpa_fxq');
    });

    //客服中心后台
    Route::group(['namespace' => 'Func\CallCenter','middleware' => ['permission:sys_call_center']], function(){
        //聊天室
        Route::get("/sys_call_center_chat_room", 'ChatRoomController@index');
        //客户管理
        Route::post('/sys_call_center_customer/getCustomerInfo', 'CustomerController@getById');

        Route::get("/sys_call_center_customer/getOnlineCustomerList", 'CustomerController@getOnlineCustomerList');
        // 模板管理
        Route::get("/sys_call_center_template/list", 'TemplateController@pagination');
        Route::get("/sys_call_center_template/export", 'TemplateController@export');
        Route::get("/sys_call_center_template/template_list_background", 'TemplateController@getTemplateList');
        Route::resource("/sys_call_center_template", 'TemplateController')->middleware('permission:sys_call_center_template');
        // 客服管理
        Route::post("/sys_call_center_manager/connect", 'ManagerController@connect');
        Route::post("/sys_call_center_manager/leave", 'ManagerController@leave');
        Route::post("/sys_call_center_manager/transfer", 'ManagerController@transfer');
        Route::get("/sys_call_center_manager/list", 'ManagerController@pagination');
        Route::get("/sys_call_center_manager/export", 'ManagerController@export');
        Route::get("/sys_call_center_manager/getOnlineManagerList", 'ManagerController@getOnlineManagerList');
        Route::post("/sys_call_center_manager/updateOne", 'ManagerController@updateOne');
        Route::post("/sys_call_center_manager/sendByManager", 'ManagerController@sendByManager');

        Route::resource("/sys_call_center_manager", 'ManagerController')->middleware('permission:sys_call_center_manager');
        // 聊天记录
        Route::get("/sys_call_center_record", 'RecordController@index')->middleware('permission:sys_call_center_record');
        Route::get("/sys_call_center_record/list", 'RecordController@pagination');
        Route::get("/sys_call_center_record/export", 'RecordController@export');
        // 聊天记录详细
        Route::get("/sys_call_center_record_detail/getRecordById", 'RecordDetailController@getRecordById');
        Route::get("/sys_call_center_record_detail/getRecordList", 'RecordDetailController@getRecordList');
        Route::get("/sys_call_center_record_detail", 'RecordDetailController@index')->middleware('permission:sys_call_center_record_detail');
        Route::get("/sys_call_center_record_detail/list", 'RecordDetailController@pagination');
        //设置
        Route::get("/sys_call_center_setting", 'SettingController@index')->middleware('permission:sys_call_center_setting');
        Route::post("/sys_call_center_setting", 'SettingController@store')->middleware('permission:sys_call_center_setting');

    });

    //华安达摩院
    Route::group(['namespace' => 'Hadmy'],function(){
        Route::get('/rpa_hadmy','HadmyController@index');
        Route::get('/rpa_hadmy/list','HadmyController@pagination');
        Route::get('/rpa_hadmy/statistics/{tzjh}','HadmyController@statistics');
        Route::get('/rpa_hadmy/single_list','HadmyController@single_pagination');
    });

    //居间人系统
    Route::group(['namespace' => 'Mediator'],function(){
        Route::get('/mediator','MediatorController@index');
        Route::get('/mediator/list','MediatorController@pagination');
        Route::get('/mediator/info/{id}','MediatorController@info');

        Route::get('/mediator/history/{id}','MediatorController@history');
        Route::get('/mediator/history_list','MediatorController@history_list');
        Route::get('/mediator/flow_info/{id}','MediatorController@flow_info');
        Route::get('/mediator/check/{id}','MediatorController@check');
        Route::patch('/mediator/check_data/{id}','MediatorController@check_data');
    });


    //居间人客户回访系统
    Route::group(['namespace' => 'Revisit', 'middleware' => ['permission:rpa_customer_revisit']], function () {
        Route::get("/rpa_customer_revisit/list", 'CustomerRevisitController@pagination');
        Route::get("/rpa_customer_revisit/export", 'CustomerRevisitController@export');
        Route::get("/rpa_customer_revisit/getAudio/{id}", 'CustomerRevisitController@getAudio');
        Route::resource("/rpa_customer_revisit", 'CustomerRevisitController');
    });
});
