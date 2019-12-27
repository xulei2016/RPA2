<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\base\WebController;
use App\Models\Admin\Base\SysDictionaries;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorPotic;
use App\Models\Index\Mediator\FuncMediatorStep;
use App\Models\Index\Common\RpaCaptcha;
use Illuminate\Http\Request;
use Mews\Captcha\Facades\Captcha;

/**
 * 未完成任务：
 *  1.协议页面
 *  2.续签信息展示
 *  3.监控crm续签确认流程（灰名单）
 *  4.后台
 *  5.跳转判断
 *  6.影像库系统
 */

/**
 * 居间人
 * Class MediatorController
 * @package App\Http\Controllers\Index
 */
class MediatorController extends WebController
{
    public  $view_prefix = "Index.Mediator.";
    public  $config = "";

    public function __construct()
    {
        $this->config = config('mediator');
    }

    //登陆页面
    public function login()
    {
        return view($this->view_prefix.'login');
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
     * 获取验证码
     * @param Request $request
     * @return array
     */
    public function getCode(Request $request)
    {
        $phone = $request->get('phone');
        $img_code = $request->get('img_code');
        if(Captcha::check($img_code)){
            $type = "居间人申请";
            $res = $this->smsCode($phone,$type);
            if($res){
                return $this->ajax_return(200,'短信发送成功');
            }else{
                return $this->ajax_return(500,'短信发送失败');
            }
        }else{
            return $this->ajax_return(500,'验证码错误');
        }
    }

    /**
     * 登陆操作
     * @param Request $request
     * @return array
     */
    public function dologin(Request $request)
    {
        $data = $this->get_params($request, ['phone','vcode']);
        $captcha = RpaCaptcha::where([['phone',$data['phone']],['type','居间人申请']])->first();
        if($captcha && $captcha->code == $data['vcode']){
            if(time() - strtotime($captcha->updated_at) <= $this->config['captcha_lifetime']){
                //判断是否是第一次登陆
                $info  = FuncMediatorInfo::where('phone',$data['phone'])->first();
                if(!$info){
                    $add = [
                        'phone' => $data['phone'],
                        'status' => 1,
                    ];
                    $info = FuncMediatorInfo::create($add);
                    
                    //新增流程
                    $add2 = [
                        'uid'=>$info->id,
                        'type' => 0
                    ];
                    $flow = FuncMediatorFlow::create($add2);
                    $request->session()->put('mediator_flow_id',$flow->id);
                }else{
                    //判断是否有未完成的流程
                    $flow = FuncMediatorFlow::where([['uid',$info->id],['is_handle','0']])->orderBy('id','desc')->first();
                    if($flow){
                        $request->session()->put('mediator_flow_id',$flow->id);
                    }
                }
                
                $request->session()->put('mediator_id',$info->id);
                return $this->ajax_return(200,'登陆成功');
            }else{
                return $this->ajax_return(500,'短信验证码已过期，请重新获取');
            }
        }else{
            return $this->ajax_return(500,'短信验证码错误');
        }
    }

    //首页 0无需续签 1新签 2续签
    public function index(Request $request){
        $id = $request->session()->get('mediator_id');
        $flow = FuncMediatorFlow::where('uid',$id)->orderBy('id','desc')->first();
        if($flow){
            //1.判断居间是否过期
            $time = strtotime($flow->xy_date_end) - time();
            if($time < 0){
                //已过期
                $status = 1;
            }elseif($time >0 && $time <= $this->config['renewal_day']*24*3600){
                //已到续签时间
                //2.判断客户经理是否同意续签
                if($flow->is_agree == 1){
                    $status = 2;
                }else{
                    $status = 1;
                }
            }else{
                //未到续签时间
                $status = 0;
            }
        }else{
            $status = 1;
        }
        return view($this->view_prefix.'index', ['status' => $status]);
    }

    //个人信息
    public function perfectInformation(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'perfectInformation',['data'=>$data]);
    }

