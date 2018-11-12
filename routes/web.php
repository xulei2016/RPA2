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

//admin user login or logout operation 
Route::group(['prefix' => 'admin', 'namespace' => 'Admin\Base'], function(){

    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login');
    Route::any('/logout', 'LoginController@logout')->name('logout');

});

// 后台路由管理
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    Route::group(['middleware' => ['auth.admin:admin','web'], ], function(){

        //Base
        Route::group(['namespace' => 'Base'], function(){
            // 首页
            Route::get('/', 'SysController@index');
            Route::get('/index', 'SysController@index')->name('index');
            Route::post('/index', 'SysController@index')->name('index');
            Route::post('/dashboard', 'SysController@get_index');

            //管理员
            Route::get('/sys_admin/export', 'AdminController@export');
            Route::get('/sys_admin/list', 'AdminController@pagenation');
            Route::post('/sys_admin/admin/changeType', 'AdminController@changeType');
            Route::post('/sys_admin/edit', 'AdminController@update');
            Route::resource('/sys_admin', 'AdminController');

            //菜单
            Route::get('/sys_icon', 'MenuController@sys_icon');
            Route::resource('/sys_menu', 'MenuController');
            Route::post('/sys_menu/order', 'MenuController@orderUpdate');
            
            //角色
            Route::get('/sys_role/export', 'RoleController@export');
            Route::get('/sys_role/list', 'RoleController@pagenation');
            Route::get('/sys_role/{id}/getPermission', 'RoleController@getPermission');
            Route::post('/sys_role/{id}/getCheckPermission', 'RoleController@getCheckPermission');
            Route::post('/sys_role/{id}/roleHasPermission', 'RoleController@roleHasPermission');
            Route::resource('/sys_role', 'RoleController');

            //权限模型
            Route::post('/sys_permission/getTree', 'PermissionController@getTree');
            Route::post('/sys_permission/order', 'PermissionController@orderUpdate');
            Route::resource('/sys_permission', 'PermissionController');
        });
    });
});
