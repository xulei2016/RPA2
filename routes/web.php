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

Route::get('/', function () {
    return view('welcome');
});
//excel
Route::get('/excel','Admin\ExcelController@index');
Route::post('/upload','Admin\ExcelController@upload');


//Bugs
Route::resource('/Bugs', 'Admin\Base\BugsController');

//Inprovement
Route::resource('/Improvement', 'Admin\Base\ImprovementController');


//admin user login or logout operation 
Route::group(['prefix' => 'admin', 'namespace' => 'Admin\Base'], function(){

    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::any('/logout', 'LoginController@logout')->name('logout');

    Route::any('/400', 'SysController@error400')->name('400');
    Route::any('/401', 'SysController@error401')->name('401');
    Route::any('/402', 'SysController@error402')->name('402');
    Route::any('/403', function(){
        return view('errors.403');
    });
    Route::any('/403.extend', function(){
        return view('errors.403_extend');
    });
    Route::any('/404', function(){
        return view('errors.404');
    });
    Route::any('/404.extend', function(){
        return view('errors.404_extend');
    });
    // Route::any('/403', 'SysController@error403')->name('403');
    // Route::any('/404', 'SysController@error404')->name('404');
    Route::any('/500', 'SysController@error500')->name('500');
});


// 后台路由管理
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    Route::group(['middleware' => ['auth.admin:admin','web'], ], function(){
        
        //Base
        Route::group(['namespace' => 'Base'], function(){
            // 首页
            Route::get('/', 'SysController@index');
            Route::get('/index', 'SysController@index')->name('index');
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
            //控制面板
            Route::group(['middleware' => ['permission:sys_board']], function () {
                //用户管理
                Route::group(['middleware' => ['permission:sys_admin']], function () {
                    Route::get('/sys_admin/export', 'AdminController@export')->middleware('permission:sys_admin_export');
                    Route::get('/sys_admin/list', 'AdminController@pagenation');
                    Route::get('/sys_admin', 'AdminController@index');
                    Route::get('/sys_admin/{id}', 'AdminController@show');
                    Route::get('/sys_admin/create', 'AdminController@create')->middleware('permission:sys_admin_add');
                    Route::post('/sys_admin', 'AdminController@store')->middleware('permission:sys_admin_add');
                    Route::get('/sys_admin/{id}/edit', 'AdminController@edit')->middleware('permission:sys_admin_edit');
                    Route::PATCH('/sys_admin/{id}', 'AdminController@update')->middleware('permission:sys_admin_edit');
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

            //api插件
            Route::group(['middleware' => ['permission:sys_api_config']], function () {
                Route::get('/sys_api/list', 'ApiController@pagination');
                Route::resource('/sys_api', 'ApiController');
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
            //FTP新增视频提醒
            Route::group(['middleware' => ['permission:rpa_NewVideoHints']], function () {
                Route::get('/rpa_NewVideoHints/list', 'NewVideoHintsController@pagenation');
                Route::get('/rpa_NewVideoHints/immedtasks/{id?}', 'NewVideoHintsController@immedtasks');
                Route::post('/rpa_NewVideoHints/insertImmedtasks', 'NewVideoHintsController@insertImmedtasks');
                Route::resource('/rpa_NewVideoHints', 'NewVideoHintsController');
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
                Route::post('/rpa_jjr_records/typeChange', 'JJRVisFuncController@typeChange');
                Route::get('/rpa_jjr_records/rpa_list', 'JJRVisFuncController@JJRpagination');
                Route::get('/rpa_jjr_records', 'JJRVisFuncController@index');
            });

            //开户云回访
            Route::group(['middleware' => ['permission:rpa_cloud_distribution']], function () {
                Route::post('/rpa_cloud_distribution/typeChange', 'ReviewtableController@typeChange');
                Route::get('/rpa_cloud_distribution/rpa_list', 'ReviewtableController@JJRpagination');
                Route::get('/rpa_cloud_distribution', 'ReviewtableController@index');
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
                Route::post('/rpa_cotton/official_detail/{id}', 'CottonController@official_detail');
            });

        });
    });

    //异常路由跳转
    // Route::get('/{any}', function(){
    //     return view('errors.404');
    // });

});
