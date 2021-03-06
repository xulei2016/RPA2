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

Route::group(['namespace' => 'CNode'], function () {
    Route::get('/cnode', 'CNodeController@index');
    Route::get('/cnode/getResultView', 'CNodeController@getResultView');
});

//客服中心前台  (*************需要修复namespace**************)
Route::group(['prefix' => 'call_center', 'namespace' => 'CallCenter'], function(){
    Route::post('/login', 'CustomerController@doLogin');
    Route::post('/logout', 'CustomerController@logout');
    Route::post('/leave', 'ManagerController@leave');

    Route::post("/send", 'CustomerController@sendByCustomer');


    //图片上传
    Route::post("/upload", 'BaseController@upload');
    //模板
    Route::get("/template_list", 'TemplateController@getList');

    // 客户部分三个界面
    Route::get('/', 'CustomerController@login');
    Route::get('/login', 'CustomerController@login');
    Route::get('/forget', 'CustomerController@forget');
    Route::get('/chat', 'CustomerController@chat');
});

//视频上传
Route::group(['prefix' => 'upload_video'], function(){
    Route::get('/', 'VideoCollectController@index');
    Route::get('/client', 'VideoCollectController@client');
    Route::get('/upload', 'VideoCollectController@upload');
    Route::get('/record', 'VideoCollectController@record');
});

Route::group(['prefix' => 'credit'], function(){
    Route::get('/', 'LoseCreditController@index');
    Route::get('/{id}', 'LoseCreditController@otherIndex');
    Route::post('/', 'LoseCreditController@query');
    Route::post('/loopQuery', 'LoseCreditController@loopQuery');
    Route::get('/show', 'LoseCreditController@showLostCredit');
    Route::get('/showTest', 'LoseCreditController@showLostCreditTest');
    Route::get('/showLocal', 'LoseCreditController@showLostCreditFromLocal');
    Route::get('/showImg', 'LoseCreditController@showImg');
});

Route::group(['prefix' => 'mediator1'], function(){
    Route::get('/', 'MediatorController@index');
    Route::get('/test', 'MediatorController@test');
    Route::get('/index', 'MediatorController@index');
    
    Route::get('/getImageCode', 'MediatorController@getImageCode');
    Route::get('/login', 'MediatorController@login');
    Route::post('/getCode', 'MediatorController@getCode');
    Route::get('/IDCard', 'MediatorController@IDCard');
    Route::get('/perfectInformation', 'MediatorController@perfectInformation');
    Route::get('/sign', 'MediatorController@sign');
    Route::get('/bankCard', 'MediatorController@bankCard');
    Route::get('/handIdCard', 'MediatorController@handIdCard');
    Route::get('/agreement', 'MediatorController@agreement');
    Route::get('/agreementDetail/{id}', 'MediatorController@agreementDetail');
    Route::get('/rate', 'MediatorController@rate');
    Route::get('/confirmRate', 'MediatorController@confirmRate');
    Route::get('/video', 'MediatorController@video');
    Route::get('/review', 'MediatorController@review');
    Route::get('/result', 'MediatorController@result');
    Route::get('/info', 'MediatorController@info');
    Route::get('/infoDetail', 'MediatorController@infoDetail');

    Route::post('/doInfo', 'MediatorController@doInfo');
    Route::post('/getCode', 'MediatorController@getCode');
    Route::post('/doLogin', 'MediatorController@doLogin');
    Route::post('/upload', 'MediatorController@upload');
    Route::post('/doConfirmRate', 'MediatorController@doConfirmRate');

    Route::get('/panel', 'MediatorController@panel');
    Route::get('/goNext', 'MediatorController@goNext');
    Route::get('/getRealDept', 'MediatorController@getRealDept');
    Route::get('/getPotic', 'MediatorController@getPotic');
    Route::post('/checkPotic', 'MediatorController@checkPotic');
    Route::post('/checkIdCard', 'MediatorController@checkIdCard');
    Route::post('/checkBankCard', 'MediatorController@checkBankCard');
    Route::get('/getDictionaries', 'MediatorController@getDictionaries');
});

Route::group(['prefix' => 'mediator'], function(){
    Route::get('/', 'MediatorController@indexView');
    Route::get('/ttt', 'MediatorController@test');
    Route::get('/sync', 'MediatorController@syncTrainingDuration');

    Route::get('/index', 'MediatorController@indexView');
    Route::get('/getImageCode', 'MediatorController@getImageCode');
    Route::get('/login', 'MediatorController@loginView');
    Route::post('/sendCode', 'MediatorController@sendCode');
    Route::get('/showStepView/{step}', 'MediatorController@showStepView');
    Route::get('/IDCard', 'MediatorController@IDCardView');
    Route::get('/agreementDetail/{id}', 'MediatorController@agreementDetailView');
    Route::get('/rate', 'MediatorController@rateView');
    Route::get('/confirmRate', 'MediatorController@confirmRateView');
    Route::get('/result', 'MediatorController@resultView');
    Route::get('/info', 'MediatorController@infoView');
    Route::get('/infoDetail', 'MediatorController@infoDetailView');

    Route::post('/doInfo', 'MediatorController@doInfo');
    Route::post('/getCode', 'MediatorController@getCode');
    Route::post('/doLogin', 'MediatorController@doLogin');
    Route::post('/upload', 'MediatorController@upload');
    Route::post('/doConfirmRate', 'MediatorController@doConfirmRate');

    Route::get('/panel', 'MediatorController@panelSkip');
    Route::get('/goNext', 'MediatorController@goNext');
    Route::get('/goBack', 'MediatorController@goBack');
    Route::get('/getRealDept', 'MediatorController@getRealDept');
    Route::get('/getPotic', 'MediatorController@getPotic');
    Route::post('/checkPotic', 'MediatorController@checkPotic');
    Route::post('/checkIdCard', 'MediatorController@checkIdCard');
    Route::post('/checkBankCard', 'MediatorController@checkBankCard');
    Route::get('/getDictionaries', 'MediatorController@getDictionaries');
    Route::get('/showImage', 'MediatorController@showImage');

    Route::post('/saveAgreement', 'MediatorController@saveAgreement');
    Route::get('/getAgreement', 'MediatorController@getAgreement');
});










