<?php
namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Admin\Rpa\rpa_uploademail;
use App\Models\Admin\Api\RpaJkzxbill;
use App\Models\Admin\Api\RpaJkzxbillTest;
use App\Models\Admin\Func\RpaProfessionChange; // 职业变更业务
use App\Models\Admin\Func\RpaKh; // 客户信息
use App\Models\Admin\Func\RpaKhFlow; // 流程
use App\Models\Admin\Api\RpaCustomerInfo;

class OfficialApiController extends BaseApiController
{
    /**
     * 发布任务for 官网
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function release_task2(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        //time存在代表定时任务，不存在代表立即任务
        if(isset($request->time)){
            $data = [
                'time' => $request->time,
                'name' => $request->name,
                'jsondata' => $request->jsondata,
                'server' => $request->serverx
            ];
            $res = rpa_timetasks::create($data);
            if($res){
                $return = [
                    'status'=>200,
                    'msg' => '定时任务发布成功！'
                ];
            }else{
                $return = [
                    'status'=>500,
                    'msg' => '定时任务发布失败！'
                ];
            }
        }else{
            $data = [
                'name' => $request->name,
                'jsondata' => $request->jsondata,
                'server' => $request->serverx
            ];
            $res = rpa_immedtasks::create($data);
            if($res){
                $return = [
                    'status'=>200,
                    'msg' => '立即任务发布成功！'
                ];
            }else{
                $return = [
                    'status'=>500,
                    'msg' => '立即任务发布失败！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取任务返回结果
     */
    public function release_task2_result(Request $request){

        //表单验证
        $validatedData = $request->validate([
            'returnweb' => 'required',
        ]);
        $res = rpa_uploademail::where("returnweb",$request->returnweb)->first();

        if($res){
            $return = [
                'status'=>200,
                'msg' => $res['content']
            ];
        }else{
            $return = [
                'status'=>500,
                'msg' => "未找到该任务"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取交易流水
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_trading_flow(Request $request){
        //表单验证
        $validatedData = $request->validate([
            'jkzxzh' => 'required',
            'type' => 'required'
        ]);
        // 查询笔数
        $where = [
            ['account','=',$request->jkzxzh],
            ['cjxh','REGEXP',"^[0-9]+$"],
            ['cjxh','!=',0],
            ['jyrq','>=',date('Y-m-d', strtotime(' -1 year, +1 day'))]
        ];
        if($request->type == 1){
            $res = RpaJkzxbill::where($where)->groupBy("jyrq")->get();
            $re = [
                'status' => 200,
                'msg' => count($res)
            ];
        }elseif($request->type == 2){
            // 查询50比流水
            $res = RpaJkzxbill::where($where)->groupBy("jyrq")->orderBy("jyrq","desc")->limit(50)->get();
            //$res = $res->toArray();
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }

    /**
     * 根据居间人编号获取居间人状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mediator_by_number(Request $request){
        //表单验证
        $validatedData = $request->validate([
            'number' => 'required',
        ]);
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JJR',
                'by' => [
                    ['BH','=',$request->number]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        $jjr = $result[0];
        if($jjr['ZHZT'] != 1){
            $re = [
                'status' => 200,
                'msg' => '未注销'
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '已注销'
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }

    /**
     * 获取职业变更信息
     */
    public function get_profession(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'zjzh' => 'required',
            'phone' => 'required|numeric',
            'sfz' => 'required',
            'name' => 'required'
        ]);
        $zjzh = $request->zjzh;
        $kh = RpaKh::where('zjzh', $zjzh)->first();
        $date = date('Y-m-d H:i:s');
        
        if($kh) {
            $uid = $kh->id;
        } else {
            $result = RpaKh::create($request->all());
            $uid = $result->id;
        }
        

        $flow = RpaKhFlow::where([
            ['status', 1],
            ['uid', $uid],
            ['tid', 163], // 163表示职业变更
        ])->first();
        if($flow) {
            $profession = RpaProfessionChange::where('id', $flow->business_id)->first()->toArray();
            $business_id = $profession['id'];
            $professionCode = $profession['profession_code'];
            $status = $profession['status'];
        } else {
            $status = 1;
            $r = RpaProfessionChange::create([
                'created_at' => $date,
                'status' => $status
            ]);
            $business_id = $r->id;
            $res = RpaKhFlow::create([
                'uid' => $uid,
                'tid' => 163,
                'business_id' => $business_id,
                'number' => 'PC'.date('YmdHis').mt_rand(100000, 999999),
                'status' => 1,
                'created_at' => $date,
            ]);
            $professionCode = '';
        }
        $re = [
            'status' => 200,
            'msg' => '成功',
            'data' => [
                'uid' => $uid,
                'status' => $status,
                'profession_code' => $professionCode
            ]
        ];
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);

    }

    /**
     * 修改职业
     */
    public function change_profession(Request $request) {
         //ip检测
         $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
         if($res !== true){
             return response()->json($res);
         }
 
         //表单验证
         $validatedData = $request->validate([
             'code' => 'required',
             'id' => 'required|numeric',
         ]);
        $id = $request->id;
        $code = $request->code;
        $flow = RpaKhFlow::where([
            ['status', 1],
            ['uid', $id],
            ['tid', 163], // 163表示职业变更
        ])->first();
        if(!$flow) {
            $re = [
                'status' => 500,
                'msg' => '业务不存在'
            ];
            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
            return response()->json($re);
        }
        $profession = RpaProfessionChange::where([
            ['status', 1],
            ['id', $flow->business_id]
        ])->first();
        if(!$profession) {
            $re = [
                'status' => 500,
                'msg' => '业务不存在,或者处于不可更改状态'
            ];
            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
            return response()->json($re);
        }
        $profession->status = 3;
        $profession->profession_code = $code;
        $result = $profession->save();
        if($result) {
            $re = [
                'status' => 200,
                'msg' => '成功'
            ];
        } else {
            $re = [
                'status' => 500,
                'msg' => '提交失败'
            ];
        }
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }

    /**
     * 获取客户照片
     */
    public function get_customer_img(Request $request){
        //表单验证
        $validatedData = $request->validate([
            'zjzh' => 'required|numeric',
            'type' => 'in:sign,sfz_zm,sfz_fm'
        ]);

        $result = RpaCustomerInfo::where("fundAccount",$request->zjzh)->first();
        if($result){
            //客户信息存在
            //是否正在执行rpa任务
            if($result->runstatus === 2){
                $re = [
                    'status' => 501,
                    'msg' => '该客户档案不存在'
                ];
            }elseif($result->runstatus === 0){
                $re = [
                    'status' => 202,
                    'msg' => 'rpa任务正在执行'
                ];
            }else{
                //判断是否所有图片都存在，如有缺失，发布rpa任务重新查询
                if(empty($result->sign) || empty($result->sfz_zm) || empty($result->sfz_fm)){
                    $data = [
                        'runstatus' => 0
                    ];
                    RpaCustomerInfo::where(['id',$result->id])->update($data);

                    $param = [
                        'exename' => '签名照片',
                        'account' => $request->zjzh
                    ];

                    $data = [
                        'name' => 'SignPicture',
                        'jsondata' => json_encode($param)
                    ];
        
                    $res = rpa_immedtasks::create($data);

                    if($res){
                        $re = [
                            'status' => 201,
                            'msg' => 'rpa任务发布成功'
                        ];
                    }else{
                        $re = [
                            'status' => 500,
                            'msg' => 'rpa任务发布失败'
                        ];
                    }
                }else{
                    switch($request->type){
                        case 'sign':
                            $re = [
                                'status' => 200,
                                'msg' => $this->base64EncodeImage("D:/uploadFile/customerImg/".$result->sign)
                            ];
                            break;
                        case 'sfz_zm':
                            $re = [
                                'status' => 200,
                                'msg' => $this->base64EncodeImage("D:/uploadFile/customerImg/".$result->sfz_zm)
                            ];
                            break;
                        case 'sfz_fm':
                            $re = [
                                'status' => 200,
                                'msg' => $this->base64EncodeImage("D:/uploadFile/customerImg/".$result->sfz_fm)
                            ];
                            break;
                        default :
                            $re = [
                                'status' => 500,
                                'msg' => "参数错误"
                            ];
                            break;
                    }
                }
            }
        }else{
            //客户信息不存在，发布rpa任务查询
            $data = [
                'runstatus' => 0,
                'fundAccount' => $request->zjzh
            ];
            RpaCustomerInfo::create($data);

            $param = [
                'exename' => '签名照片',
                'account' => $request->zjzh
            ];

            $data = [
                'name' => 'SignPicture',
                'jsondata' => json_encode($param)
            ];

            $res = rpa_immedtasks::create($data);

            if($res){
                $re = [
                    'status' => 201,
                    'msg' => 'rpa任务发布成功'
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => 'rpa任务发布失败'
                ];
            }
        }

        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);

    }
}
?>