<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\base\BaseWebController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\SysDictionaries;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorPotic;
use App\Models\Index\Mediator\FuncMediatorPoticRecord;
use App\Models\Index\Mediator\FuncMediatorStep;
use App\Models\Index\Common\RpaCaptcha;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mews\Captcha\Facades\Captcha;
use PHPExcel;
use PHPExcel_IOFactory;
use SMS;


/**
 * 居间人
 * Class MediatorController
 * @package App\Http\Controllers\Index
 */
class MediatorController extends BaseWebController
{
    public $viewPrefix = "Index.Mediator.";

    public $rootUrl = "/mediator/";  

    public $config = "";

    const BACK_TO = 'backTo'; // 打回

    public $debug = false;  // 正式部署这个字段需要改成false

    public $vCode = "1234";

    public $limitManagerList = ["2101", "2105"]; // 限制的客户经理工号

    private $limitManagerText = "无法使用该客户经理号"; //客户经理限制文字描述

    public function __construct(Request $request)
    {
        $this->config = config('mediator');
    }

    /**
     * 各个步骤页面展示
     * @param Request $request
     * @param $step
     * @return mixed
     */
    public function showStepView(Request $request, $step){
        if(isset($request->prev)) { // 上一步
            $mediatorInfo = $this->getCurrentFlowInfo($request);
            return view($this->viewPrefix.$step, ['data' => [
                'status' => 1,
                'data' => $mediatorInfo
            ]]);
        }
        $result = $this->autoRedirect($request, $step);
        if(true === $result) {
            $mediatorInfo = $this->getMediatorInfo($request);
            if('perfectInformation' == $step) {
                $flow = $this->getCurrentFlowInfo($request);
                $mediatorInfo['data']['sfz_date_end'] = $flow['sfz_date_end'];
                $mediatorInfo['data']['sfz_address'] = $flow['sfz_address'];
            }
            return view($this->viewPrefix.$step, ['data' => $mediatorInfo]);
        } else {
            return $result;
        }

    }

    /**
     * 登录页面
     * @param Request $request
     * @return mixed
     */
    public function loginView(Request $request)
    {
        $request->session()->pull('mediator_id');
        $request->session()->pull('mediator_flow_id');
        return view($this->viewPrefix . 'login');
    }

    /**
     * 获取图片验证码
     * @param Request $request
     * @return mixed
     */
    public function getImageCode(Request $request)
    {
        return $this->ajax_return(200, '', captcha_src());
    }

