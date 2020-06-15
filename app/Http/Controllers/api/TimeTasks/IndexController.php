<?php

namespace App\Http\Controllers\api\TimeTasks;

use App\Http\Controllers\api\BaseApiController;
use App\Http\Controllers\api\Mediator\MediatorApiController;
use Illuminate\Http\Request;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorFlow;

class IndexController extends BaseApiController
{
    public function mediatorTask(Request $request)
    {
        $mediator = new MediatorApiController();
        $res = $mediator->getTaskList();
        if(0 == count($res)){
            $re = [
                'status' => 200,
                'msg' => '执行任务成功！'
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => $res
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 同步培训时长
     */
    public function syncTrainingDuration(Request $request){
        $mediator = new MediatorApiController();
        $re = $mediator->syncMediatorTrainingDuration($request);
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }

    /**
     * 通过crm自动注销的居间
     */
    public function syncCancelMediator(Request $request)
    {
        //获取rpa过期居间
        $date = date('Y-m-d',strtotime('-1 day'));
        $info = FuncMediatorInfo::where([['xy_date_end',"=",$date]])->get();
        $err = [];
        foreach($info as $v){
            $post_data = [
                'type' => 'jjr',
                'action' => 'getJjrBy',
                'param' => [
                    'table' => 'JJR',
                    'by' => [
                        ['BH','=',$v->number]
                    ]
                ]
            ];
            $res = $this->getCrmData($post_data);
            if($res){
                //同步状态
                if($res[0]['ZHZT'] == 1){
                    $data['status'] = 3;
                    //注销，需要将未完成的流程作废
                    FuncMediatorFlow::where([['uid','=',$v->id],['is_handle','<>',1]])->update(['status'=>0]);
                }else{
                    $data['status'] = 1;
                }
            }else{
                $data['status'] = 2;
            }

            if($v->status != 0){
                $res = FuncMediatorInfo::where('id',$v->id)->update($data);
            }else{
                $res = 1;
            }

            if(!$res){
                $err[] = $v->number;
            }
        }
        if(empty($err)){
            $re = [
                'status' => 200,
                'msg' => '同步成功'
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => implode(",",$err)."同步失败"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }
}