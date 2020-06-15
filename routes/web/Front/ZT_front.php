<?php

/*
|--------------------------------------------------------------------------
| ZT Front Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['prefix' => 'zt', 'namespace'=> 'ZT'], function () {
    Route::get('/', 'CxYqApplyController@index');
    Route::post('/sendCode', 'LoginController@sendCode');
    Route::get('/sendCode', 'LoginController@sendCode');
    Route::post('/doLogin', 'LoginController@doLogin');

    //次席申请
    Route::post('/commit/cixi/apply', 'SeatController@createSeatApply');
    Route::get('/last/apply/data', 'SeatController@lastApplyData');
    Route::get('/last/apply/success/data', 'SeatController@lastApplySuccessData');

    //结算账户新增、变更
    Route::post('/commit/yingqi/change', 'BalanceController@createYingqiChange');
    Route::get('/last/yingqi/change/data', 'BalanceController@lastYingqiChangeData');
    Route::get('/yingqi/change/data/list', 'BalanceController@yingqiChangeDataList');
    Route::post('/bankcard/ocr', 'BalanceController@bankCardOcrBase64');
    Route::get('/get/bank/list', 'BalanceController@getBankList');
    Route::get('/get/user/account/list', 'BalanceController@getMyAccountList');
});
