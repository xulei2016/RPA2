<?php

/*
|--------------------------------------------------------------------------
| Admin sys Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

// 后台路由管理 系统管理
Route::group([ 'namespace' => 'sys'], function(){

    // 首页
    Route::get('/', 'SysController@index');
    Route::get('/index', 'SysController@index')->name('index');
    Route::post('/index', 'SysController@index')->name('index');
    Route::post('/dashboard', 'SysController@get_index');

});