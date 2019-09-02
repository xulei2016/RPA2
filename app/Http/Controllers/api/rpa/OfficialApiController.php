<?php
namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Admin\Rpa\rpa_uploademail;
use App\Models\Admin\Api\RpaJkzxbill;
use App\Models\Admin\Api\RpaJkzxbillTest;

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
     * 获取交易流水(用于测试)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_trading_flow_test(Request $request){
        //表单验证
        $validatedData = $request->validate([
            'jkzxzh' => 'required',
            'type' => 'required'
        ]);
//        // 查询笔数
        $where = [
            ['account','=',$request->jkzxzh],
            ['cjxh','REGEXP',"^[0-9]+$"],
            ['cjxh','!=',0],
            ['jyrq','>=',date('Y-m-d', strtotime(' -1 year, +1 day'))]
        ];
        if($request->type == 1){
            $res = RpaJkzxbillTest::where($where)->groupBy("jyrq")->get();
            $re = [
                'status' => 200,
                'msg' => count($res)
            ];
        }elseif($request->type == 2){
            // 查询50比流水
            $res = RpaJkzxbillTest::where($where)->groupBy("jyrq")->orderBy("jyrq","desc")->limit(50)->get();
            //$res = $res->toArray();
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }
        $re = [
                'status' => 200,
                'msg' => '测试'
            ];
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
}
?>