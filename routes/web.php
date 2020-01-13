<?php
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
Route::group(['prefix' => 'admin', 'namespace' => 'admin\Base'], function(){
    
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
    Route::any('/500', 'SysController@error500')->name('500');
});

//异常路由跳转
// Route::get('/{any}', function(){
//     return view('errors.404');
// });

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '7',
        'redirect_uri' => 'http://www.rpa.com/auth/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect('http://www.rpa.com/oauth/authorize?'.$query);
});

Route::get('/auth/callback', function (\Illuminate\Http\Request $request){
    if ($request->get('code')) {
        return 'Login Success';
    } else {
        return 'Access Denied';
    }
});