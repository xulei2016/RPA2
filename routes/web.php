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
            Route::get('/admin/export', 'AdminController@export');
            Route::get('/admin/list', 'AdminController@pagenation');
            Route::post('/admin/admin/changeType', 'AdminController@changeType');
            Route::post('/admin/edit', 'AdminController@update');
            Route::resource('/admin', 'AdminController');

            //菜单
            Route::resource('/menu', 'MenuController');
            Route::post('/menu/edit', 'MenuController@update');//可优化掉
            Route::post('/menu/order', 'MenuController@orderUpdate');

            //角色
            Route::get('/role/list', 'RoleController@pagenation');
            Route::get('/role/{id}/permission', 'RoleController@getPermission');
            Route::resource('/role', 'RoleController');
        });
    });
});
