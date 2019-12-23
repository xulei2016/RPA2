<?php


namespace App\Http\Controllers\Index;


use App\Http\Controllers\base\WebController;
use Illuminate\Http\Request;

class MediatorController extends WebController
{
    public  $view_prefix = "Index.Mediator.";

    public function login(){

    }

    //首页
    public function index(){
        $list = [
            ['id' => 1, 'name' => '陈1'],
            ['id' => 2, 'name' => '陈2'],
            ['id' => 3, 'name' => '陈3'],
        ];
        return view($this->view_prefix.'index', ['list' => $list]);
    }

    //上传身份证
    public function IDCard(){
        return view($this->view_prefix.'IDCard');
    }

    //晚上个人信息
    public function perfectInformation(){
        return view($this->view_prefix.'perfectInformation');
    }

    //签名及银行卡
    public function sign(){
        return view($this->view_prefix.'sign');
    }

    //银行卡信息
    public function bankCard(){
        return view($this->view_prefix.'bankCard');
    }

    //手持证件照
    public function handIdCard(){
        return view($this->view_prefix.'handIdCard');
    }

    //协议
    public function agreement(){
        return view($this->view_prefix.'agreement');
    }

    //返还比例
    public function rate(){
        return view($this->view_prefix.'rate');
    }

    //返还比例
    public function video(){
        return view($this->view_prefix.'video');
    }

    //测试
    public function review(){
        return view($this->view_prefix.'review');
    }





    /**
     * 获取图片验证码
     * @param Request $request
     * @return array
     */
    public function getImageCode(Request $request){
        return $this->ajax_return(200, '', captcha_src());
    }

    /**
     * 发送验证码
     */
    public function sendCode(Request $request){

    }
}