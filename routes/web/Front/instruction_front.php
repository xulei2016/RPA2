<?php

Route::group(['namespace' => 'Instruction'], function () {
    Route::get('/doc/crm/showSleepCustomer', 'CrmController@showSleepCustomer');
    Route::get('/doc/crm/showSleepCustomerForYyb', 'CrmController@showSleepCustomerForYyb');
    Route::get('/doc/crm/showSleepCustomerForManager', 'CrmController@showSleepCustomerForManager');
});