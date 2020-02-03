<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\base\WebController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\SysDictionaries;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorPotic;
use App\Models\Index\Mediator\FuncMediatorStep;
use App\Models\Index\Common\RpaCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    public $view_prefix = "Index.Mediator."; // 页面前缀
    public $root_url = "/mediator/";  // 根路由
    public $config = "";
    const BACK_TO = 'backTo';


    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $controller = request()->route()->action['controller'];
            $funcs = explode('@', $controller);
            $func = $funcs[1];

            //未登录允许访问的方法
            $allow = [
                'login', 'getImageCode', 'getCode', 'doLogin', 'goNext', 'panel', 'index', 'doConfirmRate'
            ];

            if (!in_array($func, $allow)) {
                $mediator_id = $request->session()->get('mediator_id');
                $mediator_flow_id = $request->session()->get('mediator_flow_id');
                if (!$mediator_id || !$mediator_flow_id) {
                    return redirect($this->root_url . 'login');
                } else {
                    if ($request->session()->get(self::BACK_TO)) {
                        $this->config = config('mediator');
                        $flow = FuncMediatorFlow::where('id', $mediator_flow_id)->first();
                        $backList = $flow->back_list;
                        if ($backList) {
                            $backList = explode(',', $backList);
                            if (in_array($func, $backList)) {
                                return $next($request);
                            }
                        }
                    }

                    $arr = FuncMediatorStep::pluck('url')->toArray();
                    if (in_array($func, $arr)) {
                        //跳转到指定步骤
                        //1.获取当前流程所在步骤
                        $flow = FuncMediatorFlow::where('id', $mediator_flow_id)->first();
                        //2.获取下一步的code
                        $steps = FuncMediatorStep::orderBy('code', 'asc')->get();
                        $nextCode = "";
                        foreach ($steps as $k => $v) {
                            if ($flow->step == $v['code']) {
                                if (!isset($steps[$k + 1])) {
                                    return $this->lastStep($mediator_flow_id);
                                } else {
                                    $nextCode = $steps[$k + 1]->code;
                                }
                            }
                        }
                        //3.获取当前请求步骤
                        $step = FuncMediatorStep::where('url', $func)->first();
                        //4.判断当前请求步骤是否是下一步的步骤
                        if ($step->code != $nextCode) {
                            return $this->goNext($request);
                        }
                    }
                }
            }

            $this->config = config('mediator');
            return $next($request);
        });
    }


    //登陆页面
    public function login(Request $request)
    {
        $request->session()->flush();
        return view($this->view_prefix . 'login');
    }

    /**
     * 获取图片验证码
     * @param Request $request
     * @return array
     */
    public function getImageCode(Request $request)
    {
        return $this->ajax_return(200, '', captcha_src());
    }

    /**
     * 获取验证码
     * @param Request $request
     * @return array
     */
    public function getCode(Request $request)
    {
        $phone = $request->post('phone');
        $img_code = $request->post('img_code');
        if (Captcha::check($img_code)) {
            $type = "居间人申请";
            $res = $this->smsCode($phone, $type);
            if ($res) {
                return $this->ajax_return(200, '短信发送成功');
            } else {
                return $this->ajax_return(500, '短信发送失败');
            }
        } else {
            return $this->ajax_return(500, '验证码错误');
        }
    }

    /**
     * 登陆操作
     * @param Request $request
     * @return array
     */
    public function doLogin(Request $request)
    {
        $data = $this->get_params($request, ['phone', 'vcode']);
        $captcha = RpaCaptcha::where([['phone', $data['phone']], ['type', '居间人申请']])->first();
        if ($captcha && $captcha->code == $data['vcode']) {
            if (time() - strtotime($captcha->updated_at) <= $this->config['captcha_lifetime']) {
                //判断是否是第一次登陆
                $info = FuncMediatorInfo::where('phone', $data['phone'])->first();
                if (!$info) {  // 首次登录
                    $add = [
                        'phone' => $data['phone'],
                    ];
                    $info = FuncMediatorInfo::create($add);

                    //新增流程
                    $add2 = [
                        'uid' => $info->id,
                        'type' => 0,
                        'step' => 100
                    ];
                    $flow = FuncMediatorFlow::create($add2);
                    $request->session()->put('mediator_flow_id', $flow->id);
                } else {
                    // 注销
                    if($info->status == 3) { // 该用户被注销
                        $xyEndDate = $info->xy_date_end; // 协议到期时间
                        //获取最后一条注销流程
                        $logOffFlow = FuncMediatorFlow::where('type', 3)->orderBy('id', 'desc')->first();
                        $crmEndDate = $logOffFlow->crmflow_end_time;
                        if(strtotime($crmEndDate) < strtotime($xyEndDate)) { //协议内 注销
                             // 注销时间满一年才能重新申请
                            if(time() < strtotime($crmEndDate. " +1 year -1 day")) {
                                return $this->ajax_return(500, '合同期内注销一年后方可重新申请');
                            }
                        }
                    }
                    //判断是否有未完成的 新签续签流程
                    $flow = FuncMediatorFlow::where([['uid', $info->id], ['is_handle', '0'], ['status', 1]])
                        ->whereIn('type', [0, 1])
                        ->orderBy('id', 'desc')->first();
                    if ($flow) {
                        if ($flow->type == 1) { // 续签需要判断是否到期
                            $completeFlow = FuncMediatorFlow::where([['uid', $info->id], ['is_handle', '1'], ['status', 1]])
                                ->whereIn('type', [0, 1])
                                ->orderBy('id', 'desc')->first();
                            $endDate = $completeFlow->xy_date_end; // 协议到期时间
                            if (strtotime($endDate . ' 23:59:59') >= time()) { //还未到期
                                $request->session()->put('mediator_flow_id', $flow->id);
                            } else { // 协议已到期, 将当前流程作废
                                $request->session()->pull('mediator_flow_id');
                                $flow->status = 0;
                                $flow->save();
                            }
                        } else {
                            $request->session()->put('mediator_flow_id', $flow->id);
                        }
                    }

                }
                $request->session()->put('mediator_id', $info->id);
                return $this->ajax_return(200, '登陆成功');
            } else {
                return $this->ajax_return(500, '短信验证码已过期，请重新获取');
            }
        } else {
            return $this->ajax_return(500, '短信验证码错误');
        }
    }

    /**
     * 首页-面板
     * @param Request $request
     * @return mixed
     * @desc 0 无需续签 1 新签 2 续签
     */
    public function index(Request $request)
    {
        $id = $request->session()->get('mediator_id');
        if(!$id) {
            return redirect($this->root_url . 'login');
        }
        $flow = FuncMediatorFlow::where([
            ['uid', $id],
            ['status', 1]
        ])->whereIn('type', [0, 1])->orderBy('id', 'desc')->first();
        if(!$flow) { // 一条正常流程都没有
            $status = 1;
        } else {
            $request->session()->put('mediator_flow_id', $flow->id);
            if ($flow->is_handle != 1) return $this->goNext($request);
            if ($flow) {
                //1.判断居间是否过期
                $time = strtotime($flow->xy_date_end . " 23:59:59") - time();
                if ($time < 0) {
                    //已过期
                    $status = 1;
                } elseif ($time > 0 && $time <= $this->config['renewal_day'] * 24 * 3600) {
                    //已到续签时间
                    //2.判断客户经理是否同意续签
                    if ($flow->is_manager_agree == 1) {
                        $status = '2.0';
                    } else {
                        $status = '0';
                    }
                } else {
                    //未到续签时间
                    $status = 0;
                }
            } else {
                $status = 1;
            }
        }
        return view($this->view_prefix . 'index', ['status' => $status]);
    }

    //个人信息
    public function perfectInformation(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'perfectInformation', ['data' => $data]);
    }

    //上传身份证页面
    public function IDCard(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'IDCard', ['data' => $data]);
    }

    //签名及银行卡
    public function sign(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'sign', ['data' => $data]);
    }

    //银行卡信息
    public function bankCard(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'bankCard', ['data' => $data]);
    }

    //手持证件照
    public function handIdCard(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'handIdCard', ['data' => $data]);
    }

    //协议
    public function agreement()
    {
        return view($this->view_prefix . 'agreement');
    }

    //协议详细  1 居间协议 2 权利义务告知书 3 自律承诺书
    public function agreementDetail(Request $request, $id)
    {
        $read = $request->get('read', 0);
        return view($this->view_prefix . 'agreementDetail', ['id' => $id, 'read' => $read]);
    }

    /**
     * 保存信息
     * @param Request $request
     * @return array
     */
    public function doInfo(Request $request)
    {
        $info = $request->all();
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $mediator_id = $request->session()->get('mediator_id');
        $flow = FuncMediatorFlow::find($mediator_flow_id); // 流程表
        $type = $flow->type; // 0新签  1 续签
        if($type === 0) { //新签的时候同步info表
            $baseInfo = $info;
            unset(
                $baseInfo['func'],$baseInfo['is_video'],
                $baseInfo['is_answer']
            );
            FuncMediatorInfo::where('id', $mediator_id)->update($baseInfo);
        }
        unset($info['name']); //


        if ($request->session()->get(self::BACK_TO)) { // 打回
            $backList = $flow->back_list;
            if ($backList) {
                $backList = explode(',', $backList);
            }

            $index = array_search($info['func'], $backList);
            unset($backList[$index]);
            $info['back_list'] = implode(',', $backList);
            if (empty($backList)) {
                $request->session()->pull(self::BACK_TO);
                $info['is_back'] = 0;
                $info['back_person'] = 0;
                $info['back_time'] = 0;
            }
        } else {
            $step = FuncMediatorStep::where('url', $info['func'])->first();
            $info['step'] = $step->code;
            $last = FuncMediatorStep::orderBy('code', 'desc')->first();
            if ($last->url == $info['func']) {
                $info['part_b_date'] = date('Y-m-d');
            }
        }

        unset($info['func']);
        FuncMediatorFlow::where('id', $mediator_flow_id)->update($info);
        return $this->ajax_return(200, '数据录入成功');
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
        $pathname = '/temp/' . md5($this->config['secret_key'] . $mediator_id) . "/" . $date;
        $filename = $type . "." . $ext;
        $path = $pathname."/".$filename;
        Storage::disk('local')->putFileAs('mediator', $file, $path);
        return $this->ajax_return(200, '上传成功', $path);
    }

    //返还比例
    public function rate(Request $request)
    {
        $data = $this->getAllInfo($request);
        return view($this->view_prefix . 'rate', ['data' => $data]);
    }

    //确认返回比例
    public function confirmRate(Request $request)
    {
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediator_flow_id);
        return view($this->view_prefix . 'confirmRate', ['rate' => $flow->rate]);
    }

    /**
     * 确认居间比例
     * @param Request $request
     * @return array
     */
    public function doConfirmRate(Request $request)
    {
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::where('id',$mediator_flow_id)->first();

        $flow->is_sure = $request->is_sure;
        $flow->sure_time = date('Y-m-d H:i:s');
        $flow->save();
        if($request->is_sure == 1){
            (new \App\Http\Controllers\Admin\Mediator\MediatorController())->to_crm($flow);
        }
        return $this->ajax_return(200, '操作成功');
    }

    //返还比例
    public function video()
    {
        return view($this->view_prefix . 'video');
    }

    //测试
    public function review()
    {
        return view($this->view_prefix . 'review');
    }

    /**
     * 结果
     * @param Request $request
     * @return mixed
     * @var $status 1 正常申请完成  2 有打回  3办理完成
     */
    public function result(Request $request)
    {
        $request->session()->pull(self::BACK_TO);
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediator_flow_id);
        $steps = FuncMediatorStep::all();
        if ($flow->is_handle) {
            $status = 3;
        } else {
            if($flow->is_check == 1 && $flow->is_sure != 1) { // 被审核 且 需要确认居间比例
                return redirect($this->root_url . 'confirmRate');
            }
            $status = 1;
        }
        $stepList = [];
        foreach ($steps as $step) {
            $stepList[$step->url] = $step->name;
        }
        $backList = [];
        if ($flow->is_back) { // 有打回
            $status = 2;
            $backs = explode(',', $flow->back_list);
            foreach ($backs as $back) {
                $backList[] = $stepList[$back];
            }
        }


        return view($this->view_prefix . 'result', [
            'backList' => $backList,
            'status' => $status
        ]);
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
        foreach ($dept as $k => $v) {
            $dept[$k]->text = $v->name;
            unset($dept[$k]->name);
        }
        return $this->ajax_return(200, '查询成功', $dept);
    }

    /**
     * 获取测试题目
     * @return array
     */
    public function getPotic()
    {
        $potic = FuncMediatorPotic::where('status', 1)->orderBy('order', 'desc')->orderBy('id')->get();
        $res = [];
        $i = 1;
        foreach ($potic as $k => $v) {
            $option = [
                ['key' => 'A', 'value' => $v->optionA],
                ['key' => 'B', 'value' => $v->optionB]
            ];
            if ($v->optionC) {
                array_push($option,
                    ['key' => 'C', 'value' => $v->optionC]
                );
            }
            if ($v->optionD) {
                array_push($option,
                    ['key' => 'D', 'value' => $v->optionD]
                );
            }
            $res[] = [
                'id' => $v->id,
                'title' => $i . "、" . $v->title,
                'data' => $option
            ];
            $i++;
        }
        return $this->ajax_return(200, '查询成功', $res);
    }


    /**
     * 验证答案
     * @param Request $request
     * @return array
     */
    public function checkPotic(Request $request)
    {
        $data = json_decode($request->data, true);
        $err = [];
        foreach ($data as $k => $v) {
            $potic = FuncMediatorPotic::where('id', $v['id'])->first();
            if (strtoupper($potic->answer) != strtoupper($v['option'])) {
                array_push($err, $v['id']);
            }
        }
        return $this->ajax_return(200, '', $err);

    }

    /**
     * 获取数据字典
     * @param Request $request
     * @return array
     */
    public function getDictionaries(Request $request)
    {
        $type = $request->type;
        $dictionaries = SysDictionaries::where('type', $type)->pluck('value');
        return $this->ajax_return(200, '查询成功', $dictionaries);
    }
    /**
     * 自动跳转下一步
     * @param Request $request
     * @return mixed
     */
    public function goNext(Request $request)
    {
        //当前步骤
        $mediator_flow_id = $request->session()->get('mediator_flow_id');
        if (!$mediator_flow_id) return redirect($this->root_url);
        $flow = FuncMediatorFlow::where('id', $mediator_flow_id)->first();
        if ($request->back || $request->session()->get(self::BACK_TO)) { // 被打回
            $backList = $flow->back_list;
            if ($backList) $backList = explode(',', $backList);
            $request->session()->put(self::BACK_TO, true);
            return redirect($this->root_url . $backList[0]);
        }

        $pre_step = $flow->step; //600
        //获取全部流程
        $step = FuncMediatorStep::orderBy('code', 'asc')->get();
        $count = count($step);
        if ($pre_step == '') {
            return redirect($this->root_url . $step[0]->url);
        } else {
            foreach ($step as $k => $v) {
                if ($pre_step == $v['code']) {
                    if ($k == $count - 1) {
                        return $this->lastStep($mediator_flow_id);
                    } else {
                        return redirect($this->root_url . $step[$k + 1]->url);
                    }
                }
            }
        }
    }

    /**
     * 控制面板跳转
     * @param Request $request
     * @return mixed
     */
    public function panel(Request $request)
    {
        $status = $request->status;
        $id = $request->session()->get('mediator_id');
        $status--;
        $add = [
            'uid' => $id,
            'type' => $status,
            'step' => 100
        ];
        if($status = 1) {
            $add['number'] = FuncMediatorInfo::find($id)->number;
        }
        $flow = FuncMediatorFlow::create($add);
        $request->session()->put('mediator_flow_id', $flow->id);
        return $this->goNext($request);
    }

    /**
     * 获取居间信息
     * @param Request $request
     * @return mixed
     */
    private function getAllInfo(Request $request)
    {
        $flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($flow_id)->toArray();
        if($request->session()->get(self::BACK_TO)) { // 打回 获取当前流程
            $flow['dept'] = SysDept::find($flow['dept_id'])->name;
            $flow['name'] =  FuncMediatorInfo::find($flow['uid'])->name;
            foreach ($flow as $k => $v) {
                if (strpos($k, 'img') > -1) {
                    $flow[$k . '_base64'] = $this->base64EncodeImage($v);
                }
            }
            $re = [
                'status' => 1,
                'data' => $flow
            ];
        } else {
            if ($flow['type'] == 0) {
                //新签
                $re = [
                    'status' => 0,
                    'data' => []
                ];
            } else {
                //续签
                $info = FuncMediatorInfo::find($flow['uid'])->toArray();
                $info['dept'] = SysDept::find($info['dept_id'])->name;
                $edu = SysDictionaries::where([
                    ['type', 'education'],
                    ['value', $info['edu_background']]
                ])->first();
                if(!$edu) {
                    $info['edu_background'] = '';
                }
                foreach ($info as $k => $v) {
                    if (strpos($k, 'img') > -1) {
                        $info[$k . '_base64'] = $this->base64EncodeImage($v);
                    }
                }
                $re = [
                    'status' => 1,
                    'data' => $info
                ];
            }
        }

        return $re;
    }

    /**
     * 获取当前流程信息
     * @param Request $request
     * @return mixed
     */
    private function getCurrentInfo(Request $request)
    {
        $flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($flow_id)->toArray();
        //客户
        $user = FuncMediatorInfo::find($flow['uid']);
        $flow['phone'] = $user->phone;
        $flow['zjbh'] = $user->zjbh;
        $flow['name'] = $user->name;
        $dept = SysDept::find($flow['dept_id']);
        $flow['dept'] = $dept ? $dept->name : '';
        foreach ($flow as $k => $v) {
            if (strpos($k, 'img') > -1) {
                $flow[$k . '_base64'] = $this->base64EncodeImage($v);
            }
        }
        return $flow;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function info(Request $request)
    {
        $flow_id = $request->session()->get('mediator_flow_id');
        if (!$flow_id) return redirect($this->root_url . 'login');
        $flow = FuncMediatorFlow::find($flow_id);
        if ($flow->is_handle != 1) {
            return $this->goNext($request);
        }
        $list = FuncMediatorStep::where([
            ['is_show', '=', 1],
            ['status', '=', 1],
        ])->select(['name', 'url', 'component'])->orderBy('code', 'asc')->get()->toArray();
        return view($this->view_prefix . 'info', ['infoList' => $list]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infoDetail(Request $request)
    {
        $component = $request->component;
        if($component == 'showAgreement') {
             $this->agreementPdf($request);
        }
        $flow = $this->getCurrentInfo($request);
        $re = [
            'status' => 1,
            'data' => $flow
        ];
        return view($this->view_prefix . 'infoDetail', [
            'component' => $component,
            'data' => $re
        ]);
    }

    /**
     * 图片转base64
     * @param $image_file
     * @return string
     */
    public function base64EncodeImage($image_file)
    {
        $base64_image = '';
        if (!$image_file) return $base64_image;
        $image_file = storage_path().config('mediator.file_root').$image_file;
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }


    /**
     * 最后一步跳转
     * @param $flow_id
     * @return \Illuminate\Routing\Redirector
     */
    public function lastStep($flow_id)
    {
        $flow = FuncMediatorFlow::where('id', $flow_id)->first();
        if ($flow->is_handle == 1) {
            //办理完成跳转控制面板
            return redirect($this->root_url);
        } else {
            //审核完成
            if ($flow->is_check == 1) {
                if ($flow->is_sure == 1) {
                    //确认过比例跳转结果页面
                    return redirect($this->root_url . 'result');
                } else {
                    //未确认比例跳转确认比例页面
                    return redirect($this->root_url . 'confirmRate');
                }
            } else {
                //未审核跳转结果页面
                return redirect($this->root_url . 'result');
            }
        }
    }

    /**
     * 检测身份证
     * @param Request $request
     * @return mixed
     */
    public function checkIdCard(Request $request){
        $card = $request->card;
        if(!validation_filter_id_card($card)){
            return $this->ajax_return(500, '请输入正确的身份证号码');
        }
        $flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($flow_id);
        if($flow->type == 0) {
            $info = FuncMediatorInfo::where([
                ['status', 1],
                ['zjbh', $card],
            ])->first();
            if($info) {
                return $this->ajax_return(500, '该身份证尚在合约期，如您修改了手机号码，请先进行号码变更');
            }
        }
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 检测银行卡
     * @param Request $request
     * @return array
     */
    public function checkBankCard(Request $request){
        $card = $request->card;
        if(!$card){
            return $this->ajax_return(500, '请输入正确的银行卡号');
        }
        $flow_id = $request->session()->get('mediator_flow_id');

        $flow = FuncMediatorFlow::find($flow_id);
        if($flow->type == 1) {
            $mediator_id = $request->session()->get('mediator_id');
            $info = FuncMediatorInfo::find($mediator_id);
            if($info->bank_number != $card) {
                return $this->ajax_return(500, '您输入的银行卡号与之前的不一致，变更银行卡信息请走变更流程');
            }
        }
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 展示协议pdf
     * @param Request $request
     */
    public function agreementPdf(Request $request){
        $flow_id = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($flow_id);
        $url = $flow->agreement_url;
        $file = storage_path().config('mediator.file_root').$url;
        header("Content-Type: application/pdf");
        echo file_get_contents($file);
    }
}