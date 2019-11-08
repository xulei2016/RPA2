<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Api\RpaPobo5Code;


class TradeApiController extends BaseApiController
{
    /**
     * 获取交易流水
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_jyls(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'khh' => 'required|numeric'
        ]);

        //根据资金账号查询客户交易流水
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => [
                    ['KHH','=',$request->khh],
                    ['CJRQ','>',date('Ymd',strtotime('-1 year'))]
                ],
                'order' => [
                    ['JYRQ DESC'],
                    ['HYDM DESC']
                ]
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
                'msg' => '该资金账号未找到'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取交易日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_jyr(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //获取交易日
        $sql = "select INIT_DATE from dcuser.tfu_tjyr_hs where EXCHANGE_TYPE = 'F1' ORDER BY INIT_DATE ASC";
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql,
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $arr = [];
            foreach($result as $v){
                array_push($arr,$v['INIT_DATE']);
            }
            $re = [
                'status' => 200,
                'msg' => $arr
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '查询出错'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取码表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_code_table(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        $result = RpaPobo5Code::get(['innerCode','futuShortName','futuName','futuKind']);
        if($result){
            $re = [
                'status' => 200,
                'msg' => $result
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '查询出错'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }
}