    /**
     * 获取验证码
     * @param Request $request
     * @return mixed
     */
    public function getCode(Request $request)
    {
        $phone = $request->post('phone');
        $imgCode = $request->post('icode');
        if (Captcha::check($imgCode)) {
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
     * 发送验证码
     * @param $phone
     * @param $type
     * @return bool
     */
    public function smsCode($phone,$type)
    {
        if($this->debug) return true;
        $code = mt_rand(100000,999999);
        $result = $this->sendSmsSingle($phone, $code);
        if($result === true){
            $captcha = RpaCaptcha::where([
                ['phone', $phone],
                ['type', $type]
            ])->first();
            if($captcha){
                $captcha->code = $code;
                $captcha->count = $captcha->count + 1;
                $captcha->save();
            }else{
                $add = [
                    'phone' => $phone,
                    'code' => $code,
                    'count' => 1,
                    'type' => $type
                ];
                RpaCaptcha::create($add);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 登陆操作
     * @param Request $request
     * @return mixed
     */
    public function doLogin(Request $request)
    {
        $data = $this->get_params($request, ['phone', 'vcode']);
        $captcha = RpaCaptcha::where([['phone', $data['phone']], ['type', '居间人申请']])->first();
        if (($this->debug && $this->vCode == $data['vcode']) || ($captcha && $captcha->code == $data['vcode'])) {
            if($this->debug) {
                $flag = $this->debug;
            } else {
                $flag = time() - strtotime($captcha->updated_at) <= $this->config['captcha_lifetime'];
            }
            if ($flag) {
                //判断是否是第一次登陆
                $info = FuncMediatorInfo::where('phone', $data['phone'])->first();
                if (!$info) {  // 首次登录
                    $info = FuncMediatorInfo::create([
                        'phone' => $data['phone'],
                    ]);

                    //新增流程
                    $flow = FuncMediatorFlow::create([
                        'uid' => $info->id,
                        'type' => 0,
                        'step' => 100
                    ]);
                    $request->session()->put('mediator_flow_id', $flow->id);
                } else {
                    // 注销
                    if($info->status == 3) { // 该用户被注销
                        $agreementEndDate = $info->xy_date_end; // 协议到期时间
                        //获取最后一条注销流程
                        $lastLogOffFlow = FuncMediatorFlow::where([
                            ['type', 3],
                            ['uid', $info->id]
                        ])->orderBy('id', 'desc')->first();
                        if($lastLogOffFlow) {
                            $crmEndDate = $lastLogOffFlow->crmflow_end_time;
                            if(strtotime($crmEndDate) < strtotime($agreementEndDate)) { //协议内 注销
                                // 注销时间满一年才能重新申请
                                if(time() < strtotime($crmEndDate. " +1 year -1 day")) {
                                    return $this->ajax_return(500, '合同期内注销一年后方可重新申请');
                                }
                            }
                        }
                    }
                    //判断是否有未完成的 新签续签流程
                    $flow = FuncMediatorFlow::where([['uid', $info->id], ['is_handle', 0], ['status', 1]])
                        ->whereIn('type', [0, 1])
                        ->orderBy('id', 'desc')->first();
                    if ($flow) {
                        if ($flow->type == 1) { // 续签  需要判断是否到期
                            $endDate = $info->xy_date_end; // 协议到期时间
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
                    } else {
                        $completeFlow = FuncMediatorFlow::where([['uid', $info->id], ['is_handle', 1], ['status', 1]])
                            ->whereIn('type', [0, 1])
                            ->orderBy('id', 'desc')->first();
                        if($completeFlow) {
                            $request->session()->put('mediator_flow_id', $completeFlow->id);
                        } else {
                            //一条已完成都没有  新增流程
                            $flow = FuncMediatorFlow::create([
                                'uid' => $info->id,
                                'type' => 0,
                                'step' => 100
                            ]);
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
     * @desc status 0 无需续签 1 新签 2 续签
     */
    public function indexView(Request $request)
    {
        $request->session()->pull(self::BACK_TO);
        $id = $request->session()->get('mediator_id');
        if(!$id) {
            return redirect($this->rootUrl . 'login');
        }
        $flow = FuncMediatorFlow::where([
            ['uid', $id],
            ['status', 1]
        ])->whereIn('type', [0, 1])->orderBy('id', 'desc')->first();
        if(!$flow) { // 一条正常流程都没有
            $status = '1';
            $message = "您当前只能够新签协议";
        } else {
            $request->session()->put('mediator_flow_id', $flow->id);
            if ($flow->is_handle != 1) return $this->goNext($request);
            $info = FuncMediatorInfo::find($flow->uid);
            //1.判断居间是否过期
            $time = strtotime($info->xy_date_end . " 23:59:59") - time();
            if ($time < 0) {
                //已过期
                $status = '1';
                $message = "您的协议已过期，当前只能新签协议";
            } elseif ($time > 0 && $time <= $this->config['renewal_day'] * 24 * 3600) {
                //已到续签时间
                //2.判断客户经理是否同意续签
                if ($flow->is_manager_agree == 1) {
                    // 培训时长检查
                    $data = $this->getLengthOfMediatorTraining($info->name, $info->number);
                    if($data['code'] == 200 && $data['data']['time'] >= 36000) {
                        $message = "您正在合同期内，并且满足续签条件，当前能够进行续签和查看个人信息";
                        $status = '2.0';
                        
                    } else {
                        $message = "您正在合同期内，并且未满足续签条件(培训时长未满10小时)，当前只能查看个人信息";
                        $status = '0';
                    }
                    
                } else {
                    $message = "您正在合同期内，并且未满足续签条件，请联系客户经理确认，当前只能查看个人信息";
                    $status = '0';
                }
            } else {
                //未到续签时间
                $message = "您正在合同期内，并且未到续签时间，当前只能查看个人信息";
                $status = '0';
            }
            
        }
        return view($this->viewPrefix . 'index', ['status' => $status, 'message' => $message]);
    }

    /**
     * 确认居间比例
     * @param Request $request
     * @return mixed
     */
    public function confirmRateView(Request $request)
    {
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        $isSpecial = $flow->special_rate?1:0;
        return view($this->viewPrefix . 'confirmRate', ['rate' => $flow->rate, 'isSpecial' => $isSpecial]);
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
        if(1 == $request->is_sure){

            if(0 == $flow->type ) { // 新签
                (new \App\Http\Controllers\Admin\Mediator\MediatorController())->to_crm($flow);
            } elseif(1 == $flow->type) {
                (new \App\Http\Controllers\api\Mediator\MediatorApiController())->doTask($flow->instid);
            }
        }
        return $this->ajax_return(200, '操作成功');
    }

    //协议详细  1 居间协议 2 权利义务告知书 3 自律承诺书
    public function agreementDetailView(Request $request, $id)
    {
        $read = $request->get('read', 0);
        return view($this->viewPrefix . 'agreementDetail', ['id' => $id, 'read' => $read]);
    }

    /**
     * 结果页
     * @param Request $request
     * @return mixed
     * @desc $status 1 正常申请完成  2 有打回  3办理完成
     */
    public function resultView(Request $request)
    {
        $request->session()->pull(self::BACK_TO);
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        $steps = FuncMediatorStep::all();
        if ($flow->is_handle) {
            $status = 3;
        } else {
            if($flow->is_check && !$flow->is_sure ) { // 被审核 且 需要确认居间比例
                return redirect($this->rootUrl . 'confirmRate');
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


        return view($this->viewPrefix . 'result', [
            'backList' => $backList,
            'status' => $status
        ]);
    }

    /**
     * 保存信息
     * @param Request $request
     * @return mixed
     * @desc type  0 新签  1续签
     */
    public function doInfo(Request $request)
    {
        $info = $request->all();
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $mediatorId = $request->session()->get('mediator_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId); // 流程表
        if(isset($info['zjbh'])) {
            $info['zjbh'] = strtoupper($info['zjbh']);
        }

        if(isset($info['is_exam']) && 0 == $info['is_exam']) {
            $info['exam_img'] = '';
        }

        $type = $flow->type;
        if(0 === $type) { //新签 需要同步info表
            if(isset($info['manager_number'])) {
                $result = $this->checkManagerNumber($info['manager_number']);
                if($result) { // 直销客户
                    return $this->ajax_return(500, $this->limitManagerText);
                }
            }

            $baseInfo = $info;
            unset(
                $baseInfo['func'],
                $baseInfo['is_video'],
                $baseInfo['is_answer']
            );
            FuncMediatorInfo::where('id', $mediatorId)->update($baseInfo);
        }
        unset($info['name']);
        if ($request->session()->get(self::BACK_TO)) { // 打回
            $backList = $flow->back_list;
            if ($backList) {
                $backList = explode(',', $backList);
            }

            $index = array_search($info['func'], $backList);
            unset($backList[$index]);
            $info['back_list'] = implode(',', $backList);
            if (empty($backList)) { // 打回列表已经全处理完
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
        FuncMediatorFlow::where('id', $mediatorFlowId)->update($info);
        return $this->ajax_return(200, '数据录入成功');
    }

    /**
     * 身份证识别进一步处理
     * @param $path
     * @param string $side
     * @return mixed
     */
    public function idCardOCR($path, $side)
    {
        $result = parent::idCardOCR($path, $side);
        $status = $result['image_status']; // reversed_side 表示传反了
        $result = $result['words_result'];
        if($side == 'front') { // 正面
            return  [
                'name' => isset($result['姓名'])?$result['姓名']['words']:'',
                'zjbh' => isset($result['公民身份号码'])?$result['公民身份号码']['words']:'',
                'sex' => isset($result['性别'])?$result['性别']['words']:'',
                'sfz_address' => isset($result['住址'])?$result['住址']['words']:'',
                'birthday' => isset($result['出生'])?date('Y-m-d', strtotime($result['出生']['words'])):'',
                'status' => $status
            ];
        } else { // 反面
            $endDate = isset($result['失效日期'])?$result['失效日期']['words']:'';
            if($endDate) {
                if('长期' == $endDate) {
                    $endDate = '2099-12-31';
                } else {
                    $endDate = date('Y-m-d', strtotime($endDate));
                }
            }
            return [
                'status' => $status,
                'sfz_date_end' => $endDate
            ];
        }
    }

    /**
     * 银行卡进一步识别
     * @param $path
     * @return mixed
     */
    public function bankCardOCR($path) {
        $result = parent::bankCardOCR($path);
        $bankCardNumber = '';
        $bankCardName = '';
        if(isset($result['result'])) {
            $r = $result['result'];
            if($r['bank_card_number']) $bankCardNumber = str_replace(' ', '', $r['bank_card_number']);
            if($r['bank_name']) $bankCardName = $r['bank_name'];
        }
        return [
            'bankCardNumber' => $bankCardNumber,
            'bankCardName' => $bankCardName,
        ];
    }

    /**
     * 文件上传
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $mediatorId = $request->session()->get('mediator_id');
        $type = $request->type;
        $date = date("Y-m-d");
        $file = $request->file('file');
        $size = $file->getClientSize();
        if($size < 1024) { // 图片小于1k
            return $this->ajax_return(500, '图片上传失败');
        }
        $data = [];
        if('bank_img' == $type) {
            $data = $this->bankCardOCR($file->getRealPath());
        } elseif('sfz_zm_img' == $type) { // 身份证正面
            $data = $this->idCardOCR($file->getRealPath(), 'front');
            if('reversed_side'  == $data['status']) return $this->ajax_return(500, '您的证件照传反了');
        } elseif('sfz_fm_img' == $type) { // 身份证正面
            $data = $this->idCardOCR($file->getRealPath(), 'back');
            if('reversed_side'  == $data['status']) return $this->ajax_return(500, '您的证件照传反了');
        }
        $ext = $file->getClientOriginalExtension();
        $pathname = '/temp/' . md5($this->config['secret_key'] . $mediatorId) . "/" . $date;
        $filename = $type . "." . $ext;
        $path = $pathname."/".$filename;
        Storage::disk('local')->putFileAs('mediator', $file, $path);
        return $this->ajax_return(200, '上传成功', [
            'path' => $path,
            'data' => $data
        ]);
    }

    /**
     * 获取部门
     * @return array
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
        $record = [];
        $err = [];
        foreach ($data as $k => $v) {
            $record[] = $v['option'];
            $potic = FuncMediatorPotic::where('id', $v['id'])->first();
            if (strtoupper($potic->answer) != strtoupper($v['option'])) {
                array_push($err, $v['id']);
            }
        }
        FuncMediatorPoticRecord::create([
            'uid' => $request->session()->get('mediator_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'record' => implode(',', $record),
            'error' => implode(',', $err),
        ]);
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
     * @return mixed|void
     */
    public function goNext(Request $request)
    {
        if ($request->back || $request->session()->get(self::BACK_TO)) { // 被打回
            $request->session()->put(self::BACK_TO, true);
        }
        return $this->autoRedirect($request);
    }

    /**
     * 返回上一步
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function goBack(Request $request){

        $flowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($flowId);
        if($request->session()->get(self::BACK_TO)) {
            $backList = $flow->back_list; // a,b,c形式
            if ($backList) {
                $backList = explode(',', $backList);
                return redirect($this->rootUrl. 'showStepView/'. $backList[0]); // 跳转到打回第一步
            }
        }
        $currentStep = FuncMediatorStep::where('code', $flow->step)->first();
        $step = FuncMediatorStep::where('code', '<', $flow->step)->orderBy('id', 'desc')->first();
        if($step) {
            $flow->step = $step->code;
            $flow->save();
        }

        return redirect($this->rootUrl. 'showStepView/'. $currentStep->url.'?prev=1'); // 跳转到打回第一步
    }

    /**
     * 控制面板访问跳转
     * @param Request $request
     * @return mixed
     */
    public function panelSkip(Request $request)
    {
        $status = $request->status;
        $id = $request->session()->get('mediator_id');
        $flow = FuncMediatorFlow::where([['uid', $id], ['is_handle', 0], ['status', 1]])
            ->whereIn('type', [0, 1])
            ->orderBy('id', 'desc')->first();
        if($flow) { // 表示已经添加了一次 直接跳转 
            $request->session()->put('mediator_flow_id', $flow->id);
            return $this->goNext($request);
        }
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
    private function getMediatorInfo(Request $request)
    {
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        if(!$mediatorFlowId) return redirect($this->rootUrl . 'login');
        $flow = FuncMediatorFlow::find($mediatorFlowId)->toArray();
        if($request->session()->get(self::BACK_TO)) { // 打回 获取当前流程
            $dept = SysDept::find($flow['dept_id']);
            $flow['dept'] = $dept?$dept->name:'';
            $flow['name'] =  FuncMediatorInfo::find($flow['uid'])->name;
            foreach ($flow as $k => $v) {
                if (strpos($k, 'img') > -1) {
                    $flow[$k . '_base64'] = $this->buildPath($v);
                }
            }
            $re = [
                'status' => 1,
                'data' => $flow
            ];
        } else {
            $info = FuncMediatorInfo::find($flow['uid'])->toArray();
            if ($flow['type'] == 0) {
                //新签
                $re = [
                    'status' => 0,
                    'data' => $info
                ];
            } else {
                //续签
                $dept = SysDept::find($flow['dept_id']);
                $info['dept'] = $dept?$dept->name:'';
                $edu = SysDictionaries::where([
                    ['type', 'education'],
                    ['value', $info['edu_background']]
                ])->first();
                if(!$edu) {
                    $info['edu_background'] = '';
                }
                foreach ($info as $k => $v) {
                    if (strpos($k, 'img') > -1) {
                        $info[$k . '_base64'] = $this->buildPath($v);
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
    private function getCurrentFlowInfo(Request $request)
    {
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId)->toArray();
        //客户
        $user = FuncMediatorInfo::find($flow['uid']);
        $flow['phone'] = $user->phone;
        $flow['zjbh'] = $user->zjbh;
        $flow['name'] = $user->name;
        $dept = SysDept::find($flow['dept_id']);
        $flow['dept'] = $dept ? $dept->name : '';
        foreach ($flow as $k => $v) {
            if (strpos($k, 'img') > -1) {
                $flow[$k . '_base64'] = $this->buildPath($v);
            }
        }
        return $flow;
    }

    /**
     * 查看信息页面
     * @param Request $request
     * @return mixed
     */
    public function infoView(Request $request)
    {
        $flow_id = $request->session()->get('mediator_flow_id');
        if (!$flow_id) return redirect($this->rootUrl . 'login');
        $flow = FuncMediatorFlow::find($flow_id);
        if ($flow->is_handle != 1) {
            return $this->goNext($request);
        }
        $list = FuncMediatorStep::where([
            ['is_show', '=', 1],
            ['status', '=', 1],
        ])->select(['name', 'url', 'component'])->orderBy('code', 'asc')->get()->toArray();
        return view($this->viewPrefix . 'info', ['infoList' => $list]);
    }

    /**
     * 信息单个查询
     * @param Request $request
     * @return mixed
     */
    public function infoDetailView(Request $request)
    {
        $component = $request->component;
        if($component == 'showAgreement') { // 查看协议
             $this->agreementPdf($request);
        }
        $flow = $this->getCurrentFlowInfo($request);
        $re = [
            'status' => 1,
            'data' => $flow
        ];
        return view($this->viewPrefix . 'infoDetail', [
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
        if(file_exists($image_file)) {
            $image_info = getimagesize($image_file);
            $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
            $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
            return $base64_image;
        } else {
            return "";
        }
    }

    /**
     * 最后一步跳转
     * @param $mediatorFlowId
     * @return \Illuminate\Routing\Redirector
     */
    public function lastStep($mediatorFlowId)
    {
        $flow = FuncMediatorFlow::where('id', $mediatorFlowId)->first();
        if ($flow->is_handle == 1) {
            //办理完成跳转控制面板
            return redirect($this->rootUrl);
        } else {
            //审核完成
            if ($flow->is_check == 1) {
                if ($flow->is_sure == 1) {
                    //确认过比例跳转结果页面
                    return redirect($this->rootUrl . 'result');
                } else {
                    //未确认比例跳转确认比例页面
                    return redirect($this->rootUrl . 'confirmRate');
                }
            } else {
                //未审核跳转结果页面
                return redirect($this->rootUrl . 'result');
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
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        if($flow->type == 0) {
            $info = FuncMediatorInfo::where([
                ['status', 1],
                ['zjbh', $card],
            ])->first();
            if($info) {
                return $this->ajax_return(500, '如您修改了手机号码，请先进行号码变更');
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
        $bankBranch = $request->bankBranch;
        if(!$card){
            return $this->ajax_return(500, '请输入正确的银行卡号');
        }
        $mediatorFlowId = $request->session()->get('mediator_flow_id');

        $flow = FuncMediatorFlow::find($mediatorFlowId);
        if($flow->type == 1) {
            $mediatorId = $request->session()->get('mediator_id');
            $info = FuncMediatorInfo::find($mediatorId);
            if($info->bank_number != $card || $info->bank_branch != $bankBranch) {
                return $this->ajax_return(500, '您输入的银行卡号或开户网点与之前的不一致，变更银行卡信息请走变更流程');
            }
        }
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 展示协议pdf
     * @param Request $request
     */
    public function agreementPdf(Request $request){
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        $url = $flow->agreement_url;
        $file = storage_path().config('mediator.file_root').$url;
        header("Content-Type: application/pdf");
        echo file_get_contents($file);
    }

    /**
     * 自动跳转到指定步骤
     * @param Request $request
     * @param bool $step
     * @return mixed|void
     */
    public function autoRedirect(Request $request, $step = false){

        $mediatorId = $request->session()->get('mediator_id'); //info表 pk
        $mediatorFlowId = $request->session()->get('mediator_flow_id'); //flow表 pk
        if (!$mediatorId || !$mediatorFlowId) { // session不存在的时候跳出登录
            return redirect($this->rootUrl . 'login');
        }
        $flow = FuncMediatorFlow::where('id', $mediatorFlowId)->first();
        if ($request->session()->get(self::BACK_TO)) {
            $this->config = config('mediator');

            $backList = $flow->back_list; // a,b,c形式
            if ($backList) {
                $backList = explode(',', $backList);
                if($backList[0] == $step) {
                    return true;
                } else {
                    return redirect($this->rootUrl. 'showStepView/'. $backList[0]); // 跳转到打回第一步
                }
            }
        } else {
            $steps = FuncMediatorStep::orderBy('code', 'asc')->get();
            foreach ($steps as $k => $v) {
                if ($flow->step == $v['code']) {
                    if (!isset($steps[$k + 1])) { // 没有下一步 跳转到最后一步
                        return $this->lastStep($mediatorFlowId);
                    } else { // 跳转下一步
                        if($step) {
                            if($step != $steps[$k+1]->url) {
                                return  redirect($this->rootUrl. 'showStepView/'. $steps[$k + 1]->url);
                            } else {
                                return true;
                            }
                        } else {
                            return  redirect($this->rootUrl. 'showStepView/'. $steps[$k + 1]->url);
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function test(Request $request){
        $xlsPath = __DIR__."/1.xls";
        $xlsReader = PHPExcel_IOFactory::createReader("Excel5");
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($xlsPath);
        $dept= "金融科技部";
        $list = $Sheets->getSheet(0)->toArray();
        $filename = "2.csv";
        array_shift($list);
        $header = "营业部,居间人姓名,居间人编号,身份证正面是否存在,身份证反面是否存在\r\n";
        file_put_contents($filename, $header."\r\n", FILE_APPEND);
        foreach($list as  $k => $v) {
            $yyb = trim($v[0]);
            $name = trim($v[1]);
            $number = trim($v[2]);
            $info = FuncMediatorInfo::where('number', $number)->first();
            $z = '否';
            $f = '否';
            if($info) {
                $zm = $info->sfz_zm_img;
                $fm = $info->sfz_fm_img;
                $imgZm = storage_path().config('mediator.file_root').$zm;
                $imgFm = storage_path().config('mediator.file_root').$fum;
                if(file_exists($imgZm)) $z = '是';
                if(file_exists($imgFm)) $f = '是';
            }
            $item = "{$yyb},{$name},{$number},{$z},{$f}";
            file_put_contents($filename, $item."\r\n", FILE_APPEND);
        }

        
    }

    /**
     * @param Request $request
     * 展示图片
     */
    public function showImage(Request $request){
        $url = decrypt($request->url);
        $url = storage_path().config('mediator.file_root').$url;
        if(file_exists($url)) {
            $info = getimagesize($url);
            $mime = $info['mime'];
            header("Content-type:$mime");
            echo file_get_contents($url);
        } else {
            echo "";
        }
    }

    /**
     * 构建图片地址
     * @param $image
     * @return string
     */
    public function buildPath($image){
        if(!$image) return '';
        $url = storage_path().config('mediator.file_root').$image;
        if(file_exists($url)) {
            return $this->rootUrl ."showImage?url=".encrypt($image);
        } else {
            return '';
        }
    }

    /**
     * 保存协议
     * @param Request $request
     * @return array
     */
    public function saveAgreement(Request $request){
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        $agreement = $flow->agreement??"";
        if($agreement == '' || !(strpos("{$request->agreement}", $agreement) >-1)) {
            $flow->agreement = $agreement."{$request->agreement}";
            $flow->save();
        }
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 获取用户协议
     * @param Request $request
     * @return array
     */
    public function getAgreement(Request $request) {
        $mediatorFlowId = $request->session()->get('mediator_flow_id');
        $flow = FuncMediatorFlow::find($mediatorFlowId);
        $agreement = $flow->agreement??"";
        return $this->ajax_return(200, '查询成功', $agreement);
    }


    /**
     * 同步居间人培训时长
     * @param Request $request
     * @return array
     */
    public function syncTrainingDuration(Request $request){
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => "select * from TXCTC_JJR where XM = '苏晓锋'"
            ]
        ];
        $list = $this->getCrmData($param);
        if(!$list) {
            return [
                'code' => 201,
                'message' => '没有更多的数据'
            ];
        }
        $data = [];
        foreach ($list as $k => $v) {
            $data[] = [
                'begintime' => strtotime($v['XYKSRQ']),
                'endtime' => strtotime($v['XYJSRQ'])+86400,
                'name' => $v['XM'],
                'number' => $v['BH'],
            ];
        }
        $guzzle = new Client();
        $response = $guzzle->post('http://api.hatzjh.com/live/getmedinfo',[
            'query' => [
                'username' => 'haqhJJCX',
                'password' => 'JJCXMediator',
                '_time' => 1,
            ],
            'form_params' => [
                'data' => json_encode($data)
            ]
        ]);
        $body = $response->getBody();
        $result = json_decode((string)$body,true);
        if(!$result) {
            return [
                'code' => 500,
                'message' => '获取居间培训时长接口异常'
            ];
        }
        if(isset($result['code'])) { // 表示有问题
            return [
                'code' => 500,
                'message' => "获取居间培训时长接口异常:".$result['msg']
            ];
        }
        print_r($result);die;
        $crmData = [];
        foreach ($data as $k => $v) {
            $time = 200 == $result[$k]['code']?$result[$k]['total_time']:0;
            $crmData[] = [
                'name' => $v['name'],
                'number' => $v['number'],
                'time' =>$time
            ];
        }
        $param = [
            'type' => 'jjr',
            'action' => 'SyncTrainingDuration',
            'param' => [
                'date' => date('Ymd'),
                'data' => json_encode($crmData)
            ]
        ];
        $crmResult = $this->getCrmData($param);
        if(isset($crmResult['code']) && 200 == $crmResult['code']) {
            return [
                'code' => 200,
                'message' => '同步成功'
            ];
        } else {
            return [
                'code' => 500,
                'message' => '同步失败'
            ];
        }
    }

    /**
     * 检测是否为直销员工
     * @param $manager_number
     * @return bool true 表示直销客户
     */
    private function checkManagerNumber($managerNumber)
    {
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => "select * from TXCTC_YGXX where ZXTD is not null and BH = '$managerNumber'"
            ]
        ];
        $crmResult = $this->getCrmData($param);
        return $crmResult?true:false;
    }
}