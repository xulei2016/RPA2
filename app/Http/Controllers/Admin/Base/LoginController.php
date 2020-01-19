<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Admin\SysAdmin;

use App\Models\Admin\Base\SysConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

use App\Events\Login\LoginEvent;
use Jenssegers\Agent\Agent;


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
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * login function
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //登录实现
        if (auth()->attempt(['name' => $request->input('name'), 'password' => $request->input('password'), 'type' => 1], $request->input('remember'))) {

            event(new LoginEvent($request, auth()->Guard('admin')->user(), new Agent(), $this->getTime(), true));

            return redirect()->intended($this->redirectTo);
        }

        $this->incrementLoginAttempts($request);

        event(new LoginEvent($request, auth()->Guard('admin')->user(), new Agent(), $this->getTime(), false));

        return $this->sendFailedLoginResponse($request);
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
     * 登录验证
     * @param Request $request
     * @throws \Exception
     */
    protected function validateLogin(Request $request)
    {
        $configs = SysConfig::where('item_key', 'verification_code')->first();
        $codeConfig = $configs->item_value;  //0 关闭  1 仅开启图片验证码  2仅开启滑动验证码 3全开
        $validateDetail = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];
        $validateStr = [];
        if ($codeConfig == 1 || $codeConfig == 3) {
            $validateDetail['captcha'] = 'required|captcha';
            $validateStr = [
                'captcha.required' => trans('validation.required'),
                'captcha.captcha' => trans('validation.captcha'),
            ];
        }
        try {
            $validate = $this->getValidationFactory()->make($request->all(), $validateDetail, $validateStr);
            $validate->validate();
            $admin = SysAdmin::where($this->username(), $request->name)->first();
            if ($admin->error_count >= 10) {
                $validate->errors()->add('account', '错误次数过多,账号被锁定');
                throw new ValidationException($validate);
            } else {
                $admin->error_count = 0;
                $admin->save();
            }
        } catch (\Exception $e) {
            $admin = SysAdmin::where($this->username(), $request->name)->first();
            if ($admin) {
                $admin->error_count++;
                $admin->save();
            }
            throw $e;
        }

    }

    /**
     * login page
     */
    public function showLoginForm(Request $request)
    {
        $configs = SysConfig::where('item_key', 'verification_code')->first();
        $codeConfig = $configs->item_value;  //0 关闭  1 仅开启图片验证码  2仅开启滑动验证码 3全开
        return view('admin.login', ['codeConfig' => $codeConfig]);
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
        if (auth()->Guard('admin')->check()) {
            auth()->Guard('admin')->logout();
        }
        $request->session()->flush();
        return redirect('/');
    }

}
