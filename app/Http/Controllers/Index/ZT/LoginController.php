<?php
namespace App\Http\Controllers\Index\ZT;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\Tmp\TmpCustomer;
use Illuminate\Http\Request;
use App\Models\Index\Common\RpaCaptcha;
use Illuminate\Validation\ValidationException;
use App\Exceptions\zt\ValidateException;
use App\Exceptions\zt\ZtException;
use SMS;

/*
 * 掌厅  登录相关
 */
class LoginController extends BaseController
{
    public $debug = false;

    public $verifyCodeType = '掌厅登录'; // 验证码类型

    public $verifyCodeLifeTime = 300; // 验证码生命周期

    public $phone = "18100514361"; // 测试手机号
    


    /**
     * 发送验证码
     * @param string $name 客户姓名
     * @param string $zjzh 资金账号
     * @param string $phone 手机号
     * @param int $allowDormancy 允许休眠 1是 0否
     */
    public function sendCode(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'zjzh' => 'required|numeric',
                'phone' => 'required|numeric',
                'allowDormancy' => 'required',
            ]);
            $this->verifyCustomerInfo($request->name, $request->zjzh, $request->phone, $request->allowDormancy);
            $this->sendPhoneCode($request->phone);
            return $this->ajax_return(200, '短息发送成功');
        } catch (ValidationException $e) {
            return $this->ajax_return(500, '参数验证失败');
        } catch (ValidateException $e) {
            return $this->ajax_return(500, $e->getMessage());
        } catch (ZtException $e) {
            return $this->ajax_return(500, $e->getMessage());
        } catch (\Exception $e) {
            return $this->ajax_return(500, '网络异常');
        }
    }

    /**
     * 登录
     * @param string $name 客户姓名
     * @param string $zjzh 资金账号
     * @param string $phone 手机号
     * @param string $vCode 手机验证码
     * @param int $allowDormancy 是否允许休眠
     */
    public function doLogin(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'zjzh' => 'required|numeric',
                'phone' => 'required|numeric',
                'vCode' => 'required|numeric',
                'allowDormancy' => 'required',
            ]);
            $state = $this->verifyCustomerInfo($request->name, $request->zjzh, $request->phone, $request->allowDormancy);
            $this->verifyPhoneCode($request->phone, $request->vCode);
            $user = TmpCustomer::where('fundsNum', $request->zjzh)->first();
            if ($user && $user->id) {
                $user_id = $user->id;
                if ($user->mobile != $request->phone) {
                    $user->mobile = $request->phone;
                    $user->save();
                }
            } else {
                $customer = TmpCustomer::create([
                    'name' => $request->name,
                    'fundsNum' => $request->zjzh,
                    'mobile' => $request->phone,
                ]);
                $user_id = $customer->id;
            }
            $request->session()->put('tmp_customer_id', $user_id);
            $request->session()->put('tmp_customer_state', $state);
 
            return $this->ajax_return(200, '登录成功');
        } catch (ValidationException $e) {
            return $this->ajax_return(500, '参数验证失败');
        } catch (ValidateException $e) {
            return $this->ajax_return(500, $e->getMessage());
        } catch (ZtException $e) {
            return $this->ajax_return(500, $e->getMessage());
        } catch (\Exception $e) {
            return $this->ajax_return(500, '网络异常');
        }
    }


    /**
     * 验证客户信息
     * @param string $name 姓名
     * @param string $zjzh 姓名
     * @param string $phone 姓名
     * @param string $allowDormancy 是否允许监管休眠 1是 0否
     * @todo 手机号未验证
     */
    public function verifyCustomerInfo($name, $zjzh, $phone, $allowDormancy = 0)
    {
        $check = '/^(1(([35789][0-9])|(47)))\d{8}$/';
        if (!preg_match($check, $phone)) {
            throw new ValidateException('手机号不合法');
        }
        
        $by = [
            ['KHXM', '=', $name],
            ['ZJZH', '=', $zjzh],
            ['KHZT', '=', 0], // 状态 0 表示正常
        ];
        //查询客户信息
        $params = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $by,
            ]
        ];

        $customerInfo = $this->getCrmData($params); // 客户信息

        if (!$customerInfo) {
            throw new ValidateException('该客户不存在!');
        }

        $customerInfo = $customerInfo[0];

        if ($allowDormancy) { // 允许监管休眠 s表示监管休眠
            if ($customerInfo['FXYS'] == '5' || strtolower($customerInfo['FXYS']) == '5s') {
            } else {
                throw new ValidateException('该客户状态异常');
            }
        } else { // 不允许
            if ($customerInfo['FXYS'] != '5') { //风险要素判读 5 表示正常
                if (strtolower($customerInfo['FXYS']) == '5s') {
                    throw new ValidateException('用户状态监管休眠 激活后才能办理该业务');
                } else {
                    throw new ValidateException('该客户状态异常');
                }
            }
        }

        
        // 手机列表
        $flag = false;
        $phoneList = [
            $customerInfo['DH'],
            $customerInfo['SJ'],
            $customerInfo['GTSJ']
        ];
        foreach ($phoneList as $v) {
            if (strpos("$v", "$phone") >-1) {
                $flag = true;
            }
        }
        if (!$flag) {
            throw new ValidateException('该客户留存手机号与当前手机号不一致!');
        }

        return $customerInfo['FXYS'];
    }

    /**
     * 验证手机验证码
     */
    public function verifyPhoneCode($phone, $vCode)
    {
        $captcha = RpaCaptcha::where([
            ['phone', $phone],
            ['type', $this->verifyCodeType]
        ])->first();
        if (!$captcha) {
            throw new ValidateException('验证码错误');
        }
        if (time() - strtotime($captcha->update_at) > $this->verifyCodeLifeTime) {
            if ($captcha->code == $vCode) {
                return true;
            } else {
                throw new ValidateException('验证码错误');
            }
        } else {
            throw new ValidateException('验证码已过期');
        }
    }

    /**
     * 发送手机验证码
     */
    public function sendPhoneCode($phone)
    {
        $code = rand(100000, 999999);
        $result = $this->sendSmsSingle($phone, $code, 'JY-YZM', ['templateId'=>'591529']);
        if ($result !== true) {
            throw new ZtException($result);
        }
        $captcha = RpaCaptcha::where([
            ['phone', $phone],
            ['type', $this->verifyCodeType]
        ])->first();
        if ($captcha) {
            $update = [
                'code' => $code,
                'count' => $captcha->count + 1
            ];
            RpaCaptcha::where('phone', $phone)->update($update);
        } else {
            $add = [
                'phone' => $phone,
                'code' => $code,
                'count' => 1,
                'type' => $this->verifyCodeType
            ];
            RpaCaptcha::create($add);
        }
    }

    /**
     * 测试方法
     */
    public function test()
    {
    }
}
