<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseAdminController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // dd(__CLASS__, __FUNCTION__, $request);
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * guard
     * 
     * @return Auth
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    /**
     * redirect users after login
     */
    public function redirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * login page
     */
    public function showLoginForm(Request $request)
    {
        return view('admin.login');
    }

    /**
     * username operation
     */
    public function username()
    {
        return 'name';
    }

    /**
     * user logout
     */
    public function logout(Request $request)
    {
        if(auth()->Guard('admin')->check()){
            auth()->Guard('admin')->logout();
        }
        $request->session()->flush();
        return redirect('/');
    }
    
}
