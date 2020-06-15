<?php
/*
 * @Descripttion:
 * @Author: wang hui
 * @Date: 2020-03-24 14:37:21
 * @LastEditors: wang hui
 * @LastEditTime: 2020-04-16 14:48:05
 */
/*
|--------------------------------------------------------------------------
| ZT Admin Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['middleware' => ['auth.admin:admin','web'], ], function () {
    Route::group(['namespace' => 'ZT'], function () {
        //次席申请
        Route::group(['middleware' => ['permission:rpa_seat_apply']], function () {
            Route::get("/rpa_seat_apply/list", 'SeatApplyController@pagination');
            Route::patch("/rpa_seat_apply/status/{id}", 'SeatApplyController@setStatus');
            Route::get("/zt/storage", "SeatApplyController@getFile");
            Route::get("/rpa_seat_apply/export", 'SeatApplyController@export');
            Route::resource('/rpa_seat_apply', 'SeatApplyController');
        });

        //结算账户变更
        Route::group(['middleware' => ['permission:rpa_yq_change']], function () {
            Route::get("/rpa_yq_change/list", 'BalanceAccountController@pagination');
            Route::patch("/rpa_yq_change/status/{id}", 'BalanceAccountController@setStatus');
            Route::get("/rpa_yq_change/export", 'BalanceAccountController@export');
            Route::resource('/rpa_yq_change', 'BalanceAccountController');
        });

        Route::group(['middleware' => ['role:superAdministrator,RpaAdmin']], function () {
        });
    });
});
