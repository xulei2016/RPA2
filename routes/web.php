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
            // Route::resource('/admin', 'AdminController');
            Route::get('/admin', 'AdminController@index');
            Route::get('/admin/index', 'AdminController@index');
            Route::get('/admin/list', 'AdminController@pagenation');
        });
    });
});
