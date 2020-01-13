<?php

/*
|--------------------------------------------------------------------------
| MDC Routes
|--------------------------------------------------------------------------
|
*/

//MDC
Route::group(['prefix' => 'mdc', 'namespace' => 'MDC'], function () {


    //服务器
    Route::group([], function () {
        Route::get('/apm_server','APMController@index');
    });

    //登录用户
    Route::group([], function () {
        Route::get('/heat_map','DAController@index');
        Route::get('/heat_map/getArea','DAController@getArea');
        Route::get('/heat_map/getPosition','DAController@getPosition');
        Route::get('/heat_map/getTfrequency','DAController@getTfrequency');
    });

});
