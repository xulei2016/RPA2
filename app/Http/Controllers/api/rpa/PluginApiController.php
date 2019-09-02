<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\RpaCrmFlow;
use App\Models\Admin\Api\RpaCustomerInfo;
use App\Models\Admin\Api\RpaFlow;
use App\Models\Admin\Api\RpaShixincfa;
use App\Models\Admin\Api\RpaShixinsf;
use App\Models\Admin\Rpa\rpa_immedtasks;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PluginApiController extends BaseApiController
{
    /**
     * oa流程保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function oa_flow_save(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'file_id' => 'required|integer',
            'title' => 'required',
            'customer_fundsnum' => 'required|integer',
            'flownum' => 'required'
        ]);

        $flows = RpaFlow::where("file_id",$request->file_id)->first();
//        流程已经存在，无需写入数据，和发布任务
        if($flows){
            $re = [
                'status' => 200,
                'msg' => '流程已经存在,无需重新写入！'
            ];
        }else{
            $data = [
                'title' => $request->title,
                'fundAccount' => $request->customer_fundsnum,
                'flownum' => $request->flownum,
                'username' => $request->customer_name,
                'file_id' => $request->file_id,
                'operator' => Auth::user()->name
            ];
            $res = RpaFlow::create($data);
            if($res){
                //添加rpa及时任务
                $param = [
                    'exename' => '签名照片',
                    'account' => $request->customer_fundsnum
                ];
                $data = [
                    'name' => 'SignPicture',
                    'jsondata' => json_encode($param)
                ];

                $res = rpa_immedtasks::create($data);
                if($res){
                    $re = [
                        'status' => 200,
                        'msg' => 'rpa任务发布成功！'
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => 'rpa任务发布失败，请联系金融科技部！'
                    ];
                }
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '流程写入失败，请联系金融科技部！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * oa流程获取签名照
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function oa_get_sign(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'file_id' => 'required|integer'
        ]);

        $flow = RpaFlow::where("file_id",$request->file_id)->first();
        if(!empty($flow->customer->sign)){
            $baseImg = $this->base64EncodeImage("D:/uploadFile/customerImg/".$flow->customer->sign);
            if(!$baseImg){
                $re = [
                    'status' => 500,
                    'msg' => '图片文件未找到！'
                ];
            }else{
                $re = [
                    'status' => 200,
                    'msg' => $baseImg
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '未找到该流程签名照！'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * crm流程保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crm_flow_save(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'title' => 'required',//流程标题
            'name' => 'required',//名称
            'number' => 'required',//编号
            'file_id' => 'required',//文件id
            'work_id' => 'required',//文件id
            'type' => 'required'//类型  kh客户 jjr居间人
        ]);

        $flow = RpaCrmFlow::where([["file_id",$request->file_id],['work_id',$request->work_id]])->first();
        if($flow){
            $re = [
                'status' => 200,
                'msg' => '数据已存在！'
            ];
        }else {
            $data = [
                'title' => $request->title,
                'name' => $request->name,
                'number' => $request->number,
                'work_id' => $request->work_id,
                'file_id' => $request->file_id,
                'type' => $request->type,
                'operator' => Auth::user()->name
            ];
            $flow = RpaCrmFlow::create($data);
            if ($flow) {
                //如果是客户签名，需要发布任务
                if ($request->type == 'kh') {
                    $customer = RpaCustomerInfo::where("fundAccount",$request->number)->first();
                    if (!$customer) {
                        //添加rpa及时任务
                        $param = [
                            'exename' => '签名照片',
                            'account' => $request->number
                        ];
                        $data = [
                            'name' => 'SignPicture',
                            'jsondata' => json_encode($param, JSON_UNESCAPED_UNICODE)
                        ];
                        $res = rpa_immedtasks::create($data);
                        if (!$res) {
                            $re = [
                                'status' => 500,
                                'msg' => 'rpa任务发布失败'
                            ];

                            //api日志
                            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                            return response()->json($re);
                        }
                    }
                }
                $re = [
                    'status' => 200,
                    'msg' => '数据写入成功！'
                ];
            }else {
                $re = [
                    'status' => 500,
                    'msg' => '数据写入失败，请联系软件工程部！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * crm流程获取签名照
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crm_get_sign(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'file_id' => 'required',//文件id
            'work_id' => 'required'//文件id
        ]);

        $flow = RpaCrmFlow::where([["file_id",$request->file_id],["work_id",$request->work_id]])->first();
        if($flow){
            //判断是找的客户还是居间人
            if($flow->type == "kh"){
                $customer = RpaCustomerInfo::where("fundAccount",$flow->number)->first();
                if($customer){
                    $baseImg = $this->base64EncodeImage("D:/uploadFile/customerImg/".$customer->sign);
                    if(!$baseImg){
                        $re = [
                            'status' => 500,
                            'msg' => '图片文件未找到！'
                        ];
                    }else{
                        $re = [
                            'status' => 200,
                            'msg' => $baseImg
                        ];
                    }
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => '未找到该资金账号的客户！'
                    ];
                }
            }else{
                $sql = "select signatureimg from oa_mediator where number =".$flow->number;
                $res = DB::connection("oa")->select($sql);
                if($res){
                    $res = $res[0];
                    $guzzle = new Client();
                    $response = $guzzle->post('http://172.16.253.26/interface/crm/getMediatorImg.php',[
                        'form_params' => [
                            "filename" => $res->signatureimg
                        ],
                    ]);
                    $body = $response->getBody();
                    $body = (string)$body;

                    $re = [
                        'status' => 200,
                        'msg' => $body
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => "未找到该居间人"
                    ];
                }
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '未找到该流程签名照！'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 失信查询
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function credit(Request $request)
    {
        //ip检测
        $res = $this->check_ip("credit",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required',
            'type' => 'required|in:1,2'
        ]);

        $time = date("Y-m-d",time());
        //期货
        $cfa = RpaShixincfa::where([["updatetime",$time],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //证券
        $sf = RpaShixinsf::where([["updatetime",$time],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
       
        //期货证券同时存在，且无失败记录，无需发布任务
        if(isset($cfa) && isset($sf) && ($cfa->state != -1) && ($sf->state != -1)){
            if($request->type == 1){
                $re = [
                    'status' => 200,
                    'msg' => '数据已存在，无需发布任务'
                ];
            }else{
                //期货
                if($cfa){
                    $opera1 = json_decode($cfa->operator,true);
                    if(is_array($opera1)){
                        $opera1[] = [
                            'name' => Auth::user()->name,
                            'date' => date('Y-m-d H:i:s',time())
                        ];
                    }
                    $data1 = [
                        'operator' => json_encode($opera1,JSON_UNESCAPED_UNICODE),
                        'count' => $cfa->count + 1
                    ];
                    RpaShixincfa::where("id",$cfa->id)->update($data1);
                }
                //证券
                if($sf){
                    $opera2 = json_decode($sf->operator,true);
                    if(is_array($opera2)){
                        $opera2[] = [
                            'name' => Auth::user()->name,
                            'date' => date('Y-m-d H:i:s',time())
                        ];
                    }

                    $data2 = [
                        'operator' => json_encode($opera2,JSON_UNESCAPED_UNICODE),
                        'count' => $sf->count + 1
                    ];
                    RpaShixinsf::where("id",$sf->id)->update($data2);
                }
                
                $re = [
                    'status' => 200,
                    'qh' => $cfa->state,
                    'zq' => $sf->state
                ];
            }
        }else{
            if($request->type == 2){
                //判断是否存在任务运行失败情况
                if(!isset($cfa) || !isset($sf)){
                    $re = [
                        'status' => 500,
                        'msg' => '未找到数据!'
                    ];
                }elseif($cfa->state == -1 || $sf->state == -1){
                    $re = [
                        'status' => 500,
                        'msg' => '期货或证券有任务运行失败'
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => '未找到数据!'
                    ];
                }
            }else{
                //添加rpa及时任务
                $param = [
                    'name' => $request->name,
                    'idCard' => $request->idCard,
                    'operator' => Auth::user()->name
                ];
                if(!isset($cfa) || $cfa->state == -1){
                    $data1 = [
                        'name' => 'SupervisionCFA_im',
                        'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                    ];
                    $res1 = rpa_immedtasks::create($data1);
                }
                if(!isset($sf) || $sf->state == -1){
                    $data2 = [
                        'name' => 'SupervisionSF_im',
                        'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                    ];
                    $res2 = rpa_immedtasks::create($data2);
                }
                
                $re = [
                    'status' => 200,
                    'msg' => 'rpa任务发布成功！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 保存客户信息及影像资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_customer_info(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'zjzh' => 'required',
        ]);

        $sign_base64 = isset($request->sign_base64) ? $request->sign_base64 : "";
        $sfz_zm_base64 = isset($request->sfz_zm_base64) ? $request->sfz_zm_base64 : "";
        $sfz_fm_base64 = isset($request->sfz_fm_base64) ? $request->sfz_fm_base64 : "";
        $head_zm_base64 = isset($request->head_zm_base64) ? $request->head_zm_base64 : "";
        //图片转码保存

        $data = [
            'fundAccount' => $request->zjzh,
            'operator' => Auth::user()->name,
            'state' => 2,
            'updated_at' => time()
        ];
        $date_path = date("Y")."/".date("Ym")."/".date("Ymd")."/";
        //设置传入参数
        $s = 1;
        $flag = false;
        $result = RpaCustomerInfo::where("fundAccount",$data['fundAccount'])->first();
        if($result){
            //更新
            if(!empty($sign_base64)){
                $sign = $this->base64ToImage($sign_base64,"/customerImg/sign/".$date_path,$result['sign']);
                $data['sign'] = 'sign/'.$date_path.$sign['log_img'];
                $flag = $sign['flag'];
            }
            if(!empty($sfz_zm_base64)){
                $sfz_zm = $this->base64ToImage($sfz_zm_base64,"/customerImg/sfz_zm/".$date_path,$result['sfz_zm']);
                $data['sfz_zm'] = 'sfz_zm/'.$date_path.$sfz_zm['log_img'];
                $flag = $sfz_zm['flag'];
            }
            if(!empty($sfz_fm_base64)){
                $sfz_fm = $this->base64ToImage($sfz_fm_base64,"/customerImg/sfz_fm/".$date_path,$result['sfz_fm']);
                $data['sfz_fm'] = 'sfz_fm/'.$date_path.$sfz_fm['log_img'];
                $flag = $sfz_fm['flag'];
            }
            if(!empty($head_zm_base64)){
                $head_zm = $this->base64ToImage($head_zm_base64,"/customerImg/head_zm/".$date_path,$result['head_zm']);
                $data['head_zm'] = 'head_zm/'.$date_path.$head_zm['log_img'];
                $flag = $head_zm['flag'];
            }
            $aff = RpaCustomerInfo::where("fundAccount",$data['fundAccount'])->update($data);
            if(!$aff){
                $s = 0;
            }
        }else{
            //添加
            if(!empty($sign_base64)){
                $sign = $this->base64ToImage($sign_base64,"/customerImg/sign/".$date_path);
                $data['sign'] = 'sign/'.$date_path.$sign['log_img'];
                $flag = $sign['flag'];
            }
            if(!empty($sfz_zm_base64)){
                $sfz_zm = $this->base64ToImage($sfz_zm_base64,"/customerImg/sfz_zm/".$date_path);
                $data['sfz_zm'] = 'sfz_zm/'.$date_path.$sfz_zm['log_img'];
                $flag = $sfz_zm['flag'];
            }
            if(!empty($sfz_fm_base64)){
                $sfz_fm = $this->base64ToImage($sfz_fm_base64,"/customerImg/sfz_fm/".$date_path);
                $data['sfz_fm'] = 'sfz_fm/'.$date_path.$sfz_fm['log_img'];
                $flag = $sfz_fm['flag'];
            }
            if(!empty($head_zm_base64)){
                $head_zm = $this->base64ToImage($head_zm_base64,"/customerImg/head_zm/".$date_path);
                $data['head_zm'] = 'head_zm/'.$date_path.$head_zm['log_img'];
                $flag = $head_zm['flag'];
            }
            $info = RpaCustomerInfo::create($data);
            if(!$info){
                $s = 0;
            }
        }
        if($flag){
            if($s && $flag){
                $re = [
                    'status' => 200,
                    'msg' => '图片保存成功'
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '数据写入失败，请联系金融科技部！'
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '图片保存失败！'
            ];
        }
        

        //api日志
        //$this->apiLog(__FUNCTION__,$request,$re['status'],$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 客户回访分配任务
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customer_review(Request $request)
    {
        //ip检测
        $res = $this->check_ip("customer_review",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'cookie' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'history' => 'required',
            'bfb' => 'required',
            'name_list' => 'required',
        ]);

        $datas = [
            'cookie'=>$request->cookie,
            'startDate' => date('Y-m-d',strtotime($request->startDate,time())),
            'endDate' => date('Y-m-d',strtotime($request->endDate,time())),
            'history' => $request->history,
            'bfb' => $request->bfb,
            'name_list' => trim($request->name_list, ',')
        ];

        $data = [
            'name' => "CustomerReview",
            'jsondata' => json_encode($datas, JSON_UNESCAPED_UNICODE)
        ];
        //插入即时任务
        $res = rpa_immedtasks::create($data);
        if($res){
            $return = [
                'status' => 200,
                'msg' => "任务发布成功"
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => "任务发布失败"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取历史开户及其风险要素
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function open_history(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required'
        ]);

        $post_data = [
            'type' => 'customer',
            'action' => 'open_history',
            'param' => [
                'idCard' => $request->idCard,
                'name' => $request->name
            ]
        ];
        $result = $this->getCrmData($post_data);
        $re = [
            'status' => 200,
            'data' => $result,
            'halfYearOld' => date("Ymd",strtotime("-6 month"))
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取居间人信息返回数组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediator_info(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'manager_number' => 'required'
        ]);
        $mediator_number = isset($request->mediator_number) ? $request->mediator_number : 0;

        $post_data = [
            'type' => 'jjr',
            'action' => 'get_jjr_for_array',
            'param' => [
                'mediator_number' => $mediator_number,
                'manager_number' => $request->manager_number
            ]
        ];
        $result = $this->getCrmData($post_data);

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($result,true),$request->getClientIp());

        return response()->json($result);
    }

    /**
     * 获取居间人信息返回字符串
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function mediator_info2(Request $request)
    {
        //ip检测
        $res = $this->check_ip("mediator_info2",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'number' => 'required',
        ]);

        $post_data = [
            'type' => 'jjr',
            'action' => 'get_jjr_for_string',
            'param' => [
                'number' => $request->number
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $re = [
                'status' => 200,
                'msg' => $result
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '居间人不存在或无效居间！' 
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 反洗钱插件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fxq(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'customernum' => 'required',
        ]);
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['KHH','=',$request->customernum]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if(empty($result)){
            $re = [
                'status' => 500,
                'msg' => "未找到客户"
            ];
            //api日志
            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
            return response()->json($re);
        }
        $result = $result[0];

        $result['XLMC'] = $result['XLDM'];
        $result['ZYMC'] = $result['ZYDM'];

        if(!isset($request->ie)){
            $re = [
                'status' => 200,
                'msg' => $result
            ];
            //api日志
            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
            return response()->json($re);
        }


        //////////////////////////////////////////////////////////下为网页版输出/////////////////////////////////////////////////////////////

        //模板输出
        $khfs = ($result['ZY']=='互联网开户扩展信息登记') ? '互联网' : '<span style="color:red;">未识别</span>' ;
        $age = $this->get_age($result['ZJBH']);

        //模板1
        $model['1'] = "<span style='color:red;'>——{$result['KHXM']}</span><br/>经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result['KHRQ']}通过{$khfs}方式开户，客户为普通投资者，身份证号码为{$result['ZJBH']}，年龄为{$age}岁，学历为{$result['XLDM']}。<br/>客户地域：客户身份地址为：{$result['SFZDZ']}，联系地址为：{$result['DZ']}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result['ZYDM']}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。<br/>根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";

        //模板2
        $model['2'] = "经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result['KHRQ']}通过{$khfs}方式开户，客户为普通投资者，客户关联XX银行和XX银行,身份证号码为{$result['ZJBH']}，年龄为{$age}岁，学历为{$result['XLDM']}。<br/>客户地域：客户身份地址为：{$result['SFZDZ']}，联系地址为：{$result['DZ']}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result['ZYDM']}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。<br/>根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";

        //模板3
        $model['3'] = "经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result['KHRQ']}通过{$khfs}方式开户，客户为普通投资者，身份证号码为{$result['ZJBH']}，年龄为{$age}岁，学历为{$result['XLDM']}。<br/>客户地域：客户身份地址为：{$result['SFZDZ']}，联系地址为：{$result['DZ']}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result['ZYDM']}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。客户身份证有效期为{$result['ZJJSRQ']}，我司已跟客户提示，要求其提供最新有效身份证,根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";

        $re = [
            'status' => 200,
            'msg' => $model
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }

    /**
     * 复核同步客户数据到crm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync_data(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'fundsNum' => 'required|numeric'
        ]);

        $data = [
            'yybName' => isset($request->yyb) ? $request->yyb : "",
            'jjrNum' => isset($request->jjr) ? $request->jjr : "",
            'name' => isset($request->name) ? $request->name : "",
            'idCard' =>isset($request->idCard) ? $request->idCard : "",
            'customerNum' =>isset($request->customerNum) ? $request->customerNum : "",
            'fundsNum' =>$request->fundsNum,
            'message' =>isset($request->message) ? $request->message : "",
            'creater' =>$request->getClientIp(),
            'add_time' => date('Y-m-d h:i:s'),
            'special' => trim($request->special,','),
            'is_visit' => 0
        ];
        //判断是否强制提交，如果是强制提交。验证错误时返回状态为true
        $comellent_submit = isset($request->comellent_submit) ? $request->comellent_submit : "";
        if($comellent_submit){
            $status = 200;
        }else{
            $status = 500;
        }
        //判断是否已提交
        //判断资金账号
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['ZJZH','=',$data['fundsNum']]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            //判断身份证号
            if($result[0]['ZJBH'] != $data['idCard']){
                $re = [
                    'status' => 500,
                    'msg' => "该资金账号已被占用！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
                return response()->json($re);
            }
        }
        // 判断特殊开户是否存在
        $sql = "select * from oa_customer_manager where fundsNum = ".$data['fundsNum'];
        $res = DB::connection("oa")->select($sql);
        if($res){
            foreach($res as $v){
                $arr1 = explode(",",$v->special);
                $arr2 = explode(",",$data['special']);
                $same = array_intersect($arr1,$arr2);
                if(!empty($same)){
                    $re = [
                        'status' => $status,
                        'msg' => "同一个客户不能开相同的特殊品种！！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                    return response()->json($re);
                }
            }
        }
        //非特殊客户，crm待处理信息表是否有相同资金账号
        if(!$data['special']){
            //$sql = "select * from futures.txctc_jjr_ygxxcl where zjzh={$data['fundsNum']} and CLZT != 4";
            //$result = DB::connection("crm")->select($sql);
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXXCL',
                    'by' => [
                        ['ZJZH','=',$data['fundsNum']],
                        ['CLZT','!=',4]
                    ]
                ]
            ];
            $result = $this->getCrmData($post_data);
            if($result){
                $re = [
                    'status' => 500,
                    'msg' => "CRM系统已存在该客户，请检查后再试！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                return response()->json($re);
            }
        }

        foreach($data as &$k){
            $k = addslashes($k);
        }

        //客户归属关系
        if('' != $data['customerNum'] && null != $data['customerNum']){
            //$manager = M('office_mmanager')->where('manager_number = '.$data['customerNum'])->find();
            $sql = "select * from oa_office_mmanager where manager_number =".$data['customerNum'];
            $manager = DB::connection("oa")->select($sql);

            if(isset($manager[0])){
                //客户经理
                $manager = $manager[0];
                $data['customerManagerName'] = $manager->manager_name;

                //营业部信息
                $sql = "select * from oa_dept where did =".$manager->pid;
                $yyb = DB::connection("oa")->select($sql);
                if(isset($yyb[0])){
                    $yyb = $yyb[0];
                    $data['yybNum'] = $manager->pid;
                    $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb->deptname;
                }

                //居间人信息
                if($data['jjrNum']){
                    $sql = "select * from oa_mediator where number = " . $data['jjrNum'];
                    $jjr = DB::connection("oa")->select($sql);
                    if(isset($jjr[0])){
                        $jjr = $jjr[0];
                        $data['jjrName'] = $jjr->mediatorname;
                    }
                }
            }
        }else{
            //居间人信息
            if($data['jjrNum']){
                $sql = "select * from oa_mediator where number = " . $data['jjrNum'];
                $jjr = DB::connection("oa")->select($sql);
                if(isset($jjr[0])){
                    $jjr = $jjr[0];
                    $data['jjrName'] = $jjr->mediatorname;
                    $data['yybNum'] = $jjr->did;
                    //营业部信息
                    $sql = "select * from oa_dept where did =".$jjr->did;
                    $yyb = DB::connection("oa")->select($sql);
                    if(isset($yyb[0])){
                        $yyb = $yyb[0];
                        $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb->deptname;
                    }
                }
            }
        }
        //写入内部系统
        $result1 = DB::connection("oa")->table("oa_customer_manager")->insertGetId($data);
        //写入rpa
        $result2 = DB::table("rpa_customer_manager")->insertGetId($data);
        if($result1 && $result2){
            //开户插件同步居间关系到crm系统
            //增加二次股指、激活、更新判断，以上客户不同步到crm
            if(!$data['special']){
                $post_data = [
                    'type' => 'customer',
                    'action' => 'relationCustomer',
                    'param' => [
                        'info' => $data
                    ]
                ];
                $result = $this->getCrmData($post_data);
                if(!$result){
                    $re = [
                        'status' => 500,
                        'msg' => "CRM系统推送数据失败，请联系金融科技部！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                    return response()->json($re);
                };
            }

            $re = [
                'status' => 200,
                'msg' => "信息录入成功！"
            ];

        }else{

            $re = [
                'status' => 500,
                'msg' => "内部系统信息录入失败，请联系软件工程部！"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }
}