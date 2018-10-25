<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Base\AdminController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends AdminController
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
    protected $redirectTo = '/Admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin')->except('logout');
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
        return '/admin';
    }

    /**
     * login page
     */
    public function showLoginForm()
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
}
