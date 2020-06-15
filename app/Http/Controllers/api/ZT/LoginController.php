<?php
namespace App\Http\Controllers\api\zt;

use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;
/*
 * 掌厅  登录相关
 */
class LoginController extends BaseApiController{

    /**
     * 发送验证码
     * @param string $name 客户姓名
     * @param string $zjzh 资金账号
     * @param string $phone 手机号
     */
    public function sendCode(Request $request){
        return $this->ajax_return(200, '短息发送成功');
    }

    /**
     * 登录
     * @param string $name 客户姓名
     * @param string $zjzh 资金账号
     * @param string $phone 手机号
     * @param string $vCode 手机验证码
     */
    public function doLogin(Request $request){
        return $this->ajax_return(200, '登录成功');
    }

    /**
     * 验证客户信息
     */
    public function verifyCustomerInfo(){
        
    }

}   