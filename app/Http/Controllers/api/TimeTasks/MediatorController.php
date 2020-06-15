<?php
/**
 * Created by PhpStorm.
 * User: cyx
 * Date: 2020/4/10
 * Time: 13:21
 */

namespace App\Http\Controllers\api\TimeTasks;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Common\FuncLostCreditRecord;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MediatorController extends BaseApiController
{
    /**
     * 根据卡号获取失信流水号和日期
     */
    public function getLoseCreditByIdCard(Request $request)
    {
        $res = $this->check_ip(__FUNCTION__, $request->getClientIp());
        if ($res !== true) {
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
        ]);
        //查询客户信息
        $params = [
            'type' => 'credit',
            'action' => 'getCodeByIdCard',
            'param' => [
                'table' => 'TKHXX',
                'idCard' => $request->idCard,
            ]
        ];

        $result = $this->getCrmData($params);
        if ($result) {
            $record = FuncLostCreditRecord::where('code', $result['promiseId'])->first();
            $re = [
                'status' => 200,
                'msg' => '查询成功',
                'date' => $result['date'],
                'promiseId' => $result['promiseId'],
                'data' => json_decode($record->data, true)
            ];
        } else {
            $re = [
                'status' => 500,
                'msg' => '找不到该数据',
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__, $request, json_encode($re, true), $request->getClientIp());
        return response()->json($re);
    }

    /**
     * 同步居间培训时长到rpa服务器
     * @param Request $request
     */
    public function syncMediatorTrainingDurationToRpa(Request $request)
    {
        $condition = [
            ['info.status', '=', 1]
        ];
        $list = FuncMediatorFlow::from('func_mediator_flow as flow')
            ->leftJoin('func_mediator_info as info', 'info.id', 'flow.uid')
            ->where($condition)
            ->whereIn('flow.type', [0, 1])
            ->select(['flow.id','flow.number','flow.xy_date_begin','flow.xy_date_end','info.name'])
            ->get();
        $newList = [];
        foreach ($list as $v) {
            if($v->number && $v->xy_date_end) {
                $newList[] = [
                    'begintime' => strtotime($v->xy_date_begin),
                    'endtime' => strtotime($v->xy_date_end),
                    'name' => $v->name,
                    'number' => $v->number,
                    'id' => $v->id
                ];
            }

        }
        unset($list);
        $guzzle = new Client(['verify'=>false]);
        $response = $guzzle->post('http://api.hatzjh.com/live/getmedinfo',[
            'query' => [
                'username' => 'haqhJJCX',
                'password' => 'JJCXMediator',
                '_time' => 1,
            ],
            'form_params' => [
                'data' => json_encode($newList)
            ]
        ]);
        $body = $response->getBody();
        $result = json_decode((string)$body,true);
        if(!$result) {
            $re = [
                'status' => 500,
                'msg' => '获取居间培训时长接口异常'
            ];
            return response()->json($re);
        }
        if(isset($result['code'])) { // 表示有问题
            $re = [
                'status' => 500,
                'msg' => "获取居间培训时长接口异常:".$result['msg']
            ];
            return response()->json($re);
        }
        foreach ($result as $k => $v) {
            if(200 == $v['code']) { // 有培训时长
                $time = $v['total_time'];
                $item = $newList[$k];
                FuncMediatorFlow::where('id', $item['id'])->update(['training_duration' => $time]);
            }
        }
        $re = [
            'status' => 200,
            'msg' => '成功'
        ];
        //api日志
        $this->apiLog(__FUNCTION__, $request, json_encode($re, true), $request->getClientIp());
        return response()->json($re);
    }
    /******************以下是内部方法**********************/

}