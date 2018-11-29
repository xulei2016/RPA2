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
            Route::post('/dashboard', 'SysController@get_index');

            //管理员
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
            
            //个人中心
            Route::group(['middleware' => ['permission:sys_profile']], function () {
                Route::get('/sys_profile', 'AdminController@userCenter');
                Route::post('/sys_profile', 'AdminController@updateUser');
                Route::post('/sys_profile_head_img', 'AdminController@updateUser');
            });

            //菜单
            Route::get('/sys_icon', 'MenuController@sys_icon');
            Route::resource('/sys_menu', 'MenuController');
            Route::post('/sys_menu/order', 'MenuController@orderUpdate');
            
            //角色
            Route::get('/sys_role/export', 'RoleController@export')->middleware('permission:sys_role_export');;
            Route::get('/sys_role/list', 'RoleController@pagenation');
            Route::get('/sys_role/{id}/getPermission', 'RoleController@getPermission');
            Route::post('/sys_role/{id}/getCheckPermission', 'RoleController@getCheckPermission');
            Route::post('/sys_role/{id}/roleHasPermission', 'RoleController@roleHasPermission');
            Route::resource('/sys_role', 'RoleController');

            //权限模型
            Route::post('/sys_permission/getTree', 'PermissionController@getTree');
            Route::post('/sys_permission/order', 'PermissionController@orderUpdate');
            Route::resource('/sys_permission', 'PermissionController');

            //系统设置
            Route::get('sys_system_configure', 'SysController@setting')->middleware('permission:sys_system_configure');

            //操作日志
            Route::group(['middleware' => ['permission:sys_logs']], function () {
                Route::get('/sys_logs/list', 'logController@pagenation');
                Route::get('/sys_logs/export', 'logController@export')->middleware('permission:sys_logs_export');
                Route::get('/sys_logs', 'logController@index');
                Route::get('/sys_logs/{id}', 'logController@show')->middleware('permission:sys_logs_view');
                Route::delete('/sys_logs/{id}', 'logController@destroy')->middleware('permission:sys_logs_delete');
            });

            //邮件
            Route::group(['middleware' => ['permission:sys_mail']], function () {
                Route::get('/sys_mail/list', 'MailController@pagenation');
                Route::get('/sys_mail/export', 'MailController@export');
                Route::get('/sys_mail/create', 'MailController@create')->middleware('permission:sys_mail_create');
                Route::resource('/sys_mail', 'MailController', ['except' => ['create']]);
            });
        });

        //RPA PROJECTS
        Route::group(['namespace' => 'Rpa'], function(){
            //rpa 主任务列表
            Route::group(['middleware' => ['permission:rpa_center']], function () {
                Route::get('/rpa_center/list', 'RpaController@pagenation');
                Route::post('/rpa_center/getAccepter', 'RpaController@getAccepter');
                Route::resource('/rpa_center', 'RpaController');
            });
            //朝闻天下
            Route::group(['middleware' => ['permission:rpa_news']], function () {
                Route::get('/rpa_news/list', 'NewsController@pagenation');
                Route::resource('/rpa_news', 'NewsController');
            });

        });
    });

    //异常路由跳转
    // Route::get('/{any}', function(){
    //     return view('errors.404');
    // });

});