    //上传身份证页面
    public function IDCard(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'IDCard',['data'=>$data]);
    }

    //签名及银行卡
    public function sign(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'sign',['data'=>$data]);
    }

    //银行卡信息
    public function bankCard(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'bankCard',['data'=>$data]);
    }

    //手持证件照
    public function handIdCard(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'handIdCard',['data'=>$data]);
    }

    //协议
    public function agreement(){
        return view($this->view_prefix.'agreement');
    }

    /**
     * 保存信息
     * @param Request $request
     * @return array
     */
    public function doinfo(Request $request)
    {
        $info = $request->all();
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $mediator_id = $request->session()->get('mediator_id');
        // 姓名和证件编号写入主表
        if(isset($info['name']) && isset($info['zjbh'])){
            $up = [
                'name' => $info['name'],
                'zjbh' => $info['zjbh']
            ];
            FuncMediatorInfo::where('id',$mediator_id)->update($up);
            unset($info['name']);
            unset($info['zjbh']);
        }
        $step = FuncMediatorStep::where('url',$info['func'])->first();
        $info['step'] = $step->code;
        unset($info['func']);
        FuncMediatorFlow::where('id',$mediator_flow_id)->update($info);

        return $this->ajax_return(200,'数据录入成功');
    }

    /**
     * 文件上传
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $mediator_id = $request->session()->get('mediator_id');
        $type = $request->type;
        $date = date("Y-m-d");
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $pathname = 'mediator/temp/' . md5($this->config['secret_key'].$mediator_id) . "/" . $date . "/";
        $fliename = $type.".".$ext;
        $path = $file->store($pathname.$fliename);
        return $this->ajax_return(200,'上传成功',$path);
    }

    //协议详细  1 居间协议 2 权利义务告知书 3 自律承诺书
    public function agreementDetail(Request $request, $id){
        return view($this->view_prefix.'agreementDetail', ['id' => $id]);
    }

    //返还比例
    public function rate(Request $request){
        $data = $this->getAllInfo($request);
        return view($this->view_prefix.'rate',['data'=>$data]);
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
     * 获取部门
     * @return array
     * @todo 去除灰名单
     */
    public function getRealDept()
    {
        $dept = $this->getDept();
        //去除灰名单

        foreach($dept as $k=>$v){
            $dept[$k]->text = $v->name;
            unset($dept[$k]->name);
        }
        return $this->ajax_return(200,'查询成功',$dept);
    }

    /**
     * 获取测试题目
     * @return array
     */
    public function getPotic()
    {
        $potic = FuncMediatorPotic::orderBy('order','desc')->orderBy('id')->get();
        $res = [];
        $i = 1;
        foreach($potic as $k => $v){
            $option = [
                ['key' => 'A','value' => $v->optionA],
                ['key' => 'B','value' => $v->optionB]
            ];
            if($v->optionC){
                array_push($option,
                    ['key' => 'C','value' => $v->optionC]
                );
            }
            if($v->optionD){
                array_push($option,
                    ['key' => 'D','value' => $v->optionD]
                );
            }
            $res[] = [
                'id' => $v->id,
                'title' => $i."、".$v->title,
                'data' => $option
            ];
            $i++;
        }
        return $this->ajax_return(200,'查询成功',$res);
    }

    /**
     * 验证答案
     * @param Request $request
     * @return array
     */
    public function checkPotic(Request $request)
    {
        $key = json_decode($request->key,true);
        $err = [];
        foreach($key as $k => $v){
            $potic = FuncMediatorPotic::where('id',$v['id'])->first();
            if(strtoupper($potic->answer) != strtoupper($v['option'])){
                array_push($err,$v['id']);
            }
        }

        return $this->ajax_return(200,'',$err);

    }

    /**
     * 获取数据字典
     * @param Request $request
     * @return array
     */
    public function getDictionaries(Request $request)
    {
        $type = $request->type;
        $dictionaries = SysDictionaries::where('type',$type)->pluck('value');
        return $this->ajax_return(200,'查询成功',$dictionaries);
    }
    
    /**
     * 自动跳转下一步
     * @param Request $request
     * @return \Illuminate\Routing\Redirector
     */
    public function goNext(Request $request)
    {
        //当前步骤
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::where('id',$mediator_flow_id)->first();
        $pre_step = $flow->step;
        //获取全部流程
        $step = FuncMediatorStep::orderBy('code','asc')->get();
        if($pre_step == ''){
            return redirect('/index/mediator/'.$step[0]->url);
        }else{
            foreach ($step as $k => $v){
                if($pre_step == $v['code']){
                    return redirect('/index/mediator/'.$step[$k+1]->url);
                }
            }
        }
    }

    /**
     * 控制面板跳转
     * @param Request $request
     */
    public function panel(Request $request)
    {
        $status = $request->status;
        $id = $request->session()->get('mediator_id');
        $add = [
            'uid' => $id,
            'type' => $status
        ];
        $flow = FuncMediatorFlow::create($add);
        $request->session()->put('mediator_flow_id',$flow->id);
        $this->goNext($request);
    }

    /**
     * 获取居间信息
     * @param Request $request
     * @return array
     */
    private function getAllInfo(Request $request)
    {
        $flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::where('id',$flow_id)->orderBy('id','desc')->first();

        if($flow->type == 0){
            //新签
            $re = [
                'status' => 0,
                'data' => []
            ];
        }else{
            //续签
            $data = FuncMediatorFlow::where([['uid',$flow->uid],['isHandle',0]])->orderBy('id','desc')->first();
            $re = [
                'status' => 1,
                'data' => $data
            ];
        }
        return $re;
    }
}