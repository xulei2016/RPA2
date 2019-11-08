<?php

namespace App\Http\Controllers\api\rpa\v2;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\RpaShixincfa;
use App\Models\Admin\Api\RpaShixinsf;
use App\Models\Admin\Api\RpaShixinhss;
use App\Models\Admin\Rpa\rpa_immedtasks;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PluginApiController extends BaseApiController
{
    /**
     * 失信查询
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function credit(Request $request)
    {
        // //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required',
            'type' => 'required|in:1,2'
        ]);

        $today = date("Y-m-d H:i:s",time());
        $yesterday = date("Y-m-d",strtotime("-1 day"))." 15:00:00";
        //期货
        $cfa = RpaShixincfa::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //证券
        $sf = RpaShixinsf::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //金融
        $jr = RpaShixinhss::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();

        //$type 1代表发布任务 2代表查询结果
        if($request->type == 1){
            $param = [
                'name' => $request->name,
                'idCard' => $request->idCard,
                'operator' => Auth::user()->name
            ];
            //期货
            if(!isset($cfa) || $cfa->state == -1){
                $data1 = [
                    'name' => 'SupervisionCFA_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res1 = rpa_immedtasks::create($data1);
            }
            //证券
            if(!isset($sf) || $sf->state == -1){
                $data2 = [
                    'name' => 'SupervisionSF_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res2 = rpa_immedtasks::create($data2);
            }
            //金融
            if(!isset($jr) || $jr->state == -1){
                $data3 = [
                    'name' => 'SupervisionHS_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res3 = rpa_immedtasks::create($data3);
            }

            $re = [
                'status' => 200,
                'msg' => 'rpa任务发布成功！'
            ];
        }else{
            if(!isset($cfa) || !isset($sf) || !isset($jr)){
                $re = [
                    'status' => 500,
                    'msg' => "未找到数据！"
                ];
            }elseif($cfa->state == null || $sf->state == null || $jr->state == null){
                $re = [
                    'status' => 500,
                    'msg' => 'rpa任务正在执行，请稍等...'
                ];
            }else{
                //增加查询操作人
                //期货
                if(isset($cfa) && $cfa->state != -1){
                    $data1 = $this->getOperatorArray($cfa->operator,$cfa->count);
                    RpaShixincfa::where("id",$cfa->id)->update($data1);
                }
                //证券
                if(isset($sf) && $sf->state != -1){
                    $data2 = $this->getOperatorArray($sf->operator,$sf->count);
                    RpaShixinsf::where("id",$sf->id)->update($data2);
                }
                //金融
                if(isset($jr) && $jr->state != -1){
                    $data3 = $this->getOperatorArray($jr->operator,$jr->count);
                    RpaShixinsf::where("id",$jr->id)->update($data3);
                }

                $re = [
                    'status' => 200,
                    'qh' => $cfa->state,
                    'zq' => $sf->state,
                    'hs' => $jr->state
                ];
            }
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 拼接操作人json
     * @param $operator 历史操作人
     * @param $count    查询次数
     * @return array
     */
    private function getOperatorArray($operator,$count)
    {
        $opera = json_decode($operator,true);
        if(is_array($operator)){
            $opera[] = [
                'name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s',time())
            ];
        }else{
            $opera = [
                'name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s',time())
            ];
        }

        $data = [
            'operator' => json_encode($opera,JSON_UNESCAPED_UNICODE),
            'count' => $count + 1
        ];
        return $data;
    }
}