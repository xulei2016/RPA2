<?php

namespace App\Http\Controllers\api\rpa;


use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\RpaCrmFlow;
use App\Models\Admin\Api\RpaCustomerInfo;
use App\Models\Admin\Api\RpaFlow;
use App\Models\Admin\Api\RpaShixincfa;
use App\Models\Admin\Api\RpaShixinsf;
use App\Models\Admin\Base\SysApiLog;
use App\Models\Admin\Rpa\rpa_immedtasks;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
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
            'flownum' => 'required',
            'customer_name' => 'required'
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
                'operator' => $request->getClientIp()
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
                        'msg' => 'rpa任务发布失败，请联系软件工程部！'
                    ];
                }
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '流程写入失败，请联系软件工程部！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
                'operator' => $request->getClientIp()
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
                            $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
                    $baseImg = $this->base64EncodeImage("D:/uploadFile/customerImg/".$flow->sign);
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
                $res = $res[0];
                if($res){

                    $guzzle = new Client();
                    $response = $guzzle->post('http://172.16.253.26/interface/crm/getMediatorImg.php',[
                        'form_params' => [
                            "filename" => $res['signatureimg']
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
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
        $cfa = RpaShixincfa::where([["updatetime",$time],["idnum",$request->idCard],["name",$request->name]])->first();
        //证券
        $sf = RpaShixinsf::where([["updatetime",$time],["idnum",$request->idCard],["name",$request->name]])->first();

        //查询次数是否加一
        if($request->type == 2){
            //期货
            if($cfa){
                $opera1 = json_decode($cfa->operator,true);
                if(is_array($opera1)){
                    $opera1[] = [
                        'name' => $request->getClientIp(),
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
                        'name' => $request->getClientIp(),
                        'date' => date('Y-m-d H:i:s',time())
                    ];
                }

                $data2 = [
                    'operator' => json_encode($opera2,JSON_UNESCAPED_UNICODE),
                    'count' => $sf->count + 1
                ];
                RpaShixinsf::where("id",$sf->id)->update($data2);
            }
        }

        //期货证券同时存在，无需发布任务
        if($cfa && $sf){
            if($request->type == 1){
                $re = [
                    'status' => 200,
                    'msg' => '数据已存在，无需发布任务'
                ];
            }else{
                //判断是否存在任务运行失败情况
                if($cfa->state == -1 || $sf->state == -1){
                    $re = [
                        'status' => 500,
                        'msg' => '期货或证券有任务运行失败'
                    ];
                }else{
                    $re = [
                        'status' => 2,
                        'qh' => $cfa->state,
                        'zq' => $sf->state
                    ];
                }
            }
        }else{
            if($request->type == 2){
                $re = [
                    'status' => 500,
                    'msg' => '未找到数据!'
                ];
            }else{
                //添加rpa及时任务
                $param = [
                    'name' => $request->name,
                    'idCard' => $request->idCard,
                    'operator' => $request->getClientIp()
                ];

                if(!$cfa){
                    $data1 = [
                        'name' => 'SupervisionCFA_im',
                        'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                    ];
                    $res1 = rpa_immedtasks::create($data1);
                }
                if(!$sf){
                    $data2 = [
                        'name' => 'SupervisionSF_im',
                        'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                    ];
                    $res2 = rpa_immedtasks::create($data2);
                }
                if($res1 || $res2){
                    $re = [
                        'status' => 200,
                        'msg' => 'rpa任务发布成功！'
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => 'rpa任务发布失败，请联系软件工程部！'
                    ];
                }

            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);

    }

    /**
     * 获取居间人信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediator_info(Request $request)
    {
        //ip检测
        $res = $this->check_ip("mediator_info",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'mediator_number' => 'required',
            'manager_number' => 'required'
        ]);

        $sql1 = "select mediatorname from oa_mediator where number =".$request->mediator_number;
        $sql2 = "select * from oa_office_mmanager where manager_number =".$request->manager_number;

        //居间
        $res1 = DB::connection("oa")->select($sql1);

        if($res1){
            $mediator =  $res1[0];
        }
        //经理号
        $res2 = DB::connection("oa")->select($sql2);
        if($res2){
            $manager =  $res2[0];
        }
        $g = isset($manager->manager_name) ? $manager->manager_name : "";
        $mm = "";
        if($g){
            $sql3 = "select deptname from oa_dept where did = ".$manager->pid;
            $res3 = DB::connection("oa")->select($sql3);
            if($res3){
                $dept =  $res3[0];
            }
            $d = isset($dept->deptname) ? $dept->deptname : "";
            $mm = $g."-".$d;
        }

        $m = isset($mediator->mediatorname) ? $mediator->mediatorname :"";

        $arr = [
            'mediator' => $m,
            'manager' =>  $mm
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($arr),$request->getClientIp());

        return response()->json($arr);
    }

    /**
     * 获取居间人信息
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

        $sql = "select a.mediatorname,b.manager_name,c.deptname from oa_mediator a LEFT JOIN oa_office_mmanager b
            ON a.managerNo = b.manager_number 
            LEFT JOIN oa_dept c on a.did = c.did 
            where a.number = {$request->number}";

        $res = DB::connection("oa")->select($sql);
        $data = $res[0];

        $return = $data->deptname.'-'.$data->manager_name.'-'.$data->mediatorname;
        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 保存客户信息
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
            'operator' => $request->getClientIp(),
            'state' => 2,
            'updated_at' => time()
        ];
        //设置传入参数

        $s = 1;
        $result = RpaCustomerInfo::where("fundAccount",$data['fundAccount'])->first();
        if($result){
            //更新
            if(!empty($sign_base64)){
                $sign = $this->base64ToImage($sign_base64,"/customerImg/",$result['sign']);
                $data['sign'] = $sign;
            }
            if(!empty($sfz_zm_base64)){
                $sfz_zm = $this->base64ToImage($sfz_zm_base64,"/customerImg/",$result['sfz_zm']);
                $data['sfz_zm'] = $sfz_zm;
            }
            if(!empty($sfz_fm_base64)){
                $sfz_fm = $this->base64ToImage($sfz_fm_base64,"/customerImg/",$result['sfz_fm']);
                $data['sfz_fm'] = $sfz_fm;
            }
            if(!empty($head_zm_base64)){
                $head_zm = $this->base64ToImage($head_zm_base64,"/customerImg/",$result['head_zm']);
                $data['head_zm'] = $head_zm;
            }
            $aff = RpaCustomerInfo::where("fundAccount",$data['fundAccount'])->update($data);
            if(!$aff){
                $s = 0;
            }
        }else{
            //添加
            if(!empty($sign_base64)){
                $sign = $this->base64ToImage($sign_base64,"/customerImg/");
                $data['sign'] = $sign;
            }
            if(!empty($sfz_zm_base64)){
                $sfz_zm = $this->base64ToImage($sfz_zm_base64,"/customerImg/");
                $data['sfz_zm'] = $sfz_zm;
            }
            if(!empty($sfz_fm_base64)){
                $sfz_fm = $this->base64ToImage($sfz_fm_base64,"/customerImg/");
                $data['sfz_fm'] = $sfz_fm;
            }
            if(!empty($head_zm_base64)){
                $head_zm = $this->base64ToImage($head_zm_base64,"/customerImg/");
                $data['head_zm'] = $head_zm;
            }
            $info = RpaCustomerInfo::create($data);
            if(!$info){
                $s = 0;
            }
        }
        if($s){
            $re = [
                'status' => 200,
                'msg' => '图片保存成功'
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '数据写入失败，请联系软件工程部！'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 客户PDF下载
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerPDF(Request $request)
    {
        //ip检测
        $res = $this->check_ip("customerPDF",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'cookie' => 'required',
            'date' => 'required'
        ]);

        //数据
        $data = [
            'cookie'=>$request->cookie,
            'date'=>date('Y-m-d',strtotime($request->date,time()))
        ];
        $jsondata = json_encode($data);

        $name = "DownloadPDF";
        //插入即时任务
        $data = [
            'name' => $name,
            'jsondata' => $jsondata
        ];
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
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
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
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }
}