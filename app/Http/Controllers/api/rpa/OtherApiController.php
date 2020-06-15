<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Func\Archives\func_archives;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Admin\Api\RpaHaLcztcx;
use App\Models\Admin\Rpa\rpa_immedtasks;
use Illuminate\Support\Facades\Auth;

class OtherApiController extends BaseApiController
{
    /**
     * 获取客户关系（投资江湖）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_relation(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'account' => 'required|numeric',
            'name' => 'required'
        ]);

        //根据姓名加资金账号查询客户
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => [
                    ['KHXM','=',$request->name],
                    ['ZJZH','=',$request->account]
                ],
                'columns' => ['YYB','ID']
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $post_data = [
                'type' => 'jjr',
                'action' => 'get_customer_relation',
                'param' => [
                    'yyb_number' => $result[0]['YYB'],
                    'kid' => $result[0]['ID']
                ]
            ];
            $res = $this->getCrmData($post_data);
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '该资金账号未找到'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取居间关系（投资江湖）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mediator_relation(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required|numeric'
        ]);
        
        $post_data = [
            'type' => 'jjr',
            'action' => 'get_mediator_relation',
            'param' => [
                'phone' => $request->phone
            ]
        ];
        $res = $this->getCrmData($post_data);
        if($res){
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => "居间不存在或者无效居间"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);

    }

    /**
     * 居间人验证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_mediator(Request $request)
    {
        //ip检测
//        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
//        if($res !== true){
//            return response()->json($res);
//        }

        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required|numeric',
            'number' => 'required|numeric',
            'id_card' => 'required',
            'name' => 'required'
        ]);

        $post_data = [
            'type' => 'jjr',
            'action' => 'getJjrBy',
            'param' => [
                'table' => 'JJR',
                'by' => [
                    ['BH','=',$request->number]
                ]
            ]

        ];
        $res = $this->getCrmData($post_data);
        // print_r($res);exit;
        if($res){
            $res = $res[0];
             if($res['LXSJ'] != $request->phone && $res['DH'] != $request->phone){
                $re = [
                    'status' => 400,
                    'msg' => "手机号码不匹配"
                ];
             }elseif($res['SFZH'] != $request->id_card){
                $re = [
                    'status' => 400,
                    'msg' => "身份证号码不匹配"
                ];
             }elseif($res['XM'] != $request->name){
                $re = [
                    'status' => 400,
                    'msg' => "姓名不匹配"
                ];
             }else{
                $re = [
                    'status' => 200,
                    'msg' => "成功"
                ];
             }
        }else{
            $re = [
                'status' => 400,
                'msg' => "该居间不存在"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }
}
